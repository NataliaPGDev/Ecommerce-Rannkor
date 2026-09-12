<?php
require_once __DIR__ . '/../configuracion/conexion_bd.php';

class Usuario {
    private mysqli $conn;

    public function __construct() {
        $this->conn = Conexion::getConexion();
    }


    //---------------------------------------------------------------------------- FUNCION OBTENER USUARIO DE LA TABLA USANDO CAMPO MAIL
    public function obtenerUsuario($mail) {

        $sql = "SELECT * FROM usuarios WHERE mail = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error en prepare (obtenerUsuario): " . $this->conn->error);
        }

        if (!$stmt->bind_param("s", $mail)) {
            throw new Exception("Error en bind_param (obtenerUsuario): " . $stmt->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta (obtenerUsuario): " . $stmt->error);
        }

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();
        return $usuario;
    }

    //------------------------------------------------------------------------------ FUNCION INSERTAR NUEVO USUARIO
   public function insertarNuevoUsuario($nombre, $mail, $passwordHash) {

    $rol = 2;
    $sql = "INSERT INTO usuarios (nombre, mail, password, id_rol) VALUES (?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error en prepare (insertarNuevoUsuario): " . $this->conn->error);
    }

    if (!$stmt->bind_param("sssi", $nombre, $mail, $passwordHash, $rol)) {
        throw new Exception("Error en bind_param (insertarNuevoUsuario): " . $stmt->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar consulta (insertarNuevoUsuario): " . $stmt->error);
    }

    $stmt->close();
    return true;
}


    //---------------------------------------------------------------------------------- FUNCION PARA AGREGAR DETALLES EN LA TABLA USUARIOS
    public function actualizarDatos($id_usuario, $datos) {

        $stmt = $this->conn->prepare(
            "UPDATE usuarios 
         SET apellidos = ?, telefono = ? 
         WHERE id_usuario = ?"
        );

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        if (!$stmt->bind_param(
            "ssi",
            $datos['apellidos'],
            $datos['telefono'],
            $id_usuario
        )) {
            throw new Exception("Error al bindear parámetros: " . $stmt->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();
        return true;
    }

    //------------------------------------------------------------------------------ FUNCION PARA AGREGAR DETALLES DE LA DIRECCION EN TABLA DIRECCION
    public function guardarDireccion($id_usuario, $direccion) {

        // Buscar si ya existe la misma dirección
        $sql = "SELECT id_direccion 
            FROM direccion 
            WHERE calle = ?
              AND numero = ?
              AND bloque <=> ?
              AND planta <=> ?
              AND puerta <=> ?
              AND codigo_postal = ?
              AND ciudad = ?
              AND provincia = ?
              AND id_usuario = ?
            LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sisssissi",
            $direccion['calle'],
            $direccion['numero'],
            $direccion['bloque'],
            $direccion['planta'],
            $direccion['puerta'],
            $direccion['codigo_postal'],
            $direccion['ciudad'],
            $direccion['provincia'],
            $id_usuario,
        );
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        // Si existe → devolverla
        if ($res) {
            return $res['id_direccion'];
        }

        // Si no existe → insertar nueva
        $sqlInsert = "INSERT INTO direccion (calle, numero, bloque, planta, puerta, ciudad, codigo_postal, provincia, id_usuario)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $this->conn->prepare($sqlInsert);
        $stmt2->bind_param(
            "sissssisi",
            $direccion['calle'],
            $direccion['numero'],
            $direccion['bloque'],
            $direccion['planta'],
            $direccion['puerta'],
            $direccion['ciudad'],
            $direccion['codigo_postal'],
            $direccion['provincia'],
            $id_usuario,
        );

        $stmt2->execute();
        $id_direccion = $stmt2->insert_id;

        return $id_direccion;
    }
    

    //------------------------------------------------------------------------------ FUNCION OBTENER DATOS DEL USUARIO para mostrar en seccion del perfil
    public function obtenerDatos($id_usuario) {

        $sql = "SELECT nombre, apellidos, mail, telefono
            FROM usuarios
            WHERE id_usuario = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en prepare de funcion obtenerDatos: " . $this->conn->error);
        }

        // Solo bindear el parámetro de entrada
        if (!$stmt->bind_param("i", $id_usuario)) {
            throw new Exception("Error en bind_param (obtenerDatos): " . $stmt->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta (obtenerDatos): " . $stmt->error);
        }

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();
        return $usuario;
    }

    
    //------------------------------------------------------------------------------ FUNCION PARA COMPROBAR SI ES ADMINISTRADOR EL USUARIO
    public function esAdmin($usuario) {
        return isset($usuario['nombre']) && $usuario['id_rol'] === 1;
    }
}
?>