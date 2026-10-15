<?php
/**
 * Controlador Matricula - Solo perfil SECRETARIO (escritura)
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Matricula.php';
require_once __DIR__ . '/../models/Estudiante.php';
require_once __DIR__ . '/../models/Curso.php';

class MatriculaController {
    private $matricula;
    private $estudiante;
    private $curso;

    public function __construct() {
        $db = Database::conectar("secretario");   // permisos de escritura (DCL)
        $this->matricula  = new Matricula($db);
        $this->estudiante = new Estudiante($db);
        $this->curso      = new Curso($db);
    }

    /** Acción: mostrar formulario */
    public function formulario() {
        $estudiantes = $this->estudiante->listar();
        $cursos      = $this->curso->listar();
        require_once __DIR__ . '/../views/formulario.php';
    }

    /** Acción: procesar matrícula (TCL dentro del modelo) */
    public function matricular() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: MatriculaController.php?action=formulario");
            exit();
        }
        if (!csrfValido($_POST['csrf_token'] ?? null)) {
            $resultado = ["success" => false, "message" => "Token de seguridad inválido. Recargue el formulario."];
            require_once __DIR__ . '/../views/resultado.php';
            return;
        }

        $idEstudiante = filter_input(INPUT_POST, 'estudiante', FILTER_VALIDATE_INT);
        $idCurso      = filter_input(INPUT_POST, 'curso', FILTER_VALIDATE_INT);

        if (!$idEstudiante || !$idCurso) {
            $resultado = ["success" => false, "message" => "Datos inválidos."];
            require_once __DIR__ . '/../views/resultado.php';
            return;
        }

        $resultado = $this->matricula->matricular($idEstudiante, $idCurso);
        require_once __DIR__ . '/../views/resultado.php';
    }
}

// Router básico (solo secretario)
requerirRol('secretario');

$action = $_GET['action'] ?? 'formulario';
$controller = new MatriculaController();

switch ($action) {
    case 'matricular':
        $controller->matricular();
        break;
    default:
        $controller->formulario();
}
