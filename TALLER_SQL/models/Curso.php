<?php
/**
 * Modelo Curso - Representa la entidad Curso
 */
class Curso {
    private $conn;
    private $table = "cursos";

    public $id_curso;
    public $nombre;
    public $cupos;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtener un curso por ID
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM {$this->table} WHERE id_curso = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Listar todos los cursos con sus cupos
     */
    public function listar() {
        $query = "SELECT * FROM {$this->table} ORDER BY nombre";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }

    /**
     * Verificar si hay cupos disponibles
     */
    public function tieneCupos($id) {
        $curso = $this->obtenerPorId($id);
        return $curso && $curso['cupos'] > 0;
    }

    /**
     * Descontar un cupo (DML - UPDATE)
     */
    public function descontarCupo($id) {
        $query = "UPDATE {$this->table} SET cupos = cupos - 1 WHERE id_curso = ? AND cupos > 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
?>