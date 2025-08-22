<?php
namespace App\Core;

class Helpers {
  public static function view(string $title, string $body, array $params = []) {
    extract($params);
    include __DIR__ . '/../Views/partials/header.php';
    echo $body;
    include __DIR__ . '/../Views/partials/footer.php';
  }
  public static function isPost(): bool { return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'; }
  public static function redirect(string $path) { header("Location: $path"); exit; }
  public static function now(): string { return date('Y-m-d H:i:s'); }
  public static function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
  public static function asset($path) { return '/'+$path; }
}
