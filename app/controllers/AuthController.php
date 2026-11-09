<?php
namespace App\Controllers;

use App\Models\Usuario;

class AuthController extends Controller {
    public function showLogin(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['usuario_id'])) {
            $this->redirect('index.php?action=dashboard');
        }
        $this->render('auth/login');
    }

    public function login(): void {
        $this->verificarCsrf();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            flash('error', 'Por favor complete todos los campos.');
            $this->redirect('index.php?action=login');
        }

        $usuario = Usuario::findByEmail($email);

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true);

            $_SESSION['usuario_id']     = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email']  = $usuario['correo'];
            $_SESSION['usuario_rol']    = $usuario['rol'];

            flash('success', "¡Bienvenido al sistema, {$usuario['nombre']}!");
            $this->redirect('index.php?action=dashboard');
        } else {
            flash('error', 'Credenciales inválidas o usuario inactivo.');
            $this->redirect('index.php?action=login');
        }
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
