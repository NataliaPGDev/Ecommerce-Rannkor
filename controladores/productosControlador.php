<?php
require_once __DIR__.'/../modelo/productos.php';

class ProductosControlador {

  private Productos $productoModelo;

    public function __construct() {
        $this->productoModelo = new Productos();
    }


    //---------------------------------------------------------------------------- FUNCION MOSTRAR PRODUCTOS POR CATEGORIA
    public function listarCategoria($params = []) {
        try {
            
            $categoria = $params['categoria'] ?? null; # Capturamos la categoría desde params

            if (!$categoria) {
                throw new Exception("No se especificó la categoría.");
            }

            $productos = $this->productoModelo->obtenerProductoCategoria($categoria); # variable almacena el array con los campos de la tabla productos

            $vista = __DIR__ . "/../vistas/productos/categoria.php"; # la vista a la que redirige

            if (file_exists($vista)) { #validación
                require_once $vista;
            } else {
                header("Location: index.php?controller=home&action=index");
                exit; 
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error'] = "No se pudieron cargar los productos de la categoría.";
            header("Location: index.php?controller=home&action=index");
            exit();
        }
    }


    //-----------------------------------------------------------------FUNCION VER PRODUCTO TANTO POR EL ID CON ACCESO A LA TABLA TALLAS Y PRODUCTO_TALLAS
     public function verProducto($params = []) {

     try {
        // El primer parámetro del array es el id_producto
        $id_producto = $params['id_producto'] ?? null;

        if (!$id_producto) {
            throw new Exception("No se ha especificado el producto.");
        }

        // Llamada al modelo: ahora devuelve producto + tallas
        $producto = $this->productoModelo->obtenerProductoPorId($id_producto);

        if (!$producto) {
            throw new Exception("Producto no encontrado.");
        }

        // Pasas los datos a la vista
        $vista = __DIR__ . "/../vistas/productos/detalle.php"; 

        if (file_exists($vista)) {
            require_once $vista;
        } else {
            header("Location: index.php?controller=home&action=index");
            exit; 
        }

    } catch (Exception $e) {
        echo "Error al cargar producto: " . $e->getMessage();
    }
 }
}
?>