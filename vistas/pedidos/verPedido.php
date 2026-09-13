<section class="pedido__contenedor">

    <div class="pedido__header">
        <div>
            <p class="pedido__eyebrow">Pedido confirmado</p>
            <h1>Resumen del Pedido <span class="pedido__codigo">#<?= htmlspecialchars($pedido['codigo']) ?></span></h1>
        </div>
        <div class="pedido__meta">
            <p class="pedido__fecha">Fecha: <?= htmlspecialchars($pedido['fecha_pedido']) ?></p>
        </div>
    </div>

    <div class="pedido__card">
        <div class="pedido__contenido">
            <aside class="pedido__cliente">
                <h2>Información del Cliente</h2>
                <p><strong>Nombre:</strong> <?= htmlspecialchars($pedido['nombre'] . ' ' . $pedido['apellidos']) ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['telefono']) ?></p>
                <p><strong>Dirección:</strong>
                    <?= htmlspecialchars($pedido['calle']) ?> <?= htmlspecialchars($pedido['numero']) ?>
                    <?= htmlspecialchars($pedido['bloque']) ?> <?= htmlspecialchars($pedido['planta']) ?> <?= htmlspecialchars($pedido['puerta']) ?>,
                    <?= htmlspecialchars($pedido['ciudad']) ?> (<?= htmlspecialchars($pedido['provincia']) ?>)
                    - <?= htmlspecialchars($pedido['codigo_postal']) ?>
                </p>
            </aside>

            <section class="pedido__detalles">
                <div class="pedido__detalles-header">
                    <h2>Detalles del Pedido</h2>
                    <span class="pedido__estado-badge">Pagado</span>
                </div>
                <p><strong>Método de pago:</strong> <?= htmlspecialchars($pedido['metodo_pago']) ?></p>
                <p><strong>Tipo de envío:</strong> <?= htmlspecialchars($pedido['tipo_envio']) ?></p>
                <p><strong>Gastos de envío:</strong> <?= number_format($pedido['gastos_envio'], 2) ?> €</p>
                <p class="pedido__total"><strong>Total:</strong> <?= number_format($pedido['total'], 2) ?> €</p>
            </section>
        </div>

        <div class="pedido__productos">
            <h3>Productos adquiridos</h3>
            <table class="pedido__tabla">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Talla</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $item): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($item['imagen_url']) ?>" alt="<?= htmlspecialchars($item['nombre_producto']) ?>" class="pedido__img">
                            </td>
                            <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
                            <td><?= htmlspecialchars($item['talla']) ?></td>
                            <td><?= $item['cantidad'] ?></td>
                            <td><?= number_format($item['precio_unitario'], 2) ?> €</td>
                            <td><?= number_format($item['precio_unitario'] * $item['cantidad'], 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</section>