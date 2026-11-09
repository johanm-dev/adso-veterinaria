<?php
namespace App\Controllers;

use App\Models\Mascota;
use App\Models\Propietario;

class MascotaController extends Controller {
    public function index(): void {
        $this->requireLogin();
        $buscar = trim($_GET['q'] ?? '');
        $especie = trim($_GET['especie'] ?? '');
        $mascotas = Mascota::all($buscar, $especie);

        $this->render('mascotas/index', [
            'mascotas' => $mascotas,
            'buscar'   => $buscar,
            'especie'  => $especie
        ]);
    }

    public function create(): void {
        $this->requireLogin();
        $propietarios = Propietario::all();
        $propietarioSeleccionado = (int)($_GET['propietario_id'] ?? 0);

        $this->render('mascotas/create', [
            'propietarios'            => $propietarios,
            'propietarioSeleccionado' => $propietarioSeleccionado
        ]);
    }

    public function store(): void {
        $this->requireLogin();
        $this->verificarCsrf();

        $nombre = trim($_POST['nombre'] ?? '');
        $especie = trim($_POST['especie'] ?? '');
        $raza = trim($_POST['raza'] ?? '');
        $sexo = trim($_POST['sexo'] ?? '');
        $fechaNacimiento = $_POST['fecha_nacimiento'] ?? null;
        $peso = !empty($_POST['peso']) ? (float)$_POST['peso'] : null;
        $propietarioId = (int)($_POST['propietario_id'] ?? 0);

        if (empty($nombre) || empty($especie) || empty($sexo) || empty($propietarioId)) {
            flash('error', 'Los campos Nombre, Especie, Sexo y Propietario son obligatorios.');
            $this->redirect('index.php?action=mascotas_create');
        }

        try {
            $id = Mascota::create([
                'nombre'           => $nombre,
                'especie'          => $especie,
                'raza'             => $raza,
                'sexo'             => $sexo,
                'fecha_nacimiento' => $fechaNacimiento,
                'peso'             => $peso,
                'propietario_id'   => $propietarioId,
            ]);
            flash('success', "Mascota '{$nombre}' registrada exitosamente.");
            $this->redirect("index.php?action=mascotas_historial&id={$id}");
        } catch (\PDOException $e) {
            flash('error', 'Error al registrar mascota: ' . $e->getMessage());
            $this->redirect('index.php?action=mascotas_create');
        }
    }

    public function edit(): void {
        $this->requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $mascota = Mascota::findById($id);

        if (!$mascota) {
            flash('error', 'Mascota no encontrada.');
            $this->redirect('index.php?action=mascotas');
        }

        $propietarios = Propietario::all();

        $this->render('mascotas/edit', [
            'mascota'      => $mascota,
            'propietarios' => $propietarios
        ]);
    }

    public function update(): void {
        $this->requireLogin();
        $this->verificarCsrf();

        $id = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $especie = trim($_POST['especie'] ?? '');
        $raza = trim($_POST['raza'] ?? '');
        $sexo = trim($_POST['sexo'] ?? '');
        $fechaNacimiento = $_POST['fecha_nacimiento'] ?? null;
        $peso = !empty($_POST['peso']) ? (float)$_POST['peso'] : null;
        $propietarioId = (int)($_POST['propietario_id'] ?? 0);

        if (empty($id) || empty($nombre) || empty($especie) || empty($sexo) || empty($propietarioId)) {
            flash('error', 'Todos los campos requeridos deben diligenciarse.');
            $this->redirect("index.php?action=mascotas_edit&id={$id}");
        }

        try {
            Mascota::update($id, [
                'nombre'           => $nombre,
                'especie'          => $especie,
                'raza'             => $raza,
                'sexo'             => $sexo,
                'fecha_nacimiento' => $fechaNacimiento,
                'peso'             => $peso,
                'propietario_id'   => $propietarioId,
            ]);
            flash('success', 'Mascota actualizada correctamente.');
            $this->redirect("index.php?action=mascotas_historial&id={$id}");
        } catch (\PDOException $e) {
            flash('error', 'Error al actualizar mascota: ' . $e->getMessage());
            $this->redirect("index.php?action=mascotas_edit&id={$id}");
        }
    }

    public function delete(): void {
        $this->requireLogin();
        $this->verificarCsrf();

        $id = (int)($_POST['id'] ?? 0);
        Mascota::delete($id);
        flash('success', 'Mascota dada de baja (inactivada) correctamente.');
        $this->redirect('index.php?action=mascotas');
    }

    public function historial(): void {
        $this->requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $mascota = Mascota::findById($id);

        if (!$mascota) {
            flash('error', 'Mascota no encontrada.');
            $this->redirect('index.php?action=mascotas');
        }

        $consultas = Mascota::getHistorialConsultas($id);
        $vacunas = Mascota::getHistorialVacunas($id);

        $this->render('mascotas/historial', [
            'mascota'   => $mascota,
            'consultas' => $consultas,
            'vacunas'   => $vacunas,
        ]);
    }
}
