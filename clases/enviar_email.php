<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once './config/config.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->isSMTP();
    $mail->Host        = MAIL_HOST;      //configura el servicio
    $mail->SMTPPAuth   =true;        // Habilita la autenticacion
    $mail->Username    =MAIL_USER;  // Usuario SMTP  
    $mail->Username    =MAIL_PASS;   // Contraseña SMTP
    $mail->SMTPSecure  =PHPMailer::ENCRYPTION_SMTPS;; //Habilitar el cifrado
    $mail->port        =MAIL_PORT;    // Puerto TCP al que conectarse,si usa 587                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('kinverli59@gmail.com', 'Nepetacat soprte');
    $mail->addAddress('tiendanepetacat@gmail.com', 'Joe User');     //Add a recipient
 
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Detalles de su compra';

    $cuerpo = '<h4>Gracias por su compra</h4>';
    $cuerpo = '<p>El ID de su compra es <br>' . $id_transaccion . '</b></p>';

    $mail->setLenguage('es', '../phpmailer/language/phpmailer.lang-es.php');
    
    $mail->Body    = utf8_decode($cuerpo);
    $mail->AltBody = 'Enviamos los detalles de su compra';

    $mail->send();
} catch (Exception $e) {
    echo "Error al realizar el envio del correo electronico: {$mail->ErrorInfo}";
}