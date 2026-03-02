<?php include __DIR__ . '/_header.php';
$rows = db()->query('SELECT * FROM submissions ORDER BY id DESC')->fetchAll();
?>
<section class="card"><h2>Submissions</h2>
<table style="width:100%;"><tr><th>Date</th><th>Name</th><th>Email</th><th>Phone</th><th>Prize</th></tr>
<?php foreach ($rows as $r): ?><tr>
<td><?= e($r['created_at']) ?></td><td><?= e($r['full_name']) ?></td><td><?= e($r['email']) ?></td><td><?= e($r['phone']) ?></td><td><?= e($r['prize_name']) ?></td>
</tr><?php endforeach; ?>
</table></section>
<?php include __DIR__ . '/_footer.php'; ?>
