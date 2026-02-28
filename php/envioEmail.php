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

// 2. Configuración del destinatario
$to = "clasesadomicilio30@gmail.com";
$subject = "Nuevo mensaje de contacto - " . ($_SERVER["HTTP_HOST"] ?? "Web");

// 3. Construir el cuerpo del mensaje
$email_body = "Has recibido un nuevo mensaje desde el formulario de contacto:\n\n";
$email_body .= "Nombre: $Nombre\n";
$email_body .= "Celular / WhatsApp: $Celular\n\n";
$email_body .= "Mensaje:\n$Mensaje\n";
$email_body .= "\n--------------------------------------------------\n";
$email_body .= "Enviado desde: " . ($_SERVER["HTTP_HOST"] ?? "profesordematematica.com.co");

// 4. Encabezados CRÍTICOS para Hostinger
// El remitente (From) DEBE ser una dirección del dominio para no ser bloqueada
$host = $_SERVER["HTTP_HOST"] ?? "profesordematematica.com.co";
$headers = "From: no-reply@" . $host . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// 5. Enviar el correo
mail($to, $subject, $email_body, $headers);

// 6. Redirección final con aviso de éxito
header("Location: https://profesordematematica.com.co/contacto/?status=success");
exit();
?>