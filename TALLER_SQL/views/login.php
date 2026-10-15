<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Sistema de Matrícula</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Sistema de Matrícula</h1>
            <p>Universidad del Valle - Inicio de sesión</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <span class="alert-icon">❌</span>
                <div>
                    <strong>No se pudo iniciar sesión</strong>
                    <p class="alert-message"><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <form action="AuthController.php?action=autenticar" method="POST" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required placeholder="Ej: secretario">
            </div>

            <div class="form-group">
                <label for="clave">Contraseña</label>
                <input type="password" id="clave" name="clave" required placeholder="••••••">
            </div>

            <button type="submit" class="btn btn-primary">🔐 Ingresar</button>
        </form>

        <p class="help-text text-center">
            Perfiles: <strong>secretario</strong> (matricula y consulta) ·
            <strong>asistente</strong> (solo consulta)
        </p>
    </div>
</body>
</html>
