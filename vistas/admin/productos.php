<h1 class="titulo-admin">Gestión de Productos</h1>

<div class="acciones-superiores">
    <button id="btnNuevoProducto" class="btn-accion">
        <ion-icon name="add-circle-outline"></ion-icon>
        Nuevo Producto
    </button>
</div>

<div class="tabla-contenedor">
    <table id="tablaProductos" class="tabla-admin">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoria</th>
                <th>Precio</th>
                <th>Talla</th>
                <th>Stock</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal Crear Producto -->
<div id="modalCrearProducto" class="modal">
    <div class="modal-contenido">
        <h2>Crear nuevo producto</h2>
        <div class="modal-input">
            <label>Nombre</label>
            <input type="text" id="nombre">

            <label>Descripción</label>
            <input type="text" id="descripcion">

            <label for="categoria">Categoría:</label>
            <select id="categoria">
                <option value="">Selecciona una categoría</option>
                <option value="hombre">Hombre</option>
                <option value="mujer">Mujer</option>
            </select>

            <label>Precio</label>
            <input type="number" id="precio" step="0.01" min="0">

            <label>Talla</label>
            <select id="talla">
                <option value="110">36</option>
                <option value="111">37</option>
                <option value="112">38</option>
                <option value="113">39</option>
                <option value="114">40</option>
                <option value="115">41</option>
                <option value="116">42</option>
                <option value="117">43</option>
                <option value="118">44</option>
            </select>

            <label>Stock</label>
            <input type="number" id="stock" min="0">

            <label>Imagen</label>
            <input type="text" id="imagen">
        </div>

        <div class="modal-acciones">
            <button onclick="crearProducto()">Guardar</button>
            <button onclick="cerrarModalCrearProducto()">Cancelar</button>
        </div>
    </div>
</div>

<!-- Modal Editar Producto -->
<div id="modalEditarProducto" class="modal">
    <div class="modal-contenido">
        <h2>Editar Producto</h2>
        <div class="modal-input">
            <input type="hidden" id="edit_id_producto">
            <input type="hidden" id="edit_id_productostalla" />

            <label>Nombre</label>
            <input type="text" id="edit_nombre">

            <label>Descripción</label>
            <input type="text" id="edit_descripcion">

            <label for="edit_categoria">Categoría</label>
            <select id="edit_categoria">
                <option value="">Selecciona una categoria</option>
                <option value="hombre">Hombre</option>
                <option value="mujer">Mujer</option>
            </select>

            <label>Precio</label>
            <input type="number" id="edit_precio" step="0.01" min="0">

            <!-- Los valores del value de cada opcion deben ser cada id_talla correspondiente a la talla ---->
            <label>Talla</label>
            <select id="edit_talla" disabled>
                <option value="110">36</option>
                <option value="111">37</option>
                <option value="112">38</option>
                <option value="113">39</option>
                <option value="114">40</option>
                <option value="115">41</option>
                <option value="116">42</option>
                <option value="117">43</option>
                <option value="118">44</option>
            </select>

            <label>Stock</label>
            <input type="number" id="edit_stock" min="0">

            <label>Imagen</label>
            <input type="text" id="edit_imagen">
        </div>

        <div class="modal-acciones">
            <button onclick="guardarCambiosProducto()">Guardar cambios</button>
            <button onclick="cerrarModalEditarProducto()">Cancelar</button>
        </div>
    </div>
</div>

<!-- Modal Agregar Talla -->
<div id="modalAgregarTalla" class="modal">
    <div class="modal-contenido">
        <h2>Agregar talla a producto</h2>
        <div class="modal-input">
        <input type="hidden" id="add_id_producto">
        <label>Talla</label>
        <select id="add_talla">
            <option value="110">36</option>
            <option value="111">37</option>
            <option value="112">38</option>
            <option value="113">39</option>
            <option value="114">40</option>
            <option value="115">41</option>
            <option value="116">42</option>
            <option value="117">43</option>
            <option value="118">44</option>
        </select>
        <label>Stock</label>
        <input type="number" id="add_stock" min="0">
        </div>
        
        <div class="modal-acciones">
            <button onclick="guardarTallaProducto()">Agregar</button>
            <button onclick="cerrarModalAgregarTalla()">Cancelar</button>
        </div>
    </div>
</div>