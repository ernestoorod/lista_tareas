<?php
session_start();

if (!isset($_SESSION['nombreusuario'])) {
    header("Location: ../iniciosesion.html");
    exit();
}

include_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombretarea = $_POST['nombretarea'];
    $fechainicio = $_POST['fechainicio'];
    $fechafin = $_POST['fechafin'];
    $prioridad = $_POST['prioridad'];
    $nombreusuario = $_SESSION['nombreusuario'];

    if (empty($nombretarea) || empty($fechainicio) || empty($fechafin) || empty($prioridad)) {
        echo "Por favor, completa todos los campos.";
        exit;
    }

    $sqlUsuario = "SELECT id FROM usuarios WHERE nombreusuario = ?";
    $stmtUsuario = $conexion->prepare($sqlUsuario);
    $stmtUsuario->bind_param("s", $nombreusuario);
    $stmtUsuario->execute();
    $resultado = $stmtUsuario->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $idusuario = $fila['id'];

        $sql = "INSERT INTO tareas (nombretarea, fechainicio, fechafin, prioridad, idusuario) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssi", $nombretarea, $fechainicio, $fechafin, $prioridad, $idusuario);

        if ($stmt->execute()) {
            header("Location: ./principal.php");
            exit;
        } else {
            echo "Error al crear la tarea: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "No se encontró el usuario.";
    }

    $stmtUsuario->close();
    $conexion->close();
} else {
    echo "Método no permitido.";
}
?>
