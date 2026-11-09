<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">Editar Mascota</h2>
                <p class="text-muted small">Actualice la ficha de <?= e($mascota['nombre']) ?></p>
            </div>
            <a href="index.php?action=mascotas_historial&id=<?= $mascota['id'] ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver al Historial
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form method="POST" action="index.php?action=mascotas_update" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $mascota['id'] ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="propietario_id" class="form-label fw-semibold">Propietario Responsable <span class="text-danger">*</span></label>
                            <select class="form-select" id="propietario_id" name="propietario_id" required>
                                <?php foreach ($propietarios as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= ((int)$mascota['propietario_id'] === (int)$p['id']) ? 'selected' : '' ?>>
                                        <?= e($p['nombre_completo']) ?> (Doc: <?= e($p['documento']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="nombre" class="form-label fw-semibold">Nombre de la Mascota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required value="<?= e($mascota['nombre']) ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="especie" class="form-label fw-semibold">Especie <span class="text-danger">*</span></label>
                            <select class="form-select" id="especie" name="especie" required>
                                <option value="Perro" <?= $mascota['especie'] === 'Perro' ? 'selected' : '' ?>>Perro</option>
                                <option value="Gato" <?= $mascota['especie'] === 'Gato' ? 'selected' : '' ?>>Gato</option>
                                <option value="Ave" <?= $mascota['especie'] === 'Ave' ? 'selected' : '' ?>>Ave</option>
                                <option value="Conejo" <?= $mascota['especie'] === 'Conejo' ? 'selected' : '' ?>>Conejo</option>
                                <option value="Otro" <?= $mascota['especie'] === 'Otro' ? 'selected' : '' ?>>Otro</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="raza" class="form-label fw-semibold">Raza</label>
                            <input type="text" class="form-control" id="raza" name="raza" value="<?= e($mascota['raza'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="sexo" class="form-label fw-semibold">Sexo <span class="text-danger">*</span></label>
                            <select class="form-select" id="sexo" name="sexo" required>
                                <option value="M" <?= $mascota['sexo'] === 'M' ? 'selected' : '' ?>>Macho (M)</option>
                                <option value="H" <?= $mascota['sexo'] === 'H' ? 'selected' : '' ?>>Hembra (H)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_nacimiento" class="form-label fw-semibold">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= e($mascota['fecha_nacimiento'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="peso" class="form-label fw-semibold">Peso Actual (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="peso" name="peso" value="<?= e($mascota['peso_kg'] ?? '') ?>">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php?action=mascotas_historial&id=<?= $mascota['id'] ?>" class="btn btn-light border">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Actualizar Ficha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
