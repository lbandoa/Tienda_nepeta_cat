<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$token = generarToken();
$_SESSION['token'] = $token;

$idCliente = $_SESSION['user_cliente'];

$sql = $con->prepare("SELECT id_transaccion, fecha, status, total FROM compra WHERE id_cliente = ?");
$sql->execute([$idCliente]);

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
            <h4>Mis compras</h4>
            <hr>

            <?php while($row = $sql->fetch(PDO::FETCH_ASSOC)){ ?>

            <div class="card mb-5">
            <div class="card-header">
                <?php echo $row['fecha']; ?>
            </div>
                <div class="card-body border_dark ">
                    <h5 class="card-title">Folio: <?php echo $row['id_transaccion']; ?> </h5>
                    <p class="card-text">Total: <?php echo $row['total']; ?></p>
                    <a href="compra_detalle.php?orden=<?php echo $row['id_transaccion']; ?>$token=<?php echo $token; ?>" class="btn btn-primary">Ver compra</a>
                </div>
            </div>
            <?php } ?>
        </div>
    </main>

    </body>
</html>    