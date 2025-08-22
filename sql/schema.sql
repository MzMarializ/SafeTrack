-- Esquema base
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS corrections;
DROP TABLE IF EXISTS incidents;
DROP TABLE IF EXISTS barrios;
DROP TABLE IF EXISTS municipalities;
DROP TABLE IF EXISTS provinces;
DROP TABLE IF EXISTS incident_types;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) UNIQUE NOT NULL,
  name VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NULL,
  role ENUM('reportero','validator','admin') DEFAULT 'reportero',
  created_at DATETIME NOT NULL
);

CREATE TABLE incident_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL
);

CREATE TABLE provinces (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL
);

CREATE TABLE municipalities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  province_id INT NOT NULL,
  name VARCHAR(120) NOT NULL,
  FOREIGN KEY (province_id) REFERENCES provinces(id) ON DELETE CASCADE
);

CREATE TABLE barrios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  municipality_id INT NOT NULL,
  name VARCHAR(120) NOT NULL,
  FOREIGN KEY (municipality_id) REFERENCES municipalities(id) ON DELETE CASCADE
);

CREATE TABLE incidents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  occurred_at DATETIME NOT NULL,
  title VARCHAR(255) NOT NULL,
  type_id INT NOT NULL,
  description TEXT,
  province_id INT NULL,
  municipality_id INT NULL,
  barrio_id INT NULL,
  latitude DECIMAL(10,7) NOT NULL,
  longitude DECIMAL(10,7) NOT NULL,
  deaths INT DEFAULT 0,
  injuries INT DEFAULT 0,
  loss_rd DECIMAL(15,2) DEFAULT 0,
  social_link VARCHAR(500) NULL,
  photo_path VARCHAR(500) NULL,
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  reporter_id INT NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (type_id) REFERENCES incident_types(id),
  FOREIGN KEY (province_id) REFERENCES provinces(id),
  FOREIGN KEY (municipality_id) REFERENCES municipalities(id),
  FOREIGN KEY (barrio_id) REFERENCES barrios(id),
  FOREIGN KEY (reporter_id) REFERENCES users(id)
);

CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  incident_id INT NOT NULL,
  user_id INT NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (incident_id) REFERENCES incidents(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE corrections (
  id INT AUTO_INCREMENT PRIMARY KEY,
  incident_id INT NOT NULL,
  user_id INT NOT NULL,
  fields_json JSON NOT NULL,
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  created_at DATETIME NOT NULL,
  FOREIGN KEY (incident_id) REFERENCES incidents(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Catálogos base
INSERT INTO incident_types (name) VALUES ('Accidente'),('Pelea'),('Robo'),('Desastre');

-- Provincias/muns/barrio demo mínimos (puedes cargar dataset real)
INSERT INTO provinces (name) VALUES ('Distrito Nacional'),('Santo Domingo');
INSERT INTO municipalities (province_id,name) VALUES (1,'Santo Domingo de Guzmán'),(2,'Santo Domingo Este');
INSERT INTO barrios (municipality_id,name) VALUES (1,'Gascue'),(2,'Villa Duarte');

-- Usuario de ejemplo
INSERT INTO users (email,name,role,created_at) VALUES ('demo@ejemplo.com','Demo Reportero','reportero',NOW());

-- Incidencia de ejemplo (aprobada ayer)
INSERT INTO incidents (occurred_at,title,type_id,description,province_id,municipality_id,barrio_id,latitude,longitude,deaths,injuries,loss_rd,social_link,photo_path,status,reporter_id,created_at)
VALUES (NOW() - INTERVAL 2 HOUR,'Choque en la Av. 27',1,'Colisión leve con heridos',1,1,1,18.4719,-69.8923,0,2,50000,'https://example.com',NULL,'approved',1,NOW());
