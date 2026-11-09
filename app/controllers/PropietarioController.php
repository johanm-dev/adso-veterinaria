<?php
namespace App\Controllers;

use App\Models\Propietario;

class PropietarioController extends Controller {
    public function index(): void {
        $this->requireLogin();
        $buscar = trim($_GET['q'] ?? '');
        $propietarios = Propietario::all($buscar);
        $this->render('propietarios/index', [
            'propietarios' => $propietarios,
            'buscar'       => $buscar
        ]);
    }

    public function create(): void {
        $this->requireLogin();
        $this->render('propietarios/create');
    }

    public function store(): void {
        $this->requireLogin();
        $this->verificarCsrf();

        $documento = trim($_POST['documento'] ?? '');
        $nombres = trim($_POST['nombres'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        if (empty($documento) || empty($nombres) || empty($apellidos) || empty($telefono)) {
            flash('error', 'Los campos Documento, Nombres, Apellidos y Teléfono son obligatorios.');
            $this->redirect('index.php?action=propietarios_create');
        }

        try {
            Propietario::create([
                'documento' => $documento,
                'nombres'   => $nombres,
                'apellidos' => $apellidos,
                'telefono'  => $telefono,
                'correo'    => $correo,
                'direccion' => $direccion,
            ]);
            flash('success', 'Propietario registrado exitosamente.');
            $this->redirect('index.php?action=propietarios');
        } catch (\PDOException $e) {
            if ($e->getCode() === '23505') {
                flash('error', 'Ya existe un propietario registrado con ese número de documento.');
            } else {
                flash('error', 'Error al registrar propietario: ' . $e->getMessage());
            }
            $this->redirect('index.php?action=propietarios_create');
        }
    }

    public function edit(): void {
        $this->requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $propietario = Propietario::findById($id);

        if (!$propietario) {
            flash('error', 'El propietario solicitado no existe.');
            $this->redirect('index.php?action=propietarios');
        }

        $this->render('propietarios/edit', ['propietario' => $propietario]);
    }

    public function update(): void {
        $this->requireLogin();
        $this->verificarCsrf();

        $id = (int)($_POST['id'] ?? 0);
        $documento = trim($_POST['documento'] ?? '');
        $nombres = trim($_POST['nombres'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        if (empty($id) || empty($documento) || empty($nombres) || empty($apellidos) || empty($telefono)) {
            flash('error', 'Todos los campos obligatorios deben diligenciarse.');
            $this->redirect("index.php?action=propietarios_edit&id={$id}");
        }

        try {
            Propietario::update($id, [
                'documento' => $documento,
                'nombres'   => $nombres,
                'apellidos' => $apellidos,
                'telefono'  => $telefono,
                'correo'    => $correo,
                'direccion' => $direccion,
            ]);
            flash('success', 'Datos del propietario actualizados correctamente.');
            $this->redirect('index.php?action=propietarios');
        } catch (\PDOException $e) {
            flash('error', 'Error al actualizar propietario: ' . $e->getMessage());
            $this->redirect("index.php?action=propietarios_edit&id={$id}");
        }
    }

    public function delete(): void {
        $this->requireLogin();
        $this->verificarCsrf();

        $id = (int)($_POST['id'] ?? 0);

        try {
            Propietario::delete($id);
            flash('success', 'Propietario eliminado exitosamente.');
        } catch (\DomainException $e) {
            flash('error', $e->getMessage());
        } catch (\PDOException $e) {
            if ($e->getCode() === '23503') {
                flash('error', 'Regla RN-02: No se puede eliminar el propietario porque tiene mascotas asociadas en la base de datos.');
            } else {
                flash('error', 'Error de base de datos: ' . $e->getMessage());
            }
        }

        $this->redirect('index.php?action=propietarios');
    }
}
