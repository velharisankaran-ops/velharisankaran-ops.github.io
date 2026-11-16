<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

require_once __DIR__ . '/db_config.php';

// Create visitor_logs table if not exists
$pdo->exec("
CREATE TABLE IF NOT EXISTS visitor_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    device_type VARCHAR(20) DEFAULT 'Desktop',
    browser VARCHAR(50) DEFAULT 'Unknown',
    os VARCHAR(50) DEFAULT 'Unknown',
    referrer VARCHAR(255) DEFAULT '',
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_visited_at (visited_at),
    INDEX idx_ip (ip_address)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
");

$action = $_GET['action'] ?? 'log';

// 1. LOG VISIT
if ($action === 'log') {
    // Get visitor IP
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] 
       ?? $_SERVER['HTTP_X_FORWARDED_FOR'] 
       ?? $_SERVER['REMOTE_ADDR'] 
       ?? '0.0.0.0';
    if (strpos($ip, ',') !== false) {
        $ip = trim(explode(',', $ip)[0]);
    }

    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ref = $_SERVER['HTTP_REFERER'] ?? '';

    // Simple device detection
    $device = 'Desktop';
    if (preg_match('/(android|iphone|ipad|mobile|touch)/i', $ua)) {
        $device = preg_match('/(ipad|tablet)/i', $ua) ? 'Tablet' : 'Mobile';
    }

    // Simple OS detection
    $os = 'Other';
    if (stripos($ua, 'windows') !== false) $os = 'Windows';
    elseif (stripos($ua, 'android') !== false) $os = 'Android';
    elseif (stripos($ua, 'iphone') !== false || stripos($ua, 'ipad') !== false) $os = 'iOS';
    elseif (stripos($ua, 'macintosh') !== false || stripos($ua, 'mac os') !== false) $os = 'macOS';
    elseif (stripos($ua, 'linux') !== false) $os = 'Linux';

    // Simple Browser detection
    $browser = 'Other';
    if (stripos($ua, 'chrome') !== false && stripos($ua, 'edg') === false && stripos($ua, 'opr') === false) $browser = 'Chrome';
    elseif (stripos($ua, 'safari') !== false && stripos($ua, 'chrome') === false) $browser = 'Safari';
    elseif (stripos($ua, 'firefox') !== false) $browser = 'Firefox';
    elseif (stripos($ua, 'edg') !== false) $browser = 'Edge';
    elseif (stripos($ua, 'opera') !== false || stripos($ua, 'opr') !== false) $browser = 'Opera';

    // Check if same IP visited within last 3 minutes (avoid spamming counts on reload)
    $chkStmt = $pdo->prepare("SELECT id FROM visitor_logs WHERE ip_address = ? AND visited_at > DATE_SUB(NOW(), INTERVAL 3 MINUTE) LIMIT 1");
    $chkStmt->execute([$ip]);
    if (!$chkStmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO visitor_logs (ip_address, user_agent, device_type, browser, os, referrer) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$ip, $ua, $device, $browser, $os, substr($ref, 0, 255)]);
    }

    echo json_encode(['status' => 'success', 'logged' => true]);
    exit;
}

// 2. FETCH STATS & LOGS (For Dashboard)
if ($action === 'stats') {
    // Total visits
    $totalVisits = (int) $pdo->query("SELECT COUNT(*) FROM visitor_logs")->fetchColumn();

    // Unique visitors (by IP)
    $uniqueVisitors = (int) $pdo->query("SELECT COUNT(DISTINCT ip_address) FROM visitor_logs")->fetchColumn();

    // Today's visits
    $todayVisits = (int) $pdo->query("SELECT COUNT(*) FROM visitor_logs WHERE DATE(visited_at) = CURDATE()")->fetchColumn();

    // Latest 50 visitors
    $stmt = $pdo->query("
        SELECT id, ip_address, device_type, browser, os, referrer, 
               DATE_FORMAT(visited_at, '%d %b %Y %h:%i %p') as formatted_time,
               visited_at
        FROM visitor_logs 
        ORDER BY visited_at DESC 
        LIMIT 50
    ");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'metrics' => [
            'total_visits' => $totalVisits,
            'unique_visitors' => $uniqueVisitors,
            'today_visits' => $todayVisits
        ],
        'logs' => $logs
    ]);
    exit;
}
?>