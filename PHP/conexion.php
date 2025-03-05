<?php
// Definicion de las credenciales de conexion
$servidor = "localhost";
$usuario = "root";
$password = "";
$basededatos = "lista_tareas";

//Creacion de la conexion MYSQL
$conexion = mysqli_connect($servidor, $usuario, $password, $basededatos);

// Verifica la conexion si no funciona te salta el error.
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
