<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request.'], 405);
}

verify_csrf_request();

if (!empty($_SESSION['has_spun'])) {
    json_response(['success' => false, 'message' => 'Only one spin per session allowed.'], 429);
}

$prizes = db()->query('SELECT id, prize_name, color, weight FROM prizes')->fetchAll();
if (!$prizes) {
    json_response(['success' => false, 'message' => 'No prizes available.'], 500);
}

$winner = weighted_random_prize($prizes);
$_SESSION['has_spun'] = true;
$_SESSION['won_prize_id'] = (int) $winner['id'];

json_response(['success' => true, 'prize' => $winner]);
