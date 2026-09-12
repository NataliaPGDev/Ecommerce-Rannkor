document.addEventListener("DOMContentLoaded", () => {
  const enlaces = document.querySelectorAll(".sidebar .js-nav");
  const contenido = document.getElementById("contenido-section");

  // FUNCION OBTENER ACCION DESDE HREF
  function getActionFromHref(href) {
    const url = new URL(href, window.location.href);
    return url.searchParams.get("action");
  }

  // FUNCION CARGAR UNA SECCION VIA FETCH CON GET
  async function cargarSeccion(accion) {
    try {
      const res = await fetch(`api/routerajax.php?accion=${accion}`, {
        method: "GET",
      });

      const html = await res.text();
      contenido.innerHTML = html;

      // Si la sección es historial, activa detalle
      if (accion === "historial") {
        initDetallePedidos();
      }
    } catch (err) {
      contenido.innerHTML = "<p>Error al cargar la sección.</p>";
      console.error(err);
    }
  }

  // FUNCION INICIALIZAR BOTONES DE DETALLES DE PEDIDOS
  function initDetallePedidos() {
    document.querySelectorAll(".btn-ver-detalle").forEach((btn) => {
      btn.addEventListener("click", async () => {
        const idPedido = btn.dataset.idPedido;
        const detalleContainer = document.getElementById(
          `detalle-pedido-${idPedido}`
        );

        //si está visible, ocultar

        if (detalleContainer.innerHTML.trim() !== "") {
          detalleContainer.innerHTML = "";
          detalleContainer.style.display = "none";
          return;
        }

        try {
          const res = await fetch(
            `api/routerajax.php?accion=historialDetalle&id_pedido=${idPedido}`,
            {
              method: "GET",
            }
          );
          const html = await res.text();
          detalleContainer.innerHTML = html;

          //Una vez tengo de vuelta el contenido de la vista mostrar
          detalleContainer.style.display = "block";

        } catch (err) {
          detalleContainer.innerHTML = "<p>Error al cargar detalles.</p>";
          console.error(err);
        }
      });
    });
  }

  // Click en los enlaces del sidebar
  enlaces.forEach((enlace) => {
    enlace.addEventListener("click", (e) => {
      e.preventDefault();

      enlaces.forEach((a) => a.classList.remove("active"));
      enlace.classList.add("active");

      const accion = getActionFromHref(enlace.href);
      if (!accion) return;

      cargarSeccion(accion);
    });
  });

  // Cargar sección activa por defecto
  const enlaceActivo = document.querySelector(".sidebar .js-nav.active");
  if (enlaceActivo) {
    const accion = getActionFromHref(enlaceActivo.href);
    cargarSeccion(accion);
  }
});
