<?php
//Se incluye la configuración según el entorno actual
$host = strtolower($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '');

if ($host === 'localhost' || $host === '127.0.0.1' || str_contains($host, 'rannkor.local')) {
    require_once __DIR__ . '/config.local.php';
} else {
    require_once __DIR__ . '/config.php';
}

function urlImagenProducto(?string $nombreArchivo): string
{
    $nombreArchivo = trim((string) ($nombreArchivo ?? ''));

    if ($nombreArchivo === '') {
        return rtrim(BASE_URL, '/') . '/uploads/productos/default.png';
    }

    if (preg_match('#^https?://#i', $nombreArchivo) === 1) {
        return $nombreArchivo;
    }

    return rtrim(BASE_URL, '/') . '/uploads/productos/' . ltrim($nombreArchivo, '/');
}

//Clase singleton para tomar una única instancia de la base de datos
final class Conexion
{
    private static ?mysqli $instancia = null;

    private function __construct() {}
    private function __clone() {}

    public static function getConexion(): mysqli
    {
        if (self::$instancia === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            self::$instancia = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

            if (self::$instancia->connect_errno) {
                throw new RuntimeException('Error de conexión MySQL: ' . self::$instancia->connect_error);
            }

            self::$instancia->set_charset('utf8mb4');
        }

        return self::$instancia;
    }

    public static function close(): void
    {
        if (self::$instancia !== null) {
            self::$instancia->close();
            self::$instancia = null;
        }
    }
}
