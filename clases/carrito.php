<?php
//para llamar la configuracion del token
require '../config/config.php';

//realizamos un avalidacion
//para saber si se esta enviando una variable por el metodo POST
if (isset($_POST['id'])) {

    $id = $_POST['id'];
    $token = $_POST['token'];

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if($token == $token_tmp){
        //usamos una variable de seccion
        //aca tenemos el prodcuto 1 con 1 cantidad
        //validaresmos si el elemento ya existe, si ya existe
        //indicaremos que agregue 1 más
        //se deja predeterminado con 1 y si el usuario dese más productos
        //sigue apregando al carrito mediante le boton agregar.
        if(isset($_SESSION['carrito']['productos'][$id])){
            $_SESSION['carrito']['productos'][$id] += 1;
        } else {
            $_SESSION['carrito']['productos'][$id] = 1;
        }
         //para contabilizar los productos almacenados
         //en este caso datos ok para a ser verdadero
        $datos['numero'] = count($_SESSION['carrito']['productos']);
        $datos['ok'] = true;

    } else {
        $datos['ok'] = false;
    }
} else {
    $datos['ok'] = false;
}

//regresamos el fomato
echo json_encode($datos);