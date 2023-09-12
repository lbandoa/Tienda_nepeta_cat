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
//creamos la varia sql para la consulta
//con esto creamos consultas preparadas
$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
//cuando ejecutamos la db, usamos fetchAll para llamar
//todos los productos de la tabla
//indicamos que los traiga a traves del PDO y el fetch asociativo
//esto trae los productos por nombre de columna
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

//session_destroy();

//linea mirar lo que esta cintabilizando el carrito
//muestra el arreglo de sesión carrito y el otro arrgleo productos
// dentro de este tiene otro arreglo donde estan los id de los productos
// y el valor de cuantas veces se ha agregado.
//print_r($_SESSION);

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
        <!-- Encabezado-->
        <header class="bg-info py-4">
            <div class="container px-2 px-lg-2 my-2">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">NepetaCat</h1>
                    <p class="lead fw-normal text-black mb-0">Porque la paz de tu gato es más importante</p>
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <!--Usamos foreach porque es bucle que permite recorrer estructuras que contienen varios elementos
                        llamamos a la va riable resultado qeu tiene todos los productos y la variabe row para qeu traiga las columnas-->
                   <?php foreach($resultado as $row) { ?>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <?php
                            // se crea variable id con row y se trae de la db el id del producto que genera el foreach
                            $id =$row['id'];
                            $imagen = "assets/Imagenes/productos/" . $id . "/Principal.jpg";

                            if (!file_exists($imagen)) {
                                $imagen = "assets/Imagenes/no-photo.png";
                            }
                            
                            ?>
                            <!-- Product image-->
                            <img src="<?php echo $imagen; ?>"/>
                            <!-- Sale badge-->
                            <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Disponible</div>
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <!--Cambiamos el titulo de manera dinamica-->
                                    <h5 class="fw-bolder"><?php echo $row['nombre']; ?></h5>
                                    <!-- Product price-->
                                    <span class="text-muted text-decoration-line-through"></span>
                                    <!--Se usa una funcion number_format para dar formato al precio-->
                                    <?php echo number_format($row['precio'], 2, '.', ','); ?>
                                </div>
                            </div>

                            <!--Details product-->
                            <div class="text-center">
                                <!--Enviamos el id y agregamos por medio del echo la col id.
                                el '&' lo usamos par aindicar que vamos usar otra variable.
                                usamos la funcion hash_hmac que permite cifrar informacion mediante una contraseña,
                                permite tomar un dato y agreagrle una contraseña y cifrarla para que al use en el otro lado
                                y compara si el tken es correcto -->
                                <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo 
                                hash_hmac('sha1', $row['id'], KEY_TOKEN);?>" class="btn btn-outline-dark mt-4">Detalles</a>
                            </div>
                            <br>
                            <!-- Product actions-->
                            <abbr title="PARA COMPRAR ESTE PRODUCTO, INICIA SESIÓN O REGISTRATE">
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                    <div class="text-center">
                                        <button class="btn btn-outline-dark " type="button" 
                                        onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN);?>')" > Agregar al carrito </button>
                                    </div>
                                </div>
                            </abbr>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        </section>
        <!-- Footer-->
        <footer class="py-4 bg-dark mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Leonardo y Laura 2023</div>
        </div>
    </div>
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
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
