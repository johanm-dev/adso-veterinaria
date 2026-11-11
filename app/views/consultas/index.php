<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">Gestión de Consultas Médicas</h2>
        <p class="text-muted small">Registro clínico de valoraciones, diagnósticos y actos veterinarios</p>
    </div>
    <a href="index.php?action=consultas_create" class="btn btn-primary shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Nueva Consulta
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Paciente</th>
                        <th>Propietario</th>
                        <th>Médico Veterinario</th>
                        <th>Motivo</th>
                        <th>Costo</th>
                        <th>Próximo Control</th>
                        <th class="text-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($consultas)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">No hay consultas médicas registradas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($consultas as $c): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= e($c['fecha']) ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold"><?= e($c['mascota_nombre']) ?></div>
                                    <small class="text-muted"><?= e($c['especie']) ?></small>
                                </td>
                                <td><?= e($c['propietario_nombre']) ?></td>
                                <td><?= e($c['veterinario_nombre']) ?></td>
                                <td><?= e($c['motivo']) ?></td>
                                <td><?= formato_pesos((float)$c['costo']) ?></td>
                                <td><?= $c['proximo_control'] ? e($c['proximo_control']) : '—' ?></td>
                                <td class="text-end">
                                    <a href="index.php?action=consultas_show&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalle Clínico">
                                        <i class="fa-solid fa-eye me-1"></i> Detalle
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
