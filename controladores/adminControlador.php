<?php
require_once __DIR__ . '/../modelo/admin.php';


class AdminControlador {
    private Admin $adminModelo;

    public function __construct(){
        $this->adminModelo = new Admin();
    }


    // FUNCIÓN PARA IR A VISTA DASHBOARD
    public function dashboard(){
        require_once __DIR__ . '/../vistas/admin/dashboard.php';
    }


    /* =================================================
       FUNCIONES ENDPOINT PARA LA GESTION DE USUARIOS
    ===================================================== */

    public function listarUsuarios(){
        $usuarios = $this->adminModelo->obtenerUsuarios();
        echo json_encode($usuarios);
    }

    //--------------------------------------------------------------------INSERTAR USUARIO
    public function insertarUsuario(){
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            if (!is_array($input) || empty($input)) {
                throw new Exception("No se recibieron datos para insertar");
            }

            $this->adminModelo->insertarUsuario($input);

            echo json_encode([
                "success" => true,
                "message" => "Usuario insertado correctamente"
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }


    //------------------------------------------------------------------- ACTUALIZAR USUARIO

    public function actualizarUsuario(){
        $id_usuario = $_GET['id'] ?? null;

        // Leemos los datos enviados desde el frontend (JSON)
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            if (!$id_usuario) {
                throw new Exception("ID de usuario no especificado.");
            }

            if (!is_array($input) || empty($input)) {
                throw new Exception("No se recibieron datos para actualizar.");
            }


            // Llamada al modelo
            $this->adminModelo->actualizarUsuario($id_usuario, $input);

            // Respuesta exitosa
            echo json_encode([
                "success" => true,
                "message" => "Usuario actualizado correctamente."
            ]);
        } catch (Exception $e) {
            // Captura cualquier excepción lanzada por el modelo o validaciones
            http_response_code(400); // Bad Request
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }


    //------------------------------------------------------------------- ELIMINAR USUARIO

    public function eliminarUsuario($id_usuario){

        if (empty($id_usuario)) {
            echo json_encode(["error" => true, "mensaje" => "ID no recibido"]);
            return;
        }
        $respuesta = $this->adminModelo->eliminarUsuario($id_usuario);
        echo json_encode($respuesta);
    }


    /* =================================================
       FUNCIONES ENDPOINT PARA LA GESTION DE PRODUCTOS
    ===================================================== */


    //--------------------------------------------------------------------- LISTAR PRODUCTOS
    public function listarProductos(){
        try {
            $productos = $this->adminModelo->listarProductos();

            echo json_encode([
                "success" => true,
                "data" => $productos
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "error" => $e->getMessage()
            ]);
        }
    }


    //---------------------------------------------------------------------- INSERTAR PRODUCTOS
    public function insertarProducto(){
        // Leer datos desde el body JSON
        $input = json_decode(file_get_contents('php://input'), true);

        // Validación mínima en el controlador: verificar que haya datos
        if (empty($input)) {
            echo json_encode([
                "success" => false,
                "message" => "No se recibieron datos para insertar."
            ]);
            return;
        }

        try {
            // Llamada al modelo
            $id_producto = $this->adminModelo->insertarProducto($input);

            echo json_encode([
                "success" => true,
                "message" => "Producto insertado correctamente.",
                "id_producto" => $id_producto
            ]);
        } catch (Exception $e) {
            // El modelo ya lanza excepciones si algo no es válido
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }


    //------------------------------------------------------------------------- ACTUALIZAR PRODUCTOS
    public function actualizarProducto(){
        $input = json_decode(file_get_contents('php://input'), true);

        $id_productostalla = $input['id_productostalla'] ?? null;
        $id_producto       = $input['id_producto'] ?? null;

        if (!$id_productostalla || !$id_producto) {
            echo json_encode([
                "success" => false,
                "message" => "IDs no válidos."
            ]);
            return;
        }
        // Filtramos los campos que queremos enviar al modelo
        $data = $input;
        unset($data['id_productostalla'], $data['id_producto']); // quitamos IDs del array de datos

        try {
            $this->adminModelo->actualizarProducto((int)$id_productostalla, (int)$id_producto, $data);

            echo json_encode([
                "success" => true,
                "message" => "Producto actualizado correctamente."
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    //-------------------------------------------------------------------------- INSERTAR TALLA PARA UN PRODUCTO
    public function agregarTallasProducto(){
    // Obtener datos enviados por fetch
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar que llegue id_producto y tallas
    if (empty($input['id_producto']) || !is_array($input['tallas']) || empty($input['tallas'])) {
        echo json_encode([
            "success" => false,
            "message" => "Faltan datos obligatorios: id_producto o tallas."
        ]);
        return;
    }

    $id_producto = (int)$input['id_producto'];
    $tallas = $input['tallas'];

    try {
        $this->adminModelo->insertarProductoTalla($id_producto, $tallas);

        echo json_encode([
            "success" => true,
            "message" => "Tallas agregadas correctamente."
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
}


    //--------------------------------------------------------------------------- ELIMINAR PRODUCTO
    public function eliminarProducto(){
        $input = json_decode(file_get_contents('php://input'), true);
        $id_productostalla = $input['id_productostalla'] ?? null;
        if (!$id_productostalla) {
            echo json_encode([
                "success" => false,
                "message" => "IDs no válidos."
            ]);
            return;
        }

        try {
            $this->adminModelo->eliminarProductoPorTalla((int)$id_productostalla);

            echo json_encode([
                "success" => true,
                "message" => "Producto eliminado correctamente (solo la talla especificada)."
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }
}
?>