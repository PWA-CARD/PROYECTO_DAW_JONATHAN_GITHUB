<?php
// GestorUsuarios.php
require_once 'conexion.php';
require_once 'Usuario.php';

class GestorUsuarios
{
    private PDO $con;

    public function __construct(PDO $con)
    {
        $this->con = $con;
    }

    /**
     * Registra un nuevo usuario.
     */
    public function registrarUsuario(array $datos): ?Usuario
    {
        $hash = password_hash($datos['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (email, password_hash, nombre, rol, idReferencia, avatar)
                VALUES (:email, :hash, :nombre, :rol, :idReferencia, :avatar)";
        $stmt = $this->con->prepare($sql);

        $stmt->execute([
            ':email'        => $datos['email'],
            ':hash'         => $hash,
            ':nombre'       => $datos['nombre'],
            ':rol'          => $datos['rol'],
            ':idReferencia' => $datos['idReferencia'] ?? null,
            ':avatar'       => $datos['avatar'] ?? null,
        ]);

        $id = (int)$this->con->lastInsertId();

        return new Usuario(
            $id,
            $datos['email'],
            $hash,
            $datos['nombre'],
            $datos['rol'],
            $datos['idReferencia'] ?? null,
            $datos['avatar'] ?? null
        );
    }

    /**
     * Devuelve un Usuario por email o null si no existe.
     */
    public function obtenerPorEmail(string $email): ?Usuario
    {
        $sql = "SELECT * FROM usuario WHERE email = :email";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([':email' => $email]);
        $fila = $stmt->fetch();

        if (!$fila) {
            return null;
        }

        return new Usuario(
            (int)$fila['id'],
            $fila['email'],
            $fila['password_hash'],
            $fila['nombre'],
            $fila['rol'],
            $fila['idReferencia'],
            $fila['avatar'] ?? null
        );
    }

    /**
     * Valida email + password y devuelve Usuario o null.
     */
    public function validarLogin(string $email, string $password): ?Usuario
    {
        $usuario = $this->obtenerPorEmail($email);

        if (!$usuario) {
            return null;
        }

        if (!password_verify($password, $usuario->getPasswordHash())) {
            return null;
        }

        return $usuario;
    }

    /**
     * Devuelve un array de objetos Usuario con todos los usuarios.
     */
    public function obtenerTodos(): array
    {
        $sql = "SELECT * FROM usuario ORDER BY rol, nombre";
        $stmt = $this->con->query($sql);

        $usuarios = [];

        while ($fila = $stmt->fetch()) {
            $usuarios[] = new Usuario(
                (int)$fila['id'],
                $fila['email'],
                $fila['password_hash'],
                $fila['nombre'],
                $fila['rol'],
                $fila['idReferencia'],
                $fila['avatar'] ?? null
            );
        }

        return $usuarios;
    }

    /**
     * Actualiza datos básicos del usuario (no contraseña).
     */
    public function actualizarUsuario(array $datos): bool
    {
        $sql = "UPDATE usuario
                SET nombre = :nombre,
                    email  = :email,
                    rol    = :rol
                WHERE id = :id";

        $stmt = $this->con->prepare($sql);

        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':email'  => $datos['email'],
            ':rol'    => $datos['rol'],
            ':id'     => $datos['id']
        ]);
    }

    /**
     * Actualiza la contraseña.
     */
    public function actualizarPassword(int $id, string $nuevoPassword): bool
    {
        $hash = password_hash($nuevoPassword, PASSWORD_DEFAULT);

        $sql = "UPDATE usuario
                SET password_hash = :hash
                WHERE id = :id";

        $stmt = $this->con->prepare($sql);

        return $stmt->execute([
            ':hash' => $hash,
            ':id'   => $id
        ]);
    }

    /**
     * Actualiza el avatar.
     */
    public function actualizarAvatar(int $id, ?string $rutaAvatar): bool
    {
        $sql = "UPDATE usuario
                SET avatar = :avatar
                WHERE id = :id";

        $stmt = $this->con->prepare($sql);

        return $stmt->execute([
            ':avatar' => $rutaAvatar,
            ':id'     => $id
        ]);
    }
}
