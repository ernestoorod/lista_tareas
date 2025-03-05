<?php
session_start();

if (!isset($_SESSION['nombreusuario'])) {
    header("Location: ../iniciosesion.html");
    exit();
}

include_once './conexion.php';

class tarea {
    public $ID;
    public $nombre;
    public $fecha_inicio;
    public $fecha_finalizacion;
    public $prioridad;
    public $completada;
    public $favorito;

    public function __construct($ID, $nombre, $fecha_inicio, $fecha_finalizacion, $prioridad, $completada, $favorito) {
        $this->ID = $ID;
        $this->nombre = $nombre;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_finalizacion = $fecha_finalizacion;
        $this->prioridad = ucfirst(strtolower($prioridad));
        $this->completada = $completada;
        $this->favorito = $favorito;
    }

    public static function obtenerTareasPorUsuario($conexion, $idusuario, $filtro = null, $fecha = null) {
        $sql = "SELECT ID, nombretarea, fechainicio, fechafin, prioridad, completada, favorito 
                FROM tareas 
                WHERE idusuario = ?";

        if ($filtro == 'completadas') {
            $sql .= " AND completada = 1";
        } elseif ($filtro == 'favoritos') {
            $sql .= " AND favorito = 1";
        } else {
            $sql .= " AND completada = 0";
        }

        if ($fecha) {
            $sql .= " AND DATE(fechainicio) = ?";
        }

        $stmt = $conexion->prepare($sql);
        if ($fecha) {
            $stmt->bind_param("ss", $idusuario, $fecha);
        } else {
            $stmt->bind_param("s", $idusuario);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $tareas = [];
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

$nombreusuario = $_SESSION['nombreusuario'];
$sql = "SELECT id FROM usuarios WHERE nombreusuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $nombreusuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idusuario = $row['id'];
} else {
    header("Location: ../iniciosesion.html");
    exit();
}

$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : null;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;

$tareas = tarea::obtenerTareasPorUsuario($conexion, $idusuario, $filtro, $fecha);

$no_tareas = empty($tareas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMAGENES/icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="../CSS/principal.css">
    <title>Taskly</title>
</head>
<body>
    <div class="container">
        <header>
            <div class="logoymenu">
                <div class="menu">
                    <img src="../IMAGENES/menu.png" alt="menu">
                </div>
                <div class="logo"></div>
                <a href="./principal.php">
                    <img src="../IMAGENES/logoblanco.png" alt="logo">
                    <p>TASKLY</p>
                </a>
            </div>
            <div class="cuenta">
                <p>Bienvenido, <?php echo $_SESSION['nombreusuario']; ?></p>
                <a class="cerrarsesion" href="./cierresesion.php">
                    <p>Cerrar sesión</p>
                </a>
            </div>
        </header>
        <main>
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

            <!-- <form method="GET" action="" class="filtrarfecha">
                    <label for="fecha">Filtrar por Fecha:</label>
                    <input type="date" id="fecha" name="fecha">
                    <button type="submit">Filtrar</button>
            </form> -->

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
                                    <img class="estrella-transparente" src="../IMAGENES/estrellasinfondo.png" alt="estrella transparente" style="<?php echo ($tarea->favorito == 1) ? 'display: none;' : ''; ?>">
                                    <img class="estrella-amarilla" src="../IMAGENES/estrellaamarilla.png" alt="estrella amarilla" style="<?php echo ($tarea->favorito == 1) ? 'display: flex;' : 'display: none;'; ?>">                            
                                    <a href="editartarea.php?id=<?php echo $tarea->ID; ?>">
                                        <img src="../IMAGENES/editar.png" alt="editar">
                                    </a>

                                    <a href="#" class="eliminar" data-tarea-id="<?php echo $tarea->ID; ?>">
                                        <img src="../IMAGENES/eliminar.png" alt="eliminar">
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>

    <div id="modalEliminar" class="modal" style="display: none;">
        <div class="contenido">
            <h2>¿Estás seguro de que deseas eliminar esta tarea?</h2>
            <div class="botones">
                <button class="confirmarEliminar">Sí</button>
                <button class="cancelarEliminar">No</button>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay" style="display: none;"></div>

    <script src="../JS/principal.js"></script>
</body>
</html>