<?php
require 'config/database.php';  
require 'config/config.php';

$db= new Database();
$con = $db->conectar();


$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
$lista_carrito = array();

if($productos != null){
    foreach($productos as $clave => $cantidad){
        
        $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    } 
    
} else {
    header("Location: index.php ");
    exit; 
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

        <?php include 'menu.php'; ?>
        
        <!--contenido-->
        <main>
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <h4>Detalles de pago</h4>
                        <div id="paypal-button-container"></div>

                    </div>
                    <div class="col-6">
                    <div class="table_responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
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
                                <td>
                                    <div id="subtotal_<?php echo $_id;?>" name="subtotal[]" >
                                    <?php echo MONEDA . number_format($subtotal, 2, '.', ',');?></div>
                                </td>
                            </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2">
                                        <p class="h3 text-end" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                    </td>
                                </tr>
                        </tbody>
                            <?php } ?>
                    </table>
                    <br>
                </div>
            </div>
            <br>
        </main>
        <!-- Footer-->
        <footer class="py-3 bg-dark">
            <div class="container-end"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2021</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>

        <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID ?>&currency=<?php echo CURRENCY ?>"></script>
        <script>
        paypal.Buttons({
            style:{
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            }, 
            createOrder: function(data, actions){
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: <?php echo $total; ?>
                        }
                    }]
                });
            },
            onApprove: function(data, actions){
                let URL = 'clases/captura.php'
                actions.order.capture().then(function(detalles){
                    console.log(detalles)
                    //window.location.href="completado.html"

                    let url = 'clases/captura.php'

                    return fetch(url, {
                        method: 'post',
                        headers:{
                            'content-type': 'application/json'
                        },
                        body: JSON.stringify({
                            detalles: detalles
                        })
                    }).then(function(response){
                        window.location.href="completado.php?key" + detalles['id'];
                    })
                });
            },
            onCancel: function(data) {
                alert("Pago cancelado");
                console.log(data);
            }
        }).render('#paypal-button-container');
    </script>
    </body>
</html>
