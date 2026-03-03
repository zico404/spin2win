<?php
include __DIR__ . '/_header.php';

$keys = [
    'about_content', 'site_title', 'site_logo', 'admin_email',
    'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'smtp_from_email'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf_token'] ?? null);
    foreach ($keys as $key) {
        if (isset($_POST[$key])) {
            set_site_setting($key, trim((string) $_POST[$key]));
        }
    }
    $msg = 'Settings saved.';
}
?>
<section class="card"><h2>Settings</h2>
<?php if (!empty($msg)): ?><p><?= e($msg) ?></p><?php endif; ?>
<form method="post" style="display:grid;gap:.7rem;">
<?= csrf_input() ?>
<label>Site title <input name="site_title" value="<?= e(site_setting('site_title','Spin to Win')) ?>"></label>
<label>Site logo URL/path <input name="site_logo" value="<?= e(site_setting('site_logo','')) ?>"></label>
<label>About content <textarea name="about_content" rows="5"><?= e(site_setting('about_content','')) ?></textarea></label>
<label>Admin notification email <input name="admin_email" value="<?= e(site_setting('admin_email',ADMIN_EMAIL_FALLBACK)) ?>"></label>
<hr>
<label>SMTP host <input name="smtp_host" value="<?= e(site_setting('smtp_host','smtp.example.com')) ?>"></label>
<label>SMTP port <input name="smtp_port" value="<?= e(site_setting('smtp_port','587')) ?>"></label>
<label>SMTP username <input name="smtp_username" value="<?= e(site_setting('smtp_username','')) ?>"></label>
<label>SMTP password <input type="password" name="smtp_password" value="<?= e(site_setting('smtp_password','')) ?>"></label>
<label>SMTP encryption <select name="smtp_encryption"><option value="tls" <?= site_setting('smtp_encryption','tls')==='tls'?'selected':'' ?>>TLS</option><option value="ssl" <?= site_setting('smtp_encryption')==='ssl'?'selected':'' ?>>SSL</option></select></label>
<label>SMTP from email <input name="smtp_from_email" value="<?= e(site_setting('smtp_from_email',ADMIN_EMAIL_FALLBACK)) ?>"></label>
<button>Save Settings</button>
</form></section>
<?php include __DIR__ . '/_footer.php'; ?>
