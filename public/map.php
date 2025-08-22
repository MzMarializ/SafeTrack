<?php
require __DIR__.'/../vendor/autoload.php';
use App\Core\Helpers; use App\Core\Database;
use App\Models\Incident; use App\Models\IncidentType;
ob_start();
$incidents = Incident::latest24hApproved();
$types = IncidentType::all();
?>
<h1>Mapa</h1>
<div class="grid">
  <div>
    <form method="get" action="/list.php">
      <label>Tipo de incidencia</label>
      <select name="type_id">
        <option value="">-- Todos --</option>
        <?php foreach($types as $t): ?>
          <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <label>Buscar por titulo</label>
      <input type="text" name="q" placeholder="Ej. choque Av. 27">
      <button class="btn" style="margin-top:8px;">Buscar en lista</button>
    </form>
  </div>
  <div class="small">Incidencias ocurridas.</div>
</div>
<div id="map"></div>

<div id="modal" class="modal"><div class="card">
  <button class="btn secondary" data-modal-close>&times; cerrar</button>
  <div id="modal-content"></div>
</div></div>

<script>
const incidents = <?= json_encode($incidents); ?>;
const icons = {
  'accidente': '/assets/img/icons/accidente.png',
  'pelea': '/assets/img/icons/pelea.png',
  'robo': '/assets/img/icons/robo.png',
  'desastre': '/assets/img/icons/desastre.png',
  'default': '/assets/img/icons/default.png'
};
const map = L.map('map').setView([18.4861, -69.9312], 11);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap'}).addTo(map);
const markers = L.markerClusterGroup();
incidents.forEach(i=>{
  const iconKey = (i.type_name||'').toLowerCase();
  const icon = L.icon({iconUrl: icons[iconKey] || icons['default'], iconSize:[28,28]});
  const m = L.marker([i.latitude, i.longitude], {icon});
  m.on('click', ()=> openIncident(i.id));
  markers.addLayer(m);
});
map.addLayer(markers);

function openIncident(id){
  fetch('/incident.php?id='+id).then(r=>r.text()).then(html=>{
    document.querySelector('#modal-content').innerHTML = html;
    document.querySelector('#modal').classList.add('show');
  });
}
</script>
<?php
Helpers::view('Mapa', ob_get_clean());
