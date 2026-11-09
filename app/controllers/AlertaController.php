<?php
namespace App\Controllers;

use App\Models\Alerta;

class AlertaController extends Controller {
    public function index(): void {
        $this->requireLogin();

        $alertasVacunas = Alerta::getAlertasVacunas();
        $alertasControles = Alerta::getAlertasControles();

        foreach ($alertasVacunas as &$av) {
            $av['whatsapp_url'] = Alerta::generarUrlWhatsapp(
                $av['telefono'],
                $av['propietario'],
                $av['mascota'],
                "Vacunación ({$av['vacuna']})",
                $av['proxima_dosis']
            );
        }
        unset($av);

        foreach ($alertasControles as &$ac) {
            $ac['whatsapp_url'] = Alerta::generarUrlWhatsapp(
                $ac['telefono'],
                $ac['propietario'],
                $ac['mascota'],
                "Control Médico de seguimiento",
                $ac['proximo_control']
            );
        }
        unset($ac);

        $this->render('alertas/index', [
            'alertasVacunas'   => $alertasVacunas,
            'alertasControles' => $alertasControles,
        ]);
    }
}
