<?php
require __DIR__.'/../vendor/autoload.php';
use App\Core\Helpers; use App\Core\Auth; use App\Core\Database;
use App\Models\IncidentType; use App\Models\Incident;

Auth::requireRole(['reportero','validator','admin']);
$types = IncidentType::all();

$msg = null;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $data = [
    ':occurred_at' => $_POST['occurred_at'],
    ':title' => $_POST['title'],
    ':type_id' => $_POST['type_id'],
    ':description' => $_POST['description'],
    ':province_id' => $_POST['province_id'] ?: null,
    ':municipality_id' => $_POST['municipality_id'] ?: null,
    ':barrio_id' => $_POST['barrio_id'] ?: null,
    ':latitude' => $_POST['latitude'],
    ':longitude' => $_POST['longitude'],
    ':deaths' => (int)$_POST['deaths'],
    ':injuries' => (int)$_POST['injuries'],
    ':loss_rd' => (float)$_POST['loss_rd'],
    ':social_link' => $_POST['social_link'],
    ':photo_path' => null,
    ':reporter_id' => Auth::id()
  ];
  if (!empty($_FILES['photo']['tmp_name'])) {
    $dir = __DIR__.'/../storage/uploads/';
    if (!is_dir($dir)) mkdir($dir,0777,true);
    $name = 'inc_' . time() . '_' . basename($_FILES['photo']['name']);
    $path = $dir . $name;
    move_uploaded_file($_FILES['photo']['tmp_name'], $path);
    $data[':photo_path'] = '/uploads/' . $name;
  }
  $id = Incident::create($data);
  $msg = "Reporte enviado (#$id). Queda pendiente de validación.";
}

ob_start();
?>
<h1>Reportar incidencia</h1>
<?php if ($msg): ?><div class="badge"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
  <div class="grid3">
    <div>
      <label>Fecha/hora de ocurrencia</label>
      <input type="datetime-local" name="occurred_at" required>
    </div>
    <div>
      <label>Titulo</label>
      <input name="title" required>
    </div>
    <div>
      <label>Tipo</label>
      <select name="type_id" required>
        <?php foreach($types as $t): ?>
          <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Provincia (ID)</label>
      <input name="province_id" type="number">
    </div>
    <div>
      <label>Municipio (ID)</label>
      <input name="municipality_id" type="number">
    </div>
    <div>
      <label>Barrio (ID)</label>
      <input name="barrio_id" type="number">
    </div>
    <div>
      <label>Latitud</label>
      <input name="latitude" required>
    </div>
    <div>
      <label>Longitud</label>
      <input name="longitude" required>
    </div>
    <div>
      <label>Muertos</label>
      <input name="deaths" type="number" min="0" step="1" value="0">
    </div>
    <div>
      <label>Heridos</label>
      <input name="injuries" type="number" min="0" step="1" value="0">
    </div>
    <div>
      <label>Perdida RD$</label>
      <input name="loss_rd" type="number" min="0" step="0.01" value="0">
    </div>
    <div>
      <label>Link a redes</label>
      <input name="social_link" placeholder="https://...">
    </div>
    <div style="grid-column:1/-1">
      <label>Descripción</label>
      <textarea name="description" rows="5"></textarea>
    </div>
    <div>
      <label>Foto</label>
      <input type="file" name="photo" accept="image/*">
    </div>
  </div>
  <button class="btn" style="margin-top:8px;">Enviar</button>
</form>
<?php
Helpers::view('Reportar', ob_get_clean());
