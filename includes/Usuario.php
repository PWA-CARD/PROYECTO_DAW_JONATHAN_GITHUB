<?php

class Usuario {
    private $dni;
    private $nombre;
    private $direccion;
    private $localidad;
    private $provincia;
    private $telefono;
    private $email;
    private $password;
    private $rol;

    public function __construt($dni, $nombre, $direccion, $localidad, $provincia,
                            $telefono, $email, $password, $rol = 'registrado'){
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->localidad = $localidad;
        $this->provincia = $provincia;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
    }

    // Getters

    public function getDni() {
        return $this->dni;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getDireccion() {
        return $this->direccion;
    }
    public function getLocalidad() {
        return $this->localidad;

    }
    public function getProvincia() {
        return $this->provincia;
    }
    public function getTelefono() {
        return $this->telefono;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getRol() {
        return $this->rol;
    }

    // Setters

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }
    public function setLocalidad($localidad) {
        $this->localidad = $localidad;
    }
    public function setProvincia($provincia) {
        $this->provincia = $provincia;
    }
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setPassword($password) {
        $this->password = $password;
    }
    public function setRol($rol) {
        $this->rol = $rol;
    }
    
}
?>