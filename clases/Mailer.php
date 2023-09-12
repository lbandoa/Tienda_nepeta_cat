<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


Class Mailer
{

    function enviarEmail($email, $asunto, $cuerpo)
    {
    
        require_once './config/config.php';
        require './phpmailer/src/PHPMailer.php';
        require './phpmailer/src/SMTP.php';
        require './phpmailer/src/Exception.php';

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host        = MAIL_HOST;      //configura el servicio
            $mail->SMTPPAuth   =true;        // Habilita la autenticacion
            $mail->Username    =MAIL_USER;  // Usuario SMTP  
            $mail->Username    =MAIL_PASS;   // Contraseña SMTP
            $mail->SMTPSecure  =PHPMailer::ENCRYPTION_SMTPS; //Habilitar el cifrado
            $mail->port        =MAIL_PORT;    // Puerto TCP al que conectarse,si usa 587                                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // correo emisor y nombre
            $mail->setfrom(MAIL_USER, 'CDP');
            // CORREO RECEPTOR Y NOMBRE
            $mail->addAddress($email);
            // enviar copia de correo
            $mail->addReplyTo($email);

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $asunto;
        
            $mail->Body = utf8_decode($cuerpo);
            //$mail->setLenguage('es', '/phpmailer/language/phpmailer.lang-es.php');
            

            //Enviar correo
            if ($mail->send()){
                return true;
            } else { 
                return false;
            }
        }catch (Exception $e){
                echo " No se pudo enviar mensaje, error en el envio:{$mail->ErrorInfo}";
                return false;
            }
        
    }
        
    
}