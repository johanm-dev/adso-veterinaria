<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">Generación y Descarga de Reportes</h2>
        <p class="text-muted small">Exportación de datos del negocio en formato plano CSV con codificación UTF-8 BOM para Microsoft Excel</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <form method="GET" action="index.php" class="row g-3 align-items-end">
            <input type="hidden" name="action" value="reportes">

            <div class="col-md-3">
                <label for="tipo" class="form-label fw-semibold">Tipo de Reporte</label>
                <select class="form-select" id="tipo" name="tipo">
                    <option value="consultas" <?= $tipo === 'consultas' ? 'selected' : '' ?>>Consultas Médicas</option>
                    <option value="vacunas" <?= $tipo === 'vacunas' ? 'selected' : '' ?>>Vacunas Aplicadas</option>
                    <option value="mascotas" <?= $tipo === 'mascotas' ? 'selected' : '' ?>>Censo de Mascotas</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="fecha_inicio" class="form-label fw-semibold">Fecha Inicial</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= e($fechaInicio) ?>">
            </div>

            <div class="col-md-3">
                <label for="fecha_fin" class="form-label fw-semibold">Fecha Final</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= e($fechaFin) ?>">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter me-1"></i> Filtrar
                </button>
                <a href="index.php?action=reportes_exportar&tipo=<?= e($tipo) ?>&fecha_inicio=<?= e($fechaInicio) ?>&fecha_fin=<?= e($fechaFin) ?>" class="btn btn-success text-nowrap">
                    <i class="bi bi-file-earmark-arrow-down me-1"></i> Descargar CSV
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Vista Previa de Registros (<?= count($datos) ?> encontrados)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-light">
                    <?php if (!empty($datos)): ?>
                        <tr>
                            <?php foreach (array_keys($datos[0]) as $col): ?>
                                <th class="text-capitalize"><?= str_replace('_', ' ', e($col)) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>
                </thead>
                <tbody>
                    <?php if (empty($datos)): ?>
                        <tr><td class="text-center text-muted py-4">No hay datos en el rango seleccionado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($datos as $fila): ?>
                            <tr>
                                <?php foreach ($fila as $valor): ?>
                                    <td><?= e((string)$valor) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
