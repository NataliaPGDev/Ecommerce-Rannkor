<section class="carrito__contenedor">
    <h2>Mi Carrito</h2>

    <table class="carrito">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Talla</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $detalle): ?>
                <tr id="detalle-<?= $detalle['id_carritodetalle'] ?>">
                    <td>
                        <img src="<?= urlImagenProducto($detalle['imagen_url'] ?? '') ?>" alt="Zapatilla" class="miniatura">
                        <span><?= htmlspecialchars($detalle['nombre_producto']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($detalle['talla']) ?></td>
                    <td><?= number_format($detalle['precio_unitario'], 2) ?> €</td>
                    <td>
                        <div class="cantidad-control">
                            <button type="button" class="btn-menos" data-id="<?= $detalle['id_carritodetalle'] ?>">−</button>
                            <span id="cantidad-<?= $detalle['id_carritodetalle'] ?>"><?= $detalle['cantidad'] ?></span>
                            <button type="button" class="btn-mas" data-id="<?= $detalle['id_carritodetalle'] ?>">+</button>
                        </div>
                    </td>
                    <td>
                        <span id="totalProducto-<?= $detalle['id_carritodetalle'] ?>">
                            <?= number_format((float)$detalle['total'], 2, '.', '') ?> €
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn-eliminar" data-id="<?= $detalle['id_carritodetalle'] ?>">
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="subtotal">
                    <strong>Subtotal: <span id="subtotal"><?= number_format($subtotal, 2) ?> €</span></strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="acciones-carrito">
        <a href="index.php?controller=pedido&action=checkout" class="btn-finalizar">Finalizar compra</a>
        <div class="carrito-toast" role="status" aria-live="polite" aria-atomic="true"></div>
    </div>
</section>