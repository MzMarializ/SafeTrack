<?php
require __DIR__.'/../vendor/autoload.php';
use App\Core\Auth; use App\Core\Helpers;
use App\Models\Incident; use App\Models\Comment; use App\Models\Correction;

Auth::start();
$id = (int)($_GET['id'] ?? 0);
$inc = Incident::byId($id);
if (!$inc) { http_response_code(404); echo "<p>No encontrado</p>"; exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (isset($_POST['comment']) && Auth::id()) {
    Comment::create($id, Auth::id(), trim($_POST['comment']));
  }
  if (isset($_POST['correction']) && Auth::id()) {
    $fields = [];
    foreach (['deaths','injuries','province_id','municipality_id','loss_rd','latitude','longitude'] as $f) {
      if ($_POST[$f] !== '') $fields[$f] = $_POST[$f];
    }
    Correction::create($id, Auth::id(), json_encode($fields, JSON_UNESCAPED_UNICODE));
  }
  header("Location: /incident.php?id=".$id);
  exit;
}

$comments = Comment::forIncident($id);
?>
<div>
  <a href="map.php">Inicio</a>
  <h2><?= htmlspecialchars($inc['title']) ?></h2>
  <div class="small"><?= htmlspecialchars($inc['occurred_at']) ?> — <span class="badge"><?= htmlspecialchars($inc['type_name']) ?></span></div>
  <p><?= nl2br(htmlspecialchars($inc['description'])) ?></p>
  <div><strong>Ubicación:</strong> <?= htmlspecialchars($inc['province_name']??'') ?>, <?= htmlspecialchars($inc['municipality_name']??'') ?>, <?= htmlspecialchars($inc['barrio_name']??'') ?></div>
  <div><strong>Coordenadas:</strong> <?= htmlspecialchars($inc['latitude']) ?>, <?= htmlspecialchars($inc['longitude']) ?></div>
  <div><strong>Muertos:</strong> <?= (int)$inc['deaths'] ?> — <strong>Heridos:</strong> <?= (int)$inc['injuries'] ?></div>
  <div><strong>Pérdida estimada RD$:</strong> <?= number_format((float)$inc['loss_rd'],2) ?></div>
  <?php if ($inc['social_link']): ?><div><a href="<?= htmlspecialchars($inc['social_link']) ?>" target="_blank">Ver en redes</a></div><?php endif; ?>
  <?php if ($inc['photo_path']): ?><div><img src="<?= htmlspecialchars($inc['photo_path']) ?>" alt="foto" style="max-width:100%;border-radius:8px;"></div><?php endif; ?>
</div>

<hr>
<h3>Comentarios</h3>
<?php foreach($comments as $c): ?>
  <div><strong><?= htmlspecialchars($c['name']) ?>:</strong> <?= nl2br(htmlspecialchars($c['content'])) ?> <span class="small">(<?= htmlspecialchars($c['created_at']) ?>)</span></div>
<?php endforeach; ?>

<?php if (Auth::id()): ?>
<form method="post" style="margin-top:8px;">
  <textarea name="comment" placeholder="Escribe un comentario"></textarea>
  <button class="btn">Publicar</button>
</form>

<hr>
<h3>Sugerir corrección</h3>

<form method="post">
  <input type="hidden" name="correction" value="1">
  <div class="grid3">
    
    <div>
      <label>Muertos</label>
      <input type="number" name="deaths" min="0" step="1">
    </div>
    <div>
      <label>Heridos</label>
      <input type="number" name="injuries" min="0" step="1">
    </div>
    <div>
      <label>Pérdida RD$</label>
      <input type="number" name="loss_rd" min="0" step="0.01">
    </div>
    <div>
      <label>Provincia ID</label>
      <input type="number" name="province_id" min="1">
    </div>
    <div>
      <label>Municipio ID</label>
      <input type="number" name="municipality_id" min="1">
    </div>
    <div>
      <label>Latitud / Longitud</label>
      <div class="grid"><input type="text" name="latitude"><input type="text" name="longitude"></div>
      <a href="map.php">Inicio</a>
    </div>
  </div>
  <button class="btn" style="margin-top:8px;">Enviar corrección</button>
</form>
<?php else: ?>
<p class="small">Inicia sesion.</p>

<?php endif; ?>
