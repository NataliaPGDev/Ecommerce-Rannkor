<section>
    <h2>Historial de Pedidos</h2>

    <?php if (!empty($pedidos)) : ?>
        <ul class="historial-list">
            <?php foreach ($pedidos as $pedido) : ?>
                <li class="pedido-item">
                    <div class="pedido-resumen">
                        <strong>Código:</strong> <?= htmlspecialchars($pedido['codigo']) ?>
                        <strong>Fecha:</strong> <?= htmlspecialchars($pedido['fecha_pedido']) ?>
                        <strong>Total:</strong> <?= number_format($pedido['total'], 2) ?> €
                        <button class="btn-ver-detalle" data-id-pedido="<?= $pedido['id_pedido'] ?>">
                            Ver detalle
                        </button>
                    </div>
                    <!-- Contenedor vacío donde se cargará el detalle del pedido vía fetch -->
                    <div id="detalle-pedido-<?= $pedido['id_pedido'] ?>" class="detalle-pedido"></div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No hay pedidos registrados.</p>
    <?php endif; ?>
</section>