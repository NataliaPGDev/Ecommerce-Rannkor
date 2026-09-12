<?php
// Recuperar errores si existen
$errores = $_SESSION['errores_login'] ?? [];
if (!empty($errores)) {
    unset($_SESSION['errores_login']);
}
// Recuperar correo escrito previamente
$mail_login = $_SESSION['mail_login'] ?? '';
unset($_SESSION['mail_login']);
?>

<!-- contenido vista login ---->

<section>
<div class="login__contenedor">

   <h2>Acceder</h2>

   <form class="login__formulario" action="index.php?controller=auth&action=procesarLogin" method="post">

    <input type="email" name="email" id="email" placeholder="Correo electrónico"
           value="<?= htmlspecialchars($mail_login) ?>" required>

    <?php if (isset($errores['mail'])): ?>
        <div class="mensaje-error"><?= $errores['mail'] ?></div>
    <?php endif; ?>

    <input type="password" name="password" id="password" placeholder="Contraseña" required>

    <?php if (isset($errores['password'])): ?>
        <div class="mensaje-error"><?= $errores['password'] ?></div>
    <?php endif; ?>

    <div class="login__enlace--olvido">
        <a href="#">¿Olvidaste tu contraseña?</a>
    </div>

    <button class="login__boton" type="submit">INICIAR SESIÓN</button>

    <?php if (isset($errores['general'])): ?>
        <div class="mensaje-error mensaje-general"><?= $errores['general'] ?></div>
    <?php endif; ?>

  </form>

  <div class="login__enlace--cuenta">
    <a href="index.php?controller=auth&action=registro">Crear Cuenta</a>
  </div>

</div>
</section>