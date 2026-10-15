<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Matrícula - Universidad</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <div class="container">
        <div class="header">
            <h1>📝 Sistema de Matrícula</h1>
            <p>Universidad - Gestión Académica</p>
        </div>

        <form action="MatriculaController.php?action=matricular" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="estudiante">Estudiante</label>
                <select id="estudiante" name="estudiante" required>
                    <option value="">-- Seleccione un estudiante --</option>
                    <?php foreach ($estudiantes as $e): ?>
                        <option value="<?= (int)$e['id_estudiante'] ?>">
                            <?= htmlspecialchars($e['nombre']) ?> (<?= htmlspecialchars($e['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="help-text">Estudiante que se va a matricular</small>
            </div>

            <div class="form-group">
                <label for="curso">Curso</label>
                <select id="curso" name="curso" required>
                    <option value="">-- Seleccione un curso --</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= (int)$c['id_curso'] ?>" <?= $c['cupos'] <= 0 ? 'disabled' : '' ?>>
                            <?= htmlspecialchars($c['nombre']) ?> —
                            <?= $c['cupos'] > 0 ? (int)$c['cupos'] . ' cupos' : 'Agotado' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="help-text">Los cursos sin cupos aparecen deshabilitados</small>
            </div>

            <button type="submit" class="btn btn-primary">✓ Matricular Estudiante</button>
        </form>

        <div class="text-center">
            <a href="ConsultaController.php?action=consultar" class="btn-link">
                👁️ Ver matrículas registradas
            </a>
        </div>
    </div>
</body>
</html>
