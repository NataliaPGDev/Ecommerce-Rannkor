document.querySelectorAll(".btn-detalle").forEach(btn => {
    btn.addEventListener("click", () => {
        const idPedido = btn.dataset.id;
        const detalle = document.getElementById(`detalle-${idPedido}`);
        if (detalle.style.display === "none") {
            detalle.style.display = "block";
            btn.textContent = "Ocultar detalle";
        } else {
            detalle.style.display = "none";
            btn.textContent = "Ver detalle";
        }
    });
});
