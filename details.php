<?php
//llamamos la db con require
require 'config/database.php';
//Se enlaza el archivo de configuraciones
//aqui esta el token de cifrado
require 'config/config.php';
//declaramos una variable para llamar la clase e instanciarla
$db= new Database();
//creamos la variable con
//para llamar a la funcion conectar
$con = $db->conectar();
//Agregamos el metodo GET, ay que har apor url
//usamos un isset para realizar la validacion
//si el metodo get esta definido, lo reciba, si no 
//con los dos puntos se coloca un dato predefinido
//esto para indicar que si no se toma el valor, envie ese ato predefinido y no genere error.
$id = isset($_GET['id']) ? $_GET['id']: '';
$token = isset($_GET['token']) ? $_GET['token']: '';
//se realiza un avalidacion de ambos datos
//mientra qie id sea igual igual a vacio ó lo mismo para el token
//esto para que en el echo salga elmensaje de error
//el exit es para que no siga el error
if ($id == ''|| $token == '' ) {
    echo 'Upss! hay un error al procesar';
    exit;
//procesamiento del token
// se valida si el token que usario esta enviando es igual al token generado
//en caso de si, normal, pero si no, genrara error
} else {

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if($token == $token_tmp){ 
        //Usamos la siguiente consulta para averiguar si existe el producto.
        //realizamos un count para validar si existe el producto
        //en el execute enviamos el id por medio de un arreglo y la variable
        //esto para hacer el filtro por medio de preparacion
        //la ejecucion la realizamos por medio del if, si es mayor a 0 encuentra el dato
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);
        if($sql->fetchColumn() > 0){
            //usamos un limit 1 para que solo triaga un valor
            $sql = $con->prepare("SELECT nombre,descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 
            LIMIT 1");
            $sql->execute([$id]);
            //ejecutamos con el row y asociamos
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $dir_imagenes = 'assets/Imagenes/productos/' . $id .'/';

            $rutaImg = $dir_imagenes . 'Principal.jpg';

            if(!file_exists($rutaImg)){
                $rutaImg = 'assets/Imagenes/no-photo.jpg';
            }

            $imagenes = array();
            if(!file_exists($dir_imagenes)){
            $dir = dir($dir_imagenes);

            while(($archivo = $dir->read())!=false) {
                if($archivo != 'Principal.jpg' && (strpos($archivo, 'jpg') || (strpos($archivo, 'jpeg')))){
                    $imagenes[] = $dir_imagenes . $archivo;
                    
                }
            }
            $dir->close();
        }
    }
    } else {
        echo 'Error al procesar'; 
        exit;
    }

}
//creamos la varia sql para la consulta
//con esto creamos consultas preparadas
$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
//cuando ejecutamos la db, usamos fetchAll para llamar
//todos los productos de la tabla
//indicamos que los traiga a traves del PDO y el fetch asociativo
//esto trae los productos por nombre de columna
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

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
                <img src="assets/Imagenes/Logo.PNG" alt="" width="30" height="30">
                <a class="navbar-brand" href="#!">NepetaCat</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                </div>
            </div>
                <a href="checkout.php" class="btn btn-outline-dark">
                    <i class="bi-cart-fill me-1"></i>
                    Carrito
                    <span id="num_cart" class="badge bg-dark text-white ms-1 rounded-pill"><?php echo $num_cart; ?></span>
                </a>
        </nav>
        <!--contenido-->
        <main>
            <div class="container" >
                <div class="row">
                    <div class="col-md-6 order-md-1">
                        <div id="carouselImagenes" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="<?php echo $rutaImg?>" class="d-block w-1000">
                                </div>

                                <?php foreach ($imagenes as $img) { ?>
                                    <div class="carousel-item">
                                        <img src="<?php echo $img?>" class="d-block w-100">
                                    </div>
                                    
                                <?php } ?>
                                
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselImagenes" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselImagenes" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 order-md-2">
                        <h2><?php echo $nombre ?></h2>

                        <!--realizamos una validacion para el descuento-->
                        <?php if($descuento > 0) { ?>
                            <p><del><?php echo MONEDA . number_format($precio, 2, '.', ',');?></del></p>
                            <h2>
                                <?php echo MONEDA . number_format($precio_desc, 2, '.', ',');?>
                            <samll class="text-success"><?php echo $descuento; ?> % descuento</samll>
                            </h2>

                            <?php } else { ?>

                            <h2><?php echo MONEDA . number_format($precio, 2, '.', ',');?></h2>
                        <?php } ?>
                        
                        <p class="lead">
                            <?php echo $descripcion; ?>
                        </p>
                        <br>
                        <div class= "d-grip gap-3 col-15 mx-auto">
                            <button class="btn btn-primary" type="button">Comprar ahora</button>
                            <button class="btn btn-outline-dark " type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>' )" > Agregar al carrito </button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </main>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2021</p></div>
        </footer>

        <script>
            function addProducto(id, token){
                let url = 'clases/carrito.php'
                //Enviaremoa los parametros por metodo POST
                //inicializamos la funcion FormData
                let formData = new FormData()
                formData.append('id', id)
                formData.append('token', token)

                //para enviar la url
                // trabajamos con la API fetch
                //configuramos la peticion AJAX y ya se envia con los datos
                //mediante un metodo POST
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => { //aca podemos acceder a los elementos que se estan usando en el carrito
                    if(data.ok){
                        let elemento = document.getElementById("num_cart")
                        elemento.innerHTML = data.numero
                    }

                })
            }  
        </script>

        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
         

    </body>
</html>