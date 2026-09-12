<?php
require_once __DIR__ . '/../modelo/productos.php';

class HomeControlador {
    private Productos $productoModelo;

    public function __construct() {
        $this->productoModelo = new Productos();
    }


   //---------------------------------------------------------------------------FUNCIÓN PARA IR A VISTAS DEL HOME
   public function index($params = []) {
    try {
        $novedadesMujer = $this->productoModelo->obtenerNovedadesPorCategoria('mujer', 8);
        $novedadesHombre = $this->productoModelo->obtenerNovedadesPorCategoria('hombre', 8);

        require_once __DIR__ . '/../vistas/home/index.php';

    } catch (Exception $e) {
        error_log($e->getMessage());
        $_SESSION['error'] = "No se pudieron cargar los productos en la página principal.";
        require_once __DIR__ . '/../vistas/home/index.php';
    }
  }
}
?>