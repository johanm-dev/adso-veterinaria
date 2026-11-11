<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">Panel de Control General</h2>
        <p class="text-muted small">Resumen en tiempo real de operaciones, consultas y alertas sanitarias</p>
    </div>
    <div>
        <a href="index.php?action=consultas_create" class="btn btn-primary shadow-sm me-2">
            <i class="fa-solid fa-circle-plus me-1"></i> Nueva Consulta
        </a>
        <a href="index.php?action=mascotas_create" class="btn btn-outline-primary shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> Registrar Mascota
        </a>
    </div>
</div>

<!-- Tarjetas KPI -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary-subtle text-primary p-3 rounded-3 me-3 fs-3">
                    <i class="fa-solid fa-paw"></i>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase fw-semibold mb-1 small">Pacientes Activos</h6>
                    <h3 class="fw-bold mb-0 text-dark"><?= $totalMascotas ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center">
                <div class="bg-info-subtle text-info p-3 rounded-3 me-3 fs-3">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase fw-semibold mb-1 small">Propietarios</h6>
                    <h3 class="fw-bold mb-0 text-dark"><?= $totalPropietarios ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success-subtle text-success p-3 rounded-3 me-3 fs-3">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase fw-semibold mb-1 small">Consultas del Mes</h6>
                    <h3 class="fw-bold mb-0 text-dark"><?= $totalConsultasMes ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center">
                <div class="bg-warning-subtle text-warning p-3 rounded-3 me-3 fs-3">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase fw-semibold mb-1 small">Vacunas Pendientes</h6>
                    <h3 class="fw-bold mb-0 text-dark"><?= $alertasVacunasCount ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Gráfico de Pacientes por Especie -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Distribución de Pacientes</h5>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <div style="max-width: 320px; width: 100%;">
                    <canvas id="graficoEspecies"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas de Vacunación Próxima -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-warning"><i class="fa-solid fa-triangle-exclamation me-2"></i>Vacunas Críticas y por Vencer</h5>
                <a href="index.php?action=alertas" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mascota</th>
                                <th>Vacuna</th>
                                <th>Próxima Dosis</th>
                                <th>Estado</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($alertasCriticas)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fa-solid fa-circle-check fs-3 text-success d-block mb-1"></i>
                                        Todas las mascotas se encuentran al día con sus vacunas.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($alertasCriticas as $al): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= e($al['mascota']) ?></td>
                                        <td><?= e($al['vacuna']) ?></td>
                                        <td><?= e($al['proxima_dosis']) ?></td>
                                        <td>
                                            <?php if ($al['estado'] === 'Vencida'): ?>
                                                <span class="badge bg-danger">Vencida</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Por vencer</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="index.php?action=alertas" class="btn btn-outline-success btn-sm" title="Notificar">
                                                <i class="fa-brands fa-whatsapp"></i>
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
</div>

<!-- Últimas Consultas Médicas -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>Actividad Médica Reciente</h5>
        <a href="index.php?action=consultas" class="btn btn-sm btn-outline-primary">Ver historial completo</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Mascota</th>
                        <th>Propietario</th>
                        <th>Motivo</th>
                        <th>Valor</th>
                        <th class="text-end">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ultimasConsultas)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No hay consultas registradas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($ultimasConsultas as $c): ?>
                            <tr>
                                <td><?= e($c['fecha']) ?></td>
                                <td class="fw-semibold"><?= e($c['mascota_nombre']) ?></td>
                                <td><?= e($c['propietario_nombre']) ?></td>
                                <td><?= e($c['motivo']) ?></td>
                                <td class="fw-semibold text-success"><?= formato_pesos((float)$c['costo']) ?></td>
                                <td class="text-end">
                                    <a href="index.php?action=consultas_show&id=<?= $c['id'] ?>" class="btn btn-sm btn-light border">
                                        <i class="fa-solid fa-eye"></i>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const rawData = <?= json_encode($especiesData) ?>;
    const labels = rawData.map(item => item.especie);
    const counts = rawData.map(item => item.cantidad);

    const ctx = document.getElementById('graficoEspecies');
    if (ctx && labels.length > 0) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: counts,
                    backgroundColor: ['#0d6efd', '#20c997', '#ffc107', '#fd7e14', '#6f42c1'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>
