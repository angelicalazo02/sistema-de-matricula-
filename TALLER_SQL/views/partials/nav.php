<?php
/** Barra superior: marca, perfil y enlaces según el rol */
$u = usuarioActual();
?>
<header class="topbar">
    <div class="brand">
        <strong>Sistema de Matrícula</strong>
        <small>Universidad del Valle</small>
    </div>
    <div class="topbar-right">
        <span class="perfil"><?= htmlspecialchars(ucfirst($u['rol'])) ?></span>
        <nav class="topbar-links">
            <?php if ($u['rol'] === 'secretario'): ?>
                <a href="MatriculaController.php?action=formulario">Matricular</a>
            <?php endif; ?>
            <a href="ConsultaController.php?action=consultar">Consultar</a>
            <a href="AuthController.php?action=logout" class="logout">Cerrar sesión</a>
        </nav>
    </div>
</header>