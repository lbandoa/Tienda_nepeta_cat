<?php

require 'config/database.php';  //requerimiento de archivos
require 'config/config.php';

//conexion a la base de datos
$db= new Database();
$con = $db->conectar();

// se valida si existe la variable de session, se llamara carrito y productos
//en caso de que exista, se recibira y en caso de que no, que sea nulo.
$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
//definimos la variable que sera un arreglo
$lista_carrito = array();

if($productos != null){
    foreach($productos as $clave => $cantidad){
        //pasamos la cantidad a la consulta
        $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE 
        id=? AND activo=1");
        //en la funcion ejecutar, ponemos un arreglo y atribuimos la clave
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}


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

    <?php include 'menu.php'; ?>

        <!--contenido-->
        <main>
            <div class="container">
                <div class="table_responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($lista_carrito == null){
                                echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
                            } else {
                                $total = 0;

                                foreach($lista_carrito as $productos){
                                    $_id = $productos['id'];
                                    $nombre = $productos['nombre'];
                                    $precio = $productos['precio'];
                                    $descuento = $productos['descuento'];
                                    $cantidad = $productos['cantidad'];
                                    $precio_desc = $precio - (($precio * $descuento) /100);
                                    $subtotal = $cantidad * $precio_desc;
                                    $total += $subtotal;
                                ?> 

                            <tr>
                                <td><?php echo $nombre;?></td>
                                <td><?php echo MONEDA . number_format($precio_desc, 2, '.', ',');?></td>
                                <td>
                                    <abbr title="OPRIME PARA AUMENTAR O DISMINUIR LOS PRODUCTOS">
                                    <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)">
                                </td>
                                <td>
                                    <div id="subtotal_<?php echo $_id;?>" name="subtotal[]" >
                                    <?php echo MONEDA . number_format($subtotal, 2, '.', ',');?></div>
                                </td>
                                <td><a id="eliminar" class="btn btn-info btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a></td>
                            </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">
                                        <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                    </td>
                                </tr>
                        </tbody>
                            <?php } ?>
                    </table>
                    <?php if($lista_carrito !== null) { ?>
                        <div class="row">
                            <div class="col-md-5 offset-md-7 d-grid gap-2">
                                <?php if(isset($_SESSION['user_id'])) { ?>
                                    <a href="pago.php" class="btn btn-outline-dark">Realizar pago</a>
                                <?php } else { ?>
                                    <a href="login.php?pago" class="btn btn-outline-dark">Realizar pago</a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <br>
                </div>
            </div>
            <br>
        </main>
        <!-- Modal -->
        <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminaModalLabel">Alerta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro  que deseas eliminar este producto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btn-elimina" class="btn btn-info" type="button" onclick="eliminar()">Eliminar</button>
                </div>
                </div>
            </div>
        </div>
       
        <!-- Footer-->
        <footer class="py-4 bg-dark mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Leonardo y Laura 2023</div>
        </div>
    </div>
    </footer>
        <script>
            let eliminaModal = document.getElementById('eliminaModal')
            //usamos un evento para cuando se muestre el modal
            eliminaModal.addEventListener('show.bs.modal', function(event){
                let button = event.relatedTarget
                let id = button.getAttribute('data-bs-id')
                //se trae el boton por medio de #
                let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
                buttonElimina.value = id 
            })                        

            function actualizaCantidad(cantidad, id){
                //funcion para recargar/actualizar el precio total
                location.reload()
                let url = 'clases/actualizar_carrito.php'
                //Enviaremoa los parametros por metodo POST
                //inicializamos la funcion FormData
                let formData = new FormData()
                formData.append('action', 'agregar')
                formData.append('id', id)
                formData.append('cantidad', cantidad)

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
                        
                        //data.sub, es de la respuesta de la peticion ajax
                        let divsubtotal = document.getElementById('subtotal_' + id)
                        divsubtotal.innerHTML = data.sub

                        let total = 0.00
                        let list = document.getElementsByName('subtotal[]')
                        //recorremos todos los elementos
                        for(let i = 0; i > list.length; i++){
                            total += parseFloat(list[i].innerHTML.replace(/[$,]/g, ''))
                        }
                        
                        //en javascript damos en formato a los numeros para agregar decimales y comas
                        total = new Intl.NumberFormat('en-US', {
                            minimumFractionDigits: 2
                        }).format(total)
                        //traemos el tipo de moneda
                        document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total
                    }

                })
            }  

            function eliminar(){
                
                let botonElimina = document.getElementById('btn-elimina')
                //traemos el valor dinamico que tenemos en el boton, qu eesta en el modal
                let id = botonElimina.value
                let url = 'clases/actualizar_carrito.php'
                let formData = new FormData()
                formData.append('action', 'eliminar')
                formData.append('id', id)
            

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
                        location.reload()

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
