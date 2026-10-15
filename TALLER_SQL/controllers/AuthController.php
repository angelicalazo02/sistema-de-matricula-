<?php
/**
 * Controlador Auth - Login y logout por perfil (secretario / asistente)
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuario;

    public function __construct() {
        // Solo el usuario 'auth' de MySQL puede leer la tabla usuarios
        $this->usuario = new Usuario(Database::conectar("auth"));
    }

    /** Acción: mostrar formulario de login */
    public function login() {
        if ($actual = usuarioActual()) {
            $this->redirigirPorRol($actual['rol']);
            return;
        }
        $error = null;
        require_once __DIR__ . '/../views/login.php';
    }

    /** Acción: validar credenciales */
    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrfValido($_POST['csrf_token'] ?? null)) {
            $error = "La sesión del formulario expiró. Intente de nuevo.";
            require_once __DIR__ . '/../views/login.php';
            return;
        }

        $username = trim($_POST['usuario'] ?? '');
        $password = $_POST['clave'] ?? '';
        $registro = $username !== '' ? $this->usuario->buscarPorUsername($username) : false;

        // Mismo mensaje para usuario inexistente o clave incorrecta (no revela cuál falló)
        if (!$registro || !password_verify($password, $registro['password_hash'])) {
            $error = "Usuario o contraseña incorrectos.";
            require_once __DIR__ . '/../views/login.php';
            return;
        }

        session_regenerate_id(true); // evita fijación de sesión
        $_SESSION['usuario'] = [
            'id'       => $registro['id_usuario'],
            'username' => $registro['username'],
            'nombre'   => $registro['nombre'],
            'rol'      => $registro['rol'],
        ];
        $this->redirigirPorRol($registro['rol']);
    }

    /** Acción: cerrar sesión */
    public function logout() {
        $_SESSION = [];
        session_destroy();
        header("Location: AuthController.php?action=login");
        exit();
    }

    private function redirigirPorRol($rol) {
        if ($rol === 'secretario') {
            header("Location: MatriculaController.php?action=formulario");
        } else {
            header("Location: ConsultaController.php?action=consultar");
        }
        exit();
    }
}

// Router básico
$action = $_GET['action'] ?? 'login';
$controller = new AuthController();

switch ($action) {
    case 'autenticar':
        $controller->autenticar();
        break;
    case 'logout':
        $controller->logout();
        break;
    default:
        $controller->login();
}
