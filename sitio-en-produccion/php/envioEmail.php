<?php
/**
 * Script adaptado siguiendo las recomendaciones de Hostinger
 * para garantizar la salida del correo desde el servidor.
 */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit;
}

// 1. Recoger y sanitizar datos (usando los nombres de tu formulario)
$Nombre = trim($_POST["Nombre"] ?? "");
$Celular = trim($_POST["Celular"] ?? "");
$Mensaje = trim($_POST["Texto"] ?? "");

// 2. ConfiguraciÃ³n del destinatario
$to = "clasesadomicilio30@gmail.com";
$subject = "Nuevo mensaje de contacto - " . ($_SERVER["HTTP_HOST"] ?? "Web");

// 3. Construir el cuerpo del mensaje
$email_body = "Has recibido un nuevo mensaje desde el formulario de contacto:\n\n";
$email_body .= "Nombre: $Nombre\n";
$email_body .= "Celular / WhatsApp: $Celular\n\n";
$email_body .= "Mensaje:\n$Mensaje\n";
$email_body .= "\n--------------------------------------------------\n";
$email_body .= "Enviado desde: " . ($_SERVER["HTTP_HOST"] ?? "profesordematematica.com.co");

// 4. Encabezados CRÃTICOS para Hostinger
// El remitente (From) DEBE ser una direcciÃ³n del dominio para no ser bloqueada
$host = $_SERVER["HTTP_HOST"] ?? "profesordematematica.com.co";
$headers = "From: no-reply@" . $host . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// 5. Enviar el correo
mail($to, $subject, $email_body, $headers);

// ** Registros de AnalÃ­tica **
require_once __DIR__ . '/logger.php';
log_visitor_activity(200, "Formulario Enviado con Ã©xito");

// 6. RedirecciÃ³n final con aviso de Ã©xito
header("Location: https://profesordematematica.com.co/contacto/?status=success");
exit();
?>
