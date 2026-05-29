<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hogar & Estilo Bogota - Portal de Busqueda</title>
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
        <a class="nav-pill" href="#">Muebles</a>
        <a class="nav-pill" href="#">Bums</a>
        <a class="nav-pill" href="#">Deco</a>
        <a class="nav-pill nav-pill--active" href="#">Tienda</a>
        <a class="nav-pill" href="#">Carrera</a>
        <a class="nav-pill" href="#">Logos</a>
    </nav>

    <main class="page">
        <section class="search-panel">
            <div class="panel-title">Portal Interno - Inventario</div>
            <form class="search-form" action="search.php" method="GET">
                <input type="text" name="q" placeholder="Ej: Lampara, Silla, Mesa..." required>
                <button type="submit">Buscar Activo</button>
            </form>
            <div class="panel-hint">Busqueda rapida de activos y mobiliario corporativo.</div>
        </section>
    </main>
</body>
</html>
