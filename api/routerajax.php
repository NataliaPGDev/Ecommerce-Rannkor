<?php
session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../middleware.php';
require_once __DIR__ . '/../controladores/carritoControlador.php';
require_once __DIR__ . '/../controladores/pedidoControlador.php';
require_once __DIR__ . '/../controladores/usuarioControlador.php';

Middleware::verificarSesionAPI();

// Instancias
$carrito = new CarritoControlador();
$pedido  = new PedidoControlador();
$usuario = new UsuarioControlador();

// Leer JSON (si existe)
$data = json_decode(file_get_contents("php://input"), true) ?? [];

// Acción por GET o POST (prioridad GET)
$accion = $_GET['accion'] 
       ?? $data['accion'] 
       ?? null;

if (!$accion) {
    http_response_code(400);
    echo "No se recibió ninguna acción.";
    exit;
}

// Rutas
switch ($accion) {

    case 'actualizarCantidad':
        $carrito->actualizarCantidad($data);
        break;

    case 'eliminarProducto':
        $carrito->eliminarProducto($data);
        break;

    case 'verDatos':
        $usuario->verDatos(); // devuelve HTML
        break;

    case 'historial':
        $pedido->historial(); // devuelve HTML
        break;

    case 'historialDetalle':
        $pedido->historialDetalle(); // devuelve HTML
        break;

    default:
        http_response_code(400);
        echo "Acción no válida.";
        exit;
}
?>