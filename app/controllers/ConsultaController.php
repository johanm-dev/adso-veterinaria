<?php
namespace App\Controllers;

use App\Models\Consulta;
use App\Models\Mascota;

class ConsultaController extends Controller {
    public function index(): void {
        $this->requireLogin();
        $consultas = Consulta::all();
        $this->render('consultas/index', ['consultas' => $consultas]);
    }

    public function create(): void {
        $this->requireLogin();
        $mascotas = Mascota::all();
        $veterinarios = Consulta::getVeterinarios();
        $mascotaSeleccionada = (int)($_GET['mascota_id'] ?? 0);

        $this->render('consultas/create', [
            'mascotas'            => $mascotas,
            'veterinarios'        => $veterinarios,
            'mascotaSeleccionada' => $mascotaSeleccionada,
        ]);
    }

    public function store(): void {
        $this->requireLogin();
        $this->verificarCsrf();

        $mascotaId = (int)($_POST['mascota_id'] ?? 0);
        $veterinarioId = (int)($_POST['veterinario_id'] ?? 0);
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $motivo = trim($_POST['motivo'] ?? '');
        $diagnostico = trim($_POST['diagnostico'] ?? '');
        $costo = !empty($_POST['costo']) ? (float)$_POST['costo'] : 0.00;
        $proximoControl = !empty($_POST['proximo_control']) ? $_POST['proximo_control'] : null;

        if (empty($mascotaId) || empty($veterinarioId) || empty($fecha) || empty($hora) || empty($motivo)) {
            flash('error', 'Mascota, Veterinario, Fecha, Hora y Motivo son obligatorios.');
            $this->redirect('index.php?action=consultas_create');
        }

        $timestamp = $fecha . ' ' . $hora . (strlen($hora) === 5 ? ':00' : '');

        try {
            $consultaId = Consulta::create([
                'mascota_id'      => $mascotaId,
                'veterinario_id'  => $veterinarioId,
                'fecha'           => $timestamp,
                'motivo'          => $motivo,
                'diagnostico'     => $diagnostico,
                'costo'           => $costo,
                'proximo_control' => $proximoControl,
            ]);
            flash('success', 'Consulta médica registrada exitosamente.');
            $this->redirect("index.php?action=consultas_show&id={$consultaId}");
        } catch (\DomainException $e) {
            flash('error', $e->getMessage());
            $this->redirect('index.php?action=consultas_create');
        } catch (\PDOException $e) {
            if ($e->getCode() === '23505') {
                flash('error', 'Regla RN-03: El médico veterinario ya cuenta con una consulta programada en esa misma fecha y hora.');
            } else {
                flash('error', 'Error al registrar la consulta: ' . $e->getMessage());
            }
            $this->redirect('index.php?action=consultas_create');
        }
    }

    public function show(): void {
        $this->requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $consulta = Consulta::findById($id);

        if (!$consulta) {
            flash('error', 'Consulta no encontrada.');
            $this->redirect('index.php?action=consultas');
        }

        $this->render('consultas/show', ['consulta' => $consulta]);
    }
}
