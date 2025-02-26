<?php
include_once './conexion.php';

if (isset($_POST['id']) && isset($_POST['estado'])) {
    $id = $_POST['id'];
    $estado = $_POST['estado'];

    $sql = "UPDATE tareas SET favorito = ? WHERE ID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $estado, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid";
}
?>
