<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">Detalle de Consulta Médica #<?= $consulta['id'] ?></h2>
                <p class="text-muted small">Atención veterinaria efectuada el <?= e($consulta['fecha']) ?></p>
            </div>
            <div class="d-flex gap-2">
                <a href="index.php?action=mascotas_historial&id=<?= $consulta['mascota_id'] ?>" class="btn btn-outline-primary">
                    <i class="fa-solid fa-folder-open me-1"></i> Ver Historial del Paciente
                </a>
                <a href="index.php?action=consultas" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-stethoscope me-2"></i>Informe de Acto Clínico</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Datos de Paciente y Dueño -->
                    <div class="col-md-6 border-end">
                        <h6 class="text-primary text-uppercase fw-bold small mb-3">Información del Paciente</h6>
                        <table class="table table-sm table-borderless">
                            <tr><th class="w-40 text-muted">Nombre:</th><td class="fw-bold"><?= e($consulta['mascota_nombre']) ?></td></tr>
                            <tr><th class="text-muted">Especie:</th><td><?= e($consulta['especie']) ?> (<?= e($consulta['raza'] ?? 'Mestizo') ?>)</td></tr>
                            <tr><th class="text-muted">Sexo:</th><td><?= e($consulta['sexo']) ?></td></tr>
                            <tr><th class="text-muted">Propietario:</th><td><?= e($consulta['propietario_nombre']) ?></td></tr>
                            <tr><th class="text-muted">Teléfono:</th><td><?= e($consulta['propietario_telefono']) ?></td></tr>
                        </table>
                    </div>

                    <!-- Datos del Médico y Consulta -->
                    <div class="col-md-6">
                        <h6 class="text-primary text-uppercase fw-bold small mb-3">Datos del Profesional y Estado</h6>
                        <table class="table table-sm table-borderless">
                            <tr><th class="w-40 text-muted">Veterinario:</th><td class="fw-bold"><?= e($consulta['veterinario_nombre']) ?></td></tr>
                            <tr><th class="text-muted">Tarjeta Prof.:</th><td><?= e($consulta['tarjeta_profesional']) ?></td></tr>
                            <tr><th class="text-muted">Fecha / Hora:</th><td><?= e($consulta['fecha']) ?></td></tr>
                            <tr><th class="text-muted">Valor Consulta:</th><td class="fw-bold text-success fs-5"><?= formato_pesos((float)$consulta['costo']) ?></td></tr>
                        </table>
                    </div>

                    <div class="col-12 border-top pt-3">
                        <h6 class="text-primary text-uppercase fw-bold small mb-2">Motivo de Consulta</h6>
                        <div class="p-3 bg-light rounded-3"><?= nl2br(e($consulta['motivo'])) ?></div>
                    </div>

                    <div class="col-12">
                        <h6 class="text-primary text-uppercase fw-bold small mb-2">Diagnóstico y Juicio Clínico</h6>
                        <div class="p-3 bg-light rounded-3"><?= nl2br(e($consulta['diagnostico'] ?? 'Sin diagnóstico definitivo.')) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
