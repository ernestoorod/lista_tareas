<?php
// Inicia la sesion
session_start();

// Incluye el archivo de la conexion a la base de datos
include('conexion.php');

// Verifica si la solicitud es valida y si se han enviado los datos correctamente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombreusuario']) && isset($_POST['correo']) && isset($_POST['contrasena']) && isset($_POST['contrasena2'])) {

    // Escapa los valores $nombreusuario y $correo para evitar inyección SQL
    $nombreusuario = mysqli_real_escape_string($conexion, $_POST['nombreusuario']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $contrasena2 = $_POST['contrasena2'];

    //Verifica si el nombre de usuario ya existe
    $sql_nombre = "SELECT * FROM usuarios WHERE nombreusuario = '$nombreusuario'";
    $resultado_nombre = mysqli_query($conexion, $sql_nombre);

    if (mysqli_num_rows($resultado_nombre) > 0) {
        header("Location: ../registro.html?error=1");
        exit();
    } else {
        //Verifica que el correo tiene el formato bien
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../registro.html?error=2");
            exit();
        } else {
            //Verifica si el correo ya esta registrado
            $sql_correo = "SELECT * FROM usuarios WHERE correo = '$correo'";
            $resultado_correo = mysqli_query($conexion, $sql_correo);

            if (mysqli_num_rows($resultado_correo) > 0) {
                header("Location: ../registro.html?error=3");
                exit();
            } else {
                // Comprueba si las contraseñas son iguales
                if ($contrasena == $contrasena2) {

                    // Inserta el usuario a la base de datos si todo esta correcto 
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

// Cierra la conexion a la base de datos
mysqli_close($conexion);

?>
