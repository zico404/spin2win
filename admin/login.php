<?php
require_once __DIR__ . '/_bootstrap.php';

if (current_admin()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf_token'] ?? null);
    $username = trim((string) $_POST['username']);
    $password = (string) $_POST['password'];

    $stmt = db()->prepare('SELECT id, password_hash FROM admins WHERE username = :u LIMIT 1');
    $stmt->execute(['u' => $username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = (int) $admin['id'];
        header('Location: index.php');
        exit;
    }

    $error = 'Invalid credentials';
}
?>
<!doctype html><html><head><meta charset="UTF-8"><title>Admin Login</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<main class="card" style="max-width:420px;margin:3rem auto;">
<h2>Admin Login</h2>
<?php if ($error): ?><p><?= e($error) ?></p><?php endif; ?>
<form method="post">
<?= csrf_input() ?>
<input name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>
</main></body></html>
