# Guía Maestra: Desarrollo y Migración de Laboratorio Ofensivo

Esta guía técnica consolida el ciclo de vida del entorno web **Tienda Hogar**, alineado a los requerimientos de arquitectura del escenario ofensivo. Detalla la codificación en Windows y su despliegue inseguro en **Ubuntu/Debian (LAMP)**.

---

## PARTE 1: DESARROLLO DEL ENTORNO (Windows local)

### 1.1 Estructura del Proyecto
```
tienda_hogar/
├── config/db.php
├── css/estilos.css
├── uploads/ (Vacío, para plantado de webshell)
├── database.sql
├── index.php
└── search.php
```

### 1.2 Scripts Clave

#### A. database.sql (Con hashes y notas secretas)
```sql
CREATE DATABASE IF NOT EXISTS tienda_hogar;
USE tienda_hogar;

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100), categoria VARCHAR(50), descripcion TEXT, precio DECIMAL(10,2), imagen_url VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    email VARCHAR(100),
    notas_secretas TEXT
);

INSERT INTO productos (nombre, categoria, descripcion, precio, imagen_url) VALUES 
('Lámpara de Escritorio', 'lámparas', 'Iluminación oficina.', 25.50, 'img/lampara_01.jpg'),
('Silla Ergonómica', 'sillas', 'Soporte lumbar.', 120.00, 'img/silla_pro.jpg'),
('Mesa de Centro', 'mesas', 'Madera roble.', 85.00, 'img/mesa_centro.jpg');

INSERT INTO usuarios (username, password, email, notas_secretas) VALUES 
('adminbogota', MD5('Bogota2026!*'), 'admin.bogota@hmentor.local', 'Pin de bóveda: 4458-Alpha.'),
('malava', MD5('PasswordSecure123'), 'm.alava@hmentor.local', 'Revisar Firewall.');
```

#### B. search.php (Intencionalmente Vulnerable)
```php
<?php
include 'config/db.php';
$termino = isset($_GET['q']) ? $_GET['q'] : '';

$query = "SELECT id, nombre, categoria, descripcion, precio FROM productos WHERE nombre LIKE '%" . $termino . "%'";
$resultado = mysqli_query($conexion, $query);
?>
```

---

## PARTE 2: DESPLIEGUE EN UBUNTU/DEBIAN (Preparación de la Fase 3)

Para que los ataques detallados en la guía de Red Team (Plantado de Webshell con `INTO OUTFILE`) funcionen al clonar el código en Linux, es mandatorio romper las defensas nativas de aislamiento.

### 2.1 Clonación y Permisos de Escritura Web
Clona el repositorio en `/var/www/html/` y otorga permisos absolutos a la carpeta de destino de la Webshell:
```bash
sudo git clone https://github.com/tu-usuario/tienda_hogar.git /var/www/html/tienda_hogar
sudo chmod 777 /var/www/html/tienda_hogar/uploads
```

### 2.2 Desactivación de Seguridad MySQL
Edita la configuración para permitir la función `INTO OUTFILE` (Vital para la Fase 3):
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```
Añade bajo `[mysqld]`:
```
secure_file_priv = ""
```
Reinicia el servicio: `sudo systemctl restart mysql`

### 2.3 Creación de Usuario db_operator (Fase 1 - Requisito 3)
En la consola de MySQL (`sudo mysql -u root -p`), ejecuta:
```sql
CREATE USER 'db_operator'@'localhost' IDENTIFIED BY 'OperatorPassword99!';
GRANT ALL PRIVILEGES ON tienda_hogar.* TO 'db_operator'@'localhost';
GRANT FILE ON *.* TO 'db_operator'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Finalmente, importa la base de datos:
```bash
mysql -u db_operator -p tienda_hogar < /var/www/html/tienda_hogar/database.sql
```
