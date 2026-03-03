<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/csrf.php';

$prizes = db()->query('SELECT id, prize_name, color, weight FROM prizes ORDER BY id ASC')->fetchAll();
$sponsors = db()->query('SELECT id, logo_path, sponsor_name FROM sponsors ORDER BY id DESC')->fetchAll();
$about = site_setting('about_content', 'Add an about section from admin panel.');
$siteTitle = site_setting('site_title', 'Spin to Win');
$siteLogo = site_setting('site_logo', '');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title><?= e($siteTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="brand">
        <?php if ($siteLogo): ?><img src="<?= e($siteLogo) ?>" alt="Logo"><?php endif; ?>
        <h1><?= e($siteTitle) ?></h1>
    </div>
</header>
<main>
    <section class="hero">
        <canvas id="wheel" width="500" height="500"></canvas>
        <button id="spinBtn">SPIN</button>
        <p id="statusMsg"></p>
    </section>

    <section class="about card">
        <h2>About</h2>
        <p><?= nl2br(e($about)) ?></p>
    </section>

    <section class="sponsors card">
        <h2>Our Sponsors</h2>
        <div class="marquee">
            <div class="marquee-track">
                <?php foreach (array_merge($sponsors, $sponsors) as $sponsor): ?>
                    <div class="logo-item">
                        <img src="<?= e($sponsor['logo_path']) ?>" alt="<?= e($sponsor['sponsor_name']) ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<div id="winModal" class="modal hidden">
    <div class="modal-content">
        <h3>Congratulations!</h3>
        <p>You won: <span id="wonPrize"></span></p>
        <form id="winnerForm">
            <input type="hidden" name="prize_id" id="prizeId">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <button type="submit">Claim Prize</button>
        </form>
        <p id="formFeedback"></p>
    </div>
</div>

<script>
window.SPIN_CONFIG = {
    prizes: <?= json_encode($prizes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
    csrfToken: <?= json_encode(csrf_token(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>
};
</script>
<script src="assets/js/app.js"></script>
</body>
</html>
