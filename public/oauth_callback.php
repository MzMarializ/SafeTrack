<?php
require __DIR__.'/../vendor/autoload.php';
use App\Core\Auth;

$provider = $_GET['provider'] ?? 'google';
$code = $_GET['code'] ?? null;
if (!$code) { echo 'Falta code'; exit; }
$c = require __DIR__ . '/../config/config.php';

function http_post($url,$data){
  $ch = curl_init($url);
  curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true, CURLOPT_POST=>true, CURLOPT_POSTFIELDS=>$data]);
  $res = curl_exec($ch);
  if (curl_errno($ch)) die('cURL error: '.curl_error($ch));
  curl_close($ch);
  return $res;
}
function http_get_json($url,$token=null){
  $ch = curl_init($url);
  curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true, CURLOPT_HTTPHEADER=>$token?['Authorization: Bearer '.$token]:[]]);
  $res = curl_exec($ch);
  if (curl_errno($ch)) die('cURL error: '.curl_error($ch));
  curl_close($ch);
  return json_decode($res,true);
}

if ($provider==='google') {
  $token = json_decode(http_post('https://oauth2.googleapis.com/token',[
    'code'=>$code,
    'client_id'=>$c['oauth']['google']['client_id'],
    'client_secret'=>$c['oauth']['google']['client_secret'],
    'redirect_uri'=>$c['oauth']['google']['redirect_uri'],
    'grant_type'=>'authorization_code'
  ]), true);
  $idinfo = http_get_json('https://www.googleapis.com/oauth2/v3/userinfo', $token['access_token'] ?? '');
  $email = $idinfo['email'] ?? null; $name = $idinfo['name'] ?? ($idinfo['given_name'] ?? 'Usuario');
} else {
  $tenant='common';
  $token = json_decode(http_post('https://login.microsoftonline.com/'.$tenant.'/oauth2/v2.0/token',[
    'client_id'=>$c['oauth']['microsoft']['client_id'],
    'client_secret'=>$c['oauth']['microsoft']['client_secret'],
    'redirect_uri'=>$c['oauth']['microsoft']['redirect_uri'],
    'grant_type'=>'authorization_code',
    'scope'=>'openid email profile User.Read',
    'code'=>$code
  ]), true);
  $idinfo = http_get_json('https://graph.microsoft.com/oidc/userinfo', $token['access_token'] ?? '');
  $email = $idinfo['email'] ?? ($idinfo['preferred_username'] ?? null);
  $name = $idinfo['name'] ?? 'Usuario';
}
if (!$email) { echo 'No se pudo obtener email.'; exit; }

$u = Auth::findOrCreateOidcUser($email, $name);
Auth::login($u);
header('Location: /');
