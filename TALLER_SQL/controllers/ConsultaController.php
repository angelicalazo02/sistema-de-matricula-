<?php
/**
 * Controlador Consulta - Modo solo lectura.
 * Lo usan el asistente (solo consulta) y el secretario.
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Matricula.php';

class ConsultaController {
    private $matricula;

    public function __construct() {
        // Se conecta con el usuario MySQL del perfil logueado (DCL)
        $perfil = usuarioActual()['rol'];
        $this->matricula = new Matricula(Database::conectar($perfil));
    }

    /** Acción: consultar matrículas y cupos */
    public function consultar() {
        $matriculas = $this->matricula->listarMatriculas();
        $cupos      = $this->matricula->listarCupos();
        require_once __DIR__ . '/../views/consultar.php';
    }
}

// Router básico
requerirRol(['secretario', 'asistente']);

$action = $_GET['action'] ?? 'consultar';
$controller = new ConsultaController();

switch ($action) {
    case 'consultar':
    default:
        $controller->consultar();
}
