<?php require_once __DIR__ . '../../php/logger.php'; log_visitor_activity(); ?>
<?php
// Cargar variables de entorno desde .env
$env = parse_ini_file('.env');
$pixel_id = $env['META_PIXEL_ID'];
$access_token = $env['META_ACCESS_TOKEN'];

// Obtener IP y User-Agent del usuario
$client_ip = $_SERVER['REMOTE_ADDR'];
$client_user_agent = $_SERVER['HTTP_USER_AGENT'];

// Construir los datos del evento
$data = [
    "data" => [
        [
            "event_name" => "Boton WhatsApp",
            "event_time" => time(),
            "action_source" => "website",
            "user_data" => [
                "client_ip_address" => $client_ip,
                "client_user_agent" => $client_user_agent
            ]
        ]
    ]
];

// Convertir a JSON
$json_data = json_encode($data);

// Configurar URL de la API de Conversiones
$url = "https://graph.facebook.com/v18.0/{$pixel_id}/events?access_token={$access_token}";

// Inicializar cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desactivar verificaciÃ³n SSL
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

// Ejecutar la solicitud y capturar la respuesta
$response = curl_exec($ch);

// Capturar errores de cURL si existen
if (curl_errno($ch)) {
    $response = json_encode(["error" => curl_error($ch)]);
}

// Cerrar cURL
curl_close($ch);

// Mostrar la respuesta
header('Content-Type: application/json');
echo $response;
?>


