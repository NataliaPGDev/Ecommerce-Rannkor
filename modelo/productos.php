<?php
require_once __DIR__ . '/../configuracion/conexion_bd.php';

class Productos
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Conexion::getConexion(); // Singleton
    }

    //------------------------------------------------------------------------------- FUNCION PARA OBTENER LISTADO DE PRODUCTOS SEGUN LA CATEGORIA
    public function obtenerProductoCategoria($categoria)
    {

        $sql = "SELECT * FROM productos WHERE categoria = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error en prepare (obtenerProductoCategoria): " . $this->conn->error);
        }

        if (!$stmt->bind_param("s", $categoria)) {
            throw new Exception("Error en bind_param (obtenerProductoCategoria): " . $stmt->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta (obtenerProductoCategoria): " . $stmt->error);
        }

        $resultado = $stmt->get_result();
        $productos = $resultado->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $productos;
    }



    //------------------------------------------------------------------- FUNCION PARA OBTENER LISTADO DE PRODUCTOS POR ID JOIN CON TABLA TALLAS y PRODUCTOS TALLAS
    function obtenerProductoPorId($id_producto)
    {

        $sql = "SELECT p.id_producto, p.nombre_producto, p.precio, p.categoria, p.descripcion,
               p.imagen_url, pt.id_productostalla, t.id_talla, t.talla, pt.stock
        FROM productos p
        LEFT JOIN productos_talla pt ON p.id_producto = pt.id_producto
        LEFT JOIN tallas t ON pt.id_talla = t.id_talla
        WHERE p.id_producto = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();

        $producto = null;
        $tallas = [];

        while ($row = $result->fetch_assoc()) {
            if (!$producto) {
                $producto = [
                    'id_producto'     => $row['id_producto'],
                    'nombre_producto' => $row['nombre_producto'],
                    'descripcion'     => $row['descripcion'],
                    'precio'          => $row['precio'],
                    'categoria'       => $row['categoria'],
                    'imagen_url'      => $row['imagen_url']
                ];
            }

            if (!empty($row['id_talla'])) { // solo si existe talla
                $tallas[] = [
                    'id_productostalla' => $row['id_productostalla'],
                    'id_talla'          => $row['id_talla'],
                    'talla'             => $row['talla'],
                    'stock'             => $row['stock'],
                ];
            }
        }

        $producto['tallas'] = $tallas;
        $stmt->close();
        return $producto;
    }



    //-------------------------------------------------------------------------------------- FUNCION PARA OBTENER PRODUCTOS NOVEDADES POR CATEGORIA
    public function obtenerNovedadesPorCategoria($categoria, $limite = 8)
    {

        // Selecciona los últimos productos añadidos según el ID más alto y filtrando por categoría
        $sql = "SELECT * FROM productos WHERE categoria = ? ORDER BY id_producto DESC LIMIT ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en prepare (obtenerNovedades): " . $this->conn->error);
        }

        // Vinculamos parámetros: primero la categoría (string), luego el límite (int)
        if (!$stmt->bind_param("si", $categoria, $limite)) {
            throw new Exception("Error en bind_param (obtenerNovedades): " . $stmt->error);
        }

        // Ejecutamos la consulta
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta (obtenerNovedades): " . $stmt->error);
        }

        // Obtenemos el resultado
        $resultado = $stmt->get_result();
        if (!$resultado) {
            throw new Exception("Error al obtener resultado (obtenerNovedades): " . $stmt->error);
        }

        // Devolvemos todos los productos como array asociativo
        $productos = $resultado->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $productos;
    }

    public function obtenerStockPorProductostalla($id_productostalla)
    {
        $sql = "SELECT stock FROM productos_talla WHERE id_productostalla = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error en prepare (obtenerStockPorProductostalla): " . $this->conn->error);
        }

        $stmt->bind_param("i", $id_productostalla);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $stmt->close();

        return $fila['stock'] ?? 0;
    }

    public function validarStockDisponible($id_productostalla, $cantidadSolicitada)
    {
        $cantidadSolicitada = (int)$cantidadSolicitada;
        $stockActual = $this->obtenerStockPorProductostalla($id_productostalla);

        if ($cantidadSolicitada < 1) {
            throw new Exception("La cantidad debe ser mayor que cero.");
        }

        if ($stockActual < $cantidadSolicitada) {
            throw new Exception("No hay suficiente stock para este producto. Disponible: " . $stockActual . ".");
        }

        return true;
    }

    public function restarStock($id_productostalla, $cantidad)
    {
        $cantidad = (int)$cantidad;
        $stockActual = $this->obtenerStockPorProductostalla($id_productostalla);

        if ($stockActual < $cantidad) {
            throw new Exception("No hay suficiente stock para este producto.");
        }

        $sql = "UPDATE productos_talla SET stock = stock - ? WHERE id_productostalla = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error en prepare (restarStock): " . $this->conn->error);
        }

        $stmt->bind_param("ii", $cantidad, $id_productostalla);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar stock: " . $stmt->error);
        }

        $stmt->close();
        return true;
    }
}
