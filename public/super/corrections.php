<?php
require __DIR__.'/../../vendor/autoload.php';
session_start(); if (!($_SESSION['super'] ?? false)) { header('Location: /super/'); exit; }
use App\Models\Correction; use App\Core\Database;
include __DIR__.'/../src/Views/partials/header.php';
include __DIR__.'/../../src/Views/partials/footer.php';
$pending = Correction::pending();
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $id = (int)$_POST['id'];
  $pdo = Database::conn();
  $c = Correction::byId($id);
  if ($c) {
    $fields = json_decode($c['fields_json'], true) ?? [];
    if ($fields) {
      // construir SQL dinámico
      $set = []; $params = [];
      foreach ($fields as $k=>$v){ $set[] = "$k = :$k"; $params[":$k"] = $v; }
      $params[':id'] = $c['incident_id'];
      $sql = "UPDATE incidents SET ".implode(',',$set)." WHERE id=:id";
      $st = $pdo->prepare($sql); $st->execute($params);
    }
    $pdo->prepare("UPDATE corrections SET status='approved' WHERE id=?")->execute([$id]);
  }
  header('Location: /super/corrections.php'); exit;
}
?>
<a href="map.php" class="link-inicio">Inicio</a>

<h1>Correcciones pendientes</h1>
<table class="table">
  <tr><th>#</th><th>Incidencia</th><th>Propuesta</th><th>Acción</th></tr>
  <?php foreach($pending as $p): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= htmlspecialchars($p['title']) ?></td>
      <td><pre><?= htmlspecialchars($p['fields_json']) ?></pre></td>
      <td>
        <form method="post"><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="btn">Aprobar</button></form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
<?php include __DIR__.'/../src/Views/partials/footer.php'; ?>
