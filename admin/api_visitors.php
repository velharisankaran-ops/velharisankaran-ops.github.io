<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

require_once __DIR__ . '/db_config.php';

// Create enhanced visitor_logs table with geolocation, campaign attribution, and engagement metrics
$pdo->exec("
CREATE TABLE IF NOT EXISTS visitor_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(64) DEFAULT NULL,
    ip_address VARCHAR(45) NOT NULL,
    country VARCHAR(100) DEFAULT 'Unknown',
    country_code VARCHAR(10) DEFAULT '',
    region_name VARCHAR(100) DEFAULT '',
    city VARCHAR(100) DEFAULT '',
    isp VARCHAR(150) DEFAULT '',
    user_agent TEXT,
    device_type VARCHAR(20) DEFAULT 'Desktop',
    browser VARCHAR(50) DEFAULT 'Unknown',
    os VARCHAR(50) DEFAULT 'Unknown',
    screen_res VARCHAR(30) DEFAULT '',
    language VARCHAR(20) DEFAULT '',
    timezone VARCHAR(50) DEFAULT '',
    referrer VARCHAR(255) DEFAULT '',
    utm_source VARCHAR(100) DEFAULT '',
    utm_medium VARCHAR(100) DEFAULT '',
    utm_campaign VARCHAR(100) DEFAULT '',
    scroll_depth INT DEFAULT 0,
    time_spent_seconds INT DEFAULT 0,
    actions_clicked VARCHAR(255) DEFAULT '',
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    INDEX idx_visited_at (visited_at),
    INDEX idx_ip (ip_address)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
");

// Safely ensure new columns exist if table was previously created
$colsToAdd = [
    "session_id VARCHAR(64) DEFAULT NULL",
    "country VARCHAR(100) DEFAULT 'Unknown'",
    "country_code VARCHAR(10) DEFAULT ''",
    "region_name VARCHAR(100) DEFAULT ''",
    "city VARCHAR(100) DEFAULT ''",
    "isp VARCHAR(150) DEFAULT ''",
    "screen_res VARCHAR(30) DEFAULT ''",
    "language VARCHAR(20) DEFAULT ''",
    "timezone VARCHAR(50) DEFAULT ''",
    "utm_source VARCHAR(100) DEFAULT ''",
    "utm_medium VARCHAR(100) DEFAULT ''",
    "utm_campaign VARCHAR(100) DEFAULT ''",
    "scroll_depth INT DEFAULT 0",
    "time_spent_seconds INT DEFAULT 0",
    "actions_clicked VARCHAR(255) DEFAULT ''",
    "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
];
foreach ($colsToAdd as $colDef) {
    $colName = explode(' ', $colDef)[0];
    try {
        $pdo->exec("ALTER TABLE visitor_logs ADD COLUMN $colDef");
    } catch (Exception $e) {
        // Column already exists, ignore
    }
}

$action = $_GET['action'] ?? 'log';

// 1. LOG VISIT
if ($action === 'log') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    $sessionId = substr(trim($data['session_id'] ?? ''), 0, 64);
    if (!$sessionId) {
        $sessionId = bin2hex(random_bytes(16));
    }

    // Get visitor IP
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] 
       ?? $_SERVER['HTTP_X_FORWARDED_FOR'] 
       ?? $_SERVER['REMOTE_ADDR'] 
       ?? '0.0.0.0';
    if (strpos($ip, ',') !== false) {
        $ip = trim(explode(',', $ip)[0]);
    }

    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ref = substr(trim($data['referrer'] ?? ($_SERVER['HTTP_REFERER'] ?? '')), 0, 255);
    $screenRes = substr(trim($data['screen_res'] ?? ''), 0, 30);
    $lang = substr(trim($data['language'] ?? ''), 0, 20);
    $tz = substr(trim($data['timezone'] ?? ''), 0, 50);

    $utmSource = substr(trim($data['utm_source'] ?? ''), 0, 100);
    $utmMedium = substr(trim($data['utm_medium'] ?? ''), 0, 100);
    $utmCampaign = substr(trim($data['utm_campaign'] ?? ''), 0, 100);

    // Device detection
    $device = 'Desktop';
    if (preg_match('/(android|iphone|ipad|mobile|touch)/i', $ua)) {
        $device = preg_match('/(ipad|tablet)/i', $ua) ? 'Tablet' : 'Mobile';
    }

    // OS detection
    $os = 'Other';
    if (stripos($ua, 'windows') !== false) $os = 'Windows';
    elseif (stripos($ua, 'android') !== false) $os = 'Android';
    elseif (stripos($ua, 'iphone') !== false || stripos($ua, 'ipad') !== false) $os = 'iOS';
    elseif (stripos($ua, 'macintosh') !== false || stripos($ua, 'mac os') !== false) $os = 'macOS';
    elseif (stripos($ua, 'linux') !== false) $os = 'Linux';

    // Browser detection
    $browser = 'Other';
    if (stripos($ua, 'chrome') !== false && stripos($ua, 'edg') === false && stripos($ua, 'opr') === false) $browser = 'Chrome';
    elseif (stripos($ua, 'safari') !== false && stripos($ua, 'chrome') === false) $browser = 'Safari';
    elseif (stripos($ua, 'firefox') !== false) $browser = 'Firefox';
    elseif (stripos($ua, 'edg') !== false) $browser = 'Edge';
    elseif (stripos($ua, 'opera') !== false || stripos($ua, 'opr') !== false) $browser = 'Opera';

    // Check recent visit for this session or IP within last 3 minutes
    $chkStmt = $pdo->prepare("SELECT id FROM visitor_logs WHERE (session_id = ? OR ip_address = ?) AND visited_at > DATE_SUB(NOW(), INTERVAL 3 MINUTE) LIMIT 1");
    $chkStmt->execute([$sessionId, $ip]);
    $existing = $chkStmt->fetch();

    if (!$existing) {
        // Fetch Geo Data via server-side lookup (timeout 1.5s so it never slows user)
        $country = 'Unknown';
        $countryCode = '';
        $regionName = '';
        $city = '';
        $isp = '';

        if ($ip !== '127.0.0.1' && $ip !== '0.0.0.0' && !preg_match('/^(10\.|192\.168\.|172\.(1[6-9]|2[0-9]|3[0-1])\.)/', $ip)) {
            $ctx = stream_context_create(['http' => ['timeout' => 1.5]]);
            $geoJson = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,regionName,city,isp", false, $ctx);
            if ($geoJson) {
                $geo = json_decode($geoJson, true);
                if (($geo['status'] ?? '') === 'success') {
                    $country = $geo['country'] ?? 'Unknown';
                    $countryCode = $geo['countryCode'] ?? '';
                    $regionName = $geo['regionName'] ?? '';
                    $city = $geo['city'] ?? '';
                    $isp = $geo['isp'] ?? '';
                }
            }
        }

        $stmt = $pdo->prepare("
            INSERT INTO visitor_logs 
            (session_id, ip_address, country, country_code, region_name, city, isp, user_agent, device_type, browser, os, screen_res, language, timezone, referrer, utm_source, utm_medium, utm_campaign)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $sessionId, $ip, $country, $countryCode, $regionName, $city, $isp,
            $ua, $device, $browser, $os, $screenRes, $lang, $tz,
            $ref, $utmSource, $utmMedium, $utmCampaign
        ]);
        $recordId = $pdo->lastInsertId();
    } else {
        $recordId = $existing['id'];
    }

    echo json_encode([
        'status' => 'success', 
        'session_id' => $sessionId,
        'record_id' => (int) $recordId
    ]);
    exit;
}

// 2. HEARTBEAT / ENGAGEMENT PING (Scroll Depth, Time Spent, Clicks)
if ($action === 'ping') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $sessionId = substr(trim($data['session_id'] ?? ''), 0, 64);
    $scrollDepth = (int) ($data['scroll_depth'] ?? 0);
    $timeSpent = (int) ($data['time_spent_seconds'] ?? 0);
    $clickedAction = substr(trim($data['action'] ?? ''), 0, 100);

    if ($sessionId) {
        if ($clickedAction) {
            $upd = $pdo->prepare("
                UPDATE visitor_logs 
                SET scroll_depth = GREATEST(scroll_depth, ?),
                    time_spent_seconds = GREATEST(time_spent_seconds, ?),
                    actions_clicked = CASE 
                        WHEN actions_clicked = '' OR actions_clicked IS NULL THEN ?
                        WHEN INSTR(actions_clicked, ?) > 0 THEN actions_clicked
                        ELSE CONCAT(actions_clicked, ', ', ?)
                    END
                WHERE session_id = ?
                ORDER BY id DESC LIMIT 1
            ");
            $upd->execute([$scrollDepth, $timeSpent, $clickedAction, $clickedAction, $clickedAction, $sessionId]);
        } else {
            $upd = $pdo->prepare("
                UPDATE visitor_logs 
                SET scroll_depth = GREATEST(scroll_depth, ?),
                    time_spent_seconds = GREATEST(time_spent_seconds, ?)
                WHERE session_id = ?
                ORDER BY id DESC LIMIT 1
            ");
            $upd->execute([$scrollDepth, $timeSpent, $sessionId]);
        }
    }

    echo json_encode(['status' => 'success']);
    exit;
}

// 3. STATS QUERY (For Dashboard)
if ($action === 'stats') {
    $totalVisits = (int) $pdo->query("SELECT COUNT(*) FROM visitor_logs")->fetchColumn();
    $uniqueVisitors = (int) $pdo->query("SELECT COUNT(DISTINCT ip_address) FROM visitor_logs")->fetchColumn();
    $todayVisits = (int) $pdo->query("SELECT COUNT(*) FROM visitor_logs WHERE DATE(visited_at) = CURDATE()")->fetchColumn();
    $avgTimeSpent = (int) $pdo->query("SELECT ROUND(AVG(time_spent_seconds)) FROM visitor_logs WHERE time_spent_seconds > 0")->fetchColumn();

    // Top Countries breakdown
    $countriesStmt = $pdo->query("
        SELECT country, country_code, COUNT(*) as count 
        FROM visitor_logs 
        WHERE country != 'Unknown' AND country != ''
        GROUP BY country, country_code 
        ORDER BY count DESC 
        LIMIT 5
    ");
    $topCountries = $countriesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Latest 60 records
    $stmt = $pdo->query("
        SELECT id, ip_address, country, country_code, region_name, city, isp,
               device_type, browser, os, screen_res, language, timezone,
               referrer, utm_source, utm_campaign, scroll_depth, time_spent_seconds, actions_clicked,
               DATE_FORMAT(visited_at, '%d %b %Y %h:%i %p') as formatted_time,
               visited_at
        FROM visitor_logs 
        ORDER BY visited_at DESC 
        LIMIT 60
    ");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'metrics' => [
            'total_visits' => $totalVisits,
            'unique_visitors' => $uniqueVisitors,
            'today_visits' => $todayVisits,
            'avg_time_spent' => $avgTimeSpent,
            'top_countries' => $topCountries
        ],
        'logs' => $logs
    ]);
    exit;
}
?>