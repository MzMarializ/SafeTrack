<?php
require __DIR__.'/../vendor/autoload.php';
App\Core\Auth::logout();
header('Location: /');
