<section>


    <div class="productos__contenedor">
        <h3>Calzado deportivo <?php echo ucfirst($categoria); ?></h3>

        <ul class="productos__lista">
            <?php foreach ($productos as $producto): ?>
                <li class="productos__item">
                    <!-- Cada imagen es un enlace al detalle del producto -->
                    <a href="index.php?controller=productos&action=verProducto&id_producto=<?php echo $producto['id_producto']; ?>">
                        <img src="<?php echo urlImagenProducto($producto['imagen_url'] ?? ''); ?>" alt="Zapatilla">
                    </a>
                    <p class="producto__nombre"><?php echo htmlspecialchars($producto['nombre_producto']); ?></p>
                    <p class="producto__descripcion"><?php echo  htmlspecialchars($producto['descripcion']); ?></p>
                    <p class="producto__precio"><?php echo $producto['precio']; ?> €</p>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>



</section>