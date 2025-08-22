<?php
require __DIR__.'/../../vendor/autoload.php';
session_start(); if (!($_SESSION['super'] ?? false)) { header('Location: /super/'); exit; }
use App\Models\Incident;
include __DIR__.'/../src/Views/partials/header.php';
include __DIR__.'/../../src/Views/partials/footer.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $ids = array_filter(array_map('intval', explode(',', $_POST['ids'])));
  $target = (int)$_POST['target_id'];
  if ($ids && $target) { Incident::merge($ids, $target); $msg='Unificación realizada.'; }
}
?>
<h1>Unir reportes</h1>
<?php if (!empty($msg)) echo '<div class="badge">'.$msg.'</div>'; ?>
<form method="post">
  <label>IDs a unir (separados por coma)</label>
  <input name="ids" placeholder="12,14,18">
  <label>ID destino </label>
  <input name="target_id" placeholder="12">
  <button class="btn" style="margin-top:8px;">Unir</button>
</form>
<?php include __DIR__.'/../src/Views/partials/footer.php'; ?>
