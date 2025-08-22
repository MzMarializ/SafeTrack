<?php
require __DIR__.'/../vendor/autoload.php';
use App\Core\Database;
include __DIR__.'/../src/Views/partials/header.php';
$pdo = Database::conn();
$data = $pdo->query("SELECT t.name, COUNT(*) c FROM incidents i JOIN incident_types t ON i.type_id=t.id WHERE i.status='approved' GROUP BY t.id ORDER BY c DESC")->fetchAll();
?>
<h1>Estadísticas</h1>
<canvas id="chart" width="600" height="360"></canvas>
<script>
const labels = <?= json_encode(array_column($data,'name')) ?>;
const counts = <?= json_encode(array_map('intval', array_column($data,'c'))) ?>;
const ctx = document.getElementById('chart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: { labels, datasets: [{ label:'Incidencias por tipo', data: counts }]},
  options: { scales: { y: { beginAtZero: true } } }
});
</script>
<?php include __DIR__.'/../src/Views/partials/footer.php'; ?>
