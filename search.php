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
    <title>Resultados - Hogar & Estilo Bogota</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header class="site-header">
        <div class="brand">
            <div class="brand-title">HOGAR & ESTILO BOGOTA</div>
            <div class="brand-subtitle">Infraestructura de Laboratorio - Target Debian Server</div>
        </div>
    </header>

    <nav class="site-nav">
        <a class="nav-pill" href="index.php">Muebles</a>
        <a class="nav-pill" href="index.php">Bums</a>
        <a class="nav-pill" href="index.php">Deco</a>
        <a class="nav-pill nav-pill--active" href="index.php">Tienda</a>
        <a class="nav-pill" href="index.php">Carrera</a>
        <a class="nav-pill" href="index.php">Logos</a>
    </nav>

    <main class="page">
        <section class="result-banner">
            <div class="banner-title">Query actual en el Backend:</div>
            <code>SELECT * FROM productos WHERE categoria LIKE '%<?php echo htmlspecialchars($termino); ?>%'</code>
        </section>

        <section class="result-header">
            <div class="category-line">
                <span class="pipe">|</span>
                <span>Categoria: Iluminacion</span>
            </div>
        </section>

        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
            <section class="card-grid">
                <?php while($fila = mysqli_fetch_assoc($resultado)): ?>
                    <article class="product-card">
                        <div class="product-image">Imagen</div>
                        <div class="product-info">
                            <div class="product-name"><?php echo $fila['nombre']; ?></div>
                            <div class="product-meta"><?php echo $fila['descripcion']; ?></div>
                            <div class="product-price">$<?php echo $fila['precio']; ?></div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </section>
        <?php endif; ?>

        <?php
        if (!$resultado) {
            echo "<div class=\"sql-error\"><h3>[!] Error de Sintaxis SQL:</h3><p>" . mysqli_error($conexion) . "</p></div>";
        }
        ?>
    </main>
</body>
</html>
