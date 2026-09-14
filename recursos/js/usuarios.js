/** ====== VARIABLES GLOBALES DE LA VISTA ====== */
let usuariosData = []; // Guarda los datos de los usuarios cargados

const REDIRECT_LOGIN_KEY = "rannkor_redirect_to_login";

function redirectToLoginOnce() {
  const currentUrl = window.location.href;
  if (/controller=auth|action=login/i.test(currentUrl)) {
    sessionStorage.removeItem(REDIRECT_LOGIN_KEY);
    return false;
  }

  if (sessionStorage.getItem(REDIRECT_LOGIN_KEY) === "1") {
    return false;
  }

  sessionStorage.setItem(REDIRECT_LOGIN_KEY, "1");
  window.location.href = "index.php?controller=auth&action=login";
  return true;
}

/** ====== INICIALIZACIÓN DE LA VISTA ====== */
function initUsuariosView() {
  const tablaUsuarios = document.querySelector("#tablaUsuarios");
  if (!tablaUsuarios) return;

  cargarUsuarios(); // Cargar tabla
  initModales(); // Inicializar modales
  initDelegacion(); // Inicializar delegación de eventos
}

/** ====== MODALES ====== */
function initModales() {
  const modalCrear = document.getElementById("modalCrearUsuario");
  const modalEditar = document.getElementById("modalEditarUsuario");

  initPasswordToggles();

  // Botón nuevo usuario
  const btnNuevo = document.getElementById("btnNuevoUsuario");
  if (btnNuevo) btnNuevo.addEventListener("click", abrirModalCrearUsuario);

  // Cerrar modales al hacer click fuera
  window.addEventListener("click", (e) => {
    if (e.target === modalCrear) cerrarModalCrearUsuario();
    if (e.target === modalEditar) cerrarModalEditarUsuario();
  });

  // Cerrar modales con tecla Esc
  window.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      cerrarModalCrearUsuario();
      cerrarModalEditarUsuario();
    }
  });
}

function initPasswordToggles() {
  document.querySelectorAll(".btn-password-toggle").forEach((button) => {
    const icon = button.querySelector("ion-icon");
    if (!icon) return;

    button.addEventListener("click", () => {
      const targetId = button.dataset.target;
      const input = document.getElementById(targetId);

      if (!input) return;

      const isPassword = input.type === "password";
      input.type = isPassword ? "text" : "password";
      icon.setAttribute("name", isPassword ? "eye-off-outline" : "eye-outline");
      button.setAttribute(
        "aria-label",
        isPassword ? "Ocultar contraseña" : "Mostrar contraseña",
      );
    });
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
  const isLoginPage = /controller=auth|action=login/i.test(
    window.location.search || window.location.href,
  );
  if (isLoginPage) return;

  fetch(appUrl("routeradmin.php?accion=listarUsuarios"))
    .then((res) => {
      if (!res.ok) {
        if (res.status === 403 || res.status === 401) {
          redirectToLoginOnce();
          return null;
        }

        return res
          .json()
          .then((error) => {
            throw new Error(error.error || "No autorizado");
          })
          .catch(() => {
            throw new Error("Error HTTP " + res.status);
          });
      }
      return res.json();
    })
    .then((data) => {
      if (!data) return;

      usuariosData = data;
      if (!Array.isArray(usuariosData)) {
        console.error("Error al obtener usuarios:", usuariosData);
        redirectToLoginOnce();
        return;
      }
      const tbody = document.querySelector("#tablaUsuarios tbody");
      if (!tbody) return;

      tbody.innerHTML = "";

      data.forEach((usuario) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
                    <td>${usuario.id_usuario}</td>
                    <td>${usuario.nombre}</td>
                    <td>${usuario.apellidos ?? ""}</td>
                    <td>${usuario.telefono ?? ""}</td>
                    <td>${usuario.mail}</td>
                    <td>••••••</td>
                    <td>${usuario.id_rol}</td>
                    <td class="acciones">
                        <button class="btn-icono btn-editar" data-id="${usuario.id_usuario}"><ion-icon name="create-outline"></ion-icon></button>
                        <button class="btn-icono btn-eliminar" data-id="${usuario.id_usuario}"><ion-icon name="trash-outline"></ion-icon></button>
                    </td>
                `;
        tbody.appendChild(tr);
      });
    })
    .catch((err) => {
      console.error("Error al cargar usuarios:", err);
      if (
        err &&
        err.message &&
        /403|401|No autorizado|Error HTTP/.test(err.message)
      ) {
        redirectToLoginOnce();
        return;
      }
      alert("Error al cargar usuarios: " + err.message);
    });
}

/** ====== DELEGACIÓN DE EVENTOS ====== */
function initDelegacion() {
  const tbody = document.querySelector("#tablaUsuarios tbody");
  if (!tbody) return;

  tbody.addEventListener("click", (e) => {
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
    id_rol: document.getElementById("id_rol").value,
  };

  fetch(appUrl("routeradmin.php?accion=insertarUsuario"), {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(datos),
  })
    .then((res) => res.json())
    .then((resp) => {
      if (resp.success) {
        cerrarModalCrearUsuario();
        cargarUsuarios();
      } else {
        alert("Error: " + resp.message);
      }
    })
    .catch((err) => alert("Error al insertar usuario: " + err.message));
}

/** ====== EDITAR USUARIO ====== */
function editarUsuario(id) {
  const usuario = usuariosData.find((u) => u.id_usuario == id);
  if (!usuario) return alert("Usuario no encontrado");

  document.getElementById("edit_id_usuario").value = usuario.id_usuario;
  document.getElementById("edit_nombre").value = usuario.nombre;
  document.getElementById("edit_apellidos").value = usuario.apellidos;
  document.getElementById("edit_telefono").value = usuario.telefono;
  document.getElementById("edit_mail").value = usuario.mail;
  document.getElementById("edit_password").value = "";
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
    id_rol: document.getElementById("edit_id_rol").value,
  };

  fetch(appUrl(`routeradmin.php?accion=actualizarUsuario&id=${idUsuario}`), {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(datos),
  })
    .then((res) => res.json())
    .then((resp) => {
      if (resp.success) {
        cerrarModalEditarUsuario();
        cargarUsuarios();
      } else {
        alert("Error: " + resp.message);
      }
    })
    .catch((err) => alert("Error al actualizar usuario: " + err.message));
}

/** ====== ELIMINAR USUARIO ====== */
function eliminarUsuario(id) {
  if (!confirm("¿Seguro que quieres eliminar este usuario?")) return;

  fetch(appUrl(`routeradmin.php?accion=eliminarUsuario&id=${id}`))
    .then((res) => res.json())
    .then((resp) => {
      if (resp.success) cargarUsuarios();
      else alert("Error: " + resp.mensaje);
    })
    .catch((err) => alert("Error al eliminar usuario: " + err.message));
}
