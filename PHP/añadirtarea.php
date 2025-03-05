<?php
// Inicia la sesión para verificar si el usuario ha iniciado sesión
session_start();

// Si no existe la variable de sesión 'nombreusuario', redirige al usuario a la página de inicio de sesión
if (!isset($_SESSION['nombreusuario'])) {
    header("Location: ../iniciosesion.html");
    exit();
}

// Incluye el archivo de conexión a la base de datos
include_once 'conexion.php';

// Inicializa una variable para los mensajes de error
$mensajeError = '';

// Verifica si se ha enviado el formulario mediante POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtiene los datos enviados por el formulario
    $nombretarea = $_POST['nombretarea'];
    $fechainicio = $_POST['fechainicio'];
    $fechafin = $_POST['fechafin'];
    $prioridad = $_POST['prioridad'];
    $nombreusuario = $_SESSION['nombreusuario']; // Obtiene el nombre de usuario de la sesión

    // Verifica si hay algún campo vacío
    if (empty($nombretarea) || empty($fechainicio) || empty($fechafin) || empty($prioridad)) {
        $mensajeError = "Por favor, completa todos los campos."; // Si hay campos vacíos, asigna un mensaje de error
    }

    // Verifica que la fecha de fin no sea anterior a la fecha de inicio
    if (strtotime($fechafin) < strtotime($fechainicio)) {
        $mensajeError = "La fecha de finalización no puede ser menor que la fecha de inicio."; // Si es incorrecto, asigna un mensaje de error
    }

    // Si no hay errores en los campos, procede con la inserción de la tarea
    if (empty($mensajeError)) {
        // Prepara la consulta para obtener el ID del usuario
        $sqlUsuario = "SELECT id FROM usuarios WHERE nombreusuario = ?";
        $stmtUsuario = $conexion->prepare($sqlUsuario);
        $stmtUsuario->bind_param("s", $nombreusuario); // Protege la consulta de inyección SQL
        $stmtUsuario->execute();
        $resultado = $stmtUsuario->get_result();

        // Si el usuario existe, obtiene su ID
        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $idusuario = $fila['id'];

            // Prepara la consulta para insertar una nueva tarea en la base de datos
            $sql = "INSERT INTO tareas (nombretarea, fechainicio, fechafin, prioridad, idusuario) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssi", $nombretarea, $fechainicio, $fechafin, $prioridad, $idusuario); // Vincula los parámetros de la consulta

            // Si la tarea se inserta correctamente, redirige al usuario a la página principal
            if ($stmt->execute()) {
                header("Location: ./principal.php");
                exit;
            } else {
                // Si ocurre un error, muestra un mensaje de error
                $mensajeError = "Error al crear la tarea: " . $stmt->error;
            }
            $stmt->close(); // Cierra el statement
        } else {
            // Si no se encuentra el usuario, muestra un mensaje de error
            $mensajeError = "No se encontró el usuario.";
        }

        $stmtUsuario->close(); // Cierra el statement de usuario
        $conexion->close(); // Cierra la conexión a la base de datos
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMAGENES/icono.ico" type="image/x-icon">
    <title>Taskly</title>
    <link rel="stylesheet" href="../CSS/añadirtarea.css"> <!-- Archivo de estilos para la página -->
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <!-- Logo que redirige a la página principal -->
                <a href="./principal.php">
                    <img src="../IMAGENES/logoblanco.png" alt="logo">
                    <p>TASKLY</p>
                </a>
            </div>
            
            <div class="cuenta">
                <!-- Enlace para cerrar sesión -->
                <a class="cerrarsesion" href="./cierresesion.php">
                    <p>Cerrar sesión</p>
                </a>
            </div>
        </header>
        <main>
            <div class="añadirtarea">
                <!-- Icono de bombilla que invoca una función JavaScript (aunque no está definida en este código) -->
                <div class="ia">
                    <img src="../IMAGENES/bombilla.png" alt="ia" onclick="generarNombreTarea()" style="cursor: pointer;">
                </div>
                <!-- Formulario para agregar una nueva tarea -->
                <form action="./añadirtarea.php" method="post">
                    <label for="nombretarea">Nombre de la tarea</label>
                    <input type="text" name="nombretarea" id="nombretarea" required>
                    
                    <label for="fechainicio">Fecha de inicio de la tarea</label>
                    <input type="date" name="fechainicio" id="fechainicio" required> 
                    
                    <label for="fechafin">Fecha de fin de la tarea</label>
                    <!-- Muestra el mensaje de error si hay alguno relacionado con la fecha -->
                    <p class="texto"><?php echo $mensajeError; ?></p>
                    <input type="date" name="fechafin" id="fechafin" required>
                    
                    <label for="prioridad">Prioridad de la tarea</label>
                    <div class="opciones">
                        <!-- Opciones de prioridad con botones de radio -->
                        <input type="radio" name="prioridad" id="baja" value="baja" required>
                        <label for="baja" class="baja">Baja</label>
                        
                        <input type="radio" name="prioridad" id="media" value="media">
                        <label for="media" class="media">Media</label>
                        
                        <input type="radio" name="prioridad" id="alta" value="alta">
                        <label for="alta" class="alta">Alta</label>
                        
                        <input type="radio" name="prioridad" id="inmediata" value="inmediata">
                        <label for="inmediata" class="inmediata">Inmediata</label>
                    </div>
                    
                    <!-- Botón para enviar el formulario -->
                    <input type="submit" class="botoncrear" value="Crear tarea">
                </form>                
            </div>
        </main>
    </div>
    <!-- Archivo de JavaScript asociado -->
    <script src="../JS/añadirtarea.js"></script>
</body>
</html>
