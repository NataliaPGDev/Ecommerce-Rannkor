<?php

function generarHash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Mostrar el hash en el navegador
echo generarHash("admin123");

?>
