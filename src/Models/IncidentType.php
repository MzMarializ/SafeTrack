<?php
namespace App\Models; use App\Core\Database; use PDO;
class IncidentType {
  public static function all(){ return Database::conn()->query("SELECT * FROM incident_types ORDER BY name")->fetchAll(PDO::FETCH_ASSOC); }
}