<?php
/**
 * Modelo Matricula - Maneja la lógica de matrícula con TCL (transacciones)
 */
class Matricula {
    private $conn;
    private $table = "matriculas";

    public $id_matricula;
    public $id_estudiante;
    public $id_curso;
    public $fecha;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Matricular un estudiante en un curso (DML + TCL)
     * Garantiza atomicidad: o se hace todo o no se hace nada
     */
    public function matricular($idEstudiante, $idCurso) {
        try {
            // 🔹 INICIO DE TRANSACCIÓN (TCL)
            $this->conn->beginTransaction();

            // Paso 1: Verificar que el estudiante existe
            $stmt = $this->conn->prepare("SELECT id_estudiante FROM estudiantes WHERE id_estudiante = ?");
            $stmt->execute([$idEstudiante]);
            if (!$stmt->fetch()) {
                throw new Exception("El estudiante con ID $idEstudiante no existe.");
            }

            // Paso 2: Verificar que el curso existe y tiene cupos
            // FOR UPDATE bloquea la fila: dos matrículas simultáneas no pueden tomar el mismo último cupo
            $stmt = $this->conn->prepare("SELECT cupos FROM cursos WHERE id_curso = ? FOR UPDATE");
            $stmt->execute([$idCurso]);
            $curso = $stmt->fetch();
            
            if (!$curso) {
                throw new Exception("El curso con ID $idCurso no existe.");
            }
            if ($curso['cupos'] <= 0) {
                throw new Exception("No hay cupos disponibles en el curso.");
            }

            // Paso 3: Verificar que no esté ya matriculado
            $stmt = $this->conn->prepare("SELECT id_matricula FROM matriculas WHERE id_estudiante = ? AND id_curso = ?");
            $stmt->execute([$idEstudiante, $idCurso]);
            if ($stmt->fetch()) {
                throw new Exception("El estudiante ya está matriculado en este curso.");
            }

            // Paso 4: Insertar la matrícula (DML)
            $fecha = date('Y-m-d');
            $stmt = $this->conn->prepare("INSERT INTO matriculas (id_estudiante, id_curso, fecha) VALUES (?, ?, ?)");
            $stmt->execute([$idEstudiante, $idCurso, $fecha]);

            // Paso 5: Descontar un cupo (DML)
            $stmt = $this->conn->prepare("UPDATE cursos SET cupos = cupos - 1 WHERE id_curso = ? AND cupos > 0");
            $stmt->execute([$idCurso]);
            if ($stmt->rowCount() === 0) {
                throw new Exception("No hay cupos disponibles en el curso.");
            }

            // 🔹 CONFIRMAR TRANSACCIÓN (TCL - COMMIT)
            $this->conn->commit();
            return ["success" => true, "message" => "Matrícula exitosa. Estudiante $idEstudiante matriculado en el curso $idCurso."];

        } catch (Exception $e) {
            // 🔹 REVERTIR TRANSACCIÓN (TCL - ROLLBACK)
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Listar todas las matrículas con información relacionada
     */
    public function listarMatriculas() {
        $query = "SELECT 
                    m.id_matricula, 
                    e.nombre AS estudiante, 
                    c.nombre AS curso, 
                    m.fecha 
                  FROM matriculas m 
                  JOIN estudiantes e ON m.id_estudiante = e.id_estudiante 
                  JOIN cursos c ON m.id_curso = c.id_curso
                  ORDER BY m.fecha DESC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }

    /**
     * Listar cupos disponibles por curso
     */
    public function listarCupos() {
        $query = "SELECT nombre, cupos FROM cursos ORDER BY nombre";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }
}
?>