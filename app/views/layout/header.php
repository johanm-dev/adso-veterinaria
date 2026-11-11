<?php
$isLoggedIn = isset($_SESSION['usuario_id']);
$nombreUsuario = $_SESSION['usuario_nombre'] ?? '';
$rolUsuario = $_SESSION['usuario_rol'] ?? '';
$actionActual = $_GET['action'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veterinaria Digital - Sistema Integral</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Chart.js para Dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="public/css/estilos.css">
</head>
<body class="bg-light">

<?php if ($isLoggedIn): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php?action=dashboard">
            <i class="bi bi-heart-pulse-fill me-2 fs-4"></i> Veterinaria Digital
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $actionActual === 'dashboard' ? 'active fw-bold' : '' ?>" href="index.php?action=dashboard">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($actionActual, 'propietarios') ? 'active fw-bold' : '' ?>" href="index.php?action=propietarios">
                        <i class="bi bi-people me-1"></i> Propietarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($actionActual, 'mascotas') ? 'active fw-bold' : '' ?>" href="index.php?action=mascotas">
                        <i class="bi bi-github me-1"></i> Mascotas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($actionActual, 'consultas') ? 'active fw-bold' : '' ?>" href="index.php?action=consultas">
                        <i class="bi bi-clipboard2-pulse me-1"></i> Consultas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($actionActual, 'vacunas') ? 'active fw-bold' : '' ?>" href="index.php?action=vacunas">
                        <i class="bi bi-eyedropper me-1"></i> Vacunación
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $actionActual === 'alertas' ? 'active fw-bold' : '' ?>" href="index.php?action=alertas">
                        <i class="bi bi-bell me-1"></i> Alertas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($actionActual, 'reportes') ? 'active fw-bold' : '' ?>" href="index.php?action=reportes">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Reportes
                    </a>
                </li>
                <?php if ($rolUsuario === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($actionActual, 'usuarios') ? 'active fw-bold' : '' ?>" href="index.php?action=usuarios">
                            <i class="bi bi-person-gear me-1"></i> Usuarios
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center text-white">
                <div class="me-3 text-end d-none d-md-block">
                    <div class="fw-bold"><?= e($nombreUsuario) ?></div>
                    <small class="badge bg-light text-dark text-uppercase"><?= e($rolUsuario) ?></small>
                </div>
                <a href="index.php?action=logout" class="btn btn-outline-light btn-sm" title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right me-1"></i> Salir
                </a>
            </div>
        </div>
    </div>
</nav>
<?php endif; ?>

<main class="py-4">
    <div class="container-fluid px-4">
        <!-- Notificaciones Flash -->
        <?php if ($success = flash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= e($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($error = flash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= e($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
