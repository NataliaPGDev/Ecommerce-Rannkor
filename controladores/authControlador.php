<?php
/**En este archivo se centralizada la lógica del acceso del usuario y el administrador 
 * usando el modelo Usuario
*/
require_once __DIR__ . '/../modelo/usuario.php';
require_once __DIR__ . '/../configuracion/conexion_bd.php';

class AuthControlador {
    private Usuario $usuarioModelo;
    private mysqli $conn;

    public function __construct() {
        $this->usuarioModelo = new Usuario();
        $this->conn = Conexion::getConexion();
    }


    //---------------------------------------------------------------FUNCION PARA MOSTRAR VISTA DEL FORMULARIO LOGIN
    public function login() {
        require_once __DIR__ . '/../vistas/auth/login.php';
    }


    //----------------------------------------------------------------FUNCION PARA PROCESAR EL LOGIN
    public function procesarLogin($params=[]) {
        
        $mail = trim($params['email'] ?? '');
        $password = trim($params['password'] ?? '');
        $_SESSION['mail_login'] = $mail;

        $usuario = $this->usuarioModelo->obtenerUsuario($mail); 
      
        if ($usuario && password_verify($password, $usuario['password'])) {

            // Limpiar errores previos
            unset($_SESSION['errores_login']);

            $_SESSION['usuario'] = [ #aqui estan los datos de sesion guardados
                'id'     => $usuario['id_usuario'],
                'nombre' => $usuario['nombre'],
                'mail'   => $usuario['mail'],
                'rol'    => $usuario['id_rol']
            ];
           
            $_SESSION['ultimo_acceso'] = time();
            

            if ($_SESSION['usuario']['rol'] == 1) {
                header("Location: index.php?controller=admin&action=dashboard");
            } else {
                header("Location: index.php?controller=usuario&action=perfil");
            }
            exit();
        } else {
            $_SESSION['errores_login']['general'] = "Correo o contraseña incorrectos";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }

    //------------------------------------------------------------------------------------ FUNCION LOGOUT
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?controller=home&action=index");
        exit();
    }

    //------------------------------------------------------------------------------------ FUNCION MOSTRAR FORMULARIO DE REGISTRO
    public function registro() {
        require_once __DIR__ . '/../vistas/auth/registro.php';
    }

    //------------------------------------------------------------------------------------ FUNCION PARA PROCESAR EL REGISTRO
    public function crearCuenta($params=[]) {
        $nombre   = trim($params['nombre'] ?? '');
        $email    = trim($params['email'] ?? '');
        $password = trim($params['password'] ?? '');

        if (empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['error_registro'] = "Todos los campos son obligatorios";
            header("Location: index.php?controller=auth&action=registro");
            exit();
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $this->usuarioModelo->insertarNuevoUsuario($nombre, $email, $hash);
        header("Location: index.php?controller=auth&action=login");
        exit();
    }
}
?>