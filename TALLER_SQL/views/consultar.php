<?php
// Indicadores calculados con los datos que entrega el controlador
$totalMatriculas = count($matriculas);
$cursosConCupo   = count(array_filter($cupos, fn($c) => $c['cupos'] > 0));
$cuposTotales    = (int) array_sum(array_column($cupos, 'cupos'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Matrículas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="page">
        <header class="page-header">
            <h1>Consulta de matrículas</h1>
            <p>Modo consulta · solo lectura</p>
        </header>

        <section class="kpi-grid">
            <article class="kpi is-accent">
                <div class="kpi-value"><?= $totalMatriculas ?></div>
                <div class="kpi-label">Matrículas registradas</div>
            </article>
            <article class="kpi">
                <div class="kpi-value"><?= $cursosConCupo ?> de <?= count($cupos) ?></div>
                <div class="kpi-label">Cursos con cupo disponible</div>
            </article>
            <article class="kpi">
                <div class="kpi-value"><?= $cuposTotales ?></div>
                <div class="kpi-label">Cupos libres en total</div>
            </article>
        </section>

        <section class="info-card">
            <h2>Matrículas registradas</h2>

            <div class="toolbar">
                <label for="buscador" class="sr-only">Buscar</label>
                <input type="search" id="buscador" placeholder="Buscar por estudiante o curso…" autocomplete="off">
            </div>

            <div class="table-container">
                <table id="tabla-matriculas">
                    <thead>
                        <tr><th>ID</th><th>Estudiante</th><th>Curso</th><th>Fecha</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($matriculas)): ?>
                            <tr><td colspan="4" class="empty-state">No hay matrículas registradas todavía.</td></tr>
                        <?php else: ?>
                            <?php foreach ($matriculas as $fila): ?>
                            <tr data-filtro="<?= htmlspecialchars(mb_strtolower($fila['estudiante'] . ' ' . $fila['curso'], 'UTF-8')) ?>">
                                <td><?= (int) $fila['id_matricula'] ?></td>
                                <td><?= htmlspecialchars($fila['estudiante']) ?></td>
                                <td><?= htmlspecialchars($fila['curso']) ?></td>
                                <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="info-card">
            <h2>Cupos disponibles</h2>
            <ul class="cupos-list">
                <?php foreach ($cupos as $fila):
                    $clase = $fila['cupos'] == 0 ? 'agotado' : ($fila['cupos'] <= 2 ? 'ultimo' : 'disponible');
                    $texto = $fila['cupos'] == 0 ? 'Agotado' : ($fila['cupos'] <= 2 ? 'Últimos cupos' : 'Disponible');
                ?>
                <li>
                    <strong><?= htmlspecialchars($fila['nombre']) ?></strong>
                    <span class="cupo-badge <?= $clase ?>"><?= (int) $fila['cupos'] ?> cupos · <?= $texto ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <div class="text-center mt-16">
            <a href="MatriculaController.php?action=formulario" class="btn btn-secondary">← Volver</a>
        </div>
    </main>

    <script>
        // Filtro en vivo de la tabla, sin recargar la página
        document.getElementById('buscador').addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            document.querySelectorAll('#tabla-matriculas tbody tr[data-filtro]').forEach(function (tr) {
                tr.style.display = tr.dataset.filtro.includes(q) ? '' : 'none';
            });
        });
    </script>
</body>
</html>