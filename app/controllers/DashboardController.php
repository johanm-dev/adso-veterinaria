<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\Alerta;

class DashboardController extends Controller {
    public function index(): void {
        $this->requireLogin();
        $db = Database::getConnection();

        $totalMascotas = (int)$db->query("SELECT COUNT(*) FROM mascota WHERE activo = TRUE")->fetchColumn();
        $totalPropietarios = (int)$db->query("SELECT COUNT(*) FROM propietario")->fetchColumn();
        $totalConsultasMes = (int)$db->query("
            SELECT COUNT(*) FROM consulta 
            WHERE DATE_TRUNC('month', fecha) = DATE_TRUNC('month', CURRENT_DATE)
        ")->fetchColumn();
        $alertasVacunasCount = (int)$db->query("
            SELECT COUNT(*) FROM v_alertas_vacunas 
            WHERE estado IN ('Por vencer', 'Vencida')
        ")->fetchColumn();

        $especiesData = $db->query("
            SELECT especie, COUNT(*) as cantidad 
            FROM mascota 
            WHERE activo = TRUE 
            GROUP BY especie 
            ORDER BY cantidad DESC
        ")->fetchAll();

        $ultimasConsultas = $db->query("
            SELECT c.id, c.fecha, c.motivo, c.costo, m.nombre AS mascota_nombre,
                   (p.nombres || ' ' || p.apellidos) AS propietario_nombre
            FROM consulta c
            JOIN mascota m ON m.id = c.mascota_id
            JOIN propietario p ON p.id = m.propietario_id
            ORDER BY c.fecha DESC
            LIMIT 5
        ")->fetchAll();

        $alertasCriticas = Alerta::getAlertasVacunas();

        $this->render('dashboard/index', [
            'totalMascotas'       => $totalMascotas,
            'totalPropietarios'   => $totalPropietarios,
            'totalConsultasMes'   => $totalConsultasMes,
            'alertasVacunasCount' => $alertasVacunasCount,
            'especiesData'        => $especiesData,
            'ultimasConsultas'    => $ultimasConsultas,
            'alertasCriticas'     => array_slice($alertasCriticas, 0, 5),
        ]);
    }
}
