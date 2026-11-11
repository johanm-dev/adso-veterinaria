<?php
namespace App\Controllers;

use App\Models\Usuario;

class UsuarioController extends Controller {
    public function index(): void {
        $this->requireAdmin();
        $usuarios = Usuario::all();
        $this->render('usuarios/index', ['usuarios' => $usuarios]);
    }

    public function create(): void {
        $this->requireAdmin();
        $this->render('usuarios/create');
    }

    public function store(): void {
        $this->requireAdmin();
        $this->verificarCsrf();

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = strtolower(trim($_POST['correo'] ?? ''));
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $rol = $_POST['rol'] ?? '';
        $tarjetaProfesional = trim($_POST['tarjeta_profesional'] ?? '');
        $especialidad = trim($_POST['especialidad'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');

        if ($nombre === '' || $correo === '' || $password === '' || $rol === '') {
            flash('error', 'Nombre, Correo, Contraseña y Rol son obligatorios.');
            $this->redirect('index.php?action=usuarios_create');
        }

        if ($password !== $passwordConfirm) {
            flash('error', 'Las contraseñas no coinciden.');
            $this->redirect('index.php?action=usuarios_create');
        }

        try {
            Usuario::create([
                'nombre'              => $nombre,
                'correo'              => $correo,
                'password'            => $password,
                'rol'                 => $rol,
                'tarjeta_profesional' => $tarjetaProfesional,
                'especialidad'        => $especialidad,
                'telefono'            => $telefono,
            ]);

            flash('success', "Usuario '{$nombre}' ({$rol}) creado exitosamente.");
            $this->redirect('index.php?action=usuarios');
        } catch (\InvalidArgumentException $e) {
            flash('error', $e->getMessage());
            $this->redirect('index.php?action=usuarios_create');
        } catch (\PDOException $e) {
            if ($e->getCode() === '23505') {
                flash('error', 'Ya existe un usuario con ese correo electrónico o una tarjeta profesional duplicada.');
            } else {
                flash('error', 'Error al registrar usuario: ' . $e->getMessage());
            }
            $this->redirect('index.php?action=usuarios_create');
        }
    }

    public function toggleActivo(): void {
        $this->requireAdmin();
        $this->verificarCsrf();

        $id = (int)($_POST['id'] ?? 0);
        $actorId = (int)($_SESSION['usuario_id'] ?? 0);

        try {
            Usuario::toggleActivo($id, $actorId);
            flash('success', 'Estado del usuario actualizado correctamente.');
        } catch (\DomainException $e) {
            flash('error', $e->getMessage());
        } catch (\PDOException $e) {
            flash('error', 'Error al cambiar estado del usuario: ' . $e->getMessage());
        }

        $this->redirect('index.php?action=usuarios');
    }
}
