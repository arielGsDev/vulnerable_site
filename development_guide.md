# Guía de Desarrollo: Entorno Web Vulnerable (Tienda Hogar)

Esta guía detalla paso a paso cómo desarrollar el frontend y el backend de la aplicación web de inventario **Tienda Hogar**, basándonos estrictamente en los requerimientos de la **Fase 1** de la guía del Red Team. El objetivo es construir de forma controlada un entorno intencionalmente vulnerable a **Inyección SQL (basada en errores y UNION)**.

---

## 1. Arquitectura y Estructura del Proyecto

El desarrollo se realiza utilizando **PHP Puro (Vanilla)** y **MySQL/MariaDB** (Stack LAMP). La estructura de archivos es la siguiente:

```
tienda_hogar/
│
├── config/
│   └── db.php          # Conexión pura a la base de datos mediante mysqli
│
├── css/
│   └── estilos.css     # Estilos visuales básicos para la interfaz
│
├── uploads/            # Carpeta destinada al plantado de Webshells (Fase 3)
│
├── database.sql        # Script de inicialización con credenciales hasheadas y notas secretas
├── index.php           # Frontend: Portal de búsqueda de inventario
└── search.php          # Backend/Frontend: Lógica intencionalmente vulnerable
```

---

## 2. Desarrollo de Componentes (Paso a Paso)

### A. Diccionario de Datos y Credenciales Corporativas (database.sql)
Este script inicializa el entorno. Cumple con el requisito de categorías (lámparas, mesas, sillas) y, críticamente, almacena contraseñas hasheadas e incluye la columna notas_secretas.

```sql
CREATE DATABASE IF NOT EXISTS tienda_hogar;
USE tienda_hogar;

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    categoria VARCHAR(50),
    descripcion TEXT,
    precio DECIMAL(10,2),
    imagen_url VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    email VARCHAR(100),
    notas_secretas TEXT
);

INSERT INTO productos (nombre, categoria, descripcion, precio, imagen_url) VALUES 
('Lámpara de Escritorio LED', 'lámparas', 'Iluminación regulable para entorno de oficina.', 25.50, 'img/lampara_01.jpg'),
('Silla Ergonómica Pro', 'sillas', 'Silla de oficina con soporte lumbar neumático.', 120.00, 'img/silla_pro.jpg'),
('Mesa de Centro Roble', 'mesas', 'Mesa de madera barnizada para salas de espera.', 85.00, 'img/mesa_centro.jpg');

INSERT INTO usuarios (username, password, email, notas_secretas) VALUES 
('adminbogota', MD5('Bogota2026!*'), 'admin.bogota@hmentor.local', 'Pin del Data Center: 4458-Alpha. No compartir con soporte.'),
('malava', MD5('PasswordSecure123'), 'm.alava@hmentor.local', 'Recordatorio: El túnel VPN hacia la DMZ se reinicia a las 03:00 AM.');
```

### B. Módulo de Conexión (config/db.php)
```php
<?php
$host = "localhost";
$user = "db_operator"; 
$password = "OperatorPassword99!"; 
$database = "tienda_hogar";

$conexion = mysqli_connect($host, $user, $password, $database);

if (!$conexion) {
    die("Error crítico de conexión: " . mysqli_connect_error());
}
?>
```

### C. Interfaz Principal de Usuario (index.php)
```php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda Hogar - Portal de Búsqueda</title>
</head>
<body>
    <div class="search-container">
        <h1>Portal Interno - Tienda Hogar</h1>
        <form action="search.php" method="GET">
            <input type="text" name="q" placeholder="Ej: Lámpara, Silla, Mesa..." required>
            <button type="submit">Buscar Activo</button>
        </form>
    </div>
</body>
</html>
```

### D. Motor de Búsqueda Vulnerable (search.php)
```php
<?php
include 'config/db.php';
$termino = isset($_GET['q']) ? $_GET['q'] : '';

$query = "SELECT id, nombre, categoria, descripcion, precio FROM productos WHERE nombre LIKE '%" . $termino . "%'";
$resultado = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados - Tienda Hogar</title>
</head>
<body>
    <div class="results-container">
        <h2>Resultados para: "<?php echo htmlspecialchars($termino); ?>"</h2>
        
        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
            <table border="1">
                <thead><tr><th>ID</th><th>Nombre</th><th>Categoría</th><th>Descripción</th><th>Precio</th></tr></thead>
                <tbody>
                    <?php while($fila = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo $fila['id']; ?></td>
                            <td><?php echo $fila['nombre']; ?></td>
                            <td><?php echo $fila['categoria']; ?></td>
                            <td><?php echo $fila['descripcion']; ?></td>
                            <td>$<?php echo $fila['precio']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <?php 
        if (!$resultado) {
            echo "<div><h3>[!] Error de Sintaxis SQL:</h3><p>" . mysqli_error($conexion) . "</p></div>";
        }
        ?>
    </div>
</body>
</html>
```
