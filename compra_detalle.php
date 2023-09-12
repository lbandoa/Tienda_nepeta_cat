<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();


$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$orden = $_GET['token'] ?? null;

if($orden == null || $token == null || $token !=$token_session){
    header('Location: compras.php');
    exit;
}

$sqlCompra =$con->prepare("SELECT id,id_transaccion, fecha, total FROM compra WHERE id_transaccion=? LIMIT 1");
$sqlCompra->execute([$orden]);
$rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);
$idCompra = $rowCompra['id'];

$sqlDetalle=    $con->prepare("SELECT id, nombre, precio, cantidad FROM detalle_compra WHERE id_compra=? ");
$sqlDetalle->execute([$idCompra]);


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
    <main>
        <div class="container">
            <div class="col-12 col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <strong>Detalle de la compra</strong>
                    </div>
                    <div class="card_body">
                        <p><strong>Fecha:</strong><?php echo $rowCompra['fecha'] ?></p>
                        <p><strong>Fecha:</strong><?php echo $rowCompra['id_transaccion'] ?></p>
                        <p><strong>Fecha:</strong><?php echo MONEDA . ' ' . number_format($rowCompra['total'], 2, '.', ','); ?></p>
                    </div>
                </div>
            </div>
            <div calss="col-12 col-md-8">
                <div class="table-responsive">
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
                            <?php 
                            while($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)){ 
                                $precio = $row['precio'];
                                $cantidad = $row['cantidad'];
                                $subtotal = $precio * $cantidad;
                                ?>
                                 <tr>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td><?php echo MONEDA . ' ' . number_format($precio, 2, '.', ','); ?></td>
                                    <td><?php echo $cantidad; ?></td>
                                    <td><?php echo MONEDA . ' ' . number_format($subtotal, 2, '.', ','); ?></td>

                                 </tr>   

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            </div>
        </div>
        
    </main>

    </body>
</html>    