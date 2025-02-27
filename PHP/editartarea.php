<?php
session_start();

if (!isset($_SESSION['nombreusuario'])) {
    header("Location: ../iniciosesion.html");
    exit();
}

include_once './conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreTarea = $_POST['nombretarea'];
    $fechaInicio = $_POST['fechainicio'];
    $fechaFin = $_POST['fechafin'];
    $prioridad = $_POST['prioridad'];
    $id = $_GET['id'];
    
    $sqlUpdate = "UPDATE tareas SET nombretarea = ?, fechainicio = ?, fechafin = ?, prioridad = ? WHERE ID = ?";
    $stmtUpdate = $conexion->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssssi", $nombreTarea, $fechaInicio, $fechaFin, $prioridad, $id);

    if ($stmtUpdate->execute()) {
        header("Location: ./principal.php");
        exit();
    } else {
        echo "Error al actualizar la tarea: " . $conexion->error;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT nombretarea, fechainicio, fechafin, prioridad FROM tareas WHERE ID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tarea = $result->fetch_assoc();
    } else {
        echo "No se encontró la tarea.";
        exit();
    }
} else {
    echo "No se especificó ninguna tarea.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMAGENES/icono.ico" type="image/x-icon">
    <title>Editar Tarea - Taskly</title>
    <link rel="stylesheet" href="../CSS/editartarea.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <a href="./principal.php">
                    <img src="../IMAGENES/logoblanco.png" alt="logo">
                    <p>TASKLY</p>
                </a>
            </div>
            <div class="cuenta">
                <a class="cerrarsesion" href="./cierresesion.php">
                    <p>Cerrar sesión</p>
                </a>
            </div>
        </header>
        <main>
            <div class="editartarea">
                <form action="./editartarea.php?id=<?php echo $id; ?>" method="post">
                    <label for="nombretarea">Nombre de la tarea</label>
                    <input type="text" name="nombretarea" id="nombretarea" value="<?php echo $tarea['nombretarea']; ?>" required>
                    
                    <label for="fechainicio">Fecha de inicio de la tarea</label>
                    <input type="date" name="fechainicio" id="fechainicio" value="<?php echo $tarea['fechainicio']; ?>" required> 
                    
                    <label for="fechafin">Fecha de fin de la tarea</label>
                    <input type="date" name="fechafin" id="fechafin" value="<?php echo $tarea['fechafin']; ?>" required>
                    
                    <label for="prioridad">Prioridad de la tarea</label>
                    <div class="opciones">
                        <input type="radio" name="prioridad" id="baja" value="baja" <?php echo ($tarea['prioridad'] == 'baja') ? 'checked' : ''; ?>>
                        <label for="baja" class="baja">Baja</label>
                        
                        <input type="radio" name="prioridad" id="media" value="media" <?php echo ($tarea['prioridad'] == 'media') ? 'checked' : ''; ?>>
                        <label for="media" class="media">Media</label>
                        
                        <input type="radio" name="prioridad" id="alta" value="alta" <?php echo ($tarea['prioridad'] == 'alta') ? 'checked' : ''; ?>>
                        <label for="alta" class="alta">Alta</label>
                        
                        <input type="radio" name="prioridad" id="inmediata" value="inmediata" <?php echo ($tarea['prioridad'] == 'inmediata') ? 'checked' : ''; ?>>
                        <label for="inmediata" class="inmediata">Inmediata</label>
                    </div>
                    
                    <input type="submit" class="botoncrear" value="Actualizar tarea">
                </form>                
            </div>
        </main>
    </div>
</body>
</html>
