<?php
/**
 * Modelo Estudiante - Representa la entidad Estudiante
 */
class Estudiante {
    private $conn;
    private $table = "estudiantes";

    public $id_estudiante;
    public $nombre;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtener un estudiante por ID
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM {$this->table} WHERE id_estudiante = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Listar todos los estudiantes
     */
    public function listar() {
        $query = "SELECT * FROM {$this->table} ORDER BY nombre";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }
}
?>