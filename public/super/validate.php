<?php
require __DIR__.'/../../vendor/autoload.php';
session_start(); if (!($_SESSION['super'] ?? false)) { header('Location: /super/'); exit; }
use App\Models\Incident;
use App\Core\Helpers;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (isset($_POST['approve'])) Incident::approve((int)$_POST['id']);
  if (isset($_POST['reject'])) Incident::reject((int)$_POST['id']);
  header('Location: /super/validate.php'); exit;
}
$pending = Incident::pending();
include __DIR__.'/../src/Views/partials/header.php';
include __DIR__.'/../../src/Views/partials/footer.php';
?>

<h1>Validar reportes</h1>
<table class="table">
<tr><th>#</th><th>Título</th><th>Tipo</th><th>Fecha</th><th>Acciones</th></tr>
<?php foreach($pending as $p): ?>
<tr>
  <td><?= $p['id'] ?></td>
  <td><?= htmlspecialchars($p['title']) ?></td>
  <td><?= htmlspecialchars($p['type_name']) ?></td>
  <td><?= htmlspecialchars($p['occurred_at']) ?></td>
  <td>
    <form method="post" style="display:inline"><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="btn" name="approve">Aprobar</button></form>
    <form method="post" style="display:inline"><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="btn secondary" name="reject">Rechazar</button></form>
  </td>
</tr>
<?php endforeach; ?>
</table>
<?php if (!$pending) { echo '<div class="empty-state">No hay reportes.</div>'; } ?>

<?php include __DIR__.'/../src/Views/partials/footer.php'; ?>
