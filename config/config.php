<?php
return [
  'db' => [
    'host' => '127.0.0.1',
    'port' => 3306,
    'name' => 'incidencias',
    'user' => 'root',
    'pass' => ''
  ],
  'admin' => [
    'username' => 'super',
    'password' => 'super123'
  ],
  'oauth' => [
    'google' => [
      'client_id' => 'TU_CLIENT_ID',
      'client_secret' => 'TU_CLIENT_SECRET',
      'redirect_uri' => 'http://localhost:8080/oauth_callback.php?provider=google'
    ],
    'microsoft' => [
      'client_id' => 'TU_CLIENT_ID',
      'client_secret' => 'TU_CLIENT_SECRET',
      'redirect_uri' => 'http://localhost:8080/oauth_callback.php?provider=microsoft'
    ]
  ]
];
