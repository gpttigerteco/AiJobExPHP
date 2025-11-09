<?php
if (!isset($title)) {
    $title = 'AiJobExPHP';
}
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$flashes = $_SESSION['flash'] ?? [];
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; connect-src 'self';">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="d-flex" id="app-shell">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>
    <div class="flex-grow-1">
        <?php include __DIR__ . '/../partials/navbar.php'; ?>
        <main class="container-fluid py-4">
            <?php if (!empty($flashes)): ?>
                <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
                    <?php foreach ($flashes as $flash): ?>
                        <div class="toast show align-items-center text-bg-<?= htmlspecialchars($flash['type']) ?> border-0 mb-2">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <?= htmlspecialchars($flash['message']) ?>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?= $content ?? '' ?>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>
