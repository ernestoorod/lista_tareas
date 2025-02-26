<?php
session_start();
if (!isset($_SESSION['nombreusuario'])) {
    header("Location: ../iniciosesion.html");
    exit();
}

include_once './conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $id = $conexion->real_escape_string($id);

    $sql = "DELETE FROM tareas WHERE ID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header("Location: ./principal.php");
    } else {
        echo "Error al eliminar la tarea.";
    }

    $stmt->close();
    $conexion->close();
} else {
    header("Location: ./principal.php");
}
?>
