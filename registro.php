<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Focus</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./img/favi.ico">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-primary py-2 px-xl-5">
            <div class="col-lg-6 d-none d-lg-block"></div>
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
                <a href="./index.php" class="text-decoration-none drop-shadow">
                    <img src="./img/imgindex/logof2.png" style="width: 50%;">
                </a>
            </div>
            <div class="navbar-nav ml-auto py-0">

            </div>
        </div>
    </div>
    <!-- Topbar End -->
    <!-- Validar formulario -->
    <?php
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function validarcorreo($correo)
    {
        // Expresión regular para validar el correo electrónico
        $patron = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        return preg_match($patron, $correo);
    }
    $nombre = $apellidos = $correo = $calle = $ciudad = $codigopostal = $pais = $usuario = $contraseña = $contraseña2 = $usuarioexistente = "";
    $nombreErr = $apellidosErr = $correoErr = $calleErr = $ciudadErr = $codigopostalErr = $paisErr = $usuarioErr = $contraseñaErr = $contraseña2Err = $usuarioexistente = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = test_input($_POST["nombre"]);
        $apellidos = test_input($_POST["apellidos"]);
        $correo = test_input($_POST["correo"]);
        $calle = test_input($_POST["calle"]);
        $ciudad = test_input($_POST["ciudad"]);
        $codigopostal = test_input($_POST["codigopostal"]);
        $pais = test_input($_POST["pais"]);
        $usuario = test_input($_POST["usuario"]);
        $contraseñahash = password_hash(test_input($_POST["contraseña"]), PASSWORD_DEFAULT);
        $fechahoy = date('Y-m-d');
        $error = false;
        if (empty($nombre)) {
            $nombreErr = "Error nombre";
        }
        if (empty($apellidos)) {
            $apellidosErr = "Error apellidos";
        }
        if (empty($correo) || !validarcorreo($correo)) {
            $correoErr = "Correo electrónico inválido";
        }
        if (empty($calle)) {
            $calleErr = "Falta la calle";
        }
        if (empty($ciudad)) {
            $ciudadErr = "Falta la ciudad";
        }
        if (empty($codigopostal)) {
            $codigopostalErr = "Falta el código postal";
        }
        if (empty($pais)) {
            $paisErr = "Falta el pais";
        }
        if (empty($usuario)) {
            $usuarioErr = "Falta el nombre de usuario";
        }
        if (empty($contraseña)) {
            $contraseñaErr = "Falta la contraseña";
        }
        if (empty($contraseña2)) {
            $contraseña2Err = "Falta la contraseña";
        }
        if (empty($nombreErr) && empty($apellidosErr) && empty($correoErr) && empty($calleErr) && empty($ciudadErr) && empty($codigopostalErr) && empty($paisErr) && empty($usuarioErr) && empty($contraseñaErr) && empty($contraseña2Err)) {
            require "./conexion.php";
            try {
                //comprobar si el usuario existe en la bbdd
                $sql = "SELECT * FROM clientes WHERE usuario=? OR correo_electronico=?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "<script type='text/javascript'>alert('Error');</script>";
                    echo "<script>window.location.href='registro.php'</script>";
                    exit();
                }
                //ejecutar la consulta sql
                mysqli_stmt_bind_param($stmt, "ss", $usuario, $correo);
                mysqli_stmt_execute($stmt);
                //recoger los resultados
                $result = mysqli_stmt_get_result($stmt);
                //mysqli_fetch_assoc nos devuelve true si encuentra algo y false si no encuentra nada
                if (mysqli_fetch_assoc($result)) {
                    $usuarioexistente = "Usuario en uso";
                } else {
                    $sql = "SELECT * FROM direcciones WHERE calle=? AND ciudad=? AND codigo_postal=? AND pais=?";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "<script type='text/javascript'>alert('Error');</script>";
                        echo "<script>window.location.href='registro.php'</script>";
                        exit();
                    }
                    mysqli_stmt_bind_param($stmt, "ssis", $calle, $ciudad, $codigopostal, $pais);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if ($row = mysqli_fetch_assoc($result)) {
                        $direccion_id = $row['direccion_id'];
                        $sql = "INSERT INTO clientes (direccion_id, nombre, apellido, correo_electronico, usuario, contraseña) VALUES (?,?,?,?,?,?)";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "<script type='text/javascript'>alert('Error');</script>";
                            echo "<script>window.location.href='registro.php'</script>";
                            exit();
                        }
                        mysqli_stmt_bind_param($stmt, "isssss", $direccion_id, $nombre, $apellidos, $correo, $usuario, $contraseñahash);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        $sql = "INSERT INTO carrito (usuario, fecha_creacion) VALUES (?,?)";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "<script type='text/javascript'>alert('Error');</script>";
                            echo "<script>window.location.href='registro.php'</script>";
                            exit();
                        }
                        mysqli_stmt_bind_param($stmt, "ss", $usuario, $fechahoy);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        echo "<script>window.location.href='index.php'</script>";
                        exit();
                    } else {
                        $sql = "INSERT INTO direcciones (calle, ciudad, codigo_postal, pais) VALUES (?, ?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "<script type='text/javascript'>alert('Error');</script>";
                            echo "<script>window.location.href='registro.php'</script>";
                            exit();
                        }
                        mysqli_stmt_bind_param($stmt, "ssis", $calle, $ciudad, $codigopostal, $pais);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        $sql = "SELECT * FROM direcciones WHERE calle='$calle' AND ciudad='$ciudad' AND codigo_postal='$codigopostal' AND pais='$pais'";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "<script type='text/javascript'>alert('Error');</script>";
                            echo "<script>window.location.href='registro.php'</script>";
                            exit();
                        }
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        if ($row = mysqli_fetch_assoc($result)) {
                            $direccion_id = $row['direccion_id'];
                            $sql = "INSERT INTO clientes (direccion_id, nombre, apellido, correo_electronico, usuario, contraseña) VALUES (?,?,?,?,?,?)";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                echo "<script type='text/javascript'>alert('Error');</script>";
                                echo "<script>window.location.href='registro.php'</script>";
                                exit();
                            }
                            mysqli_stmt_bind_param($stmt, "isssss", $direccion_id, $nombre, $apellidos, $correo, $usuario, $contraseñahash);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                            $sql = "INSERT INTO carrito (usuario, fecha_creacion) VALUES (?,?)";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                echo "<script type='text/javascript'>alert('Error');</script>";
                                echo "<script>window.location.href='registro.php'</script>";
                                exit();
                            }
                            mysqli_stmt_bind_param($stmt, "ss", $usuario, $fechahoy);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                            echo "<script>window.location.href='index.php'</script>";
                            exit();
                        }
                    }
                }
            } catch (Exception $e) {
                echo "Se ha producido un error al buscar";
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }
    ?>
    <!-- Validar formulario -->
    <!-- Formulario de Registro -->
    <div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="registro">
                <p>
                    <input type="text" class="campos" name="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>">
                    <span class="error"><?php echo $nombreErr; ?></span>
                </p>
                <p>
                    <input type="text" class="campos" name="apellidos" placeholder="Apellidos" value="<?php echo $apellidos; ?>">
                    <span class="error"><?php echo $apellidosErr; ?></span>
                </p>
                <p>
                    <input type="email" class="campos" name="correo" placeholder="Correo Electrónico" value="<?php echo $correo; ?>">
                    <span class="error"><?php echo $correoErr; ?></span>
                </p>
                <p>
                    <input type="text" class="campos" name="calle" placeholder="Calle" value="<?php echo $calle; ?>">
                    <span class="error"><?php echo $calleErr; ?></span>
                </p>
                <p>
                    <input type="text" class="campos" name="ciudad" placeholder="Ciudad" value="<?php echo $ciudad; ?>">
                    <span class="error"><?php echo $ciudadErr; ?></span>
                </p>
            </div>
            <div class="registro">
                <p>
                    <input type="text" class="campos" name="codigopostal" placeholder="Codigo Postal" value="<?php echo $codigopostal; ?>">
                    <span class="error"><?php echo $codigopostalErr; ?></span>
                </p>
                <p>
                    <input type="text" class="campos" name="pais" placeholder="Pais" value="<?php echo $pais; ?>">
                    <span class="error"><?php echo $paisErr; ?></span>
                </p>
                <p>
                    <input type="text" class="campos" name="usuario" placeholder="Nombre de Usuario" value="<?php echo $usuario; ?>">
                    <span class="error"><?php echo $usuarioErr; ?></span>
                </p>
                <p>
                    <input type="password" class="campos" name="contraseña" placeholder="Contraseña" value="<?php echo $contraseña; ?>">
                    <span class="error"><?php echo $contraseñaErr; ?></span>
                </p>

                <span class="error"><?php echo $usuarioexistente; ?></span>
            </div>
            <div class="col-md-2 m-0">
                <p><input type="submit" value="CREAR CUENTA" name="crear cuenta" class="btn btn-primary btn-block border-0 py-3"></p>
            </div>
        </form>
    </div>
    <!-- Fin Formulario de Registro -->
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
                                <input type="text" class="form-control border-0 py-4" placeholder="Nombre" required="required" />
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control border-0 py-4" placeholder="Correo Electrónico" required="required" />
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
                    <a class="text-dark font-weight-semi-bold" href="https://htmlcodex.com">HTML Codex</a>
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="img/payments.png" alt="">
            </div>
        </div>
    </div>
    <!-- Footer End -->
    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>