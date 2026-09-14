<?php
final class Middleware
{

    private const TIEMPO_LIMITE = 1800; // 30 minutos

    private static function esAdmin(): bool
    {
        $usuario = $_SESSION['usuario'] ?? null;
        if (!is_array($usuario)) {
            return false;
        }

        $rol = $usuario['rol'] ?? $usuario['id_rol'] ?? null;
        return $rol !== null && (int)$rol === 1;
    }

    /**
     * --------------------------------------------------------------------------------  Verifica sesión para páginas vistas del router principal
     */
    public static function verificarSesionWeb(): void
    {
        if (!isset($_SESSION['usuario'])) {
            $_SESSION['error'] = "Debes iniciar sesión";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        if (isset($_SESSION['ultimo_acceso'])) {
            $inactividad = time() - $_SESSION['ultimo_acceso'];
            if ($inactividad > self::TIEMPO_LIMITE) {
                session_unset();
                session_destroy();
                $_SESSION['error'] = "Sesión expirada";
                header("Location: index.php?controller=auth&action=login");
                exit();
            }
        }

        $_SESSION['ultimo_acceso'] = time();
    }

    /**
     * ---------------------------------------------------------------------------------- Verifica sesión del usuario para router AJAX/Fetch
     */
    public static function verificarSesionAPI(): void
    {
        if (!isset($_SESSION['usuario'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
            exit();
        }

        if (isset($_SESSION['ultimo_acceso'])) {
            $inactividad = time() - $_SESSION['ultimo_acceso'];
            if ($inactividad > self::TIEMPO_LIMITE) {
                session_unset();
                session_destroy();
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'Sesión expirada']);
                exit();
            }
        }

        $_SESSION['ultimo_acceso'] = time();
    }

    /**
     * ------------------------------------------------------------ Verifica si el usuario es admin en router principal
     * Funciona para páginas web
     */
    public static function verificarAdminWeb(): void
    {
        if (!isset($_SESSION['usuario']) || !self::esAdmin()) {
            $_SESSION['error'] = "Acceso denegado";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }

    /**
     * ------------------------------------------------------------ Verifica si el usuario es admin para usar en archivo routerajax
     */
    public static function verificarAdminAPI(): void
    {
        if (!isset($_SESSION['usuario'])) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error' => 'No hay sesión activa'
            ]);
            exit;
        }

        if (!self::esAdmin()) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error' => 'Acceso denegado'
            ]);
            exit;
        }
    }
}
