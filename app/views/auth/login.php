<div class="container-fluid p-0 login-split-wrapper">
    <div class="row g-0 min-vh-100">

        <!-- Panel Izquierdo Editorial (Paleta Sistema Azul) -->
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-between p-5 login-brand-pane">
            <div class="d-flex align-items-center gap-2">
                <span class="login-brand-badge">
                    <i class="fa-solid fa-paw"></i>
                </span>
                <span class="fw-bold fs-5 text-dark">Veterinaria</span>
                <span class="login-eyebrow ms-1">DIGITAL</span>
            </div>

            <div class="my-auto py-4" style="max-width: 470px;">
                <div class="login-eyebrow mb-3">ESPACIO PARA EL CUIDADO ANIMAL</div>

                <h1 class="login-hero-title mb-3">
                    La salud de cada<br>
                    paciente empieza con<br>
                    <span class="login-hero-serif">buena gestión.</span>
                </h1>

                <p class="text-secondary mb-0" style="font-size: 0.96rem; line-height: 1.55;">
                    Centraliza historias clínicas, agenda médica sin cruces<br>
                    y recordatorios preventivos de vacunación.
                </p>

                <div class="login-showcase-stage">
                    <div class="login-ring-deco"></div>
                    <div class="login-tilt-card">
                        <div class="login-eyebrow" style="font-size: 0.62rem;">CONTROL PREVENTIVO</div>
                        <h5>Un paciente<br>a la vez.</h5>
                        <div class="login-tilt-line" style="width: 78%;"></div>
                        <div class="login-tilt-line" style="width: 55%;"></div>
                        <span class="login-check-dot">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </div>
                    <div class="login-floating-pill">
                        <i class="fa-solid fa-arrow-up-right-dots text-primary"></i>
                        <span>Alertas al día</span>
                    </div>
                    <div class="login-spark">
                        <i class="fa-solid fa-asterisk"></i>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center text-muted small">
                <span>Una gestión clínica más clara.</span>
                <i class="fa-solid fa-shield-dog text-primary opacity-50"></i>
            </div>
        </div>

        <!-- Panel Derecho de Inicio de Sesión -->
        <div class="col-12 col-lg-6 d-flex flex-column justify-content-between p-4 p-md-5 login-form-pane">
            <!-- Encabezado Superior -->
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2 d-lg-none">
                    <span class="login-brand-badge">
                        <i class="fa-solid fa-paw"></i>
                    </span>
                    <span class="fw-bold text-dark">Veterinaria Digital</span>
                </div>
                <span class="login-pill-tag d-none d-lg-inline-block">SISTEMA CLÍNICO</span>
                <span class="text-muted small">
                    Gestión Veterinaria <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                </span>
            </div>

            <!-- Formulario Centrado -->
            <div class="mx-auto my-auto py-4 login-form-box">
                <?php if ($success = flash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> <?= e($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error = flash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="login-eyebrow mb-2">01 / INICIO DE SESIÓN</div>
                <h2 class="fw-bold text-dark mb-2" style="font-size: 1.95rem; letter-spacing: -0.02em;">
                    Bienvenido de nuevo.
                </h2>
                <p class="text-muted mb-4" style="font-size: 0.92rem;">
                    Ingresa tus credenciales para acceder a la clínica.
                </p>

                <form method="POST" action="index.php?action=login_submit" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small text-dark">Correo electrónico</label>
                        <input type="email"
                               class="form-control login-input"
                               id="email"
                               name="email"
                               placeholder="nombre@clinica.com"
                               required
                               autofocus>
                        <div class="invalid-feedback">Ingresa un correo electrónico válido.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold small text-dark">Contraseña</label>
                        <div class="login-password-wrap">
                            <input type="password"
                                   class="form-control login-input"
                                   id="password"
                                   name="password"
                                   placeholder="••••••••"
                                   required>
                            <button type="button"
                                    id="togglePassword"
                                    class="login-toggle-btn"
                                    aria-label="Mostrar u ocultar contraseña">
                                Mostrar
                            </button>
                        </div>
                        <div class="invalid-feedback">Ingresa tu contraseña.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 login-submit-btn shadow-sm">
                        <span>Iniciar sesión</span>
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </button>
                </form>
            </div>

            <!-- Pie Discreto -->
            <div class="text-center text-muted" style="font-size: 0.78rem;">
                Veterinaria Digital &copy; <?= date('Y') ?> · Acceso restringido a personal autorizado
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('togglePassword');
    const passInput = document.getElementById('password');
    if (toggleBtn && passInput) {
        toggleBtn.addEventListener('click', function () {
            const isHidden = passInput.getAttribute('type') === 'password';
            passInput.setAttribute('type', isHidden ? 'text' : 'password');
            toggleBtn.textContent = isHidden ? 'Ocultar' : 'Mostrar';
        });
    }
});
</script>
