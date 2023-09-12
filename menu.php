<?php ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <img src="assets/Imagenes/Logo.PNG" alt="" width="60" height="60">
                <a class="navbar-brand" href="index.php">NepetaCat</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!"></a></li>
                            <li class="nav-item"><a class="nav-link" href="#!"></a></li>
                            <li class="nav-item dropdown">
                                <!--<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>-->
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#!"></a></li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li><a class="dropdown-item" href="#!"></a></li>
                                    <li><a class="dropdown-item" href="#!"></a></li>
                                </ul>
                            </li>
                        </ul>
                    <a href="checkout.php" class="btn btn-outline-dark btn_sm me-2">
                        <i class="bi-cart-fill me-1"></i>
                            Carrito
                        <abbr title="CARRITO DE COMPRAS">
                        <span id="num_cart" class="badge bg-dark text-white ms-1 rounded-pill"><?php echo $num_cart; ?></span>
                    </a>

                    <?php if(isset($_SESSION['user_id'])){ ?>
                        <div class="dropdown me-2">
                        <abbr title="OPRIME PARA CERRAR SESIÓN">
                        <button class="btn btn-success btn-md dropdown-toggle" type="button" id="btn_session" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['user_name']; ?></a>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btn_session">
                            <li><a class="dropdown-item" href="compras.php">Mis compras</a></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        </ul>
                        </div>
                    <!-- <a href="#" class="btn btn-success"> <i class="bi bi-person-circle"></i> <?php echo $_SESSION['user_name']; ?></a>-->
                    <?php } else { ?>
                        <a href="login.php" class="btn btn-dark btn_sm me-2"><i class="bi bi-person-circle me-2"></i>Iniciar sesión</a>
                    <?php } ?>
                    <div class="button">
                        <abbr title="INGRESO SOLO PARA USARIOS AUTORIZADOS">
                        <a href="admin/index.php" class="btn btn-success btn-md">
                            <i class="bi bi-person-circle">Admin</i></a>
                        </a>
                </div>
            </div>
        </nav>
     