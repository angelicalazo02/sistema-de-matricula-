<?php
/**
 * Modelo Usuario - Consulta de credenciales para el login
 */
class Usuario {
    private $conn;
    private $table = "usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    /** Busca un usuario por nombre de usuario */
    public function buscarPorUsername($username) {
        $query = "SELECT id_usuario, username, password_hash, nombre, rol
                  FROM {$this->table} WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
}
