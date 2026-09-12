
document.addEventListener("DOMContentLoaded", function () {

  const Carrito = {
    // ============================
    // Función genérica para llamar al router AJAX
    // ============================
    callAJAX: async function (accion, data) {
      try {
        const res = await fetch("api/routerajax.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ accion, ...data })
        });
        return await res.json();
      } catch (err) {
        console.error("Error fetch:", err);
        return { success: false, error: "Error de conexión" };
      }
    },

    // ============================
    // Actualizar cantidad de un producto
    // ============================
    actualizarCantidad: async function (id_carritodetalle, cambio) {
      const cantidadElem = document.getElementById(`cantidad-${id_carritodetalle}`);
      if (!cantidadElem) return;

      let cantidad = parseInt(cantidadElem.textContent) + cambio;
      if (cantidad < 1) return;

      const data = await this.callAJAX("actualizarCantidad", { id_carritodetalle, cantidad });

      if (data.success) {
        cantidadElem.textContent = cantidad;

        const totalProductoElem = document.getElementById(`totalProducto-${id_carritodetalle}`);
        if (totalProductoElem) totalProductoElem.textContent = parseFloat(data.totalProducto).toFixed(2) + " €";
        const subtotalElem = document.getElementById("subtotal");
        if (subtotalElem) subtotalElem.textContent = data.subtotalCarrito + " €";
      } else {
        alert(data.error);
      }
    },

    // ============================
    // Eliminar producto del carrito
    // ============================
    eliminarProducto: async function (id_carritodetalle) {
      if (!confirm("¿Deseas eliminar este producto?")) return;

      const data = await this.callAJAX("eliminarProducto", { id_carritodetalle });

      if (data.success) {
        const row = document.getElementById(`detalle-${id_carritodetalle}`);
        if (row) row.remove();

        const subtotalElem = document.getElementById("subtotal");
        if (subtotalElem) subtotalElem.textContent = data.subtotalCarrito + " €";
      } else {
        alert(data.error);
      }
    },

    // ============================
    // Inicializar eventos de botones
    // ============================
    initEventos: function () {
      document.querySelectorAll(".btn-mas").forEach(button => {
        button.addEventListener("click", () => {
          this.actualizarCantidad(parseInt(button.dataset.id), 1);
        });
      });

      document.querySelectorAll(".btn-menos").forEach(button => {
        button.addEventListener("click", () => {
          this.actualizarCantidad(parseInt(button.dataset.id), -1);
        });
      });

      document.querySelectorAll(".btn-eliminar").forEach(button => {
        button.addEventListener("click", () => {
          this.eliminarProducto(parseInt(button.dataset.id));
        });
      });
    },

    // ============================
    // Inicialización
    // ============================
    init: function () {
      this.initEventos();
    }
  };

  // Inicializar carrito
  Carrito.init();

  // Exponer al global si es necesario
  window.Carrito = Carrito;
});
