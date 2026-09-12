document.addEventListener("DOMContentLoaded", () => {
    initSidebar();      // Inicializa botones del sidebar
    loadDefaultView();  // Carga la vista por defecto
});

/** ====== INICIALIZAR SIDEBAR ====== */
function initSidebar() {
    document.querySelectorAll(".sidebar button").forEach(btn => {
        btn.addEventListener("click", () => {
            const view = btn.dataset.view;
            loadView(view).then(() => {
                // Llamar al init adecuado según la vista
                switch(view) {
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
    // Ruta absoluta desde la raíz del proyecto
    return fetch(`/vistas/admin/${view}.php`)
        .then(res => {
            if (!res.ok) throw new Error("Vista no encontrada");
            return res.text();
        })
        .then(html => {
            const content = document.getElementById("content");
            if (!content) throw new Error("Elemento #content no encontrado");
            content.innerHTML = html;
        })
        .catch(err => {
            document.getElementById("content").innerHTML = `<p>Error: ${err.message}</p>`;
        });
}

/** ====== VISTA POR DEFECTO ====== */
function loadDefaultView() {
    loadView("usuarios").then(() => {
        if (typeof initUsuariosView === "function") initUsuariosView();
    });
}