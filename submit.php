<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request.'], 405);
}

verify_csrf_request();

$fullName = trim((string) filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$phone = trim((string) filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS));
$prizeId = (int) ($_POST['prize_id'] ?? 0);

if (!$fullName || !$email || !$phone || !$prizeId || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(['success' => false, 'message' => 'Please fill all fields with valid values.'], 422);
}

if (empty($_SESSION['won_prize_id']) || (int) $_SESSION['won_prize_id'] !== $prizeId) {
    json_response(['success' => false, 'message' => 'Invalid prize submission.'], 403);
}

$stmt = db()->prepare('SELECT prize_name FROM prizes WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $prizeId]);
$prize = $stmt->fetch();
if (!$prize) {
    json_response(['success' => false, 'message' => 'Prize not found.'], 404);
}

$insert = db()->prepare('INSERT INTO submissions (full_name, email, phone, prize_id, prize_name) VALUES (:n, :e, :p, :pid, :pn)');
$insert->execute([
    'n' => $fullName,
    'e' => $email,
    'p' => $phone,
    'pid' => $prizeId,
    'pn' => $prize['prize_name'],
]);

$submission = [
    'full_name' => $fullName,
    'email' => $email,
    'phone' => $phone,
    'prize_name' => $prize['prize_name'],
    'created_at' => date('Y-m-d H:i:s'),
];
send_submission_email($submission);

json_response(['success' => true, 'message' => 'Submission received successfully.']);
