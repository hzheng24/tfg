<?php
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
$usuario = $contraseña = $usuarioErr = $contraseñaErr = $usucontraErr = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = test_input($_POST["usuario"]);
        $contraseña = test_input($_POST["contraseña"]);
        if (empty($usuario)) {
            $usuarioErr = "Falta el usuario";
        }
        if (empty($contraseña)) {
            $contraseñaErr = "Falta la contraseña";
        }
        if (empty($usuarioErr) && empty($contraseñaErr)) {
            require "./conexion.php";
            try {
                $sql = "SELECT * FROM clientes WHERE usuario=?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "Error en la preparación de la consulta.";
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $usuario);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if ($row = mysqli_fetch_assoc($result)) {
                        if (password_verify($contraseña, $row["contraseña"])) {
                            $_SESSION["usuario"] = $row["usuario"];
                            echo "<script>window.location.href='index.php'</script>";
                            exit();
                        } else {
                            $usucontraErr = "Usuario o contraseña incorrectos";
                        }
                    } else {
                        $usucontraErr = "Usuario o contraseña incorrectos";
                    }
                }
                mysqli_stmt_close($stmt);
            } catch (Exception $e) {
                echo "Se ha producido un error al buscar. Error: " . $e->getMessage();
            }
        }
    }
?>