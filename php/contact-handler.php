<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Usar los nombres de campo que funcionaban en el sitio antiguo
    $Nombre = strip_tags(trim($_POST["Nombre"]));
    $Celular = strip_tags(trim($_POST["Celular"]));
    $Mensaje = strip_tags(trim($_POST["Texto"]));

    $to = "clasesadomicilio30@gmail.com";
    $subject = "Nuevo envio - Web 2025";

    // El header que funcionaba antes (aunque sea simple, es el que el servidor aceptaba)
    $headers = "From: $Celular";

    $email_body = "Nombre: $Nombre\n\nCelular: $Celular\n\nMensaje:\n$Mensaje";

    // Enviar el correo
    if (mail($to, $subject, $email_body, $headers)) {
        // Redirigir con éxito
        header("Location: ../contacto/?status=success");
    } else {
        // Redirigir con error
        header("Location: ../contacto/?status=error");
    }
    exit();
}
?>