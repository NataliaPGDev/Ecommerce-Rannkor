<?php

require_once __DIR__ . '/../modelo/productos.php';
require_once __DIR__ . '/../modelo/carrito.php';
require_once __DIR__ . '/../modelo/carritoDetalle.php';

class CarritoControlador
{
    private Productos $productoModelo;
    private Carrito $carritoModelo;
    private CarritoDetalle $carritoDetalleModelo;

    public function __construct()
    {
        $this->productoModelo       = new Productos();
        $this->carritoModelo        = new Carrito();
        $this->carritoDetalleModelo = new CarritoDetalle();
    }


    //---------------------------------------------------------------------- FUNCION OBTENER O CREAR CARRITO ACTIVO DEL USUARIO
    private function obtenerCarritoUsuario()
    {
        $id_usuario = $_SESSION['usuario']['id'] ?? null;
        if (!$id_usuario) {
            throw new Exception("Usuario no logueado.");
        }

        $carrito = $this->carritoModelo->obtenerCarritoActivo($id_usuario);
        if (!$carrito) {
            $this->carritoModelo->crearCarrito($id_usuario);
            $carrito = $this->carritoModelo->obtenerCarritoActivo($id_usuario);
        }

        return $carrito;
    }


    //----------------------------------------------------------------------- FUNCIÓN VER CARRITO
    public function ver($params = [])
    {
        try {
            $carrito  = $this->obtenerCarritoUsuario();
            $detalles = $this->carritoDetalleModelo->obtenerPorCarrito($carrito['id_carrito']);

            // Calcular total por producto
            foreach ($detalles as &$detalle) {
                $detalle['total'] = $this->carritoDetalleModelo->totalProducto($detalle['id_carritodetalle']);
            }
            unset($detalle);

            // Subtotal del carrito
            $subtotal = 0;
            if (!empty($carrito) && isset($carrito['id_carrito'])) {
                $subtotal = $this->carritoDetalleModelo->subtotalCarrito($carrito['id_carrito']);
            }

            require_once __DIR__ . '/../vistas/carrito/carrito.php';
        } catch (Exception $e) {
            echo "Error al mostrar carrito: " . $e->getMessage();
        }
    }


    //--------------------------------------------------------------------------------- FUNCION AGREGAR PRODUCTO AL CARRITO
    public function agregar($params = [])
    {
        try {
            $carrito = $this->obtenerCarritoUsuario();

            $id_producto       = $params['id_producto'] ?? null;
            $id_productostalla = $params['id_productostalla'] ?? null;
            $cantidad          = (int)($params['cantidad'] ?? 1);

            if (!$id_producto || !$id_productostalla || $cantidad < 1) {
                throw new Exception("Datos inválidos para agregar producto.");
            }

            $producto = $this->productoModelo->obtenerProductoPorId($id_producto);
            if (!$producto || !isset($producto['precio'])) {
                throw new Exception("No se pudo obtener el precio del producto.");
            }

            $this->productoModelo->validarStockDisponible($id_productostalla, $cantidad);

            $precio_unitario = $producto['precio'];

            $this->carritoDetalleModelo->agregar(
                $carrito['id_carrito'],
                $id_productostalla,
                $cantidad,
                $precio_unitario
            );

            header("Location: index.php?controller=carrito&action=ver");
            exit;
        } catch (Exception $e) {
            echo "Error al agregar al carrito: " . $e->getMessage();
        }
    }


    //---------------------------------------------------------------------------------- FUNCION ENDPOINT ACTUALIZAR CANTIDAD VIA FECHT
    public function actualizarCantidad($data)
    {
        header('Content-Type: application/json');
        try {
            $id_carritodetalle = isset($data['id_carritodetalle']) ? (int)$data['id_carritodetalle'] : null;
            $cantidad = isset($data['cantidad']) ? (int)$data['cantidad'] : null;

            if (!$id_carritodetalle || !$cantidad) {
                throw new Exception("Parámetros inválidos.");
            }

            $this->carritoDetalleModelo->actualizarCantidad($id_carritodetalle, $cantidad);

            $detalle = $this->carritoDetalleModelo->obtenerDetalle($id_carritodetalle);
            $totales = $this->carritoDetalleModelo->recalcularTotales($id_carritodetalle, $detalle['id_carrito']);

            echo json_encode([
                "success" => true,
                "totalProducto" => $totales['totalProducto'],
                "subtotalCarrito" => $totales['subtotalCarrito']
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "error" => $e->getMessage()
            ]);
        }
    }


    //-------------------------------------------------------------------------------  FUNCION ENDPOINT ELIMINAR PRODUCTO CON AJAX
    public function eliminarProducto()
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $id_detalle = isset($data['id_carritodetalle']) ? (int)$data['id_carritodetalle'] : 0;

            if (!$id_detalle) throw new Exception("Detalle no especificado.");

            $id_carrito = $this->carritoDetalleModelo->eliminar($id_detalle);
            $subtotal = $this->carritoDetalleModelo->subtotalCarrito($id_carrito);

            echo json_encode([
                'success' => true,
                'subtotal' => number_format($subtotal, 2)
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit;
    }


    //--------------------------------------------------------------------------------------- FUNCION VACÍAR CARRITO
    public function vaciarCarrito()
    {
        header('Content-Type: application/json');

        try {
            $carrito = $this->obtenerCarritoUsuario();
            $this->carritoModelo->vaciar($carrito['id_carrito']);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }


    //---------------------------------------------------------------------------------------- FUNCIÓN CHECKOUT
    public function checkout()
    {
        try {
            $carrito = $this->obtenerCarritoUsuario();
            $this->carritoModelo->actualizarEstado($carrito['id_carrito'], 'pendiente');

            header("Location: index.php?controller=carrito&action=ver");
            exit;
        } catch (Exception $e) {
            echo "Error en checkout: " . $e->getMessage();
        }
    }


    //----------------------------------------------------------------------------------------- FINALIZAR COMPRA
    public function finalizar()
    {
        try {
            $carrito = $this->obtenerCarritoUsuario();
            $this->carritoModelo->actualizarEstado($carrito['id_carrito'], 'finalizado');

            header("Location: index.php?controller=pedido&action=crear");
            exit;
        } catch (Exception $e) {
            echo "Error al finalizar carrito: " . $e->getMessage();
        }
    }


    //------------------------------------------------------------------------------------------- FUNCIÓN CANCELAR CARRITO
    public function cancelar()
    {
        try {
            $carrito = $this->obtenerCarritoUsuario();
            $this->carritoModelo->actualizarEstado($carrito['id_carrito'], 'cancelado');

            header("Location: index.php?controller=carrito&action=ver");
            exit;
        } catch (Exception $e) {
            echo "Error al cancelar carrito: " . $e->getMessage();
        }
    }
}
