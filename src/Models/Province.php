<?php
namespace App\Models; use App\Core\Database; use PDO;
class Province {
  public static function all(){ return Database::conn()->query("SELECT * FROM provinces ORDER BY name")->fetchAll(PDO::FETCH_ASSOC); }
}