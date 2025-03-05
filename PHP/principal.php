<?php
// Inicia la sesión para verificar si el usuario está autenticado
session_start();

// Si el usuario no ha iniciado sesión, redirige a la página de inicio de sesión
if (!isset($_SESSION['nombreusuario'])) {
    header("Location: ../iniciosesion.html");
    exit();
}

// Incluye el archivo de conexión a la base de datos
include_once './conexion.php';

// Definición de la clase "tarea" para manejar las tareas
class tarea {
    public $ID;
    public $nombre;
    public $fecha_inicio;
    public $fecha_finalizacion;
    public $prioridad;
    public $completada;
    public $favorito;

    // Constructor de la clase tarea, asigna valores a las propiedades de la tarea
    public function __construct($ID, $nombre, $fecha_inicio, $fecha_finalizacion, $prioridad, $completada, $favorito) {
        $this->ID = $ID;
        $this->nombre = $nombre;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_finalizacion = $fecha_finalizacion;
        $this->prioridad = ucfirst(strtolower($prioridad)); // Pone la primera letra en mayúscula
        $this->completada = $completada;
        $this->favorito = $favorito;
    }

    // Función estática para obtener las tareas de un usuario desde la base de datos
    public static function obtenerTareasPorUsuario($conexion, $idusuario, $filtro = null, $fecha = null) {
        $sql = "SELECT ID, nombretarea, fechainicio, fechafin, prioridad, completada, favorito 
                FROM tareas 
                WHERE idusuario = ?"; // Selecciona las tareas para un usuario

        // Filtros opcionales para mostrar tareas completadas, favoritas o no completadas
        if ($filtro == 'completadas') {
            $sql .= " AND completada = 1";
        } elseif ($filtro == 'favoritos') {
            $sql .= " AND favorito = 1";
        } else {
            $sql .= " AND completada = 0";
        }

        // Si se especifica una fecha, filtra por esa fecha
        if ($fecha) {
            $sql .= " AND DATE(fechainicio) = ?";
        }

        // Prepara y ejecuta la consulta con parámetros
        $stmt = $conexion->prepare($sql);
        if ($fecha) {
            $stmt->bind_param("ss", $idusuario, $fecha);
        } else {
            $stmt->bind_param("s", $idusuario);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $tareas = [];
        // Si hay resultados, crea objetos tarea con los datos de la base de datos
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tareas[] = new tarea(
                    $row['ID'],
                    $row['nombretarea'],
                    $row['fechainicio'],
                    $row['fechafin'],
                    $row['prioridad'],
                    $row['completada'],
                    $row['favorito']
                );
            }
        }

        return $tareas;
    }
}

// Obtiene el nombre de usuario de la sesión
$nombreusuario = $_SESSION['nombreusuario'];
$sql = "SELECT id FROM usuarios WHERE nombreusuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $nombreusuario);
$stmt->execute();
$result = $stmt->get_result();

// Verifica si el usuario existe en la base de datos
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idusuario = $row['id']; // Asigna el ID del usuario
} else {
    // Si el usuario no existe, redirige a la página de inicio de sesión
    header("Location: ../iniciosesion.html");
    exit();
}

// Obtiene los parámetros de filtro y fecha desde la URL (si existen)
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : null;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;

// Llama a la función para obtener las tareas del usuario con los filtros aplicados
$tareas = tarea::obtenerTareasPorUsuario($conexion, $idusuario, $filtro, $fecha);

// Verifica si no hay tareas para mostrar
$no_tareas = empty($tareas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMAGENES/icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="../CSS/principal.css"> <!-- Enlace al archivo de estilo CSS -->
    <title>Taskly</title>
</head>
<body>
    <div class="container">
        <header>
            <div class="logoymenu">
                <div class="logo"></div>
                <a href="./principal.php">
                    <img src="../IMAGENES/logoblanco.png" alt="logo">
                    <p>TASKLY</p>
                </a>
            </div>

            <!-- Muestra el nombre del usuario y un enlace para cerrar sesión -->
            <div class="cuenta">
                <p>Bienvenido, <?php echo $_SESSION['nombreusuario']; ?></p>
                <a class="cerrarsesion" href="./cierresesion.php">
                    <p>Cerrar sesión</p>
                </a>
            </div>
        </header>
        <main>
            <!-- Opciones para filtrar las tareas (agregar tarea, ver tareas completadas, ver favoritos) -->
            <div class="tareasopciones">
                <div class="agregartarea">
                    <a href="./añadirtarea.php"><div>+ Agregar Tarea</div></a>
                </div>
                <div class="tareascompletadas">
                    <a href="?filtro=completadas"><div>Tareas Completadas</div></a>
                </div>
                <div class="favoritos">
                    <a href="?filtro=favoritos"><div>Favoritos</div></a>
                </div>
            </div> 

            <!-- Formulario para filtrar tareas por fecha -->
            <form method="GET" action="" class="filtrarfecha">
                    <label for="fecha">Filtrar por Fecha:</label>
                    <input type="date" id="fecha" name="fecha">
                    <button type="submit">Filtrar</button>
            </form>

            <!-- Tabla que muestra las tareas del usuario -->
            <table class="tareas">
                <thead>
                    <tr>
                        <th>Terminar tarea</th>
                        <th>Nombre de la tarea</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Finalización</th>
                        <th>Prioridad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($no_tareas): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">
                                <?php
                                // Mensajes según el filtro o la fecha seleccionada
                                if ($filtro == 'completadas') {
                                    echo "No hay tareas completadas.";
                                } elseif ($filtro == 'favoritos') {
                                    echo "No hay tareas favoritas.";
                                } elseif ($fecha) {
                                    echo "No hay tareas este día.";
                                } else {
                                    echo "No hay tareas por realizar.";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <!-- Muestra las tareas en la tabla -->
                        <?php foreach ($tareas as $tarea): ?>
                        <tr>
                            <td class="checkear">
                                <div class="check-container" data-id="<?php echo $tarea->ID; ?>">
                                    <img src="../IMAGENES/circulo.png" alt="circulo" class="check-icon" style="<?php echo ($tarea->completada == 1) ? 'display: none;' : ''; ?>">
                                    <img src="../IMAGENES/cheque.png" class="check" alt="check" style="<?php echo ($tarea->completada == 1) ? 'display: flex;' : 'display: none;'; ?>">
                                </div>  
                            </td>
                            <td><p><?php echo $tarea->nombre; ?></p></td>
                            <td><p><?php echo date('d/m/Y', strtotime($tarea->fecha_inicio)); ?></p></td>
                            <td><p><?php echo date('d/m/Y', strtotime($tarea->fecha_finalizacion)); ?></p></td>
                            <td>
                                <p class="prioridad" data-prioridad="<?php echo $tarea->prioridad; ?>">
                                    <?php echo $tarea->prioridad; ?>
                                </p>
                            </td>
                            <td>
                                <div class="acciones">
                                    <!-- Estrellas para marcar tareas como favoritas -->
                                    <img class="estrella-transparente" src="../IMAGENES/estrellasinfondo.png" alt="estrella transparente" style="<?php echo ($tarea->favorito == 1) ? 'display: none;' : ''; ?>">
                                    <img class="estrella-amarilla" src="../IMAGENES/estrellaamarilla.png" alt="estrella amarilla" style="<?php echo ($tarea->favorito == 1) ? 'display: block;' : ''; ?>">
                                    <a href="editar.php?id=<?php echo $tarea->ID; ?>"><img src="../IMAGENES/lapiz.png" alt="editar"></a>
                                    <a href="eliminar.php?id=<?php echo $tarea->ID; ?>"><img src="../IMAGENES/borrar.png" alt="eliminar"></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
