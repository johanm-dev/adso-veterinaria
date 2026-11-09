<?php
namespace App\Controllers;

use App\Models\Consulta;
use App\Models\Mascota;
use App\Models\Vacuna;

class VacunaController extends Controller {
    public function index(): void {
        $this->requireLogin();
        $aplicaciones = Vacuna::listadoAplicaciones();
        $this->render('vacunas/index', ['aplicaciones' => $aplicaciones]);
    }

    public function create(): void {
        $this->requireLogin();
        $mascotas = Mascota::all();
        $vacunas = Vacuna::all();
        $veterinarios = Consulta::getVeterinarios();
        $mascotaSeleccionada = (int)($_GET['mascota_id'] ?? 0);

        $this->render('vacunas/create', [
            'mascotas'            => $mascotas,
            'vacunas'             => $vacunas,
            'veterinarios'        => $veterinarios,
            'mascotaSeleccionada' => $mascotaSeleccionada,
        ]);
    }

    public function store(): void {
        $this->requireLogin();
        $this->verificarCsrf();

        $mascotaId = (int)($_POST['mascota_id'] ?? 0);
        $vacunaId = (int)($_POST['vacuna_id'] ?? 0);
        $veterinarioId = (int)($_POST['veterinario_id'] ?? 0);
        $fechaAplicacion = $_POST['fecha_aplicacion'] ?? date('Y-m-d');
        $lote = trim($_POST['lote'] ?? '');
        $observaciones = trim($_POST['observaciones'] ?? '');

        if (empty($mascotaId) || empty($vacunaId) || empty($veterinarioId) || empty($fechaAplicacion)) {
            flash('error', 'Mascota, Vacuna, Veterinario y Fecha de Aplicación son obligatorios.');
            $this->redirect('index.php?action=vacunas_create');
        }

        try {
            // Regla RN-01 se ejecuta de forma automática en Vacuna::aplicar()
            Vacuna::aplicar([
                'mascota_id'       => $mascotaId,
                'vacuna_id'        => $vacunaId,
                'veterinario_id'   => $veterinarioId,
                'fecha_aplicacion' => $fechaAplicacion,
                'lote'             => $lote,
                'observaciones'    => $observaciones,
            ]);

            flash('success', 'Vacuna aplicada y próxima dosis calculada automáticamente con éxito (RN-01).');
            $this->redirect("index.php?action=mascotas_historial&id={$mascotaId}");
        } catch (\Exception $e) {
            flash('error', 'Error al aplicar vacuna: ' . $e->getMessage());
            $this->redirect('index.php?action=vacunas_create');
        }
    }
}
