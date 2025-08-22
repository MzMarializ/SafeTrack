<?php
require __DIR__.'/../vendor/autoload.php';
use App\Core\Helpers;
use App\Models\Incident; use App\Models\IncidentType; use App\Models\Province;

$filters = [
  'province_id' => $_GET['province_id'] ?? null,
  'type_id' => $_GET['type_id'] ?? null,
  'q' => $_GET['q'] ?? null,
  'from' => $_GET['from'] ?? null,
  'to' => $_GET['to'] ?? null,
];

$rows = Incident::search($filters);
$types = IncidentType::all();
$provinces = Province::all();

ob_start();
?>
<h1>Incidencias (lista)</h1>

<form class="grid3" method="get">
  <div>
    <label>Provincia</label>
    <select name="province_id">
      <option value="">-- Todas --</option>
      <?php foreach($provinces as $p): ?>
        <option value="<?= $p['id'] ?>" <?= ($filters['province_id']==$p['id']?'selected':'') ?>><?= htmlspecialchars($p['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label>Tipo</label>
    <select name="type_id">
      <option value="">-- Todos --</option>
      <?php foreach($types as $t): ?>
        <option value="<?= $t['id'] ?>" <?= ($filters['type_id']==$t['id']?'selected':'') ?>><?= htmlspecialchars($t['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label>Busqueda por titulo</label>
    <input name="q" value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
  </div>
  <div>
    <label>Desde</label>
    <input type="datetime-local" name="from" value="<?= htmlspecialchars($filters['from'] ?? '') ?>">
  </div>
  <div>
    <label>Hasta</label>
    <input type="datetime-local" name="to" value="<?= htmlspecialchars($filters['to'] ?? '') ?>">
  </div>
  <div style="align-self:end;"><button class="btn">Filtrar</button></div>
</form>

<table class="table">
  <thead><tr><th>#</th><th>Fecha</th><th>Título</th><th>Tipo</th><th>Provincia</th><th>Coords</th></tr></thead>
  <tbody>
  <?php foreach($rows as $r): ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td><?= htmlspecialchars($r['occurred_at']) ?></td>
      <td><a href="/incident.php?id=<?= $r['id'] ?>"><?= htmlspecialchars($r['title']) ?></a></td>
      <td><span class="badge"><?= htmlspecialchars($r['type_name']) ?></span></td>
      <td><?= htmlspecialchars($r['province_name'] ?? '') ?></td>
      <td><?= htmlspecialchars($r['latitude']) ?>, <?= htmlspecialchars($r['longitude']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php
Helpers::view('Lista', ob_get_clean());
