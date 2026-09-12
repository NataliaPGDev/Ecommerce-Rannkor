<?php
require_once __DIR__ . '/../configuracion/conexion_bd.php';

class CarritoDetalle {
    private mysqli $conn;

    public function __construct() {
        $this->conn = Conexion::getConexion();
    }

    
    //----------------------------------------------------------------------------------- FUNCIÓN INSERTAR PRODUCTO A la tabla CARRITO detalle
    public function agregar($id_carrito, $id_productostalla, $cantidad, $precio_unitario) {

        if ($cantidad <= 0) {
            throw new Exception("La cantidad debe ser mayor que cero.");
        }

        $sql = "INSERT INTO carrito_detalle 
                (id_carrito, id_productostalla, cantidad, precio_unitario)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) throw new Exception($this->conn->error);

        $stmt->bind_param("iiid", $id_carrito, $id_productostalla, $cantidad, $precio_unitario);

        if (!$stmt->execute()) throw new Exception($stmt->error);

        $id_carritodetalle = $this->conn->insert_id;
        $stmt->close();
        return $id_carritodetalle;
    }

    
    //-------------------------------------------------------------------------------------- OBTENER DETALLES DEL CARRITO
    public function obtenerPorCarrito($id_carrito) {

        $sql = "SELECT
                cd.id_carritodetalle,
                cd.id_carrito,
                cd.cantidad,
                cd.precio_unitario,

                pt.id_productostalla,
                pt.id_talla,

                p.id_producto AS producto_id,
                p.nombre_producto,
                p.imagen_url,

                t.talla
                FROM carrito_detalle cd
                JOIN productos_talla pt ON cd.id_productostalla = pt.id_productostalla
                JOIN productos p ON pt.id_producto = p.id_producto
                JOIN tallas t ON pt.id_talla = t.id_talla
                WHERE cd.id_carrito = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) throw new Exception($this->conn->error);

        $stmt->bind_param("i", $id_carrito);
        $stmt->execute();

        $result = $stmt->get_result();
        $detalles = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $detalles;
    }


    
    //---------------------------------------------------------------------------------------- ACTUALIZAR CANTIDAD
    public function actualizarCantidad($id_carritodetalle, $cantidad) {

        if ($cantidad <= 0) {
            throw new Exception("La cantidad debe ser mayor que cero.");
        }

        $sql = "UPDATE carrito_detalle SET cantidad = ? 
                WHERE id_carritodetalle = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) throw new Exception($this->conn->error);

        $stmt->bind_param("ii", $cantidad, $id_carritodetalle);

        if (!$stmt->execute()) throw new Exception($stmt->error);

        $stmt->close();

        return $this->obtenerDetalle($id_carritodetalle);
    }

    
    //------------------------------------------------------------------------------------------ FUNCIÓN ELIMINAR PRODUCTO DEL CARRITO
    public function eliminar($id_carritodetalle) {

        $sqlSelect = "SELECT id_carrito FROM carrito_detalle WHERE id_carritodetalle = ?";
        $stmt = $this->conn->prepare($sqlSelect);

        if (!$stmt) throw new Exception($this->conn->error);

        $stmt->bind_param("i", $id_carritodetalle);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row) throw new Exception("El producto no existe en el carrito.");

        $id_carrito = $row['id_carrito'];

        // Eliminar
        $sqlDelete = "DELETE FROM carrito_detalle WHERE id_carritodetalle = ?";
        $stmt2 = $this->conn->prepare($sqlDelete);

        if (!$stmt2) throw new Exception($this->conn->error);

        $stmt2->bind_param("i", $id_carritodetalle);
        if (!$stmt2->execute()) throw new Exception($stmt2->error);

        $stmt2->close();

        return $id_carrito;
    }

   
    //----------------------------------------------------------------------------------------------- FUNCIÓN OBTENER DETALLE
    public function obtenerDetalle($id_carritodetalle) {

        $sql = "SELECT id_carrito, cantidad, precio_unitario 
            FROM carrito_detalle 
            WHERE id_carritodetalle = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) throw new Exception($this->conn->error);

        $stmt->bind_param("i", $id_carritodetalle);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row) throw new Exception("Detalle no encontrado.");

        return $row;
    }


    //------------------------------------------------------------------------------------------ FUNCIÓN TOTAL PRODUCTO
    public function totalProducto($id_carritodetalle) {

        $sql = "SELECT cantidad * precio_unitario AS total
        FROM carrito_detalle
        WHERE id_carritodetalle = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error preparando consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id_carritodetalle);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $resultado['total'] ?? 0;
    }


   
    //------------------------------------------------------------------------------------------ FUNCIÓN SUBTOTAL CARRITO
    public function subtotalCarrito($id_carrito) {

        $sql = "SELECT SUM(cantidad * precio_unitario) AS subtotal
                FROM carrito_detalle
                WHERE id_carrito = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) throw new Exception($this->conn->error);

        $stmt->bind_param("i", $id_carrito);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row['subtotal'] ?? 0;
    }

    
    //-------------------------------------------------------------------------------------------- FUNCIÓN RECALCULAR TOTALES
    public function recalcularTotales($id_carritodetalle, $id_carrito) {
        
        $detalle = $this->obtenerDetalle($id_carritodetalle);

        return [
            "totalProducto"   => $detalle['cantidad'] * $detalle['precio_unitario'],
            "subtotalCarrito" => $this->subtotalCarrito($id_carrito)
        ];
    }
}
?>