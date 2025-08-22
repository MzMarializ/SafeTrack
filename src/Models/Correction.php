<?php
namespace App\Models;
use App\Core\Database;
use PDO;

class Correction {
  public static function create($incident_id, $user_id, $fields_json) {
    $pdo = Database::conn();
    $stmt = $pdo->prepare("INSERT INTO corrections (incident_id,user_id,fields_json,status,created_at) VALUES (?,?,?,'pending',NOW())");
    $stmt->execute([$incident_id,$user_id,$fields_json]);
  }
  public static function pending() {
    $pdo = Database::conn();
    return $pdo->query("SELECT co.*, i.title FROM corrections co JOIN incidents i ON co.incident_id=i.id WHERE co.status='pending' ORDER BY co.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
  }
  public static function approve($id) {
    $pdo = Database::conn();
    $stmt = $pdo->prepare("UPDATE corrections SET status='approved' WHERE id=?");
    $stmt->execute([$id]);
    // Apply fields
    $c = $pdo->prepare("SELECT * FROM corrections WHERE id=?")->execute([$id]);
  }
  public static function byId($id) {
    $pdo = Database::conn();
    $stmt = $pdo->prepare("SELECT * FROM corrections WHERE id=?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
