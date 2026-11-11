<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">Gestión de Usuarios del Sistema</h2>
        <p class="text-muted small">Módulo exclusivo de Administrador para crear cuentas y asignar roles (RBAC)</p>
    </div>
    <a href="index.php?action=usuarios_create" class="btn btn-primary shadow-sm">
        <i class="fa-solid fa-user-plus me-1"></i> Nuevo Usuario
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Correo de Acceso</th>
                        <th>Rol</th>
                        <th>Detalles Profesionales</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No hay usuarios registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td class="text-muted">#<?= $u['id'] ?></td>
                                <td class="fw-bold text-dark"><?= e($u['nombre']) ?></td>
                                <td><code><?= e($u['correo']) ?></code></td>
                                <td>
                                    <?php
                                    $badgeColor = match ($u['rol']) {
                                        'admin'       => 'danger',
                                        'veterinario' => 'primary',
                                        default       => 'info text-dark',
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badgeColor ?> text-uppercase"><?= e($u['rol']) ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($u['tarjeta_profesional'])): ?>
                                        <span class="small">TP: <strong><?= e($u['tarjeta_profesional']) ?></strong></span>
                                        <?php if (!empty($u['especialidad'])): ?>
                                            <div class="small text-muted"><?= e($u['especialidad']) ?></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($u['activo']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <?php if ((int)$u['id'] === (int)($_SESSION['usuario_id'] ?? 0)): ?>
                                        <span class="badge bg-light text-muted border">Sesión actual</span>
                                    <?php else: ?>
                                        <form method="POST" action="index.php?action=usuarios_toggle" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                            <?php if ($u['activo']): ?>
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Desactivar acceso">
                                                    <i class="fa-solid fa-user-slash me-1"></i> Desactivar
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Reactivar acceso">
                                                    <i class="fa-solid fa-user-check me-1"></i> Activar
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
