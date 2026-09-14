document.addEventListener("DOMContentLoaded", () => {
  initSidebar();
  loadDefaultView();
});

/** ====== INICIALIZAR SIDEBAR ====== */
function initSidebar() {
  document.querySelectorAll(".sidebar button").forEach((btn) => {
    btn.addEventListener("click", () => {
      const view = btn.dataset.view;
      loadView(view).then(() => {
        switch (view) {
          case "usuarios":
            if (typeof initUsuariosView === "function") initUsuariosView();
            break;
          case "productos":
            if (typeof initProductosView === "function") initProductosView();
            break;
        }
      });
    });
  });
}

/** ====== CARGAR VISTA DINÁMICA ====== */
function loadView(view) {
  return fetch(appUrl(`vistas/admin/${view}.php`))
    .then((res) => {
      if (!res.ok) throw new Error("Vista no encontrada");
      return res.text();
    })
    .then((html) => {
      const content = document.getElementById("content");
      if (!content) throw new Error("Elemento #content no encontrado");
      content.innerHTML = html;
    })
    .catch((err) => {
      const content = document.getElementById("content");
      if (content) {
        content.innerHTML = `<p>Error: ${err.message}</p>`;
      }
    });
}

/** ====== VISTA POR DEFECTO ====== */
function loadDefaultView() {
  loadView("usuarios").then(() => {
    if (typeof initUsuariosView === "function") initUsuariosView();
  });
}
