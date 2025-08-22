<?php
require __DIR__.'/../vendor/autoload.php';
session_start();
if (!($_SESSION['super'] ?? false)) { echo '<p class="small">Solo validadores/administradores.</p>'; }
use App\Core\Database; use App\Core\Helpers;
$pdo = Database::conn();
$tab = $_GET['tab'] ?? 'provinces';
$tables = [
  'provinces'=>['name'],
  'municipalities'=>['name','province_id'],
  'barrios'=>['name','municipality_id'],
  'incident_types'=>['name']
];
if (!array_key_exists($tab,$tables)) $tab='provinces';
$cols = $tables[$tab];
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_SESSION['super'] ?? false)) {
  if (isset($_POST['create'])) {
    $fields = []; $vals=[]; $q=[];
    foreach ($cols as $c){ $fields[]=$c; $vals[]=$_POST[$c]; $q[]='?'; }
    $sql = "INSERT INTO $tab (".implode(',',$fields).") VALUES (".implode(',',$q).")";
    $st=$pdo->prepare($sql); $st->execute($vals);
  }
  if (isset($_POST['delete'])) {
    $st=$pdo->prepare("DELETE FROM $tab WHERE id=?"); $st->execute([ (int)$_POST['id'] ]);
  }
  header('Location: /catalogs.php?tab='.$tab); exit;
}
$rows = $pdo->query("SELECT * FROM $tab ORDER BY id DESC")->fetchAll();
include __DIR__.'/../src/Views/partials/header.php';
?>
<h1>Catálogos</h1>
<a href="map.php">Inicio</a>
<nav>
  <a href="/catalogs.php?tab=provinces">Provincias</a> |
  <a href="/catalogs.php?tab=municipalities">Municipios</a> |
  <a href="/catalogs.php?tab=barrios">Barrios</a> |
  <a href="/catalogs.php?tab=incident_types">Tipos de Incidencia</a>
</nav>
<?php if (!($_SESSION['super'] ?? false)): ?>
<p class="small"> Inicia en /super para editar.</p>
<?php endif; ?>
<table class="table">
<tr>
  <th>ID</th>
  <?php foreach($cols as $c): ?><th><?= htmlspecialchars($c) ?></th><?php endforeach; ?>
  <?php if (($_SESSION['super'] ?? false)): ?><th>Acciones</th><?php endif; ?>
</tr>
<?php foreach($rows as $r): ?>
<tr>
  <td><?= $r['id'] ?></td>
  <?php foreach($cols as $c): ?><td><?= htmlspecialchars($r[$c]) ?></td><?php endforeach; ?>
  <?php if (($_SESSION['super'] ?? false)): ?>
    <td>
      <form method="post" style="display:inline">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">
        <button class="btn secondary" name="delete" onclick="return confirm('Eliminar?')">Eliminar</button>
      </form>
    </td>
  <?php endif; ?>
</tr>
<?php endforeach; ?>
</table>

<?php if (($_SESSION['super'] ?? false)): ?>
<h3>Agregar</h3>
<form method="post" class="grid3">
  <?php foreach($cols as $c): ?>
    <div>
      <label><?= htmlspecialchars($c) ?></label>
      <input name="<?= htmlspecialchars($c) ?>" required>
    </div>
  <?php endforeach; ?>
  <div style="align-self:end;"><button class="btn" name="create">Crear</button></div>
</form>
<?php endif; ?>

<?php include __DIR__.'/../src/Views/partials/footer.php'; ?>
