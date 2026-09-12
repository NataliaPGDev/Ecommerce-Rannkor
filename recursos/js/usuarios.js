/** ====== VARIABLES GLOBALES DE LA VISTA ====== */
let usuariosData = []; // Guarda los datos de los usuarios cargados

/** ====== INICIALIZACIÓN DE LA VISTA ====== */
function initUsuariosView() {
    cargarUsuarios();      // Cargar tabla
    initModales();         // Inicializar modales
    initDelegacion();      // Inicializar delegación de eventos
}

/** ====== MODALES ====== */
function initModales() {
    const modalCrear = document.getElementById("modalCrearUsuario");
    const modalEditar = document.getElementById("modalEditarUsuario");

    // Botón nuevo usuario
    const btnNuevo = document.getElementById("btnNuevoUsuario");
    if (btnNuevo) btnNuevo.addEventListener("click", abrirModalCrearUsuario);

    // Cerrar modales al hacer click fuera
    window.addEventListener("click", e => {
        if (e.target === modalCrear) cerrarModalCrearUsuario();
        if (e.target === modalEditar) cerrarModalEditarUsuario();
    });

    // Cerrar modales con tecla Esc
    window.addEventListener("keydown", e => {
        if (e.key === "Escape") {
            cerrarModalCrearUsuario();
            cerrarModalEditarUsuario();
        }
    });
}

function abrirModalCrearUsuario() {
    document.getElementById("modalCrearUsuario").classList.add("show");
}

function cerrarModalCrearUsuario() {
    document.getElementById("modalCrearUsuario").classList.remove("show");
}

function abrirModalEditarUsuario() {
    document.getElementById("modalEditarUsuario").classList.add("show");
}

function cerrarModalEditarUsuario() {
    document.getElementById("modalEditarUsuario").classList.remove("show");
}


/** ====== CARGAR USUARIOS ====== */
function cargarUsuarios() {
    fetch("/routeradmin.php?accion=listarUsuarios")
        .then(res => res.json())
        .then(data => {
            usuariosData = data; // Guardamos la info globalmente
             if (!Array.isArray(usuariosData)) {
            console.error("Error al obtener usuarios:", usuariosData);
            return; // Detener ejecución si no es array
        }
            const tbody = document.querySelector("#tablaUsuarios tbody");
            if (!tbody) return;

            tbody.innerHTML = ""; // Limpiar tabla

            data.forEach(usuario => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${usuario.id_usuario}</td>
                    <td>${usuario.nombre}</td>
                    <td>${usuario.apellidos ?? ''}</td>
                    <td>${usuario.telefono ?? ''}</td>
                    <td>${usuario.mail}</td>
                    <td>${usuario.password}</td>
                    <td>${usuario.id_rol}</td>
                    <td class="acciones">
                        <button class="btn-icono btn-editar" data-id="${usuario.id_usuario}"><ion-icon name="create-outline"></ion-icon></button>
                        <button class="btn-icono btn-eliminar" data-id="${usuario.id_usuario}"><ion-icon name="trash-outline"></ion-icon></button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => alert("Error al cargar usuarios: " + err.message));
}

/** ====== DELEGACIÓN DE EVENTOS ====== */
function initDelegacion() {
    const tbody = document.querySelector("#tablaUsuarios tbody");
    if (!tbody) return;

    tbody.addEventListener("click", e => {
        const editarBtn = e.target.closest(".btn-editar");
        const eliminarBtn = e.target.closest(".btn-eliminar");

        if (editarBtn) {
            const id = editarBtn.dataset.id;
            editarUsuario(id);
        }

        if (eliminarBtn) {
            const id = eliminarBtn.dataset.id;
            eliminarUsuario(id);
        }
    });
}

/** ====== CREAR USUARIO ====== */
function crearUsuario() {
    const datos = {
        nombre: document.getElementById("nombre").value,
        apellidos: document.getElementById("apellidos").value,
        telefono: document.getElementById("telefono").value,
        mail: document.getElementById("mail").value,
        password: document.getElementById("password").value,
        id_rol: document.getElementById("id_rol").value
    };

    fetch("/routeradmin.php?accion=insertarUsuario", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos)
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.success) {
            cerrarModalCrearUsuario();
            cargarUsuarios();
        } else {
            alert("Error: " + resp.message);
        }
    })
    .catch(err => alert("Error al insertar usuario: " + err.message));
}

/** ====== EDITAR USUARIO ====== */
function editarUsuario(id) {
    const usuario = usuariosData.find(u => u.id_usuario == id);
    if (!usuario) return alert("Usuario no encontrado");

    document.getElementById("edit_id_usuario").value = usuario.id_usuario;
    document.getElementById("edit_nombre").value = usuario.nombre;
    document.getElementById("edit_apellidos").value = usuario.apellidos;
    document.getElementById("edit_telefono").value = usuario.telefono;
    document.getElementById("edit_mail").value = usuario.mail;
    document.getElementById("edit_password").value = usuario.password;
    document.getElementById("edit_id_rol").value = usuario.id_rol;

    abrirModalEditarUsuario();
}

function guardarCambiosUsuario() {
    const idUsuario = document.getElementById("edit_id_usuario").value;

    const datos = {
        nombre: document.getElementById("edit_nombre").value,
        apellidos: document.getElementById("edit_apellidos").value,
        telefono: document.getElementById("edit_telefono").value,
        mail: document.getElementById("edit_mail").value,
        password: document.getElementById("edit_password").value,
        id_rol: document.getElementById("edit_id_rol").value
    };

    fetch(`/routeradmin.php?accion=actualizarUsuario&id=${idUsuario}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos)
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.success) {
            cerrarModalEditarUsuario();
            cargarUsuarios();
        } else {
            alert("Error: " + resp.message);
        }
    })
    .catch(err => alert("Error al actualizar usuario: " + err.message));
}


/** ====== ELIMINAR USUARIO ====== */
function eliminarUsuario(id) {
    if (!confirm("¿Seguro que quieres eliminar este usuario?")) return;

    fetch(`routeradmin.php?accion=eliminarUsuario&id=${id}`)
        .then(res => res.json())
        .then(resp => {
            if (resp.success) cargarUsuarios();
            else alert("Error: " + resp.mensaje);
        })
        .catch(err => alert("Error al eliminar usuario: " + err.message));
}