<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">Crear Nuevo Usuario</h2>
                <p class="text-muted small">Registro exclusivo del Administrador con cifrado Bcrypt y asignación de rol</p>
            </div>
            <a href="index.php?action=usuarios" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form method="POST" action="index.php?action=usuarios_store" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label fw-semibold">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej: Dra. Camila Vega">
                            <div class="invalid-feedback">El nombre es obligatorio.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="correo" class="form-label fw-semibold">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="correo" name="correo" required placeholder="usuario@vet.com">
                            <div class="invalid-feedback">Ingrese un correo válido.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="rol" class="form-label fw-semibold">Rol del Usuario <span class="text-danger">*</span></label>
                            <select class="form-select" id="rol" name="rol" required>
                                <option value="">Seleccione un rol...</option>
                                <option value="recepcion">Recepción (recepcion)</option>
                                <option value="veterinario">Veterinario (veterinario)</option>
                                <option value="admin">Administrador (admin)</option>
                            </select>
                            <div class="invalid-feedback">Debe asignar un rol.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="password" class="form-label fw-semibold">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" minlength="6" required placeholder="Mínimo 6 caracteres">
                            <div class="invalid-feedback">Mínimo 6 caracteres.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="password_confirm" class="form-label fw-semibold">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="6" required placeholder="Repita la contraseña">
                            <div class="invalid-feedback">Confirme la contraseña.</div>
                        </div>
                    </div>

                    <!-- Campos adicionales automáticos si el rol es Veterinario -->
                    <div id="datosVeterinario" class="mt-4 p-3 bg-light rounded-3 border d-none">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-hospital me-1"></i>Perfil Profesional de Veterinario</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="tarjeta_profesional" class="form-label fw-semibold">Tarjeta Profesional <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tarjeta_profesional" name="tarjeta_profesional" placeholder="Ej: TP-1003">
                                <div class="invalid-feedback">Obligatoria para el rol Veterinario.</div>
                            </div>
                            <div class="col-md-4">
                                <label for="especialidad" class="form-label fw-semibold">Especialidad</label>
                                <input type="text" class="form-control" id="especialidad" name="especialidad" placeholder="Ej: Medicina General">
                            </div>
                            <div class="col-md-4">
                                <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ej: 3001112233">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php?action=usuarios" class="btn btn-light border">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-person-check-fill me-1"></i> Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const rolSelect = document.getElementById('rol');
    const vetBox = document.getElementById('datosVeterinario');
    const tpInput = document.getElementById('tarjeta_profesional');

    function toggleVetFields() {
        if (rolSelect.value === 'veterinario') {
            vetBox.classList.remove('d-none');
            tpInput.setAttribute('required', 'required');
        } else {
            vetBox.classList.add('d-none');
            tpInput.removeAttribute('required');
        }
    }

    rolSelect.addEventListener('change', toggleVetFields);
    toggleVetFields();
});
</script>
