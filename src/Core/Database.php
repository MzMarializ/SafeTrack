<?php
namespace App\Core;
use PDO; use PDOException;

class Database {
  private static ?PDO $pdo = null;
  public static function conn(): PDO {
    if (self::$pdo) return self::$pdo;
    $c = require __DIR__ . '/../../config/config.php';
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
      $c['db']['host'], $c['db']['port'], $c['db']['name']);
    try {
      self::$pdo = new PDO($dsn, $c['db']['user'], $c['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
    } catch (PDOException $e) {
      die('DB error: ' . $e->getMessage());
    }
    return self::$pdo;
  }
}
