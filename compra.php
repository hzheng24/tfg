<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Focus</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">
    <link href="img/favi.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .error {
            color: red;
            font-size: 0.9em;
        }

        .hidden {
            display: none;
        }
    </style>
    <script>
        function metodopago() {
            var metodopago = document.querySelector('select[name="pago"]').value;
            var tarjeta = document.getElementById('tarjeta');
            var paypal = document.getElementById('paypal');

            if (metodopago === 'tarjeta') {
                tarjeta.classList.remove('hidden');
                paypal.classList.add('hidden');
            } else if (metodopago === 'paypal') {
                tarjeta.classList.add('hidden');
                paypal.classList.remove('hidden');
            } else {
                tarjeta.classList.add('hidden');
                paypal.classList.add('hidden');
            }
        }
    </script>
</head>

<body>
    <div class="container-fluid">
        <div class="row bg-primary py-2 px-xl-5">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark" href="">FAQs</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="">AYUDA</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="">SOPORTE</a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark px-2" href="https://www.facebook.com" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-dark px-2" href="https://www.twitter.com" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-dark px-2" href="https://www.linkedin.com" target="_blank">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="text-dark px-2" href="https://www.instagram.com" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-dark pl-2" href="https://www.youtube.com" target="_blank">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="./index.php" class="text-decoration-none">
                    <img src="./img/imgindex/logof2.png" style="width: 50%;">
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left">
                <form action="buscador.php" method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Buscar productos" name="busqueda">
                        <div class="input-group-append">
                            <button type="submit" class="btn bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 col-6 text-right">
                <a href="./carrito.php" class="btn border">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    <span class="badge"></span>
                </a>
            </div>
        </div>
    </div>
    <?php
    if (isset($_SESSION["usuario"])) {
    ?>
        <p>Hola, <?php echo $_SESSION["usuario"]; ?></p>
        <a href="perfil.php">Perfil</a>
        <a href="cerrarsesion.php">Cerrar Sesión</a>
    <?php
    } else {
        echo '<a href="./iniciarsesion.php" class="nav-item nav-link">Iniciar Sesión</a>';
        echo '<a href="./registro.php" class="nav-item nav-link">Registrarse</a>';
    }
    ?>
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center text-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Realizar compra</h1>
        </div>
    </div>
    <?php
    include 'productos_functions.php';
    include 'conexion.php';
// Eliminar producto entero del carrito
if (isset($_POST['eliminar']) && isset($_POST['producto_id'])) {
    $producto_id = $_POST['producto_id'];

    // Verificar el carrito_id del usuario al que pertenece
    if (isset($_SESSION['usuario'])) {
        $usuario = $_SESSION['usuario'];
        $sql = "SELECT carrito_id FROM carrito WHERE usuario = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $carrito_id = $row['carrito_id'];
        }
        mysqli_stmt_close($stmt);

        // Eliminar el producto del carrito
        $sql = "DELETE FROM producto_carrito WHERE carrito_id = ? AND producto_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "<script type='text/javascript'>alert('Error');</script>";
            echo "<script>window.location.href='index.php'</script>";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "is", $carrito_id, $producto_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Redirigir de nuevo a la página del carrito
        echo "<script>window.location.href='carrito.php'</script>";
        exit();
    }
}

    if (isset($_SESSION['usuario']) && isset($_POST['comprar'])) {
        $usuario = $_SESSION['usuario'];
        $categoria = 'CAMISA';
        $tipoPersona = 'H';
        $productos = obtenerProductosPorCategoriaYTipo($tipoPersona, $categoria);
        // Verificar el carrito_id del usuario al que pertenece
        $sql = "SELECT * FROM carrito WHERE usuario = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "<script type='text/javascript'>alert('Error');</script>";
            echo "<script>window.location.href='carrito.php'</script>";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $carrito_id = $row['carrito_id'];
        }
        mysqli_stmt_close($stmt);
        $micarrito = micarrito($carrito_id);
    ?>
        <div class="col-md-12">
            <div class="row pb-3">
                <?php
                $preciototal = 0;
                $preciototal2 = 0;
                foreach ($micarrito as $producto) {
                ?>

                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <form method="post" action="realizarcompra.php">
                                <input type="hidden" name="paginanom" value="compra.php">
                                <a href="#">
                                    <h3 class="text-center"><?php
                                                            //buscar el nombre del producto en la tabla productos
                                                            $sql = "SELECT * FROM productos WHERE producto_id = ?";
                                                            $stmt = mysqli_stmt_init($conn);
                                                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                                                exit();
                                                            }
                                                            mysqli_stmt_bind_param($stmt, "s", $producto["producto_id"]);
                                                            mysqli_stmt_execute($stmt);
                                                            $result = mysqli_stmt_get_result($stmt);
                                                            if ($row = mysqli_fetch_assoc($result)) {
                                                                echo $row['nombre'];
                                                                $prodruta = $row['ruta'];
                                                            }
                                                            ?></h3>
                                </a>
                                <div class="product-img position-relative overflow-hidden bg-transparent text-center">
                                    <img src="<?php echo $prodruta; ?>" style="width:50%" class="img-fluid">
                                </div>
                                <h6 style="text-align: center;">Cantidad: <?php echo $producto['cantidad']; ?></h6><br>
                                <h6 style="text-align: center;">Precio: $<?php echo $producto['precio'];
                                                                                $preciototal = $preciototal + $producto['precio'];
                                                                                $preciototal2 = $preciototal * $producto['cantidad']; ?></h6>
                                <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">
                        </div>
                    </div>
                    <input type="hidden" name="producto_id" value="<?php echo $producto['producto_id']; ?>">

                <?php
                }
                ?>
            </div>
        </div>
        <?php
        echo "Total: $preciototal2 €<br>";
        ?>
        <input type="hidden" name="preciototal" value="<?php echo $preciototal ?>">
        <select name="pago" onchange="metodopago()">
            <option selected value="1">Seleccione método de pago</option>
            <option value="tarjeta">Tarjeta</option>
            <option value="paypal">Paypal</option>
        </select>

        <div id="tarjeta" class="hidden">
            <input type="text" placeholder="Número de tarjeta" name="tarjeta">
        </div>

        <div id="paypal" class="hidden">
            <input type="email" placeholder="Correo electrónico" name="paypal">
        </div>
        <input type="submit" value="Realizar compra" name="realizarcompra">
        </form>
        </div>
    <?php
        if (isset($_GET['error'])) {
            $error = urldecode($_GET['error']);
            echo "<p style='color: red;'>$error</p>";
        }
    } else {
        echo "No has iniciado sesión.";
    }
    ?>
    <!-- Footer Start -->
    <div class="container-fluid bg-primary text-dark mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <a href="" class="text-decoration-none">
                    <h1 class="mb-4 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border border-white px-3 mr-1">FOCUS</span></h1>
                </a>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>Calle San Benito, 13</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>asir202400@estudiantes.salesianasnsp.es</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+34 666 666 666</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+34 919 919 919</p>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Sobre nosotros</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <p>En FOCUS, nos apasiona la moda y creemos que cada prenda cuenta una historia. Nuestra misión es ofrecerte ropa de alta calidad,
                                diseño único y a precios accesibles, para que puedas expresar tu estilo personal sin límites.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Enlaces</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-dark mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Página principal</a>
                            <a class="text-dark mb-2" href="carrito.php"><i class="fa fa-angle-right mr-2"></i>Carrito</a>
                            <a class="text-dark" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Contacto</a>
                        </div>
                    </div>

                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Escríbenos</h5>
                        <form action="escribenos.php">
                            <div class="form-group">
                                <input type="text" class="form-control border-0 py-4" placeholder="Nombre" />
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control border-0 py-4" placeholder="Correo Electrónico" />
                            </div>
                            <div>
                                <button class="btn btn-primary btn-block border-0 py-3" type="submit">Enviar</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <div class="row border-top border-light mx-xl-5 py-4">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-dark">
                    &copy; <a class="text-dark font-weight-semi-bold" href="#">FOCUS</a>. All Rights Reserved. Designed
                    by
                    <a class="text-dark font-weight-semi-bold" href="https://htmlcodex.com">HTML Codex. </a><a style="font-style:italic; font-weight:bold;">Esto no es una página real.</a>
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="img/payments.png" alt="">
            </div>
        </div>
    </div>
    <!-- Footer End -->