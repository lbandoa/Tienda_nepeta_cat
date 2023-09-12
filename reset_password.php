<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

//usamos dos metodos para enviar
$user_id = $_GET['id'] ?? $_POST ['user_id'] ?? '';
$token = $_GET['token'] ?? $_POST ['token'] ?? '';

if($user_id == '' || $token == ''){
    header("Location: index.php");
    exit;
}


$db = new Database();
$con = $db->conectar();

$errors=[];

if(verificaTokenRequest ($user_id, $token, $con)){
    echo  "No se pudo verificar la informacion";
    exit;
}

if(!empty($_POST)){
    
    $password =trim($_POST['password']);
    $repassword =trim($_POST['repassword']);

    if(esNulo([$user_id, $token,$password,$repassword])){
        $errors[]= "Debe llenar todos los campos";
    }
    
    if (!validaPassword($password, $repassword)) 
    $errors[] = " Las contraseñas no coinciden";
    if(count($errors) == 0){
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);

    }

    if (count($errors)==0){
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if(actualizaPassword($user_id, $pass_hash, $con)){
            echo " Contraseña modificada.<br><a href='login.php'> Iniciar Sesion</a>";
            exit;
        } else{
            $errors[]= " Error al modificar contraseña, Intentalo nuevamente";
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Tienda NepetaCat</title>
        <!-- Favicon-->
        <link rel="icon" type="assets/image/x-icon" href="assets/Imagenes/Logo.PNG" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="assets/css/styles.css" rel="stylesheet" />
    </head>
    <body>

    <?php include 'menu.php' ?> 

        <main class="form-login  m-auto pt-4">
            <h3>Cambiar contraseña</h3>

            <?php mostrarmensajes($errors); ?>

            <form action="reset_password.php" method="post" class="row g-3" autocomplete="off">

                <input type="hidden" name= "user_id" id="user_id" value="<?= $user_id; ?>" />
                <input type="hidden" name= "token" id="token" value="<?= $token; ?>" />

                <div class= "form-floting">
                    <input class="form-control" type="password" name="password" id="password" placeholder="Nueva contraseña" required>
                    <label for="password">Nueva contraseña</label>
                </div>

                <div class= "form-floting">
                    <input class="form-control" type="password" name="repassword" id="repassword" placeholder="Nueva contraseña" required>
                    <label for="repassword">Confirmar contraseña</label>
                </div>

                <div class="d-grid gap-3 col-6">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </div>

                <div class="col-6" >
                    <a href="login.php">Iniciar sesion</a>
                </div>

            </form>
        </main>
    </body>
</html>    
