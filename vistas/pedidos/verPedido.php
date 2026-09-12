<section class="pedido__contenedor">

    <div>
        <h1>Resumen del Pedido <span class="pedido__codigo">#<?= htmlspecialchars($pedido['codigo']) ?></span></h1>
        <p class="pedido__fecha">Fecha: <?= htmlspecialchars($pedido['fecha_pedido']) ?></p>
        <p class="pedido__estado">Estado: <?= htmlspecialchars($pedido['estado_pedido']) ?></p>
    </div>


    <div class="pedido__contenido">

        <!-- DATOS DEL CLIENTE Y DIRECCIÓN -->
        <aside class="pedido__cliente">
            <h2>📦 Información del Cliente</h2>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($pedido['nombre'] . ' ' . $pedido['apellidos']) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['telefono']) ?></p>
            <p><strong>Dirección:</strong>
                <?= htmlspecialchars($pedido['calle']) ?> <?= htmlspecialchars($pedido['numero']) ?>
                <?= htmlspecialchars($pedido['bloque']) ?> <?= htmlspecialchars($pedido['planta']) ?> <?= htmlspecialchars($pedido['puerta']) ?>,
                <?= htmlspecialchars($pedido['ciudad']) ?> (<?= htmlspecialchars($pedido['provincia']) ?>)
                - <?= htmlspecialchars($pedido['codigo_postal']) ?>
            </p>
        </aside>

        <!-- DETALLES DEL PEDIDO -->
        <section class="pedido__detalles">
            <h2>📦 Detalles del Pedido</h2>
            <p><strong>Método de pago:</strong> <?= htmlspecialchars($pedido['metodo_pago']) ?></p>
            <p><strong>Tipo de envío:</strong> <?= htmlspecialchars($pedido['tipo_envio']) ?></p>
            <p><strong>Gastos de envío:</strong> <?= number_format($pedido['gastos_envio'], 2) ?> €</p>
            <p class="pedido__total"><strong>Total:</strong> <?= number_format($pedido['total'], 2) ?> €</p>

            <!-- PRODUCTOS -->
            <div class="pedido__productos">
                <h3>🛒 Productos adquiridos</h3>
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

        </section>

    </div>

</section>