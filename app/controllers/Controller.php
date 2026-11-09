<?php
namespace App\Controllers;

abstract class Controller {
    protected function render(string $view, array $data = []): void {
        extract($data);
        $viewFile = dirname(__DIR__) . '/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("Vista no encontrada: " . e($viewFile));
        }

        require_once dirname(__DIR__) . '/views/layout/header.php';
        require_once $viewFile;
        require_once dirname(__DIR__) . '/views/layout/footer.php';
    }

    protected function redirect(string $url): void {
        header("Location: " . $url);
        exit;
    }

    protected function requireLogin(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario_id'])) {
            flash('error', 'Debe iniciar sesión para acceder al sistema.');
            $this->redirect('index.php?action=login');
        }
    }

    protected function requireAdmin(): void {
        $this->requireLogin();
        if ($_SESSION['usuario_rol'] !== 'administrador') {
            flash('error', 'Acceso denegado: se requieren permisos de administrador.');
            $this->redirect('index.php?action=dashboard');
        }
    }

    protected function verificarCsrf(): void {
        if (!verificar_csrf()) {
            http_response_code(403);
            die("Error de seguridad: Token CSRF inválido.");
        }
    }
}
