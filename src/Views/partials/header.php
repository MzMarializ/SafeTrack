<?php
use App\Core\Auth;
Auth::start();
$user = Auth::user();
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> SafeTrack</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
  <link rel="stylesheet" href="/assets/css/styles.css">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header class="top">
  <div class="brand"><a href="/">SafeTrack</a></div>
  <nav>
    <a href="/map.php">Mapa</a>
    <a href="/list.php">Lista</a>
    <a href="/report.php">Reportar</a>
    <a href="/stats.php">Estadísticas</a>
    <a href="/catalogs.php">Catálogos</a>
    <?php if ($user && $user['role']==='reportero'): ?>
      <span>Hola, <?= htmlspecialchars($user['name']) ?></span>
      <a href="/logout.php">Salir</a>
    <?php else: ?>
      <a href="/login.php">Entrar</a>
    <?php endif; ?>
    <a href="/super/">/super</a>
  </nav>
</header>
<main class="container">
