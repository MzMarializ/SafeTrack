<<<<<<< HEAD
# SafeTrack
=======
# 🧩 Proyecto Final – Aplicación Web de Reporte de Incidencias (PHP + MySQL)

Este proyecto implementa el sistema solicitado: registro y visualización de incidencias con mapa, comentarios, correcciones, validación y panel de catálogos.

## 🚀 Requisitos
- PHP 8.1+ con extensiones: `pdo_mysql`, `curl`, `mbstring`, `openssl`
- MySQL 8+
- Servidor web (Apache/Nginx). En desarrollo puedes usar: `php -S localhost:8080 -t public`

## ⚙️ Configuración
1) Crea la base de datos e importa el esquema:
```sql
CREATE DATABASE incidencias CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
Importa `sql/schema.sql` en esa base de datos.

2) Copia `config/config.example.php` a `config/config.php` y ajusta credenciales:
```php
<?php
return [
  'db' => [
    'host' => '127.0.0.1',
    'port' => 3306,
    'name' => 'incidencias',
    'user' => 'root',
    'pass' => ''
  ],
  // crea un usuario /super para validadores:
  'admin' => [
    'username' => 'super',
    'password' => 'super123' // cámbialo
  ],
  // OAuth (opcional: para Gmail y Office 365)
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
```
3) Inicia servidor de desarrollo:
```bash
php -S localhost:8080 -t public
```

4) Abre `http://localhost:8080`:
- **Reportero**: iniciar sesión con Google/Microsoft (o registrarte con email/clave simple si lo habilitas).
- **Validador**: `http://localhost:8080/super/` con credenciales del archivo de config.

## 🗺️ Mapa
- Leaflet + OpenStreetMap y clustering (MarkerCluster).
- Incidencias últimas 24 horas con íconos por tipo. Modal con detalles, comentarios y correcciones.

## 👤 Roles
- **Reportero**: puede crear incidencias, comentar y sugerir correcciones.
- **Validador (/super)**: valida incidencias, aprueba correcciones, unifica duplicados y administra catálogos.

## 📦 Estructura
```
public/           Archivos públicos (rutas, assets)
src/              Modelos, controladores, vistas parciales
sql/              Esquema y datos iniciales
storage/uploads/  Fotos subidas
config/           Configuración de DB y OAuth
```

## 🧪 Datos demo
Al importar `sql/schema.sql` se crean tipos de incidencias y demos. Cambia/borra a gusto.

## 🔐 Notas de OAuth
Este proyecto incluye una implementación OIDC simple por `cURL` (sin composer). Debes registrar tu app en Google/Microsoft, colocar `client_id/secret` y URLs de redirección. También puedes desactivar OAuth y usar registro local básico (ver `public/login.php`, variables al inicio).

## 🛡️ Descargo
Proyecto educativo: sin hardening. Antes de producción, aplica sanitización extra, rate-limit, CSRF tokens, HTTPS, etc.
>>>>>>> 85e9af2 (Primer commit en develop)
