CREATE DATABASE safetrack_db1;
USE safetrack_db1;

CREATE TABLE incidencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    provincia VARCHAR(100),
    municipio VARCHAR(100),
    barrio VARCHAR(100),
    latitud DECIMAL(10,6),
    longitud DECIMAL(10,6),
    muertos INT DEFAULT 0,
    heridos INT DEFAULT 0,
    perdida DECIMAL(12,2) DEFAULT 0,
    link_redes VARCHAR(255),
    foto VARCHAR(255)
);