<?php
require_once __DIR__ . '/../modelo/carrito.php';
require_once __DIR__ . '/../modelo/carritoDetalle.php';
require_once __DIR__ . '/../modelo/pedido.php';
require_once __DIR__ . '/../modelo/usuario.php';
require_once __DIR__ . '/../componentes/datos.php';
require_once __DIR__ . '/../configuracion/conexion_bd.php';


class PedidoControlador{
    private Pedido $pedidoModel;
    private Carrito $carritoModel;
    private CarritoDetalle $carritoDetalleModel;
    private Usuario $usuarioModel;
    private mysqli $conn;

    public function __construct(){
        $this->pedidoModel        = new Pedido();
        $this->carritoModel       = new Carrito();
        $this->carritoDetalleModel = new CarritoDetalle();
        $this->usuarioModel = new Usuario();
        $this->conn = Conexion::getConexion();
    }

    
    //------------------------------------------------------------------------------ FUNCION PARA GENERAR VISTA CHECKOUT
    public function checkout($params = []){

        try {
            // Middleware ya validó sesión
            $id_usuario = $_SESSION['usuario']['id'] ?? null;

            if (!$id_usuario) {
                header("Location: index.php?controller=auth&action=login");
                exit;
            }

            // Obtener carrito activo
            $carrito = $this->carritoModel->obtenerCarritoActivo($id_usuario);
            $id_carrito = $carrito['id_carrito'] ?? null;

            if (!$id_carrito) {
                header("Location: index.php?controller=carrito&action=ver");
                exit;
            }

            // Obtener productos del carrito
            $detallesCarro = $this->carritoDetalleModel->obtenerPorCarrito($id_carrito);

            // Datos que enviaremos a la vista
            $lineasCheckout = [];
            $subtotal = 0;

            foreach ($detallesCarro as $detalle) {

                $id_carritodetalle = $detalle['id_carritodetalle'];

                // Recalcular total del producto + subtotal del carrito
                $totales = $this->carritoDetalleModel->recalcularTotales($id_carritodetalle, $id_carrito);

                $lineasCheckout[] = [
                    "nombre" => $detalle['nombre_producto'],
                    "imagen" => $detalle['imagen_url'],
                    "cantidad" => $detalle['cantidad'],
                    "total_producto" => $totales['totalProducto']
                ];

                // Subtotal global del carrito (lo devuelve tu modelo)
                $subtotal = $totales['subtotalCarrito'];
            }

            // Provincias estáticas
            $provincias = DatosEstaticos::provincias();

            // Cargar vista
            require_once __DIR__ . '/../vistas/pedidos/checkout.php';
        } catch (\Exception $e) {
            error_log("Error en checkout: " . $e->getMessage());
            echo "<p>Ha ocurrido un error al cargar el checkout. Por favor, inténtalo más tarde.</p>";
            exit;
        }
    }

    
    //------------------------------------------------------------------------------------- FUNCION FINALIZAR COMPRA
    public function compra($params = []) {
        $this->conn->begin_transaction();
        try {

            $id_usuario = $_SESSION['usuario']['id'] ?? null;
            if (!$id_usuario) {
                throw new Exception("Usuario no logueado");
            }

            // Obtener el carrito activo del usuario
            $carrito = $this->carritoModel->obtenerCarritoActivo($id_usuario);
            if (!$carrito) {
                throw new Exception("No existe carrito activo para este usuario");
            }

            $id_carrito = $carrito['id_carrito'];

            $tipo_envio = $params['tipo_envio'] ?? 'estandar';

            // Validar método de pago
            $opciones_pago = ['visa', 'paypal', 'googlepay'];
            if (!in_array($params['metodo_pago'] ?? '', $opciones_pago)) {
                $metodo_pago = 'visa'; // valor por defecto
            } else {
                $metodo_pago = $params['metodo_pago'];
            }

            // Datos de dirección
            $direccion = [
                'calle'                => $params['calle'] ?? null,
                'numero'               => $params['numero'] ?? null,
                'bloque'               => $params['bloque'] ?? '',
                'planta'               => $params['planta'] ?? '',
                'puerta'               => $params['puerta'] ?? '',
                'ciudad'               => $params['ciudad'] ?? null,
                'codigo_postal'        => $params['codigo_postal'] ?? null,
                'provincia'            => $params['provincia'] ?? null
            ];

            if (!$id_carrito || !$id_usuario) {
                throw new Exception("Faltan datos para finalizar la compra.");
            }

            // Guardar la dirección y obtener id_direccion
            $id_direccion = $this->usuarioModel->guardarDireccion($id_usuario, $direccion);
            if (!$id_direccion) {
                throw new Exception("Error al guardar la dirección.");
            }

            // Actualizar datos del usuario (teléfono y apellidos)
            $datosUsuario = [
                'apellidos' => $params['apellidos'] ?? null,
                'telefono'  => $params['telefono'] ?? null
            ];
            $this->usuarioModel->actualizarDatos($id_usuario, $datosUsuario);

            // Obtener detalles del carrito
            $detalles = $this->carritoDetalleModel->obtenerPorCarrito($id_carrito);
            if (empty($detalles)) {
                throw new Exception("El carrito está vacío.");
            }

            // Calcular subtotal
            $subtotal = 0;
            foreach ($detalles as $item) {
                $subtotal += $item['precio_unitario'] * $item['cantidad'];
            }

            // Calcular gastos de envío
            $costes_envio = ['estandar' => 5, 'urgente' => 10];
            $gastos_envio = $costes_envio[$tipo_envio] ?? 5;

            // Calcular total final
            $total = $subtotal + $gastos_envio;

            // Crear pedido en la tabla pedido
            $resultadoPedido = $this->pedidoModel->crearPedido(
                $id_usuario,
                $id_direccion,
                $metodo_pago,
                $tipo_envio,
                $gastos_envio,
                $total
            );

            $id_pedido = $resultadoPedido['id_pedido'];

            // Insertar detalles en la tabla pedido detalles
            foreach ($detalles as $item) {
                $this->pedidoModel->insertarDetalle(
                    $id_pedido,
                    $item['producto_id'],
                    $item['talla'],
                    $item['cantidad'],
                    $item['precio_unitario']
                );
            }

            // Finalizar carrito
            $this->carritoModel->actualizarEstado($id_carrito, 'finalizado');
            $this->carritoModel->vaciar($id_carrito);

            $this->conn->commit();

            // Redirigir a la vista del pedido
            header("Location: index.php?controller=pedido&action=ver&id_pedido=$id_pedido");
            exit;
        } catch (Exception $e) {
            $this->conn->rollback();
            echo "Error en la simulación de compra: " . $e->getMessage();
        }
    }

    
    //---------------------------------------------------------------------------------- FUNCION VER UN PEDIDO ESPECIFICO DEL USUARIO
    public function ver($params = []){
        try {
            $id_pedido = $params['id_pedido'] ?? $_GET['id_pedido'] ?? null;
            if (!$id_pedido) throw new Exception("Pedido no especificado.");

            $pedido   = $this->pedidoModel->obtenerPedidoCompleto($id_pedido);
            $detalles = $this->pedidoModel->obtenerDetallesPorPedido($id_pedido);

            if (!$pedido) {
                throw new Exception("No se encontró el pedido con ID $id_pedido.");
            }

            require_once __DIR__ . "/../vistas/pedidos/verPedido.php";
        } catch (Exception $e) {
            echo "Error al mostrar pedido: " . $e->getMessage();
        }
    }

    
    //-----------------------------------------------------------------------   FUNCION ENPOINT VER TODOS LOS PEDIDOS DEL USUARIO LLAMDA DESDE ROUTERAJAX
    public function historial(){
    try {
        $id_usuario = $_SESSION['usuario']['id'] ?? null;
        if (!$id_usuario) {
            throw new Exception("Usuario no especificado o no logueado.");
        }

        $pedidos = $this->pedidoModel->obtenerHistorialPedidos($id_usuario);

        if ($pedidos === false) {
            throw new Exception("Error al obtener el historial de pedidos.");
        }

        // Cargar la vista del historial
        require_once __DIR__ . "/../vistas/pedidos/historial.php";
    } catch (Exception $e) {
        http_response_code(500);
        echo "<p>Error al mostrar histórico de pedidos: " . htmlspecialchars($e->getMessage()) . "</p>";
        error_log("PedidoControlador::historial - " . $e->getMessage());
    }
}

//-----------------------------------------------------------------------   FUNCION ENPOINT DETALLE DE UN PEDIDO DEL HISTORIAL DEL USUARIO ROUTERAJAX
public function historialDetalle() {
    try {
        $id_pedido = $_GET['id_pedido'] ?? null;
        if (!$id_pedido) {
            http_response_code(400);
            echo "<p>Pedido no especificado.</p>";
            return;
        }

        $detalles = $this->pedidoModel->obtenerDetallesPorPedido($id_pedido);

        if ($detalles === false) {
            throw new Exception("No se pudieron obtener los detalles del pedido.");
        }

        // Cargar la vista del detalle del pedido
        require_once __DIR__ . '/../vistas/pedidos/detallePedido.php';
    } catch (Exception $e) {
        http_response_code(500);
        echo "<p>Error al mostrar el detalle del pedido: " . htmlspecialchars($e->getMessage()) . "</p>";
        error_log("PedidoControlador::historialDetalle - " . $e->getMessage());
    }
 }
}
?>