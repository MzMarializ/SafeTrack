<?php
require __DIR__.'/../vendor/autoload.php';
use App\Core\Auth;

$ALLOW_LOCAL = true;
$msg = null;
if ($_SERVER['REQUEST_METHOD']==='POST' && $ALLOW_LOCAL) {
  $email = trim($_POST['email'] ?? '');
  $name = trim($_POST['name'] ?? '');
  $pass = $_POST['password'] ?? '';
  if ($email && $name && $pass) {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    App\Models\User::createLocal($email,$name,$hash);
    $u = App\Models\User::byEmail($email);
    Auth::login(['id'=>$u['id'],'email'=>$u['email'],'name'=>$u['name'],'role'=>'reportero']);
    header('Location: /'); exit;
  } else $msg = 'Completa los campos.';
}
?>
<?php include __DIR__.'/../src/Views/partials/header.php'; ?>
<h1>Iniciar sesión</h1>
<?php if ($msg): ?><div class="badge"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<div class="grid">
  <div>
    <a class="btn" href="/oauth_redirect.php?provider=google">Continuar con Google</a>
    <a class="btn" href="/oauth_redirect.php?provider=microsoft" style="margin-left:8px;">Continuar con Microsoft</a>
  </div>
  <?php if ($ALLOW_LOCAL): ?>
  <form method="post">
    <h3>Inicio de sesion</h3>
    <label>Username</label><input name="name" required>
    <label>Email</label><input name="email" type="email" required>
    <label>Password</label><input name="password" type="password" required>
    <button class="btn" style="margin-top:8px;">Entrar</button>
  </form>
  <?php endif; ?>
</div>
<?php include __DIR__.'/../src/Views/partials/footer.php'; ?>
