<?php
require_once '../config/database.php';
require_once 'clienteFunciones.php';

$datos = [];

if(isset($_POST['action'])){
    $action = $_POST['action'];

    if ($action =='existeUsuario'){
        $db = new Database();
        $con = $db-> conectar ();
        $datos ['ok'] = usuarioExiste($_POST['usuario'],$con);  
    }else if ($action = 'existeEmail') {
        $datos['ok'] = emailExiste($_POST['email'], $con);
    }
}

echo json_encode($datos);