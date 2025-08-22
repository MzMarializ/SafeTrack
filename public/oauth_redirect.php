<?php
require __DIR__.'/../vendor/autoload.php';
use App\Core\Auth;
$c = require __DIR__ . '/../config/config.php';
$provider = $_GET['provider'] ?? 'google';

if ($provider==='google') {
  $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query([
    'client_id'=>$c['oauth']['google']['client_id'],
    'redirect_uri'=>$c['oauth']['google']['redirect_uri'],
    'response_type'=>'code',
    'scope'=>'openid email profile',
    'prompt'=>'select_account',
    'access_type'=>'online'
  ]);
} else {
  $tenant = 'common';
  $authUrl = 'https://login.microsoftonline.com/'.$tenant.'/oauth2/v2.0/authorize?'.http_build_query([
    'client_id'=>$c['oauth']['microsoft']['client_id'],
    'redirect_uri'=>$c['oauth']['microsoft']['redirect_uri'],
    'response_type'=>'code',
    'scope'=>'openid email profile User.Read'
  ]);
}
header('Location: '.$authUrl);
exit;
