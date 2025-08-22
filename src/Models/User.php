<?php
namespace App\Models; use App\Core\Database; use PDO;
class User {
  public static function createLocal($email,$name,$hash){
    $st=Database::conn()->prepare("INSERT INTO users (email,name,password_hash,role,created_at) VALUES (?,?,?,'reportero',NOW())");$st->execute([$email,$name,$hash]);
  }
  public static function byEmail($email){
    $st=Database::conn()->prepare("SELECT * FROM users WHERE email=?");$st->execute([$email]);return $st->fetch(PDO::FETCH_ASSOC);
  }
}