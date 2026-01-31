<?php
// Usuario.php

class Usuario
{
    private ?int $id;
    private string $email;
    private string $passwordHash;
    private string $nombre;
    private string $rol;          // admin, entrenador, recepcionista, cliente
    private ?int $idReferencia;   // idCliente, idEmpleado, etc. según el rol
    private ?string $avatar; 

    public function __construct(
        ?int $id,
        string $email,
        string $passwordHash,
        string $nombre,
        string $rol,
        ?int $idReferencia = null,
        ?string $avatar = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->nombre = $nombre;
        $this->rol = $rol;
        $this->idReferencia = $idReferencia;
        $this->avatar = $avatar;
    }

    // Getters
    public function getId(): ?int            { return $this->id; }
    public function getEmail(): string       { return $this->email; }
    public function getPasswordHash(): string{ return $this->passwordHash; }
    public function getNombre(): string      { return $this->nombre; }
    public function getRol(): string         { return $this->rol; }
    public function getIdReferencia(): ?int  { return $this->idReferencia; }
    public function getAvatar(): ?string     { return $this->avatar; }

    // Setters básicos (por si luego los necesitas)
    public function setNombre(string $nombre): void       { $this->nombre = $nombre; }
    public function setRol(string $rol): void             { $this->rol = $rol; }
    public function setIdReferencia(?int $idRef): void    { $this->idReferencia = $idRef; }
    public function setAvatar(?string $avatar): void      { $this->avatar = $avatar; }
}
