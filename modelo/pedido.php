<?php
require_once __DIR__ . '/../configuracion/conexion_bd.php';

class Pedido {

    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Conexion::getConexion();
    }

    /* ============================================================
       1. CREAR PEDIDO
    ============================================================ */
    public function crearPedido($id_usuario, $id_direccion, $metodo_pago, $tipo_envio, $gastos_envio, $total) {

        // Generar código único para el pedido
        $codigo = 'PED-' . strtoupper(uniqid());
        $estado_pedido = "pagado";

        $stmt = $this->conn->prepare(
            "INSERT INTO pedidos (
            id_usuario,
            id_direccion,
            metodo_pago,
            fecha_pedido,
            estado_pedido,
            tipo_envio,
            gastos_envio,
            total,
            codigo
        )
        VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            throw new Exception("Error al preparar pedido: " . $this->conn->error);
        }

        // Vincular parámetros (tipos exactos)
        // i -> entero, s -> string, d -> double
        if (!$stmt->bind_param(
            "iisssdds",
            $id_usuario,
            $id_direccion,
            $metodo_pago,
            $estado_pedido,
            $tipo_envio,
            $gastos_envio,
            $total,
            $codigo
        )) {
            throw new Exception("Error al bindear parámetros del pedido: " . $stmt->error);
        }

        // Ejecutar la inserción
        if (!$stmt->execute()) {
            throw new Exception("Error al insertar pedido: " . $stmt->error);
        }

        // Obtener ID del pedido recién insertado
        $id_pedido = $this->conn->insert_id;
        $stmt->close();

        // Retornar ID y código para usar en detalles o vistas
        return [
            'id_pedido'     => $id_pedido,
            'codigo'        => $codigo
        ];
    }


    /* ============================================================
       2. INSERTAR DETALLE EN LA TABLA PEDIDOS_DETALLE
    ============================================================ */
    public function insertarDetalle($id_pedido, $id_producto, $talla, $cantidad, $precio_unitario) {

        $stmt = $this->conn->prepare(
            "INSERT INTO pedidos_detalle (id_pedido, id_producto, talla, cantidad, precio_unitario)
         VALUES (?, ?, ?, ?, ?)"
        );

        if (!$stmt) throw new Exception("Error al preparar detalle: " . $this->conn->error);

        if (!$stmt->bind_param("iiiid", $id_pedido, $id_producto, $talla, $cantidad, $precio_unitario)) {
            throw new Exception("Error al bindear parámetros del detalle: " . $stmt->error);
        }

        if (!$stmt->execute()) throw new Exception("Error al insertar detalle: " . $stmt->error);

        $stmt->close();
        return true;
    }


    /* ============================================================
       3. OBTENER UN PEDIDO
    ============================================================ */
   public function obtenerPedidoCompleto($id_pedido) {

    $sql = "SELECT 
                p.*,
                u.nombre,
                u.apellidos,
                u.telefono,
                d.calle,
                d.numero,
                d.bloque,
                d.planta,
                d.puerta,
                d.ciudad,
                d.provincia,
                d.codigo_postal
            FROM pedidos p
            JOIN usuarios u ON p.id_usuario = u.id_usuario
            JOIN direccion d ON p.id_direccion = d.id_direccion
            WHERE p.id_pedido = ?";

    $stmt = $this->conn->prepare($sql);
    if (!$stmt) throw new Exception("Error al preparar consulta: " . $this->conn->error);

    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();

    $result = $stmt->get_result();
    $pedido = $result->fetch_assoc();

    $stmt->close();
    return $pedido;
}


    /* ============================================================
       3. OBTENER LOS DETALLES DE UN PEDIDO
    ============================================================ */
    public function obtenerDetallesPorPedido($id_pedido) {
    $sql = "SELECT 
                pd.id_producto,
                pd.talla,
                pd.cantidad,
                pd.precio_unitario,    -- aquí viene de pedidos_detalle
                p.nombre_producto,
                p.imagen_url
            FROM pedidos_detalle pd
            JOIN productos p ON pd.id_producto = p.id_producto
            WHERE pd.id_pedido = ?";

    $stmt = $this->conn->prepare($sql);
    if (!$stmt) throw new Exception("Error al preparar detalles: " . $this->conn->error);

    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();

    $result = $stmt->get_result();
    $detalles = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    return $detalles;
}


    /* ============================================================
       4. OBTENER TODOS LOS PEDIDOS DE UN USUARIO
    ============================================================ */
    public function obtenerHistorialPedidos($id_usuario) {
        
        $sql = "SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY fecha_pedido DESC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) throw new Exception("Error al preparar consulta: " . $this->conn->error);

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        $result = $stmt->get_result();
        $pedidos = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $pedidos;
    }
}
?>