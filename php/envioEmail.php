<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Nombre = $_POST["Nombre"];
    $Celular = $_POST["Celular"];
    $Mensaje = $_POST["Texto"];

    $to = "clasesadomicilio30@gmail.com";  // Correo a donde se envian los formularios
    $subject = "Nuevo envio";
    $headers = "From: $Celular";

    // El cuerpo del correo electronico que llegara a la bandeja de estrada
    $email_body = "Nombre: $Nombre\n\nCelular: $Celular\n\nMensaje:\n$Mensaje";

    // Esto es lo que envia el correo
    mail($to, $subject, $email_body, $headers);

    // Redirecciona hacia la pagina despues de que se envie el formulario
    // Ajustado para que vuelva a la página de contacto con el aviso de éxito
    header("Location: https://profesordematematica.com.co/contacto/?status=success");
    exit();
}
?>