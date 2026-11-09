<?php
/**
 * Funciones Auxiliares Globales - Veterinaria Digital
 */

if (!function_exists('e')) {
    /**
     * Escapa valores para mitigar ataques XSS.
     */
    function e(?string $valor): string {
        return htmlspecialchars((string)$valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('flash')) {
    /**
     * Establece o recupera mensajes flash de sesión.
     */
    function flash(string $clave, ?string $mensaje = null): ?string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($mensaje !== null) {
            $_SESSION['flash'][$clave] = $mensaje;
            return null;
        }

        if (isset($_SESSION['flash'][$clave])) {
            $msg = $_SESSION['flash'][$clave];
            unset($_SESSION['flash'][$clave]);
            return $msg;
        }

        return null;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Genera o retorna el token CSRF activo.
     */
    function csrf_token(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Renderiza un campo input hidden con el token CSRF.
     */
    function csrf_field(): string {
        return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('verificar_csrf')) {
    /**
     * Valida el token CSRF recibido por POST.
     */
    function verificar_csrf(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $postToken = $_POST['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        return !empty($postToken) && hash_equals($sessionToken, $postToken);
    }
}

if (!function_exists('formato_pesos')) {
    /**
     * Formatea un número a pesos colombianos (COP).
     */
    function formato_pesos(float $monto): string {
        return '$ ' . number_format($monto, 0, ',', '.');
    }
}

if (!function_exists('calcular_edad')) {
    /**
     * Calcula la edad legible a partir de la fecha de nacimiento.
     */
    function calcular_edad(?string $fechaNacimiento): string {
        if (!$fechaNacimiento) return 'No registrada';
        $nacimiento = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $diff = $hoy->diff($nacimiento);

        if ($diff->y > 0) {
            return $diff->y . ' año' . ($diff->y > 1 ? 's' : '') . ($diff->m > 0 ? ' y ' . $diff->m . ' m' : '');
        }
        if ($diff->m > 0) {
            return $diff->m . ' mes' . ($diff->m > 1 ? 'es' : '');
        }
        return $diff->d . ' día' . ($diff->d > 1 ? 's' : '');
    }
}
