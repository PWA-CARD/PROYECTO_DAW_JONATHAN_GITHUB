<?php
require_once 'Usuario.php';

class GestorUsuarios {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Buscar usuario por DNI

    public function buscarPorDni($dni) {
        $sql = "SELECT * FROM usuarios WHERE dni = :dni";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':dni' => $dni]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return new Usuario(
            $row['dni'],
            $row['nombre'],
            $row['direccion'],
            $row['localidad'],
            $row['provincia'],
            $row['telefono'],
            $row['email'],
            $row['password'],  
            $row['rol']
            );
        }
        return null;
       
    }
    public function obtenerFilaPorDni($dni) {
    $sql = "SELECT * FROM usuarios WHERE dni = :dni";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':dni' => $dni]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // devuelve array o false
}


    // comprobacion si existe DNI

    public function dniExiste($dni) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE dni = :dni";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':dni' => $dni]);
        return $stmt->fetchColumn() > 0;
    }

    // Registros de usuarios

    public function registrarUsuario($datos) {
        $sql = "INSERT INTO usuarios (dni, nombre, direccion, localidad, provincia, telefono, email, password, rol)
                VALUES (:dni, :nombre, :direccion, :localidad, :provincia, :telefono, :email, :password, :rol)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($datos);
    }

    // Actualización de datos de usuarios sin cambio DNI

    public function actualizarUsuarios($datos) {
        $sql = "UPDATE usuarios SET nombre = :nombre, direccion = :direccion, localidad = :localidad, provincia = :provincia,
                                    telefono = :telefono, email = :email, password = :password, rol = :rol
                                    WHERE dni = :dni";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($datos);
    }

    // Actualización de contraseña

    public function actualizarContraseñaUsuario($dni, $hash) {
        $sql = "UPDATE usuarios SET password = :pwd WHERE dni = :dni";
        $stmt = $this->db->prepare($sql);
        return $stmt->exexute([':pwd' =>$hash, ':dni' => $dni]);
    }

    // Quitar usuario de base

    public function quitarUsuario($dni) {
        $sql = "DELETE FROM usuarios WHERE dni = :dni";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':dni' => $dni]);
    }

    // Listado con paginación, ordenación y búsqueda por nombre y otros campos

    public function listarPaginacion($pagina, $porPagina, $busqueda = '', $orden = 'nombre') {
        $offset = ($pagina - 1) * $porPagina;
    
        $ordenPorColumnas = ['dni', 'nombre', 'localidad', 'provincia'];
        if (!in_array($orden, $ordenPorColumnas)) {
            $orden = 'nombre';
        }

        $parametros = [];
        $donde = '';

        if ($busqueda !== '') {
            $donde = "WHERE nombre LIKE :buscar OR dni LIKE :buscar";
            $parametros[':buscar'] = "%$busqueda%";
        }

        // paginación 
        $sqlTotal = "SELECT COUNT(*) FROM usuarios $donde";
        $stmt = $this->db->prepare($sqlTotal);
        $stmt->execute($parametros);
        $total = $stmt->fetchColumn();

        // datos

        $sql = "SELECT * FROM usuarios $donde ORDER BY $orden LIMIT :offset, :limite";
        $stmt = $this->db->prepare($sql);
        foreach ($parametros as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limite', (int)$porPagina, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
        $usuarios = $stmt->fetchAll();

        return[$usuarios, $total];
    }    

    
}
