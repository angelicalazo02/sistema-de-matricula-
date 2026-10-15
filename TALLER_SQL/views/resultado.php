<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de Matrícula</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <div class="container">
        <div class="header">
            <h1>📋 Resultado de Matrícula</h1>
            <p>Procesamiento completado</p>
        </div>

        <?php if ($resultado['success']): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✅</span>
                <div>
                    <strong>¡Matrícula Exitosa!</strong>
                    <p class="alert-message"><?= htmlspecialchars($resultado['message']) ?></p>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-error">
                <span class="alert-icon">❌</span>
                <div>
                    <strong>Error en la Matrícula</strong>
                    <p class="alert-message"><?= htmlspecialchars($resultado['message']) ?></p>
                    <p class="alert-detail">🔄 La transacción fue revertida automáticamente (TCL)</p>
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center">
            <a href="MatriculaController.php?action=formulario" class="btn btn-secondary">
                ← Realizar Nueva Matrícula
            </a>
        </div>
    </div>
</body>
</html>
