<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if(!empty($_POST)){
    
    $nombres =trim($_POST['nombres']);
    $apellidos =trim($_POST['apellidos']);
    $email =trim($_POST['email']);
    $telefono =trim($_POST['telefono']);
    $documento =trim($_POST['documento']);
    $usuario=trim($_POST['usuario']);
    $password =trim($_POST['password']);
    $repassword =trim($_POST['repassword']);


    //hacemos las vaidaciones del servidor
    if(esNulo([$nombres, $apellidos, $email, $documento, $usuario, $password, $repassword])){
        $errors[]= "Debe llenar todos los campos";
    }

    if(!esEmail($email)){
        $errors[] = "La direción de correo no es válida";

    }

    if (!validaPassword($password, $repassword)){
        $errors[] = " Las contraseñas no coinciden";
    }

    if (UsuarioExiste($usuario, $con)){
        $errors[]= " El nombre del usuario $usuario ya existe";
    }

    if (emailExiste($email, $con)){
        $errors[]= " El correo electronico $email ya existe";
    }

    if (count($errors)== 0){
        $id = registraCliente([$nombres, $apellidos, $email, $telefono, $documento], $con);

        if($id > 0){

            require 'clases/Mailer.php';
            $mailer = new Mailer;
            $token= generarToken();
            $pass_hash =password_hash($password, PASSWORD_DEFAULT);
            
            $idUsuario = registraUsuario([$usuario, $pass_hash, $token, $id], $con);
            if($idUsuario > 0){

                $url = SITE_URL . '/activa_cliente.php ?id='. $idUsuario . '&token='.$token;
                $asunto ="Activar cuenta";
                $cuerpo = "Estimado $nombres: <br> Para continuar con el proceso es indispensable dar click en el siguiente enlace <a href='$url'>Activar cuenta</a>";
                
                if($mailer->enviarEmail($email, $asunto, $cuerpo)){
                    echo "Para terinar el proceso de registro, siga las instrucciones que se le enviaron al coreeo electronico $email";

                    exit;
                }
                
            }else {
                $errors[] = "Error al registrar usuario";
            }
        } else {
            $errors[] = "Error al registrar cliente"; 
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
        
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <img src="assets/Imagenes/Logo.PNG" alt="" width="60" height="60">
                <a class="navbar-brand" href="#!">NepetaCat</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Productos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!"></a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link " id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#!"></a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="#!"></a></li>
                                <li><a class="dropdown-item" href="#!"></a></li>
                            </ul>
                        </li>
                    </ul>
                    <a href="checkout.php" class="btn btn-outline-dark">
                        <i class="bi-cart-fill me-1"></i>
                        Carrito
                        <span id="num_cart" class="badge bg-dark text-white ms-1 rounded-pill"><?php echo $num_cart; ?></span>
                    </a>
                </div>
            </div>
        </nav>
        <!-- Section-->
        <main>
            <div class="container">
                <h2>Datos de usuario</h2>

                <?php mostrarMensajes($errors); ?>
                
                <form class="row g-3"action="registro.php" method="post" autocomplete="off">
                    <div  class="col-md-6">
                        <label for="nombres"><span class="text-danger">*</span>Nombres</label>
                        <input type="text" name="nombres" id="nombres" class="form-control" requireda>
                    </div>
                    <div  class="col-md-6">
                        <label for="apellidos"><span class="text-danger">*</span>Apellidos</label>
                        <input type="text" name="apellidos" id="apellidos" class="form-control"requireda>
                    </div>
                    <div  class="col-md-6">
                        <label for="email"><span class="text-danger">*</span>Email</label>
                        <input type="Email" name="email" id="email" class="form-control"requireda>
                        <span id="validaEmail" class="text-danger"></span>
                    </div>
                    <div  class="col-md-6">
                        <label for="telefono"><span class="text-danger">*</span>Telefono</label>
                        <input type="tel" name="telefono" id="telefono" class="form-control"requireda>
                    </div>
                    <div  class="col-md-6">
                        <label for="documento"><span class="text-danger">*</span>Documento de identidad</label>
                        <input type="text" name="documento" id="documento" class="form-control" requireda>
                    </div>
                    <div  class="col-md-6">
                        <label for="usuario"><span class="text-danger">*</span>Usuario</label>
                        <input type="text" name="usuario" id="usuario" class="form-control" requireda>
                        <span id="validaUsuario" class="text-danger"></span>
                    </div>
                    <div  class="col-md-6">
                        <label for="password"><span class="text-danger">*</span>Contraseña</label>
                        <input type="password" name="password" id="pasword" class="form-control" requireda>
                    </div>
                    <div  class="col-md-6">
                        <label for="repassword"><span class="text-danger">*</span>Confirmar contraseña</label>
                        <input type="password" name="repassword" id="repassword" class="form-control" requireda>
                    </div>
                    <i><b>Nota:</b> Los campos con aterisco son obligatorios</i>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-5 ">
                            <button type="submit" class="btn btn-info"> <span><i class="bi bi-unlock-fill"></i></span>Registrarse</button>
                        </div>
                    </div>
                </form>
            </div>
        </main>

        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>


        <script>
        let txtUsuario = document.getElementById('usuario')
        txtUsuario.addEventListener("blur",function(){
            existeUsuario(txtUsuario.value)

        }, false)

        let txtEmail =document.getElementById('email')
        txtEmail.addEventListener("blur",function(){
            existeEmail(txtEmail.value)

        }, false)

        function existeUsuario(Usuario){

        let url = "clases/clienteAjax.php"
        let formData = new FormData()
        formData.append("action","existeUsuario")
        formData.append("usuario", usuario)

        //API que permite hacer peticiones
        //es de javascript
        fetch(Url,{
            method: 'POST',
            body:formData
        }).then(response => response.json())
        .then(data => {

            if(data.ok){
            document.getElementById('usuario').value=''
            document.getElementById('validaUsuario').innerHTML='El usuario ya se encuentra registrado '
            } else {
                document.getElementById('validaUsuario').innerHTML='' 
            }
        })

        }

        function existeEmail(email){

            let url = "clases/clienteAjax.php"
            let formData = new FormData()
            formData.append("action","existeEmail")
            formData.append("email",email)
            fetch(Url,{
                method: 'POST',
                body:formData
            }).then(response => response.json())
            .then(data=>{

                if(data.ok){
                   document.getElementById('email').value=''
                   document.getElementById('validaEmail').innerHTML='El Email ya se encuentra registrado'
                } else {
                    document.getElementById('validaEmail').innerHTML='' 
                }
            })

        }
    </scrip>
    </body>
</html>   