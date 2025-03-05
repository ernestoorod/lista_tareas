<?php
// Incluye el archivo de conexión a la base de datos
include_once './conexion.php';

// Verifica si los parámetros 'id' y 'estado' han sido enviados mediante POST
if (isset($_POST['id']) && isset($_POST['estado'])) {
    // Asigna las variables 'id' y 'estado' con los valores recibidos por POST
    $id = $_POST['id'];
    $estado = $_POST['estado'];

    // Prepara la consulta SQL para actualizar el estado de la tarea (favorito)
    $sql = "UPDATE tareas SET favorito = ? WHERE ID = ?";
    
    // Prepara la declaración SQL con los parámetros
    $stmt = $conexion->prepare($sql);
    
    // Asocia los valores de los parámetros a la consulta SQL
    $stmt->bind_param("ii", $estado, $id); // 'ii' indica que ambos parámetros son enteros

    // Ejecuta la consulta y verifica si se ejecutó correctamente
    if ($stmt->execute()) {
        // Si la consulta se ejecutó correctamente, imprime "success"
        echo "success";
    } else {
        // Si ocurrió un error en la ejecución de la consulta, imprime "error"
        echo "error";
    }
} else {
    // Si los parámetros 'id' y 'estado' no están presentes en la solicitud POST, imprime "invalid"
    echo "invalid";
}
?>
