<?php
namespace App\Core;
use App\Core\Database;
use PDO;

class Auth {
  public static function start() { if (session_status() === PHP_SESSION_NONE) session_start(); }
  public static function user() { self::start(); return $_SESSION['user'] ?? null; }
  public static function id() { return self::user()['id'] ?? null; }
  public static function role() { return self::user()['role'] ?? null; }
  public static function login(array $u) { self::start(); $_SESSION['user'] = $u; }
  public static function logout() { self::start(); session_destroy(); }
  public static function requireRole(array $roles) {
    $u = self::user();
    if (!$u || !in_array($u['role'], $roles)) { header('Location: /login.php'); exit; }
  }
  public static function findOrCreateOidcUser(string $email, string $name): array {
    $pdo = Database::conn();
    $pdo->prepare("INSERT INTO users (email,name,role,created_at) VALUES (?,?, 'reportero', NOW())
                   ON DUPLICATE KEY UPDATE name = VALUES(name)")->execute([$email, $name]);
    $stmt = $pdo->prepare("SELECT id,email,name,role FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
