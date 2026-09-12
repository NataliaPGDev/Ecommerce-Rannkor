<section class="checkout">
    <h2>Finalizar Compra</h2>

    <div class="checkout-container">

        <!-- COLUMNA IZQUIERDA -->
        <div class="checkout-left">
            <h2>Datos de Envío</h2>

            <form id="form-envio" action="index.php?controller=pedido&action=compra" method="POST">

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required>
                </div>

                <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" required>
                </div>

                <div class="form-group">
                    <label>Calle</label>
                    <input type="text" name="calle" required>
                </div>

                <div class="form-group">
                    <label>Número</label>
                    <input type="text" name="numero" required>
                </div>

                <div class="form-row">
                    <div class="form-group small-3">
                        <label>Bloque</label>
                        <input type="text" name="bloque">
                    </div>

                    <div class="form-group small-3">
                        <label>Planta</label>
                        <input type="text" name="planta">
                    </div>

                    <div class="form-group small-3">
                        <label>Puerta</label>
                        <input type="text" name="puerta">
                    </div>
                </div>

                <div class="form-group">
                    <label>Ciudad</label>
                    <input type="text" name="ciudad" required>
                </div>

                <div class="form-group small-2">
                    <label>Código Postal</label>
                    <input type="text" name="codigo_postal" required>
                </div>

                <div class="form-group">
                    <label>Provincia</label>
                    <select name="provincia" required>
                        <?php foreach ($provincias as $p): ?>
                            <option value="<?= $p ?>"><?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" required>
                </div>

                <h3>Tipo de Envío</h3>

                <div class="envio-options">
                    <div class="envio-card" data-envio="estandar">
                        <strong>Envío Estándar</strong>
                        <p>5€ — Llega en 48/72h</p>
                    </div>

                    <div class="envio-card" data-envio="urgente">
                        <strong>Envío Urgente</strong>
                        <p>10€ — Llega en 24h</p>
                    </div>
                </div>

                <!-- Guardamos tipo de envío -->
                <input type="hidden" id="tipo_envio" name="tipo_envio" value="estandar">

                <h3>Método de Pago</h3>
                <div class="payment-options">
                    <label><input type="radio" name="metodo_pago" value="visa" checked> Visa</label>
                    <label><input type="radio" name="metodo_pago" value="paypal"> PayPal</label>
                    <label><input type="radio" name="metodo_pago" value="googlepay"> Google Pay</label>
                </div>

                <button type="submit" class="btn-finalizar">Finalizar Compra</button>
            </form>
        </div>

        <!-- COLUMNA DERECHA -->
        <div class="checkout-right">
            <h2>Resumen del Pedido</h2>

            <?php foreach ($lineasCheckout as $line): ?>
                <div class="producto-resumen">
                    <img src="<?= $line['imagen'] ?>" alt="">
                    <div>
                        <strong><?= $line['nombre'] ?></strong>
                        <p>Cantidad: <?= $line['cantidad'] ?></p>
                    </div>
                    <span><?= number_format($line['total_producto'], 2) ?> €</span>
                </div>
            <?php endforeach; ?>

            <div class="resumen-line">
                <span>Subtotal:</span>
                <strong id="subtotal"><?= number_format($subtotal, 2) ?> €</strong>
            </div>

            <div class="resumen-line">
                <span>Envío:</span>
                <strong id="envio">5 €</strong>
            </div>

            <div class="resumen-total">
                <span>Total:</span>
                <strong id="total"><?= number_format($subtotal + 5, 2) ?> €</strong>
            </div>
        </div>
    </div>
</section>