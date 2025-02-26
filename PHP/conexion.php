<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$basededatos = "lista_tareas";

$conexion = mysqli_connect($servidor, $usuario, $password, $basededatos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
