<section class="home-hero">
    <div class="hero-grid">
        <a class="hero-card hero-card--mujer" href="index.php?controller=productos&action=listar&categoria=mujer">
            <div class="hero-card__content">
                <span>Nuevo</span>
                <h2>Para ella</h2>
                <p>Descubre la colección femenina</p>
            </div>
            <img src="../../recursos/img/imgbanner/banner_mujer.webp" alt="Productos para ella">
        </a>

        <a class="hero-card hero-card--hombre" href="index.php?controller=productos&action=listar&categoria=hombre">
            <div class="hero-card__content">
                <span>Trending</span>
                <h2>Para él</h2>
                <p>Estilo urbano y comodidad</p>
            </div>
            <img src="../../recursos/img/imgbanner/banner_hombre.webp" alt="Productos para él">
        </a>
    </div>
</section>

<section class="features">
    <div class="feature">
        <span>🚚</span>
        <p>Envío rápido</p>
    </div>
    <div class="feature">
        <span>🛡️</span>
        <p>Pago seguro</p>
    </div>
    <div class="feature">
        <span>↩️</span>
        <p>Devoluciones sencillas</p>
    </div>
</section>

<section class="destacados">
    <div class="section-heading">
        <h2>Productos destacados para ella</h2>
        <a href="index.php?controller=productos&action=listar&categoria=mujer">Ver más</a>
    </div>

    <div class="carousel">
        <?php foreach ($novedadesMujer as $producto): ?>
            <article class="item">
                <img src="<?php echo urlImagenProducto($producto['imagen_url'] ?? ''); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                <div class="item__body">
                    <p class="item__name"><?php echo $producto['nombre_producto']; ?></p>
                    <p class="item__desc"><?php echo $producto['descripcion']; ?></p>
                    <div class="item__footer">
                        <strong><?php echo $producto['precio']; ?> €</strong>
                        <a href="index.php?controller=productos&action=verProducto&id_producto=<?php echo $producto['id_producto']; ?>">Ver</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="marca">
    <div class="marca__content">
        <h3>Calzado con estilo</h3>
        <p>Comodidad, diseño y detalles premium para cada momento del día.</p>
    </div>
    <img src="../../recursos/img/imgbanner/banner_mujer.webp" alt="Particularidad de la marca">
</section>

<section class="destacados destacados--secondary">
    <div class="section-heading">
        <h2>Productos destacados para él</h2>
        <a href="index.php?controller=productos&action=listar&categoria=hombre">Ver más</a>
    </div>

    <div class="carousel">
        <?php foreach ($novedadesHombre as $producto): ?>
            <article class="item">
                <img src="<?php echo urlImagenProducto($producto['imagen_url'] ?? ''); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                <div class="item__body">
                    <p class="item__name"><?php echo $producto['nombre_producto']; ?></p>
                    <p class="item__desc"><?php echo $producto['descripcion']; ?></p>
                    <div class="item__footer">
                        <strong><?php echo $producto['precio']; ?> €</strong>
                        <a href="index.php?controller=productos&action=verProducto&id_producto=<?php echo $producto['id_producto']; ?>">Ver</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>