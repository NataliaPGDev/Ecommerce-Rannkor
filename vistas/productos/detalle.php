<!----VISTA PÁGINA DETALLE PRODUCTO ---->

<section>

  <div class="detalleproducto__contenedor">

    <!---- Columna 1: Imagen del producto ---->

    <div class="detalleproducto__imagen">
      <img src="<?php echo urlImagenProducto($producto['imagen_url'] ?? ''); ?>" alt="Zapatilla">
    </div>


    <!---- Columna 2: Especificaciones del producto ---->

    <div class="detalleproducto__especificaciones">

      <div class="especificaciones__titulos">
        <h1><?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>
        <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
        <p><?php echo $producto['precio']; ?> €</p>
      </div>

      <?php
      if ($producto['categoria'] === 'mujer') {
        $tallas_categoria = array_filter($producto['tallas'], function ($talla) {
          return $talla['talla'] >= 36 && $talla['talla'] <= 40;
        });
      } elseif ($producto['categoria'] === 'hombre') {
        $tallas_categoria = array_filter($producto['tallas'], function ($talla) {
          return $talla['talla'] >= 41 && $talla['talla'] <= 44;
        });
      }
      ?>

      <form class="detalleproducto__formulario"
        action="index.php?controller=carrito&action=agregar"
        method="post">

        <!-- ID producto -->
        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

        <!-- Cantidad -->
        <input type="hidden" id="cantidad" name="cantidad" value="1">

        <?php if (!empty($tallas_categoria)): ?>

          <!-- Tallas -->
          <p class="formulario__tallas--titulo">Tallas</p>
          <div class="formulario__tallas">
            <?php foreach ($tallas_categoria as $talla): ?>
              <label class="formulario__tallas--item">
                <input type="radio" name="id_productostalla"
                  value="<?php echo $talla['id_productostalla']; ?>"
                  <?php echo ($talla['stock'] <= 0) ? 'disabled' : ''; ?> required>
                <span class="<?php echo ($talla['stock'] > 0) ? 'talla-on' : 'talla-off'; ?>">
                  <?php echo htmlspecialchars($talla['talla']); ?>
                </span>
              </label>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p>No hay tallas disponibles para este producto en la categoría seleccionada.</p>
        <?php endif; ?>

        <button class="formulario__tallas--btn" type="submit">Agregar al carrito</button>
      </form>


      <div class="detalleproducto__menu">
        <div class="detalleproducto__menu--item activo">
          <div class="detalleproducto__menu--titulo" tabindex="0" role="button" aria-expanded="true">
            <span>Descripción</span>
            <span class="detalleproducto__menu--icono">−</span>
          </div>
          <div class="detalleproducto__menu--contenido activo">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Alias at nostrum praesentium ullam, natus pariatur.</p>
          </div>
        </div>

        <div class="detalleproducto__menu--item">
          <div class="detalleproducto__menu--titulo" tabindex="0" role="button" aria-expanded="false">
            <span>Sello de garantía</span>
            <span class="detalleproducto__menu--icono">➕</span>
          </div>
          <div class="detalleproducto__menu--contenido">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Alias at nostrum praesentium ullam, natus pariatur.</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.detalleproducto__menu--item');

    items.forEach((item) => {
      const titulo = item.querySelector('.detalleproducto__menu--titulo');
      const contenido = item.querySelector('.detalleproducto__menu--contenido');
      const icono = item.querySelector('.detalleproducto__menu--icono');

      const alternar = () => {
        const estaAbierto = item.classList.contains('activo');

        items.forEach((el) => {
          el.classList.remove('activo');
          const contenidoEl = el.querySelector('.detalleproducto__menu--contenido');
          const iconoEl = el.querySelector('.detalleproducto__menu--icono');
          const tituloEl = el.querySelector('.detalleproducto__menu--titulo');

          if (contenidoEl) contenidoEl.classList.remove('activo');
          if (iconoEl) iconoEl.textContent = '➕';
          if (tituloEl) tituloEl.setAttribute('aria-expanded', 'false');
        });

        if (!estaAbierto) {
          item.classList.add('activo');
          if (contenido) contenido.classList.add('activo');
          if (icono) icono.textContent = '−';
          if (titulo) titulo.setAttribute('aria-expanded', 'true');
        }
      };

      titulo.addEventListener('click', alternar);
      titulo.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault();
          alternar();
        }
      });
    });
  });
</script>