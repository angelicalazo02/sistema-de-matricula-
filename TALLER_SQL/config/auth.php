<?php
/**
 * Sesiones, perfiles (roles) y protección CSRF.
 * Se incluye al inicio de cada controlador.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Devuelve los datos del usuario logueado o null */
function usuarioActual() {
    return $_SESSION['usuario'] ?? null;
}

/**
 * Exige sesión y un perfil permitido.
 * $roles: 'secretario' o ['secretario', 'asistente']
 */
function requerirRol($roles) {
    $usuario = usuarioActual();
    if ($usuario === null) {
        header("Location: AuthController.php?action=login");
        exit();
    }
    if (!in_array($usuario['rol'], (array) $roles, true)) {
        http_response_code(403);
        die("Acceso denegado: su perfil no tiene permiso para esta sección. "
          . "<a href='AuthController.php?action=logout'>Cerrar sesión</a>");
    }
}

/** Token anti-CSRF: se incrusta en cada formulario POST */
function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfValido($token) {
    return isset($_SESSION['csrf_token'])
        && is_string($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}
