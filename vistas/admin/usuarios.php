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
            <div class="modal-campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre">
            </div>

            <div class="modal-campo">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos">
            </div>

            <div class="modal-campo">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono">
            </div>

            <div class="modal-campo">
                <label for="mail">Email</label>
                <input type="email" id="mail">
            </div>

            <div class="modal-campo modal-campo--password">
                <label for="password">Contraseña</label>
                <div class="password-input">
                    <input type="password" id="password" autocomplete="new-password" placeholder="Escribe la contraseña">
                    <button type="button" class="btn-password-toggle" data-target="password" aria-label="Mostrar contraseña">
                        <ion-icon name="eye-outline"></ion-icon>
                    </button>
                </div>
            </div>

            <div class="modal-campo">
                <label for="id_rol">Rol</label>
                <input type="number" id="id_rol" min="1">
            </div>
        </div>

        <div class="modal-acciones">
            <button class="btn-modal btn-modal--secondary" onclick="cerrarModalCrearUsuario()">Cancelar</button>
            <button class="btn-modal btn-modal--primary" onclick="crearUsuario()">Guardar</button>
        </div>
    </div>
</div>

<!-- Vista del modal para modificar a un usuario-->

<div id="modalEditarUsuario" class="modal">
    <div class="modal-contenido">
        <h2>Editar usuario</h2>
        <div class="modal-input">
            <input type="hidden" id="edit_id_usuario">

            <div class="modal-campo">
                <label for="edit_nombre">Nombre</label>
                <input type="text" id="edit_nombre">
            </div>

            <div class="modal-campo">
                <label for="edit_apellidos">Apellidos</label>
                <input type="text" id="edit_apellidos">
            </div>

            <div class="modal-campo">
                <label for="edit_telefono">Teléfono</label>
                <input type="text" id="edit_telefono">
            </div>

            <div class="modal-campo">
                <label for="edit_mail">Email</label>
                <input type="email" id="edit_mail">
            </div>

            <div class="modal-campo modal-campo--password">
                <label for="edit_password">Contraseña</label>
                <div class="password-input">
                    <input type="password" id="edit_password" autocomplete="new-password" placeholder="Deja en blanco para mantener la actual">
                    <button type="button" class="btn-password-toggle" data-target="edit_password" aria-label="Mostrar contraseña">
                        <ion-icon name="eye-outline"></ion-icon>
                    </button>
                </div>
            </div>

            <div class="modal-campo">
                <label for="edit_id_rol">Rol</label>
                <input type="number" id="edit_id_rol" min="1">
            </div>
        </div>
        <div class="modal-acciones">
            <button class="btn-modal btn-modal--secondary" onclick="cerrarModalEditarUsuario()">Cancelar</button>
            <button class="btn-modal btn-modal--primary" onclick="guardarCambiosUsuario()">Guardar cambios</button>
        </div>
    </div>
</div>