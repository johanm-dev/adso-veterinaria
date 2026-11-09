<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">Expediente Clínico: <?= e($mascota['nombre']) ?></h2>
        <p class="text-muted small">Ficha médica digital, carnet de vacunación y consultas acumuladas</p>
    </div>
    <div class="d-flex gap-2">
        <a href="index.php?action=consultas_create&mascota_id=<?= $mascota['id'] ?>" class="btn btn-primary">
            <i class="bi bi-clipboard2-pulse me-1"></i> Nueva Consulta
        </a>
        <a href="index.php?action=vacunas_create&mascota_id=<?= $mascota['id'] ?>" class="btn btn-success">
            <i class="bi bi-eyedropper me-1"></i> Aplicar Vacuna
        </a>
        <a href="index.php?action=mascotas_edit&id=<?= $mascota['id'] ?>" class="btn btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i> Editar Ficha
        </a>
        <a href="index.php?action=mascotas" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>
</div>

<!-- Tarjeta de Información General -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-3 border-end">
                <span class="text-muted small text-uppercase">Paciente</span>
                <h4 class="fw-bold text-primary mb-1"><?= e($mascota['nombre']) ?></h4>
                <div><span class="badge bg-primary"><?= e($mascota['especie']) ?></span> • <?= e($mascota['raza'] ?? 'Mestizo') ?></div>
                <div class="small text-muted mt-2">Sexo: <strong><?= e($mascota['sexo']) ?></strong></div>
                <div class="small text-muted">Edad: <strong><?= calcular_edad($mascota['fecha_nacimiento']) ?></strong></div>
                <div class="small text-muted">Peso actual: <strong><?= $mascota['peso_kg'] ? e($mascota['peso_kg']) . ' kg' : 'No registrado' ?></strong></div>
            </div>

            <div class="col-md-5 border-end">
                <span class="text-muted small text-uppercase">Propietario Responsable</span>
                <h5 class="fw-bold text-dark mb-1"><?= e($mascota['propietario_nombre']) ?></h5>
                <div class="small text-muted mb-1"><i class="bi bi-person-vcard me-1"></i> Doc: <?= e($mascota['propietario_doc']) ?></div>
                <div class="small text-muted mb-1">
                    <i class="bi bi-whatsapp me-1 text-success"></i> Tel:
                    <a href="https://api.whatsapp.com/send?phone=57<?= preg_replace('/\D/', '', $mascota['propietario_telefono']) ?>" target="_blank" class="text-success text-decoration-none">
                        <?= e($mascota['propietario_telefono']) ?>
                    </a>
                </div>
                <div class="small text-muted mb-1"><i class="bi bi-envelope me-1"></i> Correo: <?= e($mascota['propietario_email'] ?? 'No registrado') ?></div>
                <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i> Dir: <?= e($mascota['propietario_dir'] ?? 'No registrada') ?></div>
            </div>

            <div class="col-md-4 d-flex flex-column justify-content-center">
                <div class="p-3 bg-light rounded-3 text-center">
                    <span class="text-muted small d-block mb-1">Total Atenciones</span>
                    <div class="d-flex justify-content-around">
                        <div>
                            <span class="fs-4 fw-bold text-primary"><?= count($consultas) ?></span>
                            <small class="d-block text-muted">Consultas</small>
                        </div>
                        <div class="border-start"></div>
                        <div>
                            <span class="fs-4 fw-bold text-success"><?= count($vacunas) ?></span>
                            <small class="d-block text-muted">Vacunas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pestañas de Expediente -->
<ul class="nav nav-tabs mb-3" id="expedienteTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-semibold" id="consultas-tab" data-bs-toggle="tab" data-bs-target="#consultas-pane" type="button" role="tab">
            <i class="bi bi-journal-medical me-1"></i> Historial de Consultas (<?= count($consultas) ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold" id="vacunas-tab" data-bs-toggle="tab" data-bs-target="#vacunas-pane" type="button" role="tab">
            <i class="bi bi-shield-check me-1"></i> Carnet de Vacunación (<?= count($vacunas) ?>)
        </button>
    </li>
</ul>

<div class="tab-content" id="expedienteTabContent">
    <!-- Pestaña 1: Consultas -->
    <div class="tab-pane fade show active" id="consultas-pane" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Veterinario</th>
                                <th>Motivo Consulta</th>
                                <th>Diagnóstico</th>
                                <th>Costo</th>
                                <th>Próximo Control</th>
                                <th class="text-end">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($consultas)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">No hay consultas registradas para este paciente.</td></tr>
                            <?php else: ?>
                                <?php foreach ($consultas as $c): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?= e($c['fecha']) ?></div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= e($c['veterinario_nombre']) ?></div>
                                            <small class="text-muted">TP: <?= e($c['tarjeta_profesional']) ?></small>
                                        </td>
                                        <td><?= e($c['motivo']) ?></td>
                                        <td><?= e($c['diagnostico'] ?? 'Sin diagnóstico cerrado') ?></td>
                                        <td><?= formato_pesos((float)$c['costo']) ?></td>
                                        <td><?= $c['proximo_control'] ? e($c['proximo_control']) : '—' ?></td>
                                        <td class="text-end">
                                            <a href="index.php?action=consultas_show&id=<?= $c['id'] ?>" class="btn btn-sm btn-light border">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestaña 2: Carnet de Vacunación -->
    <div class="tab-pane fade" id="vacunas-pane" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Vacuna</th>
                                <th>Intervalo Días</th>
                                <th>Fecha Aplicación</th>
                                <th>Próxima Dosis (RN-01)</th>
                                <th>Lote</th>
                                <th>Profesional</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($vacunas)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">No se han administrado biológicos al paciente aún.</td></tr>
                            <?php else: ?>
                                <?php foreach ($vacunas as $v): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?= e($v['vacuna_nombre']) ?></td>
                                        <td><span class="badge bg-info-subtle text-info border"><?= e($v['intervalo_dias']) ?> días</span></td>
                                        <td><?= e($v['fecha_aplicacion']) ?></td>
                                        <td>
                                            <span class="fw-semibold text-primary"><?= e($v['proxima_dosis']) ?></span>
                                        </td>
                                        <td><code><?= e($v['lote'] ?? 'N/A') ?></code></td>
                                        <td><?= e($v['veterinario_nombre']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
