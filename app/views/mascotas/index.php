<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">Directorio de Mascotas</h2>
        <p class="text-muted small">Pacientes registrados en el sistema veterinario</p>
    </div>
    <a href="index.php?action=mascotas_create" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Registrar Paciente
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-2">
            <input type="hidden" name="action" value="mascotas">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Buscar por mascota, propietario o documento..." value="<?= e($buscar) ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="especie" class="form-select">
                    <option value="">Todas las especies</option>
                    <option value="Perro" <?= $especie === 'Perro' ? 'selected' : '' ?>>Perro</option>
                    <option value="Gato" <?= $especie === 'Gato' ? 'selected' : '' ?>>Gato</option>
                    <option value="Ave" <?= $especie === 'Ave' ? 'selected' : '' ?>>Ave</option>
                    <option value="Conejo" <?= $especie === 'Conejo' ? 'selected' : '' ?>>Conejo</option>
                    <option value="Otro" <?= $especie === 'Otro' ? 'selected' : '' ?>>Otro</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                <?php if (!empty($buscar) || !empty($especie)): ?>
                    <a href="index.php?action=mascotas" class="btn btn-outline-secondary">Limpiar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mascota</th>
                        <th>Especie / Raza</th>
                        <th>Sexo</th>
                        <th>Edad Aprox.</th>
                        <th>Peso</th>
                        <th>Propietario Responsable</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mascotas)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No se encontraron mascotas registradas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($mascotas as $m): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= e($m['nombre']) ?></div>
                                    <small class="text-muted">ID: #<?= $m['id'] ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= e($m['especie']) ?></span>
                                    <div class="small text-muted"><?= e($m['raza'] ?? 'Mestizo') ?></div>
                                </td>
                                <td><?= $m['sexo'] === 'M' ? 'Macho' : 'Hembra' ?></td>
                                <td><?= calcular_edad($m['fecha_nacimiento']) ?></td>
                                <td><?= $m['peso_kg'] ? e($m['peso_kg']) . ' kg' : '—' ?></td>
                                <td>
                                    <div class="fw-semibold"><?= e($m['propietario_nombre']) ?></div>
                                    <small class="text-muted">Doc: <?= e($m['propietario_doc']) ?></small>
                                </td>
                                <td class="text-end">
                                    <a href="index.php?action=mascotas_historial&id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-primary" title="Ver Historial Clínico">
                                        <i class="bi bi-folder2-open me-1"></i> Historial
                                    </a>
                                    <a href="index.php?action=mascotas_edit&id=<?= $m['id'] ?>" class="btn btn-sm btn-light border" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Inactivar"
                                            data-bs-toggle="modal" data-bs-target="#delMascota<?= $m['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <!-- Modal Confirmación Eliminación Lógica -->
                                    <div class="modal fade" id="delMascota<?= $m['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Inactivar Mascota</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    ¿Confirma que desea dar de baja a <strong><?= e($m['nombre']) ?></strong>? El historial clínico permanecerá archivado.
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="index.php?action=mascotas_delete">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-danger">Confirmar Baja</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
