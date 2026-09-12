<?php 
require_once __DIR__ . '/../configuracion/conexion_bd.php';

class Carrito {
   
    private mysqli $conn;

    public function __construct() {
        $this->conn = Conexion::getConexion(); // Singleton
    }

    //---------------------------------------------------------------------------------- FUNCION CREAR UN NUEVO CARRITO
    public function crearCarrito($id_usuario) {

        $sql = "INSERT INTO carrito (id_usuario, fecha_creacion, estado) VALUES (?, NOW(), 'activo')";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar inserción de carrito: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id_usuario);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar inserción de carrito: " . $stmt->error);
        }

        $id_carrito = $stmt->insert_id;
        $stmt->close();
        return $id_carrito;
    }

    //-------------------------------------------------------------------------------------- FUNCION OBTENER CARRITO ACTIVO
    public function obtenerCarritoActivo($id_usuario) {

        $sql = "SELECT * FROM carrito WHERE id_usuario = ? AND estado = 'activo' LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta de carrito activo: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id_usuario);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta de carrito activo: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $carrito = $result->fetch_assoc();
        $stmt->close();
        return $carrito;
    }

    //---------------------------------------------------------------------------------------- FUNCION ACTUALIZAR ESTADO DEL CARRITO
    public function actualizarEstado($id_carrito, $estado) {

        $estados_validos = ['activo','pendiente','finalizado','cancelado'];
        if (!in_array($estado, $estados_validos)) {
            throw new Exception("Estado inválido: $estado");
        }

        $sql = "UPDATE carrito SET estado = ? WHERE id_carrito = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar actualización de estado: " . $this->conn->error);
        }

        $stmt->bind_param("si", $estado, $id_carrito);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar actualización de estado: " . $stmt->error);
        }

        $stmt->close();
    }

     //-------------------------------------------------------------------------------------------- FUNCION ELIMINAR EL CARRITO
    public function eliminar($id_carritodetalle) {

        $sql = "DELETE FROM carrito_detalle WHERE id_carritodetalle = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar eliminación de carrito_detalle: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id_carritodetalle);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar eliminación de carrito_detalle: " . $stmt->error);
        }

        $stmt->close();
    }

    //------------------------------------------------------------------------------------------------  FUNCION VACIAR EL CARRITO
    public function vaciar($id_carrito) {

        $sql = "DELETE FROM carrito_detalle WHERE id_carrito = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) throw new Exception("Error preparando DELETE: " . $this->conn->error);

        $stmt->bind_param("i", $id_carrito);
        if (!$stmt->execute()) throw new Exception("Error ejecutando DELETE: " . $stmt->error);

        $stmt->close();
    }
}
?>