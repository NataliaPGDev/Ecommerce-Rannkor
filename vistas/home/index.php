
<!-- Foto principal con enlace a productos mujer -->
<section class="hero">
    <a href="index.php?controller=producto&action=listar&categoria=mujer">
        <img src="../../recursos/img/imgbanner/banner_mujer.webp" alt="Productos para ella">
    </a>
</section>

<!-- Productos destacados Mujer -->
<section class="destacados">
    <h2>Productos destacados para ella</h2>
    <div class="carousel">
        <?php foreach ($novedadesMujer as $producto): ?>
            <div class="item">
                <img src="<?php echo $producto['imagen_url']; ?>" alt="Zapatillas">
                <p><?php echo $producto['nombre_producto']; ?></p>
                <p><?php echo $producto['descripcion']; ?></p>
                <p><?php echo $producto['precio']; ?> €</p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Foto con particularidad de la marca -->
<section class="marca">
    <img src="assets/img/marca1.jpg" alt="Particularidad de la marca">
</section>

<!-- Foto con enlace a productos hombre -->
<section class="hero">
    <a href="index.php?controller=productos&action=listar&categoria=hombre">
        <img src="../../recursos/img/imgbanner/banner_hombre.webp" alt="Productos para él">
    </a>
</section>

<!-- Carrusel productos hombre -->
<section class="destacados">
    <h2>Productos destacados para él</h2>
    <div class="carousel">
        <?php foreach ($novedadesHombre as $producto): ?>
            <div class="item">
                <img src="<?php echo $producto['imagen_url']; ?>" alt="Zapatillas">
                <p><?php echo $producto['nombre_producto']; ?></p>
                <p><?php echo $producto['descripcion']; ?></p>
                <p><?php echo $producto['precio']; ?> €</p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Foto final destacando algo de la marca -->
<section class="marca">
    <img src="assets/img/marca2.jpg" alt="Otra particularidad de la marca">
</section>
