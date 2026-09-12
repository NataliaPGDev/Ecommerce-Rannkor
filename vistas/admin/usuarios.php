<!-- vistas/admin/usuarios.php -->

<h1 class="titulo-admin">Gestión de Usuarios</h1>

<div class="acciones-superiores">
    <button id="btnNuevoUsuario" class="btn-accion">
        <ion-icon name="add-circle-outline"></ion-icon>
        Nuevo usuario
    </button>
</div>

<div class="tabla-contenedor">
    <table id="tablaUsuarios" class="tabla-admin">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Contraseña</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Se rellena dinámicamente -->
        </tbody>
    </table>
</div>

<!-- Vista del modal para insertar un usuario-->

<div id="modalCrearUsuario" class="modal">
    <div class="modal-contenido">
        <h2>Crear nuevo usuario</h2>
        <div class="modal-input">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre">

            <label for="apellidos">Apellidos</label>
            <input type="text" id="apellidos">

            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono">

            <label for="mail">Email</label>
            <input type="email" id="mail">

            <label for="password">Password</label>
            <input type="password" id="password">

            <label for="id_rol">Rol</label>
            <input type="number" id="id_rol">
        </div>

        <div class="modal-acciones">
            <button onclick="crearUsuario()">Guardar</button>
            <button onclick="cerrarModalCrearUsuario()">Cancelar</button>
        </div>
    </div>
</div>

<!-- Vista del modal para modificar a un usuario-->

<div id="modalEditarUsuario" class="modal">
    <div class="modal-contenido">
        <h2>Editar usuario</h2>
        <div class="modal-input">
            <input type="hidden" id="edit_id_usuario">

            <label>Nombre</label>
            <input type="text" id="edit_nombre">

            <label>Apellidos</label>
            <input type="text" id="edit_apellidos">

            <label>Teléfono</label>
            <input type="text" id="edit_telefono">

            <label>Email</label>
            <input type="email" id="edit_mail">

            <label>Email</label>
            <input type="email" id="edit_password">

            <label>Rol</label>
            <input type="number" id="edit_id_rol">
        </div>
        <div class="modal-acciones">
            <button onclick="guardarCambiosUsuario()">Guardar cambios</button>
            <button onclick="cerrarModalEditarUsuario()">Cancelar</button>
        </div>
    </div>
</div>