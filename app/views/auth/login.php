<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-heart-pulse fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-primary">Veterinaria Digital</h3>
                    <p class="text-muted small">Ingresa tus credenciales para acceder</p>
                </div>

                <form method="POST" action="index.php?action=login_submit" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="admin@veterinaria.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3 shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top text-center text-muted small">
                    <strong>Cuentas de prueba:</strong><br>
                    <code>admin@veterinaria.com</code> / <code>Admin123*</code><br>
                    <code>carlos.mendoza@veterinaria.com</code> / <code>Admin123*</code>
                </div>
            </div>
        </div>
    </div>
</div>
