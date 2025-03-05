<?php
// Aqui iniciamos la sesion
session_start();

// Incluye el archivo de conexion a la base de datos
include('conexion.php');

// Verificacion de conexion, si no conecta bien te sale un error, esto lo hice porque tuve problema para la conexion.
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtencion de los datos del formulario
$nombreusuario = $_POST['nombreusuario'];
$contrasena = $_POST['contrasena'];

// Consulta a la base de datos en la que busca comprobar que el nombreusuario y la contraseña que cogemos del formulario
// son iguales a algun usuario que tenemos en la base de datos
$query = "SELECT * FROM usuarios WHERE nombreusuario = '$nombreusuario' AND contrasena = '$contrasena'";
$resultado = mysqli_query($conexion, $query);

// Verificacion de resultados si es correcto me deja acceder y me crea la sesion con mi nombredeusuario y si no me tira para atras
// con el error=1 para que salga una modal con el error.
if (mysqli_num_rows($resultado) > 0) {
    $_SESSION['nombreusuario'] = $nombreusuario;
    header("Location: ./principal.php");
    exit();
} else {
    header("Location: ../iniciosesion.html?error=1");
    exit();
} 

?>
