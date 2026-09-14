/** ====== VARIABLES GLOBALES ====== */
let productosData = [];

/** ====== INICIALIZACIÓN DE LA VISTA ====== */
function initProductosView() {
  cargarProductos();
  initModalesProductos();
  initDelegacionProductos();
}

/** ====== MODALES ====== */
function initModalesProductos() {
  const modalCrear = document.getElementById("modalCrearProducto");
  const modalEditar = document.getElementById("modalEditarProducto");
  const modalAgregarTalla = document.getElementById("modalAgregarTalla");

  const btnNuevo = document.getElementById("btnNuevoProducto");
  if (btnNuevo) btnNuevo.addEventListener("click", abrirModalCrearProducto);

  window.addEventListener("click", (e) => {
    if (e.target === modalCrear) cerrarModalCrearProducto();
    if (e.target === modalEditar) cerrarModalEditarProducto();
    if (e.target === modalAgregarTalla) cerrarModalAgregarTalla();
  });

  window.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      cerrarModalCrearProducto();
      cerrarModalEditarProducto();
      cerrarModalAgregarTalla();
    }
  });
}

function abrirModalCrearProducto() {
  document.getElementById("modalCrearProducto").classList.add("show");
}

function cerrarModalCrearProducto() {
  document.getElementById("modalCrearProducto").classList.remove("show");
}

function abrirModalEditarProducto() {
  document.getElementById("modalEditarProducto").classList.add("show");
}

function cerrarModalEditarProducto() {
  document.getElementById("modalEditarProducto").classList.remove("show");
}

function abrirModalAgregarTalla(id_producto) {
  document.getElementById("add_id_producto").value = id_producto;
  document.getElementById("modalAgregarTalla").classList.add("show");
}

function cerrarModalAgregarTalla() {
  document.getElementById("modalAgregarTalla").classList.remove("show");
}

/** ====== CARGAR PRODUCTOS ====== */
function cargarProductos() {
  fetch(appUrl("routeradmin.php?accion=listarProductos"))
    .then((res) => {
      if (!res.ok) {
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
    .then((resp) => {
      if (!resp.success) {
        console.error("Error al obtener productos:", resp);
        alert(
          "No se pudieron cargar los productos. Revisa la sesión de administrador.",
        );
        return;
      }

      productosData = resp.data;

      if (!Array.isArray(productosData)) {
        console.error("productosData no es un array:", productosData);
        alert("La respuesta no contiene un array de productos.");
        return;
      }

      const tbody = document.querySelector("#tablaProductos tbody");
      if (!tbody) return;

      tbody.innerHTML = "";

      productosData.forEach((prod) => {
        const tr = document.createElement("tr");
        const imagenUrl = prod.imagen_url
          ? `${window.location.origin}${window.APP_BASE || ""}/uploads/productos/${prod.imagen_url}`
          : "";

        tr.innerHTML = `
        <td>${prod.id_producto}</td>
        <td>${prod.nombre_producto}</td>
        <td>${prod.descripcion ?? ""}</td>
        <td>${prod.categoria ?? ""}</td>
        <td>${parseFloat(prod.precio).toFixed(2)}</td>
        <td>${prod.talla}</td>
        <td>${prod.stock}</td>
        <td><img src="${imagenUrl}" alt="${prod.nombre_producto}" width="50"></td>
        <td class="acciones">
            <button class="btn-icono btn-editar" data-id_producto="${prod.id_producto}" data-id_productostalla="${prod.id_productostalla}">
                <ion-icon name="create-outline"></ion-icon>
            </button>
            <button class="btn-icono btn-eliminar" data-id_productostalla="${prod.id_productostalla}">
                <ion-icon name="trash-outline"></ion-icon>
            </button>
             <button class="btn-icono btn-agregar-talla" data-id_producto="${prod.id_producto}">
             <ion-icon name="add-circle-outline"></ion-icon>
             </button>
        </td>
    `;
        tbody.appendChild(tr);
      });
    })
    .catch((err) => {
      console.error("Error al cargar productos:", err);
      alert("Error al cargar productos: " + err.message);
    });
}

/** ====== DELEGACIÓN ====== */
function initDelegacionProductos() {
  const tbody = document.querySelector("#tablaProductos tbody");
  if (!tbody) return;

  tbody.addEventListener("click", (e) => {
    const editarBtn = e.target.closest(".btn-editar");
    const eliminarBtn = e.target.closest(".btn-eliminar");
    const agregarTallaBtn = e.target.closest(".btn-agregar-talla");

    if (editarBtn) {
      const id_producto = parseInt(editarBtn.dataset.id_producto);
      const id_productostalla = parseInt(editarBtn.dataset.id_productostalla);
      editarProducto(id_producto, id_productostalla);
    }

    if (eliminarBtn) {
      const id_productostalla = parseInt(eliminarBtn.dataset.id_productostalla);
      eliminarProducto(id_productostalla);
    }

    if (agregarTallaBtn) {
      const id_producto = parseInt(agregarTallaBtn.dataset.id_producto);
      abrirModalAgregarTalla(id_producto);
    }
  });
}

/** ====== CREAR ====== */
function crearProducto() {
  const formData = new FormData();
  const archivo = document.getElementById("imagen").files[0];

  formData.append("nombre_producto", document.getElementById("nombre").value);
  formData.append("descripcion", document.getElementById("descripcion").value);
  formData.append("categoria", document.getElementById("categoria").value);
  formData.append("precio", document.getElementById("precio").value);
  formData.append(
    "tallas",
    JSON.stringify([
      {
        id_talla: parseInt(document.getElementById("talla").value),
        stock: parseInt(document.getElementById("stock").value),
      },
    ]),
  );

  if (archivo) {
    formData.append("imagen", archivo);
  }

  fetch(appUrl("routeradmin.php?accion=insertarProducto"), {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((resp) => {
      if (resp.success) {
        document.getElementById("imagen").value = "";
        document.getElementById("nombre").value = "";
        document.getElementById("descripcion").value = "";
        document.getElementById("categoria").value = "";
        document.getElementById("precio").value = "";
        document.getElementById("talla").value = "110";
        document.getElementById("stock").value = "";
        cerrarModalCrearProducto();
        cargarProductos();
      } else {
        alert("Error: " + resp.message);
      }
    })
    .catch((err) => alert("Error al insertar producto: " + err.message));
}

/** ====== EDITAR ====== */
function editarProducto(id_producto, id_productostalla) {
  const p = productosData.find(
    (x) =>
      Number(x.id_producto) === id_producto &&
      Number(x.id_productostalla) === id_productostalla,
  );
  if (!p) return alert("Producto no encontrado");

  document.getElementById("edit_id_producto").value = p.id_producto;
  document.getElementById("edit_id_productostalla").value = p.id_productostalla;
  document.getElementById("edit_nombre").value = p.nombre_producto;
  document.getElementById("edit_descripcion").value = p.descripcion ?? "";
  document.getElementById("edit_categoria").value = p.categoria ?? "";
  document.getElementById("edit_precio").value = parseFloat(p.precio).toFixed(
    2,
  );
  document.getElementById("edit_talla").value = p.id_talla ?? "";
  document.getElementById("edit_stock").value = p.stock ?? 0;
  document.getElementById("edit_imagen").value = "";

  abrirModalEditarProducto();
}

function guardarCambiosProducto() {
  const id_producto = parseInt(
    document.getElementById("edit_id_producto").value,
  );
  const id_productostalla = parseInt(
    document.getElementById("edit_id_productostalla").value,
  );

  if (isNaN(id_producto) || isNaN(id_productostalla))
    return alert("IDs no válidos");

  const formData = new FormData();
  const archivo = document.getElementById("edit_imagen").files[0];

  formData.append("id_producto", String(id_producto));
  formData.append("id_productostalla", String(id_productostalla));
  formData.append(
    "nombre_producto",
    document.getElementById("edit_nombre").value,
  );
  formData.append(
    "descripcion",
    document.getElementById("edit_descripcion").value,
  );
  formData.append("categoria", document.getElementById("edit_categoria").value);
  formData.append("precio", document.getElementById("edit_precio").value);
  formData.append("stock", document.getElementById("edit_stock").value);

  if (archivo) {
    formData.append("imagen", archivo);
  }

  fetch(appUrl("routeradmin.php?accion=actualizarProducto"), {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((resp) => {
      if (resp.success) {
        document.getElementById("edit_imagen").value = "";
        cerrarModalEditarProducto();
        cargarProductos();
      } else {
        alert("Error: " + resp.message);
      }
    })
    .catch((err) => alert("Error al actualizar producto: " + err.message));
}

/** ====== GUARDAR PRODUCTO CON TALLA ====== */

function guardarTallaProducto() {
  const id_producto = parseInt(
    document.getElementById("add_id_producto").value,
  );
  const id_talla = parseInt(document.getElementById("add_talla").value);
  const stock = parseInt(document.getElementById("add_stock").value);

  if (!id_producto || !id_talla || stock < 0) return alert("Datos inválidos");

  const datos = {
    id_producto,
    tallas: [{ id_talla, stock }],
  };

  fetch(appUrl("routeradmin.php?accion=agregarTallasProducto"), {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(datos),
  })
    .then((res) => res.json())
    .then((resp) => {
      if (resp.success) {
        cerrarModalAgregarTalla();
        cargarProductos();
      } else {
        alert("Error: " + resp.message);
      }
    })
    .catch((err) => alert("Error al agregar talla: " + err.message));
}

/** ====== ELIMINAR ====== */
function eliminarProducto(id_productostalla) {
  if (!confirm("¿Deseas eliminar este producto/talla?")) return;

  fetch(appUrl("routeradmin.php?accion=eliminarProducto"), {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id_productostalla }),
  })
    .then((res) => res.json())
    .then((resp) => {
      if (resp.success) {
        cargarProductos();
      } else {
        alert("Error: " + resp.message);
      }
    })
    .catch((err) => alert("Error al eliminar producto: " + err.message));
}
