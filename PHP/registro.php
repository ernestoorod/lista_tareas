<?php
session_start();
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombreusuario']) && isset($_POST['correo']) && isset($_POST['contrasena']) && isset($_POST['contrasena2'])) {

    $nombreusuario = mysqli_real_escape_string($conexion, $_POST['nombreusuario']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $contrasena2 = $_POST['contrasena2'];

    $sql_nombre = "SELECT * FROM usuarios WHERE nombreusuario = '$nombreusuario'";
    $resultado_nombre = mysqli_query($conexion, $sql_nombre);

    if (mysqli_num_rows($resultado_nombre) > 0) {
        header("Location: ../registro.html?error=1");
        exit();
    } else {
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../registro.html?error=2");
            exit();
        } else {
            $sql_correo = "SELECT * FROM usuarios WHERE correo = '$correo'";
            $resultado_correo = mysqli_query($conexion, $sql_correo);

            if (mysqli_num_rows($resultado_correo) > 0) {
                header("Location: ../registro.html?error=3");
                exit();
            } else {
                if ($contrasena == $contrasena2) {

                    $sql = "INSERT INTO usuarios (nombreusuario, correo, contrasena) VALUES ('$nombreusuario', '$correo', '$contrasena')";

                    if (mysqli_query($conexion, $sql)) {
                        $_SESSION['nombreusuario'] = $nombreusuario;
                        header("Location: ./principal.php");
                        exit();
                    } else {
                        echo "Error: " . mysqli_error($conexion);
                    }
                } else {
                    header("Location: ../registro.html?error=4");
                    exit();
                }
            }
        }
    }
}

mysqli_close($conexion);

?>
