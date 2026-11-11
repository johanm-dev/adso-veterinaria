<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">Registrar Nuevo Paciente</h2>
                <p class="text-muted small">Diligencie la información de la mascota y asóciela a su propietario</p>
            </div>
            <a href="index.php?action=mascotas" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form method="POST" action="index.php?action=mascotas_store" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="propietario_id" class="form-label fw-semibold">Propietario Responsable <span class="text-danger">*</span></label>
                            <select class="form-select" id="propietario_id" name="propietario_id" required>
                                <option value="">Seleccione un propietario...</option>
                                <?php foreach ($propietarios as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= ($propietarioSeleccionado === (int)$p['id']) ? 'selected' : '' ?>>
                                        <?= e($p['nombre_completo']) ?> (Doc: <?= e($p['documento']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Debe asociar un propietario.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="nombre" class="form-label fw-semibold">Nombre de la Mascota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej: Rocky">
                            <div class="invalid-feedback">El nombre es obligatorio.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="especie" class="form-label fw-semibold">Especie <span class="text-danger">*</span></label>
                            <select class="form-select" id="especie" name="especie" required>
                                <option value="Perro">Perro</option>
                                <option value="Gato">Gato</option>
                                <option value="Ave">Ave</option>
                                <option value="Conejo">Conejo</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="raza" class="form-label fw-semibold">Raza</label>
                            <input type="text" class="form-control" id="raza" name="raza" placeholder="Ej: Labrador Retriever">
                        </div>

                        <div class="col-md-4">
                            <label for="sexo" class="form-label fw-semibold">Sexo <span class="text-danger">*</span></label>
                            <select class="form-select" id="sexo" name="sexo" required>
                                <option value="M">Macho (M)</option>
                                <option value="H">Hembra (H)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_nacimiento" class="form-label fw-semibold">Fecha de Nacimiento (Aprox.)</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                        </div>

                        <div class="col-md-6">
                            <label for="peso" class="form-label fw-semibold">Peso Inicial (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="peso" name="peso" placeholder="Ej: 14.50">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php?action=mascotas" class="btn btn-light border">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Guardar Mascota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
