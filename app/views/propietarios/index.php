<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">Directorio de Propietarios</h2>
        <p class="text-muted small">Administración de clientes y sus animales asociados</p>
    </div>
    <a href="index.php?action=propietarios_create" class="btn btn-primary shadow-sm">
        <i class="fa-solid fa-user-plus me-1"></i> Registrar Propietario
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-2">
            <input type="hidden" name="action" value="propietarios">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Buscar por nombres, apellidos, documento o teléfono..." value="<?= e($buscar) ?>">
                </div>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                <?php if (!empty($buscar)): ?>
                    <a href="index.php?action=propietarios" class="btn btn-outline-secondary">Limpiar</a>
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
                        <th>Documento</th>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Dirección</th>
                        <th class="text-center">Mascotas</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($propietarios)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No se encontraron propietarios registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($propietarios as $p): ?>
                            <tr>
                                <td class="fw-semibold"><?= e($p['documento']) ?></td>
                                <td><?= e($p['nombre_completo']) ?></td>
                                <td>
                                    <a href="https://api.whatsapp.com/send?phone=57<?= preg_replace('/\D/', '', $p['telefono']) ?>" target="_blank" class="text-decoration-none text-success">
                                        <i class="fa-brands fa-whatsapp me-1"></i> <?= e($p['telefono']) ?>
                                    </a>
                                </td>
                                <td><?= e($p['correo'] ?? '—') ?></td>
                                <td><small class="text-muted"><?= e($p['direccion'] ?? '—') ?></small></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-<?= $p['total_mascotas'] > 0 ? 'info' : 'secondary' ?>">
                                        <?= $p['total_mascotas'] ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="index.php?action=mascotas_create&propietario_id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-success" title="Agregar Mascota">
                                        <i class="fa-solid fa-circle-plus"></i>
                                    </a>
                                    <a href="index.php?action=propietarios_edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal<?= $p['id'] ?>">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>

                                    <!-- Modal Confirmación Eliminación (RN-02) -->
                                    <div class="modal fade" id="deleteModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Eliminar Propietario</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    ¿Confirma que desea eliminar al propietario <strong><?= e($p['nombre_completo']) ?></strong>?
                                                    <?php if ($p['total_mascotas'] > 0): ?>
                                                        <div class="alert alert-warning mt-3 small">
                                                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                                            <strong>Aviso (Regla RN-02):</strong> Este propietario tiene <strong><?= $p['total_mascotas'] ?> mascota(s)</strong> asociadas. El sistema denegará la eliminación para preservar el historial clínico.
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="index.php?action=propietarios_delete">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-danger">Confirmar Eliminación</button>
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
