<?php
session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/configuracion/conexion_bd.php';
require_once __DIR__ . '/middleware.php';
require_once __DIR__ . '/controladores/adminControlador.php';


Middleware::verificarAdminAPI();

// Instancias
$administrador = new AdminControlador();


// Leer JSON
$data = json_decode(file_get_contents("php://input"), true) ?? [];

// Acción
$accion = $_GET['accion'] 
       ?? $data['accion'] 
       ?? null;

if (!$accion) {
    http_response_code(400);
    echo json_encode(["error" => "No se recibió ninguna acción"]);
    exit;
}

switch ($accion) {

    // USUARIOS
    case 'listarUsuarios':
        $administrador->listarUsuarios();
        break;

    case 'insertarUsuario':
        $administrador->insertarUsuario($data);
        break;

    case 'actualizarUsuario':
        $administrador->actualizarUsuario();
        break;

    case 'eliminarUsuario':
        $administrador->eliminarUsuario($_GET['id']);
        break;

    case 'listarProductos':
        $administrador->listarProductos();
        break;

    case 'insertarProducto':
        $administrador->insertarProducto();
        break;

    case 'actualizarProducto':
        $administrador->actualizarProducto();
        break;

    case 'agregarTallasProducto':
        $administrador->agregarTallasProducto();
        break;

    case 'eliminarProducto':
        $administrador->eliminarProducto();
        break;


    default:
        http_response_code(400);
        echo json_encode(["error" => "Acción no válida"]);
        exit;
}
?>