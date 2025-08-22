<?php
namespace App\Models;
use App\Core\Database;
use PDO;

class Incident {
  public static function create(array $data): int {
    $pdo = Database::conn();
    $sql = "INSERT INTO incidents
      (occurred_at, title, type_id, description, province_id, municipality_id, barrio_id,
       latitude, longitude, deaths, injuries, loss_rd, social_link, photo_path, status, reporter_id, created_at)
      VALUES (:occurred_at,:title,:type_id,:description,:province_id,:municipality_id,:barrio_id,
       :latitude,:longitude,:deaths,:injuries,:loss_rd,:social_link,:photo_path,'pending',:reporter_id,NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    return (int)$pdo->lastInsertId();
  }
  public static function latest24hApproved() {
    $pdo = Database::conn();
    $stmt = $pdo->query("SELECT i.*, t.name AS type_name, p.name AS province_name, m.name AS municipality_name, b.name AS barrio_name
      FROM incidents i
      JOIN incident_types t ON i.type_id = t.id
      LEFT JOIN provinces p ON i.province_id = p.id
      LEFT JOIN municipalities m ON i.municipality_id = m.id
      LEFT JOIN barrios b ON i.barrio_id = b.id
      WHERE i.status='approved' AND i.occurred_at >= (NOW() - INTERVAL 1 DAY)
      ORDER BY i.occurred_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public static function search(array $f) {
    $pdo = Database::conn();
    $w = ["1=1"];
    $params = [];
    if (!empty($f['province_id'])) { $w[] = "i.province_id = :province_id"; $params[':province_id'] = $f['province_id']; }
    if (!empty($f['type_id'])) { $w[] = "i.type_id = :type_id"; $params[':type_id'] = $f['type_id']; }
    if (!empty($f['q'])) { $w[] = "i.title LIKE :q"; $params[':q'] = '%' . $f['q'] . '%'; }
    if (!empty($f['from'])) { $w[] = "i.occurred_at >= :from"; $params[':from'] = $f['from']; }
    if (!empty($f['to'])) { $w[] = "i.occurred_at <= :to"; $params[':to'] = $f['to']; }
    $sql = "SELECT i.*, t.name AS type_name, p.name AS province_name, m.name AS municipality_name, b.name AS barrio_name
      FROM incidents i
      JOIN incident_types t ON i.type_id = t.id
      LEFT JOIN provinces p ON i.province_id = p.id
      LEFT JOIN municipalities m ON i.municipality_id = m.id
      LEFT JOIN barrios b ON i.barrio_id = b.id
      WHERE ".implode(' AND ', $w)."
      ORDER BY i.occurred_at DESC LIMIT 200";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public static function byId($id) {
    $pdo = Database::conn();
    $stmt = $pdo->prepare("SELECT i.*, t.name AS type_name, p.name AS province_name, m.name AS municipality_name, b.name AS barrio_name
      FROM incidents i
      JOIN incident_types t ON i.type_id = t.id
      LEFT JOIN provinces p ON i.province_id = p.id
      LEFT JOIN municipalities m ON i.municipality_id = m.id
      LEFT JOIN barrios b ON i.barrio_id = b.id
      WHERE i.id=? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public static function pending() {
    $pdo = Database::conn();
    $stmt = $pdo->query("SELECT i.*, t.name AS type_name FROM incidents i JOIN incident_types t ON i.type_id=t.id WHERE status='pending' ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public static function approve($id) {
    $pdo = Database::conn();
    $stmt = $pdo->prepare("UPDATE incidents SET status='approved' WHERE id=?");
    return $stmt->execute([$id]);
  }
  public static function reject($id) {
    $pdo = Database::conn();
    $stmt = $pdo->prepare("UPDATE incidents SET status='rejected' WHERE id=?");
    return $stmt->execute([$id]);
  }
  public static function merge(array $ids, int $targetId) {
    $pdo = Database::conn();
    // Reassociate comments/corrections to target, then delete others
    $in = implode(',', array_map('intval',$ids));
    $pdo->exec("UPDATE comments SET incident_id = $targetId WHERE incident_id IN ($in) AND incident_id <> $targetId");
    $pdo->exec("UPDATE corrections SET incident_id = $targetId WHERE incident_id IN ($in) AND incident_id <> $targetId");
    $pdo->exec("DELETE FROM incidents WHERE id IN ($in) AND id <> $targetId");
  }
}
