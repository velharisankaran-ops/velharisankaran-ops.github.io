<?php
require_once 'auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'] ?? '';
    $pin = $_POST['pin'] ?? '';
    
    $authUser = verifyAdminCredentials($userId, $pin);
    if ($authUser) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $authUser['user_id'];
        $_SESSION['role'] = $authUser['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = 'Invalid User ID or M-PIN. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Velhari Sankaran Hub - Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0f172a",
                        "on-primary": "#ffffff",
                        "surface-white": "#FFFFFF",
                        "background-gray": "#F8FAFC",
                        "text-muted": "#64748B",
                        "outline-variant": "#cbd5e1",
                        "surface-container-low": "#f1f5f9",
                        "surface-container": "#e2e8f0",
                        "on-surface": "#0f172a",
                        "on-surface-variant": "#475569",
                        "inverse-surface": "#1e293b",
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "stack-base": "1rem",
                        "gutter": "0.75rem",
                        "container-margin": "1rem",
                        "stack-tight": "0.35rem",
                        "card-padding": "1rem"
                    },
                    fontFamily: {
                        "headline-lg": ["Hanken Grotesk", "sans-serif"],
                        "headline-md": ["Hanken Grotesk", "sans-serif"],
                        "body-md": ["Hanken Grotesk", "sans-serif"],
                        "body-sm": ["Hanken Grotesk", "sans-serif"],
                        "label-bold": ["Inter", "sans-serif"],
                        "numeric-data": ["Inter", "monospace"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>
<body class="bg-background-gray text-on-surface min-h-screen flex flex-col antialiased">

    <!-- Top Navigation Header -->
    <header class="w-full flex justify-center items-center px-container-margin py-6 mt-4 sm:mt-8">
        <a href="../index.html" class="flex items-center gap-2.5 group">
            <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center font-bold text-base shadow-sm group-hover:scale-105 transition-transform">
                VS
            </div>
            <h1 class="font-headline-lg text-xl sm:text-2xl text-primary font-bold tracking-tight">Velhari Sankaran Hub</h1>
        </a>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow flex flex-col items-center justify-center px-4 pb-12 w-full max-w-md mx-auto">
        <div class="w-full bg-surface-white rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.06)] border border-outline-variant/60 p-6 sm:p-8">
            <div class="mb-6 text-center">
                <h2 class="font-headline-md text-lg sm:text-xl font-bold text-on-surface mb-1">Welcome to Velhari Sankaran Hub</h2>
                <p class="font-body-md text-xs sm:text-sm text-on-surface-variant">Securely manage your financial portfolio & operations.</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2 animate-shake">
                    <span class="material-symbols-outlined text-[18px]">error</span>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" class="space-y-4 w-full">
                <!-- User ID Field -->
                <div>
                    <label class="block font-label-bold text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="userId">USER ID</label>
                    <div class="relative">
                        <input class="w-full h-12 px-4 bg-surface-white border border-outline-variant rounded-xl font-body-md text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-text-muted" id="userId" name="userId" placeholder="Enter your User ID (e.g. velharisankaran)" required="" type="text" autocomplete="username" autofocus value="<?= htmlspecialchars($_POST['userId'] ?? 'velharisankaran') ?>"/>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-text-muted">person</span>
                        </div>
                    </div>
                </div>

                <!-- PIN Field -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block font-label-bold text-xs font-bold uppercase tracking-wider text-on-surface-variant" for="pin">M-PIN</label>
                        <a class="font-body-sm text-xs font-semibold text-blue-600 hover:underline transition-all" href="../dashboard.html">Founder Mode</a>
                    </div>
                    <div class="relative">
                        <input class="w-full h-12 px-4 bg-surface-white border border-outline-variant rounded-xl font-numeric-data text-base text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-text-muted tracking-[0.2em]" id="pin" name="pin" placeholder="Enter password / M-PIN" required="" type="password" maxlength="32" autocomplete="current-password"/>
                        <button type="button" onclick="togglePinVisibility()" aria-label="Toggle PIN visibility" class="absolute inset-y-0 right-0 pr-3 flex items-center text-text-muted hover:text-on-surface transition-colors">
                            <span id="pinIcon" class="material-symbols-outlined">visibility_off</span>
                        </button>
                    </div>
                </div>

                <!-- Login Button -->
                <div class="pt-2">
                    <button class="w-full h-12 bg-primary hover:bg-inverse-surface text-on-primary font-headline-md font-bold text-sm rounded-xl flex items-center justify-center gap-2 active:scale-[0.98] transition-all shadow-md" type="submit">
                        <span>Login</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 flex flex-col items-center gap-4">
                <div class="relative w-full flex items-center py-1">
                    <div class="flex-grow border-t border-outline-variant/60"></div>
                    <span class="flex-shrink-0 mx-3 font-body-sm text-[11px] text-text-muted uppercase tracking-widest font-semibold">Or Quick Access</span>
                    <div class="flex-grow border-t border-outline-variant/60"></div>
                </div>
                <a href="../dashboard.html" class="w-12 h-12 rounded-2xl bg-surface-container-low border border-outline-variant hover:border-primary flex items-center justify-center text-primary hover:bg-surface-container transition-all shadow-sm active:scale-95" title="Passcode Command Center">
                    <span class="material-symbols-outlined text-2xl">fingerprint</span>
                </a>
            </div>
        </div>

        <div class="mt-6 text-center w-full">
            <a class="font-body-md text-xs font-medium text-on-surface-variant hover:text-primary transition-colors inline-flex items-center gap-1" href="https://wa.me/919567092833" target="_blank" rel="noopener noreferrer">
                Need help? <span class="material-symbols-outlined text-sm">help</span>
            </a>
        </div>
    </main>

    <script>
        function togglePinVisibility() {
            const pinInput = document.getElementById('pin');
            const pinIcon = document.getElementById('pinIcon');
            if (pinInput.type === 'password') {
                pinInput.type = 'text';
                pinIcon.textContent = 'visibility';
            } else {
                pinInput.type = 'password';
                pinIcon.textContent = 'visibility_off';
            }
        }
    </script>
</body>
</html>
