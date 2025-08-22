<?php
require __DIR__.'/../../vendor/autoload.php';
use App\Core\Auth;
$c = require __DIR__ . '/../../config/config.php';
session_start();
if (!($_SESSION['super'] ?? false)) {
  if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (($_POST['username'] ?? '')===$c['admin']['username'] && ($_POST['password'] ?? '')===$c['admin']['password']) {
      $_SESSION['super'] = true; header('Location: /super/'); exit;
    } else $err='Credenciales inválidas';
  }
  include __DIR__.'/../src/Views/partials/header.php';
  echo '<h1>/super</h1>';
  if (!empty($err)) echo '<div class="badge">'.$err.'</div>';
  echo '<form method="post"><label>Usuario</label><input name="username"><label>Clave</label><input type="password" name="password"><button class="btn" style="margin-top:8px;">Entrar</button></form>';
  include __DIR__.'/../src/Views/partials/footer.php';
  exit;
}
include __DIR__.'/../src/Views/partials/header.php';
include __DIR__.'/../../src/Views/partials/footer.php';
?>
<h1>Panel /super</h1>
<ul>
  <li><a href="/super/validate.php">Validar reportes</a></li>
  <li><a href="/super/corrections.php">Aprobar correcciones</a></li>
  <li><a href="/super/merge.php">Unir reportes</a></li>
  <li><a href="/catalogs.php">Catálogos</a></li>
  <li><a href="/stats.php">Estadísticas</a></li>
</ul>



<?php include __DIR__.'/../src/Views/partials/footer.php'; ?>
