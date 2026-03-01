<?php
/**
 * Log System - Captura analíticas sin cookies ni permisos invasivos.
 * Respeta la privacidad anonimizando la IP.
 */

function log_visitor_activity($status_code = 200, $error_msg = "")
{
    $log_file = __DIR__ . '/../logs/visitas_profesor.csv';

    // 1. Capturar y Anonimizar IP
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    // Anonimización básica: reemplaza el último bloque por 0 (IPv4) o limpia la IPv6
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $ip = preg_replace('/[0-9]+$/', '0', $ip);
    } else {
        $ip = bin2hex(inet_pton($ip)); // Formato hexadecimal anónimo para IPv6
    }

    // 2. Extraer información del User-Agent
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    // Identificación simple de OS y Navegador
    $os = "Desconocido";
    if (preg_match('/windows|win32/i', $ua))
        $os = 'Windows';
    else if (preg_match('/android/i', $ua))
        $os = 'Android';
    else if (preg_match('/iphone|ipad|ipod/i', $ua))
        $os = 'iOS';
    else if (preg_match('/macintosh|mac os x/i', $ua))
        $os = 'Mac OS';
    else if (preg_match('/linux/i', $ua))
        $os = 'Linux';

    $browser = "Desconocido";
    if (preg_match('/msie|trident/i', $ua))
        $browser = 'IE';
    else if (preg_match('/firefox/i', $ua))
        $browser = 'Firefox';
    else if (preg_match('/chrome/i', $ua))
        $browser = 'Chrome';
    else if (preg_match('/safari/i', $ua))
        $browser = 'Safari';
    else if (preg_match('/opera|opr/i', $ua))
        $browser = 'Opera';

    $device = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $ua)) ? 'Mobile' : 'Desktop';

    // 3. Otros datos
    $timestamp = date('Y-m-d H:i:s');
    $url = $_SERVER['REQUEST_URI'] ?? '/';
    $referer = $_SERVER['HTTP_REFERER'] ?? 'Directo';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Desconocido';
    $protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';

    // 4. Preparar línea CSV
    $data = [
        $timestamp,
        $ip,
        $os,
        $browser,
        $device,
        $lang,
        $url,
        $method,
        $referer,
        $status_code,
        $protocol,
        str_replace('"', "'", $error_msg) // Limpiar comillas para evitar romper CSV
    ];

    // 5. Escribir en el archivo
    $file_exists = file_exists($log_file);
    $handle = fopen($log_file, 'a');

    // Añadir encabezados si el archivo es nuevo
    if (!$file_exists) {
        fputcsv($handle, [
            'Fecha/Hora',
            'IP_Anónima',
            'OS',
            'Navegador',
            'Dispositivo',
            'Idioma',
            'URL',
            'Metodo',
            'Referer',
            'Status',
            'Protocolo',
            'Errores'
        ]);
    }

    fputcsv($handle, $data);
    fclose($handle);
}
?>