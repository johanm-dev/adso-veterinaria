<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">Plan y Registro de Vacunación</h2>
        <p class="text-muted small">Administración de biológicos y calendarización automática de refuerzos (Regla RN-01)</p>
    </div>
    <a href="index.php?action=vacunas_create" class="btn btn-success shadow-sm">
        <i class="bi bi-eyedropper me-1"></i> Aplicar Vacuna
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha Aplicación</th>
                        <th>Paciente</th>
                        <th>Vacuna</th>
                        <th>Lote</th>
                        <th>Próxima Dosis (Calculada RN-01)</th>
                        <th>Propietario</th>
                        <th>Profesional</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($aplicaciones)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No se han registrado aplicaciones de vacunas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($aplicaciones as $ap): ?>
                            <tr>
                                <td class="fw-semibold"><?= e($ap['fecha_aplicacion']) ?></td>
                                <td>
                                    <div class="fw-bold"><?= e($ap['mascota_nombre']) ?></div>
                                    <small class="text-muted"><?= e($ap['especie']) ?></small>
                                </td>
                                <td><span class="badge bg-primary-subtle text-primary border"><?= e($ap['vacuna_nombre']) ?></span></td>
                                <td><code><?= e($ap['lote'] ?? 'S/L') ?></code></td>
                                <td>
                                    <span class="fw-bold text-success"><?= e($ap['proxima_dosis']) ?></span>
                                </td>
                                <td><?= e($ap['propietario_nombre']) ?></td>
                                <td><?= e($ap['veterinario_nombre']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
