<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$proceso = isset($_GET['pago']) ? 'pago' : 'login';

$errors=[];

if(!empty($_POST)){
    
    $usuario=trim($_POST['usuario']);
    $password =trim($_POST['password']);
    $proceso = $_POST['proceso'] ?? 'login';

    if(esNulo([$usuario,$password])){
        $errors[]= "Debe llenar todos los campos";
    }
    if (count($errors) == 0){
        $errors[] = login($usuario, $password, $con, $proceso);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inicio de sesión</title>
        <link rel="stylesheet" href="assets/public/css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="assets/public/css/bootstrap/bootstrap-icons.css">
        <link rel="stylesheet" href="assets/public/css/style.css">
    </head>
    <style>
        body{

            background-image: url(assets/Imagenes/gigi.jpeg);
            background-position: left;
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }
    </style>
    <body>
        <main>

            <?php mostrarMensajes($errors); ?>

            <form  class="row g-3"action="login.php" method="post" autocomplete="off">

                <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">
                
                <div class=" white py-5 d-flex justify-content-center ">
                    <div class="col-lg-4">
                        <div class="card text-center borde-elegante">
                            <div class="card-header text-bg-primary">
                                <h5>Iniciar sesión </h5>
                            </div>
                            <div class="card-body text-bg-info">
                                <div class="primary">
                                    <div class="col-lg-12">
                                        <label for="usuario" class="d-flex flex-row">Ingrese usuario</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text text-bg-primary" id="basic-addon1"><i class="bi bi-envelope-fill"></i></span>
                                            <input type="text" name="usuario" id="usuario" class="form-control control-focus" placeholder="Usuario" requireda>
                                        </div>
                                    </div>
                                </div>
                                <div class="primary">
                                    <div class="col-lg-12">
                                        <label for="password" class="d-flex flex-row">Ingrese Contraseña</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text text-bg-primary" id="basic-addon1"><i class="bi bi-key-fill"></i></span>
                                            <input type="password"  name="password" id="password" class="form-control control-focus" placeholder="contraseña" requireda>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="form-control btn btn-primary btn-lg btn-block"> <span><i class="bi bi-unlock-fill"></i></span> Ingresar</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                ¿Olvido su contraseña ? 
                                <a href="recupera.php">Recuperar contraseña</a>
                            </div>
                            <div class="col-12" >
                                ¿No tiene cuenta? <a href="Registro.php"> Registrate aqui</a>
                            </div>
                        </div>
                    </div>
                </div> 
            </form>
        </main>
    </body>    
</html>


