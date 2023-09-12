<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors=[];

if(!empty($_POST)){
    
    $email = trim($_POST['email']);

    if(esNulo([$email])){
        $errors[]= "Debe llenar todos los campos";
    }
 
    if(!esEmail($email)){
        $errors[] = "La dirrecion de correo es incorrecta";

    }

    if(count($errors)==0 ){
        if(emailExiste($email, $con)){                                                    //INNER JOIN que me indica si el usuario esta asociado al cliente
            $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios  INNER JOIN clientes  ON usuarios.id_cliente=clientes.id WHERE clientes.email LIKE 1");
            $sql->execute([$email]);
            $row = $sql->fetch(PDO:: FETCH_ASSOC);
            $user_id = $row['id'];
            $nombres = $row['nombres'];

           $token= solicitaPassword($user_id, $con);

           if($token !== null){
            require 'clases/Mailer.php';
            $mailer = new Mailer();

                $url = SITE_URL. '/reset_password.php?id= '.'&token=' . $token;
            
                $asunto = "Recuperar password- Tienda online";
                $cuerpo= "Estimado $nombres:<br> Si ha solicitado cambiar tu contraseña 
                es necesario dar click en el siguinte link <a href='$url'>$url</a>.";
                $cuerpo="<br> si no hiciste esta solicitud puedes ignorar este correo";


                if ($mailer->enviarEmail($email, $asunto, $cuerpo)){
                    echo"<p><b> Correo enviado</b></p>";
                    echo"<p> Hemos enviado un correo electronico a la direccion $email para restablecer la contraseña </p>";

                    exit;
                } 
            }  else {
                $errors[] = "Error al registrar Cliente"; 
            }
        } else {
            $errors[]="No existe  una cuenta asociada a esta direccion de correo ";
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
            <h3> Recuperar contraseña </h3>

            <?php mostrarmensajes($errors); ?>

            <form action="recupera.php" method="post" class="row g-3" autocomplete="off">

                <div class= "form-floting">
                <input class="form-control" type="email" name="email" id="email" placeholder="Correo Electronico" required>
                <label for="email"> Correo Electrónico </label>
                </div>

                <div class="d-grid gap-3 col-12">
                    <button type="submit" class="btn btn-primary"> Solicitar </button>
                </div>

                <div class="col-12" >
                    ¿No tiene cuenta? <a href="Registro.php"> Registrate aqui</a>
                </div>

            </form>
        </main>
    </body>
</html>