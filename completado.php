<?php

require 'config/database.php';  
require 'config/config.php';

$db= new Database();
$con = $db->conectar();

$id_transaccion = isset($_GET['key']) ? $_GET['key']: '0';

$error = '';
if($id_transaccion == ''){
    $error ='Error al procesar peticion';
}else{
    $sql = $con->prepare("SELECT count(id) FROM compra WHERE id_transaccion=? AND status=?");
        $sql->execute([$id_transaccion, 'COMPLETED']);
        if($sql->fetchColumn() > 0){
            $sql = $con->prepare("SELECT id, fecha, email, total FROM compra WHERE id_transaccion=? AND status=?
            LIMIT 1");
            $sql->execute([$id_transaccion, 'COMPLETED']);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            
            $idCompra =$row['id'];
            $total =$row['total'];
            $fecha =$row['fecha'];

            $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra=?");
            $sqlDet->execute([$idCompra, 'COMPLETED']);
            
        } else{
            $error='Error al comprobar la cuenta';
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
                <img src="assets/Imagenes/Logo.PNG" alt="" width="30" height="30">
                <a class="navbar-brand" href="index.php">NeptaCat pago</a> 
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                </div>
            </div>
                <a href="carrito.php" class="btn btn-outline-dark">
                    <i class="bi-cart-fill me-1"></i>
                    Carrito
                    <span id="num_cart" class="badge bg-dark text-white ms-1 rounded-pill"><?php echo $num_cart; ?></span>
                </a>
        </nav>
        <!--contenido-->
        <main>
            <div class="container">
                <?php if(strlen($error) > 0){ ?>
                    <div class="row">
                        <div class="col">
                            <h3><?php echo $error; ?></h3>

                        </div>
                    </div>
                    
                    <?php } else {?>
        
                    <div class="row">
                        <div class="col">
                            <b>Folio de la compra: </b><?php echo $id_transaccion; ?><br>
                            <b>Fecha de la compra: </b><?php echo $fecha; ?><br>
                            <b>Totak: </b><?php echo MONEDA . number_format($total, 2, '.', ',');?><br>
                        </div>
                    </div>
                    < class="row">
                        <div class="col">
                            <table class="table"></table>
                            <thead>
                            <tr>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Importe</th>
                            </tr>
                            </thead>
                           
                            <tbody>
                                <?php while($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)){ 
                                    $importe = $row_det['precio'] = $row_det['cantidad']; ?>
                                    <tr>
                                        <td><?php echo $row_det['cantidad']?></td>
                                        <td><?php echo $row_det['nombre']?></td>
                                        <td><?php echo $importe?></td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </main>
    </body>
</html>