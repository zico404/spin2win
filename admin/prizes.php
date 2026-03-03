<?php
include __DIR__ . '/_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf_token'] ?? null);
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $stmt = db()->prepare('INSERT INTO prizes (prize_name, color, weight) VALUES (:n, :c, :w)');
        $stmt->execute([
            'n' => trim((string) $_POST['prize_name']),
            'c' => trim((string) $_POST['color']),
            'w' => max(1, (int) $_POST['weight']),
        ]);
    }

    if ($action === 'update') {
        $stmt = db()->prepare('UPDATE prizes SET prize_name = :n, color = :c, weight = :w WHERE id = :id');
        $stmt->execute([
            'id' => (int) $_POST['id'],
            'n' => trim((string) $_POST['prize_name']),
            'c' => trim((string) $_POST['color']),
            'w' => max(1, (int) $_POST['weight']),
        ]);
    }

    if ($action === 'delete') {
        $stmt = db()->prepare('DELETE FROM prizes WHERE id = :id');
        $stmt->execute(['id' => (int) $_POST['id']]);
    }

    header('Location: prizes.php');
    exit;
}

$prizes = db()->query('SELECT * FROM prizes ORDER BY id DESC')->fetchAll();
?>
<section class="card">
<h2>Prizes</h2>
<form method="post" style="display:grid;gap:.6rem;grid-template-columns:2fr 1fr 1fr auto;align-items:end;">
<?= csrf_input() ?><input type="hidden" name="action" value="create">
<input name="prize_name" placeholder="Prize name" required>
<input name="color" type="color" value="#d4af37" required>
<input name="weight" type="number" min="1" value="1" required>
<button>Add</button>
</form>
<table style="width:100%;margin-top:1rem;"><tr><th>ID</th><th>Name</th><th>Color</th><th>Weight</th><th>Action</th></tr>
<?php foreach ($prizes as $p): ?>
<tr>
<form method="post">
<?= csrf_input() ?>
<input type="hidden" name="action" value="update"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
<td><?= (int)$p['id'] ?></td>
<td><input name="prize_name" value="<?= e($p['prize_name']) ?>"></td>
<td><input type="color" name="color" value="<?= e($p['color']) ?>"></td>
<td><input type="number" name="weight" min="1" value="<?= (int)$p['weight'] ?>"></td>
<td><button>Save</button></form>
<form method="post" style="display:inline-block;" onsubmit="return confirm('Delete prize?')">
<?= csrf_input() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button>Delete</button>
</form></td>
</tr>
<?php endforeach; ?></table>
</section>
<?php include __DIR__ . '/_footer.php'; ?>
