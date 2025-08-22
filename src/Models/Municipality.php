<?php
namespace App\Models; use App\Core\Database; use PDO;
class Municipality {
  public static function byProvince($pid){
    $st=Database::conn()->prepare("SELECT * FROM municipalities WHERE province_id=? ORDER BY name");$st->execute([$pid]);return $st->fetchAll(PDO::FETCH_ASSOC);
  }
  public static function all(){return Database::conn()->query("SELECT * FROM municipalities ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);}
}