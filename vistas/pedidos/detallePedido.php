<?php if (!empty($detalles)) : ?>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Talla</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $item) : ?>
                <tr>
                    <td class="producto-info">
                        <img src="<?= urlImagenProducto($item['imagen_url'] ?? '') ?>"
                            alt="<?= htmlspecialchars($item['nombre_producto']) ?>"
                            class="miniatura">
                        <span><?= htmlspecialchars($item['nombre_producto']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($item['talla']) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td><?= number_format($item['precio_unitario'], 2) ?> €</td>
                    <td><?= number_format($item['cantidad'] * $item['precio_unitario'], 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>No hay detalles disponibles para este pedido.</p>
<?php endif; ?>