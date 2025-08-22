<?php
namespace App\Models;
use App\Core\Database;
use PDO;

class Comment {
  public static function create($incident_id, $user_id, $content) {
    $pdo = Database::conn();
    $stmt = $pdo->prepare("INSERT INTO comments (incident_id,user_id,content,created_at) VALUES (?,?,?,NOW())");
    $stmt->execute([$incident_id,$user_id,$content]);
  }
  public static function forIncident($incident_id) {
    $pdo = Database::conn();
    $stmt = $pdo->prepare("SELECT c.*, u.name FROM comments c JOIN users u ON c.user_id=u.id WHERE incident_id=? ORDER BY created_at DESC");
    $stmt->execute([$incident_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
