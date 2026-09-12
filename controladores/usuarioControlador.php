<?php
require_once __DIR__ . '/../modelo/usuario.php';


class UsuarioControlador {
    private Usuario $usuarioModelo;
    

    public function __construct() {
        $this->usuarioModelo = new Usuario();
    }

    //------------------------------------------------------------------------ FUNCION PARA REDIRIGIR AL PERFIL DEL USUARIO
   public function perfil($params = []) {

    // Verificar que existe la sesión del usuario
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
        // Si no hay sesión, redirigir al login
        header("Location: index.php?controller=auth&action=login");
        exit();
    }

    // Obtener el ID del usuario desde la sesión
    $id_usuario = $_SESSION['usuario']['id'];

    // Cargar la vista del perfil
    require_once __DIR__ . '/../vistas/usuario/perfil.php';
}


    //------------------------------------------------------------------------- FUNCION PARA VER LOS DATOS VIA FECTH DEL USUARIO EN SU PERFIL
    public function verDatos(){

    try {
        $id_usuario = $_SESSION['usuario']['id'] ?? null;
        if (!$id_usuario) {
            throw new Exception("Usuario no especificado o no logueado.");
        }

        $datos = $this->usuarioModelo->obtenerDatos($id_usuario);

        if ($datos === false) {
            throw new Exception("No se pudieron obtener los datos del usuario.");
        }

        // Cargar la vista de datos del usuario
        require_once __DIR__ . '/../vistas/usuario/datos.php';
        
    } catch (Exception $e) {
        http_response_code(500);
        echo "<p>Error al mostrar los datos del usuario: " . htmlspecialchars($e->getMessage()) . "</p>";
        error_log("UsuarioControlador::verDatos - " . $e->getMessage());
    }
 }
}
?>