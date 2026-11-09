<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Alerta {
    public static function getAlertasVacunas(): array {
        $db = Database::getConnection();
        return $db->query("
            SELECT * FROM v_alertas_vacunas
            WHERE estado IN ('Por vencer', 'Vencida')
            ORDER BY proxima_dosis ASC
        ")->fetchAll();
    }

    public static function getAlertasControles(): array {
        $db = Database::getConnection();
        return $db->query("
            SELECT * FROM v_alertas_controles
            WHERE estado IN ('Vencido', 'Por asistir')
            ORDER BY proximo_control ASC
        ")->fetchAll();
    }

    public static function generarUrlWhatsapp(string $telefono, string $nombrePropietario, string $nombreMascota, string $asunto, string $fecha): string {
        $tel = preg_replace('/[^\d]/', '', $telefono);
        if (strlen($tel) === 10 && str_starts_with($tel, '3')) {
            $tel = '57' . $tel;
        }

        $mensaje = "Hola {$nombrePropietario}, te saludamos de Veterinaria Digital. Te recordamos que tu mascota *{$nombreMascota}* tiene pendiente: *{$asunto}* para la fecha *{$fecha}*. ¡Por favor contáctanos para agendar su cita!";
        return 'https://api.whatsapp.com/send?phone=' . $tel . '&text=' . rawurlencode($mensaje);
    }
}
