<?php
// Comienza la sesion
session_start();

// Comprobacion de si el usuario ha iniciado sesion
if (!isset($_SESSION['nombreusuario'])) {
    header("Location: ../iniciosesion.html");
    exit();
}

// Incluye el archivo de conexion a la base de datos
include_once './conexion.php';

// Comprueba si existe un apartado id de tareas
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Protege el id de inyeccion SQL
    $id = $conexion->real_escape_string($id);

    // Prepara la eliminacion segun el ID de la tarea en la base de datos
    $sql = "DELETE FROM tareas WHERE ID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $id);

    // Ejecuta la eliminacion de las tareas si todo va bien y si no te salta el error
    if ($stmt->execute()) {
        header("Location: ./principal.php");
    } else {
        echo "Error al eliminar la tarea.";
    }

    // Cierre de la consulta y de la conexion
    $stmt->close();
    $conexion->close();
} else {
    
    // Redireccion en caso de no existir id
    header("Location: ./principal.php");
}
?>
