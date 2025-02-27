<?php
session_start();

if (!isset($_SESSION['nombreusuario'])) {
    header("Location: ../iniciosesion.html");
    exit();
}

include_once 'conexion.php';

$mensajeError = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombretarea = $_POST['nombretarea'];
    $fechainicio = $_POST['fechainicio'];
    $fechafin = $_POST['fechafin'];
    $prioridad = $_POST['prioridad'];
    $nombreusuario = $_SESSION['nombreusuario'];

    if (empty($nombretarea) || empty($fechainicio) || empty($fechafin) || empty($prioridad)) {
        $mensajeError = "Por favor, completa todos los campos.";
    }

    if (strtotime($fechafin) < strtotime($fechainicio)) {
        $mensajeError = "La fecha de finalización no puede ser menor que la fecha de inicio.";
    }

    if (empty($mensajeError)) {
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
                $mensajeError = "Error al crear la tarea: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $mensajeError = "No se encontró el usuario.";
        }

        $stmtUsuario->close();
        $conexion->close();
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
    <link rel="stylesheet" href="../CSS/añadirtarea.css">
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
            <div class="añadirtarea">
                <div class="ia">
                    <img src="../IMAGENES/bombilla.png" alt="ia" onclick="generarNombreTarea()" style="cursor: pointer;">
                </div>
                <form action="./añadirtarea.php" method="post">
                    <label for="nombretarea">Nombre de la tarea</label>
                    <input type="text" name="nombretarea" id="nombretarea" required>
                    
                    <label for="fechainicio">Fecha de inicio de la tarea</label>
                    <input type="date" name="fechainicio" id="fechainicio" required> 
                    
                    <label for="fechafin">Fecha de fin de la tarea</label>
                    <p class="texto"><?php echo $mensajeError; ?></p>
                    <input type="date" name="fechafin" id="fechafin" required>
                    
                    <label for="prioridad">Prioridad de la tarea</label>
                    <div class="opciones">
                        <input type="radio" name="prioridad" id="baja" value="baja" required>
                        <label for="baja" class="baja">Baja</label>
                        
                        <input type="radio" name="prioridad" id="media" value="media">
                        <label for="media" class="media">Media</label>
                        
                        <input type="radio" name="prioridad" id="alta" value="alta">
                        <label for="alta" class="alta">Alta</label>
                        
                        <input type="radio" name="prioridad" id="inmediata" value="inmediata">
                        <label for="inmediata" class="inmediata">Inmediata</label>
                    </div>
                    
                    <input type="submit" class="botoncrear" value="Crear tarea">
                </form>                
            </div>
        </main>
    </div>
    <script src="../JS/añadirtarea.js"></script>
</body>
</html>