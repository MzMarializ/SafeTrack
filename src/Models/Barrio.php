<?php
namespace App\Models; use App\Core\Database; use PDO;
class Barrio {
  public static function byMunicipality($mid){
    $st=Database::conn()->prepare("SELECT * FROM barrios WHERE municipality_id=? ORDER BY name");$st->execute([$mid]);return $st->fetchAll(PDO::FETCH_ASSOC);
  }
  public static function all(){return Database::conn()->query("SELECT * FROM barrios ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);}
}