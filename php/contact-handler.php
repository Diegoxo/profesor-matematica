<?php
/**
 * Script para procesar el formulario de contacto
 * Envía la información al correo clasesadomicilio30@gmail.com
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recoger y sanitizar datos
    $nombre  = strip_tags(trim($_POST["nombre"]));
    $celular = strip_tags(trim($_POST["celular"]));
    $mensaje_usuario = strip_tags(trim($_POST["mensaje"]));

    // 2. Validar campos obligatorios
    if (empty($nombre) || empty($celular)) {
        header("Location: ../contacto/?status=error");
        exit;
    }

    // 3. Configuración del correo
    $destinatario = "clasesadomicilio30@gmail.com";
    $asunto = "Nuevo Mensaje de Contacto - Profesor de Matemática";

    // 4. Construir el cuerpo del mensaje
    $contenido = "Has recibido un nuevo mensaje desde el sitio web:\n\n";
    $contenido .= "Nombre: $nombre\n";
    $contenido .= "Celular / WhatsApp: $celular\n";
    $contenido .= "Mensaje:\n$mensaje_usuario\n\n";
    $contenido .= "--- Fin del Mensaje ---";

    // 5. Encabezados (headers)
    $headers = "From: Profesor de Matemática <no-reply@profesordematematica.com.co>\r\n";
    $headers .= "Reply-To: $destinatario\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // 6. Enviar el correo
    if (mail($destinatario, $asunto, $contenido, $headers)) {
        header("Location: ../contacto/?status=success");
    } else {
        header("Location: ../contacto/?status=error&msg=mail_failed");
    }
} else {
    // Si no es POST, redirigir al inicio
    header("Location: ../");
}
exit;
?>
