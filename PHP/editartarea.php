<?php
// Inicia la sesión para verificar si el usuario ha iniciado sesión
session_start();

// Comprueba si el usuario ha iniciado sesión, si no, lo redirige a la página de inicio de sesión
if (!isset($_SESSION['nombreusuario'])) {
    header("Location: ../iniciosesion.html");
    exit();
}

// Incluye el archivo de conexión a la base de datos
include_once './conexion.php';

// Variable para almacenar mensajes de error
$mensajeError = '';

// Verifica si el formulario ha sido enviado mediante el método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtiene los datos del formulario
    $nombreTarea = $_POST['nombretarea'];
    $fechaInicio = $_POST['fechainicio'];
    $fechaFin = $_POST['fechafin'];
    $prioridad = $_POST['prioridad'];
    $id = $_GET['id']; // Obtiene el ID de la tarea a editar

    // Verifica que no haya campos vacíos
    if (empty($nombreTarea) || empty($fechaInicio) || empty($fechaFin) || empty($prioridad)) {
        echo "Por favor, completa todos los campos.";
        exit;
    }

    // Valida que la fecha de finalización no sea menor que la de inicio
    if (strtotime($fechaFin) < strtotime($fechaInicio)) {
        $mensajeError = "La fecha de finalización no puede ser menor que la fecha de inicio.";
    }

    // Si no hay errores, procede con la actualización de la tarea
    if (empty($mensajeError)) {
        $sqlUpdate = "UPDATE tareas SET nombretarea = ?, fechainicio = ?, fechafin = ?, prioridad = ? WHERE ID = ?";
        $stmtUpdate = $conexion->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ssssi", $nombreTarea, $fechaInicio, $fechaFin, $prioridad, $id);

        // Ejecuta la actualización y redirige si es exitosa
        if ($stmtUpdate->execute()) {
            header("Location: ./principal.php");
            exit();
        } else {
            echo "Error al actualizar la tarea: " . $conexion->error;
        }
    }
}

// Verifica si se ha recibido un ID en la URL para cargar los datos de la tarea
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara la consulta para obtener la tarea con el ID proporcionado
    $sql = "SELECT nombretarea, fechainicio, fechafin, prioridad FROM tareas WHERE ID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si la tarea existe, almacena sus datos en una variable
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
                <!-- Formulario para editar la tarea -->
                <form action="./editartarea.php?id=<?php echo $id; ?>" method="post">
                    <label for="nombretarea">Nombre de la tarea</label>
                    <input type="text" name="nombretarea" id="nombretarea" value="<?php echo $tarea['nombretarea']; ?>" required>
                    
                    <label for="fechainicio">Fecha de inicio de la tarea</label>
                    <input type="date" name="fechainicio" id="fechainicio" value="<?php echo $tarea['fechainicio']; ?>" required> 
                    
                    <label for="fechafin">Fecha de fin de la tarea</label>
                    <p class="texto"><?php echo $mensajeError; ?></p> <!-- Muestra el mensaje de error si la fecha de finalización no es válida -->
                    <input type="date" name="fechafin" id="fechafin" value="<?php echo $tarea['fechafin']; ?>" required>
                    
                    <label for="prioridad">Prioridad de la tarea</label>
                    <div class="opciones">
                        <!-- Opciones de prioridad, con la opción seleccionada de la tarea actual -->
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
