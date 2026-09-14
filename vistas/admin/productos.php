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
            <div class="modal-campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre">
            </div>

            <div class="modal-campo">
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion">
            </div>

            <div class="modal-campo">
                <label for="categoria">Categoría</label>
                <select id="categoria">
                    <option value="">Selecciona una categoría</option>
                    <option value="hombre">Hombre</option>
                    <option value="mujer">Mujer</option>
                </select>
            </div>

            <div class="modal-campo">
                <label for="precio">Precio</label>
                <input type="number" id="precio" step="0.01" min="0">
            </div>

            <div class="modal-campo">
                <label for="talla">Talla</label>
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
            </div>

            <div class="modal-campo">
                <label for="stock">Stock</label>
                <input type="number" id="stock" min="0">
            </div>

            <div class="modal-campo modal-campo--full">
                <label for="imagen">Imagen</label>
                <input type="file" id="imagen" accept="image/*">
            </div>
        </div>

        <div class="modal-acciones">
            <button class="btn-modal btn-modal--secondary" onclick="cerrarModalCrearProducto()">Cancelar</button>
            <button class="btn-modal btn-modal--primary" onclick="crearProducto()">Guardar</button>
        </div>
    </div>
</div>

<!-- Modal Editar Producto -->
<div id="modalEditarProducto" class="modal">
    <div class="modal-contenido">
        <h2>Editar producto</h2>
        <div class="modal-input">
            <input type="hidden" id="edit_id_producto">
            <input type="hidden" id="edit_id_productostalla" />

            <div class="modal-campo">
                <label for="edit_nombre">Nombre</label>
                <input type="text" id="edit_nombre">
            </div>

            <div class="modal-campo">
                <label for="edit_descripcion">Descripción</label>
                <input type="text" id="edit_descripcion">
            </div>

            <div class="modal-campo">
                <label for="edit_categoria">Categoría</label>
                <select id="edit_categoria">
                    <option value="">Selecciona una categoria</option>
                    <option value="hombre">Hombre</option>
                    <option value="mujer">Mujer</option>
                </select>
            </div>

            <div class="modal-campo">
                <label for="edit_precio">Precio</label>
                <input type="number" id="edit_precio" step="0.01" min="0">
            </div>

            <div class="modal-campo">
                <label for="edit_talla">Talla</label>
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
            </div>

            <div class="modal-campo">
                <label for="edit_stock">Stock</label>
                <input type="number" id="edit_stock" min="0">
            </div>

            <div class="modal-campo modal-campo--full">
                <label for="edit_imagen">Imagen nueva</label>
                <input type="file" id="edit_imagen" accept="image/*">
            </div>
        </div>

        <div class="modal-acciones">
            <button class="btn-modal btn-modal--secondary" onclick="cerrarModalEditarProducto()">Cancelar</button>
            <button class="btn-modal btn-modal--primary" onclick="guardarCambiosProducto()">Guardar cambios</button>
        </div>
    </div>
</div>

<!-- Modal Agregar Talla -->
<div id="modalAgregarTalla" class="modal">
    <div class="modal-contenido modal-contenido--compacto">
        <h2>Agregar talla</h2>
        <div class="modal-input">
            <input type="hidden" id="add_id_producto">

            <div class="modal-campo">
                <label for="add_talla">Talla</label>
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
            </div>

            <div class="modal-campo">
                <label for="add_stock">Stock</label>
                <input type="number" id="add_stock" min="0">
            </div>
        </div>

        <div class="modal-acciones">
            <button class="btn-modal btn-modal--secondary" onclick="cerrarModalAgregarTalla()">Cancelar</button>
            <button class="btn-modal btn-modal--primary" onclick="guardarTallaProducto()">Agregar</button>
        </div>
    </div>
</div>