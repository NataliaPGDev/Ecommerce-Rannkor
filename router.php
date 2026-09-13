<?php
require_once __DIR__ . '/configuracion/conexion_bd.php';
require_once __DIR__ . '/middleware.php';


// 1. Controlador y acción desde GET
$controller = strtolower($_GET['controller'] ?? 'home');
$action     = strtolower($_GET['action'] ?? 'index');

// Compatibilidad de rutas: normaliza nombres antiguos
if ($controller === 'producto') {
    $controller = 'productos';
}
if ($action === 'listar') {
    $action = 'listarCategoria';
}

// 2. Archivo y clase del controlador
$controllerClass = ucfirst($controller) . "Controlador";
$controllerFile  = __DIR__ . "/controladores/" . $controllerClass . ".php";

if (!file_exists($controllerFile)) {
    die("<h1>404</h1><p>Controlador '$controllerClass' no encontrado</p>");
}

require_once $controllerFile;
$controlador = new $controllerClass();


// 3. Middleware
if (in_array($controller, ['usuario', 'carrito', 'pedido'])) {
    Middleware::verificarSesionWeb();
}

if ($controller === 'admin') {
    Middleware::verificarAdminWeb();
}


// 4. Parámetros
$params = ($_SERVER['REQUEST_METHOD'] === 'POST')
    ? array_merge($_GET, $_POST)  // Para formularios POST
    : $_GET;                      // Para enlaces normales


// 5. Ejecutar acción
if (!method_exists($controlador, $action)) {
    die("<h1>404</h1><p>Acción '$action' no encontrada en $controllerClass</p>");
}

try {
    $controlador->$action($params);
} catch (Exception $e) {
    echo "<h1>Error</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
