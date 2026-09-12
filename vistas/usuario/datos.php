<h2>Datos</h2>
    <ul class="perfil__lista">
        <li>👤 <strong>Nombre:</strong> <?= htmlspecialchars($datos['nombre'] ?? '') ?></li>
        <li>📝 <strong>Apellidos:</strong> <?= htmlspecialchars($datos['apellidos'] ?? '') ?></li>
        <li>📧 <strong>Correo:</strong> <?= htmlspecialchars($datos['mail'] ?? '') ?></li>
        <li>📱 <strong>Teléfono:</strong> <?= htmlspecialchars($datos['telefono'] ?? '') ?></li>
    </ul>