<?php
include __DIR__ . '/_header.php';

function upload_logo(array $file): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Logo upload failed.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Invalid image format.');
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Image too large.');
    }

    $name = 'sponsor_' . bin2hex(random_bytes(6)) . '.' . $allowed[$mime];
    $target = UPLOAD_DIR . $name;
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        throw new RuntimeException('Unable to save image.');
    }

    return UPLOAD_URL . $name;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf_token'] ?? null);
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $logoPath = upload_logo($_FILES['logo']);
        $stmt = db()->prepare('INSERT INTO sponsors (sponsor_name, logo_path) VALUES (:n, :l)');
        $stmt->execute(['n' => trim((string) $_POST['sponsor_name']), 'l' => $logoPath]);
    }

    if ($action === 'delete') {
        $stmt = db()->prepare('DELETE FROM sponsors WHERE id = :id');
        $stmt->execute(['id' => (int) $_POST['id']]);
    }

    header('Location: sponsors.php');
    exit;
}

$sponsors = db()->query('SELECT * FROM sponsors ORDER BY id DESC')->fetchAll();
?>
<section class="card">
<h2>Sponsors</h2>
<form method="post" enctype="multipart/form-data">
<?= csrf_input() ?><input type="hidden" name="action" value="create">
<input name="sponsor_name" placeholder="Sponsor name" required>
<input name="logo" type="file" accept="image/*" required>
<button>Add Sponsor</button>
</form>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;margin-top:1rem;">
<?php foreach ($sponsors as $s): ?>
<article class="card" style="margin:0;">
<img src="../<?= e($s['logo_path']) ?>" alt="<?= e($s['sponsor_name']) ?>" style="width:100%;height:120px;object-fit:contain;">
<p><?= e($s['sponsor_name']) ?></p>
<form method="post" onsubmit="return confirm('Delete sponsor?')">
<?= csrf_input() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
<button>Delete</button></form>
</article>
<?php endforeach; ?>
</div></section>
<?php include __DIR__ . '/_footer.php'; ?>
