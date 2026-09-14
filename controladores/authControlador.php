<?php

/**En este archivo se centralizada la lógica del acceso del usuario y el administrador 
 * usando el modelo Usuario
 */
require_once __DIR__ . '/../modelo/usuario.php';
require_once __DIR__ . '/../configuracion/conexion_bd.php';

class AuthControlador
{
    private Usuario $usuarioModelo;
    private mysqli $conn;

    public function __construct()
    {
        $this->usuarioModelo = new Usuario();
        $this->conn = Conexion::getConexion();
    }


    //---------------------------------------------------------------FUNCION PARA MOSTRAR VISTA DEL FORMULARIO LOGIN
    public function login()
    {
        require_once __DIR__ . '/../vistas/auth/login.php';
    }


    //----------------------------------------------------------------FUNCION PARA PROCESAR EL LOGIN
    public function procesarLogin($params = [])
    {
        $mail = trim($params['email'] ?? '');
        $password = trim($params['password'] ?? '');

        $_SESSION['errores_login'] = [];

        if ($mail === '') {
            $_SESSION['errores_login']['mail'] = "El correo es obligatorio.";
        } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errores_login']['mail'] = "Introduce un correo válido.";
        }

        if ($password === '') {
            $_SESSION['errores_login']['password'] = "La contraseña es obligatoria.";
        }

        if (!empty($_SESSION['errores_login'])) {
            $_SESSION['mail_login'] = $mail;
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $_SESSION['mail_login'] = $mail;

        $usuario = $this->usuarioModelo->obtenerUsuario($mail);
        $passwordHash = isset($usuario['password']) ? trim((string)$usuario['password']) : '';

        if ($usuario && password_verify($password, $passwordHash)) {
            unset($_SESSION['errores_login']);

            $rolUsuario = (int)($usuario['id_rol'] ?? 0);

            $_SESSION['usuario'] = [ #aqui estan los datos de sesion guardados
                'id'     => $usuario['id_usuario'],
                'nombre' => $usuario['nombre'],
                'mail'   => $usuario['mail'],
                'rol'    => $rolUsuario,
                'id_rol' => $rolUsuario,
            ];

            $_SESSION['ultimo_acceso'] = time();

            if ($_SESSION['usuario']['rol'] == 1) {
                header("Location: index.php?controller=admin&action=dashboard");
            } else {
                header("Location: index.php?controller=usuario&action=perfil");
            }
            exit();
        }

        $_SESSION['errores_login']['general'] = "Correo o contraseña incorrectos.";
        header("Location: index.php?controller=auth&action=login");
        exit();
    }

    //------------------------------------------------------------------------------------ FUNCION LOGOUT
    public function logout()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_unset();
        session_destroy();

        header("Location: index.php?controller=home&action=index");
        exit();
    }

    //------------------------------------------------------------------------------------ FUNCION MOSTRAR FORMULARIO DE REGISTRO
    public function registro()
    {
        require_once __DIR__ . '/../vistas/auth/registro.php';
    }

    //------------------------------------------------------------------------------------ FUNCION PARA PROCESAR EL REGISTRO
    public function crearCuenta($params = [])
    {
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
