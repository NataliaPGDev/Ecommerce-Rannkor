<?php
require_once __DIR__ . '/../configuracion/conexion_bd.php';

class Admin
{

    private mysqli $conn;
    private const CATEGORIAS_VALIDAS = ['hombre', 'mujer'];

    public function __construct()
    {
        $this->conn = Conexion::getConexion(); // Singleton
    }

    /* ============================
       FUNCIONES PARA LA GESTION DE USUARIOS
       ============================ */

    //---------------------------------------------------------------------------------- OBTENER USUARIOS
    public function obtenerUsuarios()
    {

        $sql = "SELECT * FROM usuarios";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $resultado = $stmt->get_result();

        if (!$resultado) {
            throw new Exception("Error al obtener el resultado: " . $stmt->error);
        }

        $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $usuarios;
    }

    //------------------------------------------------------------------------------------------ INSERTAR USUARIO
    public function insertarUsuario(array $data)
    {
        // --- Campos obligatorios ---
        $nombre   = trim($data['nombre'] ?? '');
        $mail     = trim($data['mail'] ?? '');
        $password = trim($data['password'] ?? '');
        $id_rol   = $data['id_rol'] ?? null;

        if ($nombre === '' || $mail === '' || $password === '' || !$id_rol) {
            throw new Exception("Faltan datos obligatorios");
        }

        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El correo no es válido");
        }

        // --- Campos opcionales ---
        $apellidos = trim($data['apellidos'] ?? '') === '' ? null : $data['apellidos'];
        $telefono  = trim($data['telefono'] ?? '') === '' ? null : $data['telefono'];

        // --- Hash de la contraseña ---
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // --- Preparar consulta ---
        $sql = "INSERT INTO usuarios (nombre, apellidos, telefono, mail, password, id_rol)
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("sssssi", $nombre, $apellidos, $telefono, $mail, $passwordHash, $id_rol);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();

        return ["success" => true];
    }

    //--------------------------------------------------------------------------------------------- ACTUALIZAR USUARIO
    public function actualizarUsuario($id_usuario, array $data)
    {
        if (empty($id_usuario)) {
            throw new Exception("El ID del usuario es obligatorio.");
        }

        $campos = [];
        $valores = [];
        $tipos = "";

        // --- Validación y actualización de campos obligatorios ---
        if (isset($data['nombre'])) {
            if (trim($data['nombre']) === "") {
                throw new Exception("El nombre no puede estar vacío.");
            }
            $campos[] = "nombre = ?";
            $valores[] = $data['nombre'];
            $tipos .= "s";
        }

        if (isset($data['mail'])) {
            if (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El correo no es válido.");
            }
            $campos[] = "mail = ?";
            $valores[] = $data['mail'];
            $tipos .= "s";
        }

        if (!empty($data['password'])) {
            if (strlen($data['password']) < 8) {
                throw new Exception("La contraseña debe tener al menos 8 caracteres.");
            }
            $campos[] = "password = ?";
            $valores[] = password_hash($data['password'], PASSWORD_DEFAULT);
            $tipos .= "s";
        }

        if (isset($data['id_rol'])) {
            if (!is_numeric($data['id_rol'])) {
                throw new Exception("El ID de rol no es válido.");
            }
            $campos[] = "id_rol = ?";
            $valores[] = (int)$data['id_rol'];
            $tipos .= "i";
        }

        // --- Campos opcionales (pueden ser NULL) ---
        if (array_key_exists('apellidos', $data)) {
            $campos[] = "apellidos = ?";
            $valores[] = trim($data['apellidos']) === "" ? null : $data['apellidos'];
            $tipos .= "s";
        }

        if (array_key_exists('telefono', $data)) {
            $campos[] = "telefono = ?";
            $valores[] = trim($data['telefono']) === "" ? null : $data['telefono'];
            $tipos .= "s";
        }

        if (empty($campos)) {
            throw new Exception("No hay campos para actualizar.");
        }

        // --- Construcción del UPDATE dinámico ---
        $sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id_usuario = ?";
        $valores[] = $id_usuario;
        $tipos .= "i";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param($tipos, ...$valores);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();

        return ["success" => true];
    }


    //---------------------------------------------------------------------------------------------------- ELIMINAR USUARIO
    public function eliminarUsuario($id_usuario)
    {

        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        if (!$stmt->bind_param("i", $id_usuario)) {
            throw new Exception("Error al vincular parámetros: " . $stmt->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();
        return ["success" => true];
    }


    /* =======================================
       FUNCIONES PARA LA GESTION DE PRODUCTOS
       ======================================= */

    //----------------------------------------------------------------------------------------- OBTENER PRODUCTOS
    public function listarProductos()
    {
        $sql = "SELECT 
                p.id_producto,
                pt.id_productostalla,  
                p.nombre_producto,
                p.descripcion,
                p.categoria,
                p.precio,
                t.talla,
                pt.stock,
                p.imagen_url
            FROM productos p
            INNER JOIN productos_talla pt ON p.id_producto = pt.id_producto
            INNER JOIN tallas t ON pt.id_talla = t.id_talla
            ORDER BY p.id_producto DESC";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error al obtener resultados: " . $stmt->error);
        }

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }

        $stmt->close();
        return $productos;
    }


    public function subirImagenProducto(array $archivo): string
    {
        if (!isset($archivo['tmp_name']) || !is_uploaded_file($archivo['tmp_name'])) {
            throw new Exception("No se recibió ninguna imagen válida.");
        }

        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $permitidas, true)) {
            throw new Exception("Formato de imagen no permitido.");
        }

        $raizProyecto = rtrim(dirname(__DIR__), DIRECTORY_SEPARATOR);
        $carpetaLocal = $raizProyecto . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'productos' . DIRECTORY_SEPARATOR;

        if (!is_dir($carpetaLocal) && !mkdir($carpetaLocal, 0777, true) && !is_dir($carpetaLocal)) {
            throw new Exception("No se pudo crear la carpeta local de imágenes.");
        }

        $nombreArchivo = md5(uniqid((string) microtime(true), true)) . '.' . $extension;
        $destino = $carpetaLocal . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
            throw new Exception("No se pudo guardar la imagen en el servidor.");
        }

        $documentRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? $raizProyecto);
        $rutaProyecto = str_replace('\\', '/', $raizProyecto);
        $rutaRelativa = str_replace($documentRoot, '', $rutaProyecto);
        $rutaRelativa = trim($rutaRelativa, '/');

        if ($rutaRelativa !== '' && strpos($rutaRelativa, 'htdocs') === false && strpos($rutaRelativa, 'public_html') === false) {
            $rutaBase = '/' . $rutaRelativa;
        } else {
            $rutaBase = '';
        }

        return $rutaBase . '/uploads/productos/' . $nombreArchivo;
    }

    //---------------------------------------------------------------------------- INSERTAR PRODUCTOS
    public function insertarProducto(array $data)
    {
        // --- Campos obligatorios ---
        $nombre      = trim($data['nombre_producto'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $categoria   = trim($data['categoria'] ?? '');
        $precio      = $data['precio'] ?? null;
        $imagen_url  = trim($data['imagen_url'] ?? '');
        $tallas      = $data['tallas'] ?? null;

        // --- VALIDACIÓN DE DATOS ---
        if (
            $nombre === '' ||
            $descripcion === '' ||
            $categoria === '' ||
            $imagen_url === '' ||
            !is_array($tallas) ||
            empty($tallas)
        ) {
            throw new Exception("Faltan datos obligatorios para crear el producto");
        }

        if (!is_numeric($precio) || $precio <= 0) {
            throw new Exception("Precio no válido");
        }

        $precio = (float) $precio;

        foreach ($tallas as $t) {
            if (
                !isset($t['id_talla'], $t['stock']) ||
                !is_numeric($t['id_talla']) ||
                $t['id_talla'] <= 0 ||
                !is_numeric($t['stock']) ||
                $t['stock'] < 0
            ) {
                throw new Exception("Datos de talla inválidos");
            }
        }

        if (!in_array($categoria, self::CATEGORIAS_VALIDAS, true)) {
            throw new Exception("Categoría no válida.");
        }


        // --- TRANSACCIÓN ---
        $this->conn->begin_transaction();

        // Insertar producto
        $stmt = $this->conn->prepare(
            "INSERT INTO productos 
         (nombre_producto, descripcion, categoria, precio, imagen_url)
         VALUES (?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            $this->conn->rollback();
            throw new Exception(
                "Error al preparar inserción de producto: " . $this->conn->error
            );
        }

        $stmt->bind_param(
            "sssds",
            $nombre,
            $descripcion,
            $categoria,
            $precio,
            $imagen_url
        );

        if (!$stmt->execute()) {
            $stmt->close();
            $this->conn->rollback();
            throw new Exception(
                "Error al insertar producto: " . $stmt->error
            );
        }

        $id_producto = $stmt->insert_id;
        $stmt->close();

        // Insertar tallas
        $stmt2 = $this->conn->prepare(
            "INSERT INTO productos_talla (id_talla, id_producto, stock)
         VALUES (?, ?, ?)"
        );

        if (!$stmt2) {
            $this->conn->rollback();
            throw new Exception(
                "Error al preparar inserción de tallas: " . $this->conn->error
            );
        }

        foreach ($tallas as $t) {
            $id_talla = (int) $t['id_talla'];
            $stock    = (int) $t['stock'];

            $stmt2->bind_param(
                "iii",
                $id_talla,
                $id_producto,
                $stock
            );

            if (!$stmt2->execute()) {
                $stmt2->close();
                $this->conn->rollback();
                throw new Exception(
                    "Error al insertar talla: " . $stmt2->error
                );
            }
        }

        $stmt2->close();
        $this->conn->commit();
        return $id_producto;
    }


    //--------------------------------------------------------------------------------- ACTUALIZAR PRODUCTO
    public function actualizarProducto($id_productostalla, $id_producto, array $data)
    {
        // --- Validaciones básicas ---
        if (!$id_productostalla || !is_numeric($id_productostalla)) {
            throw new Exception("ID de producto talla no especificado.");
        }

        if (!$id_producto || !is_numeric($id_producto)) {
            throw new Exception("ID de producto no especificado.");
        }

        if (empty($data)) {
            throw new Exception("No se recibieron datos para actualizar.");
        }

        $camposPermitidos = ['nombre_producto', 'descripcion', 'categoria', 'precio', 'imagen_url'];

        $campos = array_intersect_key($data, array_flip($camposPermitidos));
        $actualizarStock = isset($data['stock']);

        if (empty($campos) && !$actualizarStock) {
            throw new Exception("No se proporcionaron campos válidos para actualizar.");
        }

        // Validación de stock
        if ($actualizarStock && (!is_numeric($data['stock']) || $data['stock'] < 0)) {
            throw new Exception("Stock no válido.");
        }

        if (isset($campos['categoria'])) {
            if (!in_array($campos['categoria'], self::CATEGORIAS_VALIDAS, true)) {
                throw new Exception("Categoría no válida.");
            }
        }

        // --- Transacción ---
        $this->conn->begin_transaction();

        // --- Actualización de productos ---
        if (!empty($campos)) {
            $sets = [];
            $valores = [];
            $tipos = '';

            foreach ($campos as $campo => $valor) {
                $sets[] = "$campo=?";
                $valores[] = ($campo === 'precio') ? (float)$valor : $valor;
                $tipos .= ($campo === 'precio') ? 'd' : 's';
            }

            $sql = "UPDATE productos SET " . implode(", ", $sets) . " WHERE id_producto=?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                $this->conn->rollback();
                throw new Exception("Error al preparar actualización de producto: " . $this->conn->error);
            }

            $tipos .= 'i';
            $valores[] = $id_producto;

            $stmt->bind_param($tipos, ...$valores);

            if (!$stmt->execute()) {
                $stmt->close();
                $this->conn->rollback();
                throw new Exception("Error al ejecutar actualización de producto: " . $stmt->error);
            }

            $stmt->close();
        }

        // --- Actualización de stock ---
        if ($actualizarStock) {
            $stmtStock = $this->conn->prepare(
                "UPDATE productos_talla SET stock=? WHERE id_productostalla=?"
            );

            if (!$stmtStock) {
                $this->conn->rollback();
                throw new Exception("Error al preparar actualización de stock: " . $this->conn->error);
            }

            $stock = (int)$data['stock'];
            $stmtStock->bind_param("ii", $stock, $id_productostalla);

            if (!$stmtStock->execute()) {
                $stmtStock->close();
                $this->conn->rollback();
                throw new Exception("Error al actualizar stock: " . $stmtStock->error);
            }

            $stmtStock->close();
        }
        $this->conn->commit();
        return true;
    }


    //----------------------------------------------------------------------------- INSERTAR TALLA PARA UN PRODUCTO
    public function insertarProductoTalla($id_producto, array $tallas)
    {
        if (!$id_producto || !is_numeric($id_producto)) {
            throw new Exception("ID de producto no válido.");
        }

        if (!is_array($tallas) || empty($tallas)) {
            throw new Exception("No se recibieron tallas para insertar.");
        }

        $this->conn->begin_transaction();

        foreach ($tallas as $t) {
            if (!isset($t['id_talla'], $t['stock'])) {
                $this->conn->rollback();
                throw new Exception("Datos de talla incompletos.");
            }

            $id_talla = (int)$t['id_talla'];
            $stock = (int)$t['stock'];

            if ($id_talla <= 0) {
                $this->conn->rollback();
                throw new Exception("ID de talla no válido.");
            }

            if ($stock < 0) {
                $this->conn->rollback();
                throw new Exception("Stock no puede ser negativo.");
            }

            $stmt = $this->conn->prepare(
                "INSERT INTO productos_talla (id_producto, id_talla, stock) VALUES (?, ?, ?)"
            );

            if (!$stmt) {
                $this->conn->rollback();
                throw new Exception("Error al preparar inserción de talla: " . $this->conn->error);
            }

            $stmt->bind_param("iii", $id_producto, $id_talla, $stock);

            if (!$stmt->execute()) {
                $stmt->close();
                $this->conn->rollback();
                throw new Exception("Error al insertar talla: " . $stmt->error);
            }

            $stmt->close();
        }
        $this->conn->commit();
        return true;
    }


    //----------------------------------------------------------------------------------------- ELIMINAR PRODUCTO
    public function eliminarProductoPorTalla($id_productostalla)
    {

        if (!$id_productostalla || !is_numeric($id_productostalla)) {
            throw new Exception("ID de producto-talla no válido.");
        }

        // Iniciar transacción
        $this->conn->begin_transaction();

        // Obtener el id_producto asociado a esta talla
        $stmt = $this->conn->prepare(
            "SELECT id_producto FROM productos_talla WHERE id_productostalla=?"
        );
        if (!$stmt) {
            $this->conn->rollback();
            throw new Exception("Error al preparar consulta: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id_productostalla);
        $stmt->execute();
        $stmt->bind_result($id_producto);
        if (!$stmt->fetch()) {
            $stmt->close();
            $this->conn->rollback();
            throw new Exception("Producto-talla no encontrado.");
        }
        $stmt->close();

        // Eliminar solo la talla
        $stmtDel = $this->conn->prepare(
            "DELETE FROM productos_talla WHERE id_productostalla=?"
        );
        if (!$stmtDel) {
            $this->conn->rollback();
            throw new Exception("Error al preparar eliminación de talla: " . $this->conn->error);
        }
        $stmtDel->bind_param("i", $id_productostalla);
        if (!$stmtDel->execute()) {
            $stmtDel->close();
            $this->conn->rollback();
            throw new Exception("Error al eliminar talla: " . $stmtDel->error);
        }
        $stmtDel->close();

        // Verificar si quedan tallas para este producto
        $stmtCheck = $this->conn->prepare(
            "SELECT COUNT(*) FROM productos_talla WHERE id_producto=?"
        );
        $stmtCheck->bind_param("i", $id_producto);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        // Si no quedan tallas, eliminar el producto
        if ($count === 0) {
            $stmtProd = $this->conn->prepare(
                "DELETE FROM productos WHERE id_producto=?"
            );
            if (!$stmtProd) {
                $this->conn->rollback();
                throw new Exception("Error al preparar eliminación de producto: " . $this->conn->error);
            }
            $stmtProd->bind_param("i", $id_producto);
            if (!$stmtProd->execute()) {
                $stmtProd->close();
                $this->conn->rollback();
                throw new Exception("Error al eliminar producto: " . $stmtProd->error);
            }
            $stmtProd->close();
        }

        $this->conn->commit();
        return true;
    }
}
