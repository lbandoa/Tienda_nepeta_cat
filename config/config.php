<?php

//Configuracion del sistema
define ("SITE_URL", "http://localhost");
//Token para cifrar la informacion
define("CLIENT_ID", "AXLwkzP1nvRnhBGlMOovoNAjTvS3tA9DM0CENO0LzWAel6JaJC2ermBN2vWZE16nPlQ-8prCmqMD91OP");
define("CURRENCY", "USD");
define("KEY_TOKEN", "APR.wqc-354*");
define("MONEDA", "$");

//Datos para correo electronico
define("MAIL_HOST", "smtp.gmail.com");
define("MAIL_USER", "kinverli59@gmail.com");
define("MAIL_PASS", "ivwbuhjzfnvymhce");
define("MAIL_PORT", "465");




//para indicar inciaio de sesion
session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}
?>

