<?php
session_start();
include 'conexion.php';

$nombreusuario = $_POST['nombreusuario'];
$contrasena = $_POST['contrasena'];

$query = "SELECT * FROM usuarios WHERE nombreusuario = '$nombreusuario' AND contrasena = '$contrasena'";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado) > 0) {
    $_SESSION['nombreusuario'] = $nombreusuario;
    header("Location: ./principal.php");
    exit();
} else {
    header("Location: ../iniciosesion.html?error=1");
    exit();
} 

?>
