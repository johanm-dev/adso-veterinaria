<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">Centro de Alertas y Notificaciones</h2>
        <p class="text-muted small">Monitor preventivo de vacunas por vencer y controles de seguimiento con enlace directo a WhatsApp</p>
    </div>
</div>

<!-- Alertas de Vacunación -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="fw-bold mb-0 text-danger"><i class="fa-solid fa-bell me-2"></i>Alertas de Vacunas (Por Vencer o Vencidas)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mascota</th>
                        <th>Vacuna</th>
                        <th>Propietario</th>
                        <th>Teléfono</th>
                        <th>Fecha Límite</th>
                        <th>Días Restantes</th>
                        <th>Estado</th>
                        <th class="text-center">Notificar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($alertasVacunas)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">No hay vacunas en condición crítica o por vencer.</td></tr>
                    <?php else: ?>
                        <?php foreach ($alertasVacunas as $av): ?>
                            <tr>
                                <td class="fw-bold"><?= e($av['mascota']) ?></td>
                                <td><?= e($av['vacuna']) ?></td>
                                <td><?= e($av['propietario']) ?></td>
                                <td><?= e($av['telefono']) ?></td>
                                <td class="fw-semibold"><?= e($av['proxima_dosis']) ?></td>
                                <td>
                                    <?php if ($av['dias_restantes'] < 0): ?>
                                        <span class="text-danger fw-bold">Hace <?= abs($av['dias_restantes']) ?> días</span>
                                    <?php elseif ($av['dias_restantes'] == 0): ?>
                                        <span class="text-warning fw-bold">¡Vence Hoy!</span>
                                    <?php else: ?>
                                        <span class="text-warning-emphasis fw-bold">En <?= $av['dias_restantes'] ?> días</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $av['estado'] === 'Vencida' ? 'danger' : 'warning text-dark' ?>">
                                        <?= e($av['estado']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= e($av['whatsapp_url']) ?>" target="_blank" class="btn btn-success btn-sm px-3 shadow-sm">
                                        <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
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

<!-- Alertas de Controles Médicos -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-calendar-check me-2"></i>Controles Médicos Programados</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mascota</th>
                        <th>Propietario</th>
                        <th>Teléfono</th>
                        <th>Fecha Control</th>
                        <th>Días Restantes</th>
                        <th>Estado</th>
                        <th class="text-center">Notificar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($alertasControles)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No hay controles médicos pendientes para los próximos días.</td></tr>
                    <?php else: ?>
                        <?php foreach ($alertasControles as $ac): ?>
                            <tr>
                                <td class="fw-bold"><?= e($ac['mascota']) ?></td>
                                <td><?= e($ac['propietario']) ?></td>
                                <td><?= e($ac['telefono']) ?></td>
                                <td class="fw-semibold"><?= e($ac['proximo_control']) ?></td>
                                <td>
                                    <?php if ($ac['dias_restantes'] < 0): ?>
                                        <span class="text-danger fw-bold">Vencido hace <?= abs($ac['dias_restantes']) ?> d</span>
                                    <?php elseif ($ac['dias_restantes'] == 0): ?>
                                        <span class="text-primary fw-bold">Hoy</span>
                                    <?php else: ?>
                                        <span>En <?= $ac['dias_restantes'] ?> días</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-info text-dark"><?= e($ac['estado']) ?></span></td>
                                <td class="text-center">
                                    <a href="<?= e($ac['whatsapp_url']) ?>" target="_blank" class="btn btn-success btn-sm px-3 shadow-sm">
                                        <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
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
