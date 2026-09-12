document.addEventListener('DOMContentLoaded', () => {

    const envioCards = document.querySelectorAll('.envio-card');
    const tipoEnvioInput = document.getElementById('tipo_envio');
    const subtotalElem = document.getElementById('subtotal');
    const envioElem = document.getElementById('envio');
    const totalElem = document.getElementById('total');

    const preciosEnvio = {
        estandar: 5,
        urgente: 10
    };

    envioCards.forEach(card => {
        card.addEventListener('click', () => {
            // Quitar selección anterior
            envioCards.forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');

            // Guardar tipo de envío
            const tipo = card.dataset.envio;
            tipoEnvioInput.value = tipo;

            // Calcular total visual
            const subtotal = parseFloat(subtotalElem.textContent.replace('€','').trim());
            const envio = preciosEnvio[tipo];
            const total = subtotal + envio;

            envioElem.textContent = envio.toFixed(2) + " €";
            totalElem.textContent = total.toFixed(2) + " €";
        });
    });

});
