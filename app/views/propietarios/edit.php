<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">Editar Propietario</h2>
                <p class="text-muted small">Actualice la información de contacto de <?= e($propietario['nombre_completo']) ?></p>
            </div>
            <a href="index.php?action=propietarios" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form method="POST" action="index.php?action=propietarios_update" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $propietario['id'] ?>">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="documento" class="form-label fw-semibold">Número de Documento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="documento" name="documento" required value="<?= e($propietario['documento']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="nombres" class="form-label fw-semibold">Nombres <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombres" name="nombres" required value="<?= e($propietario['nombres']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="apellidos" class="form-label fw-semibold">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" required value="<?= e($propietario['apellidos']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="telefono" class="form-label fw-semibold">Teléfono / Celular <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" required value="<?= e($propietario['telefono']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" value="<?= e($propietario['correo'] ?? '') ?>">
                        </div>

                        <div class="col-12">
                            <label for="direccion" class="form-label fw-semibold">Dirección de Residencia</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="<?= e($propietario['direccion'] ?? '') ?>">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php?action=propietarios" class="btn btn-light border">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-check me-1"></i> Actualizar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
