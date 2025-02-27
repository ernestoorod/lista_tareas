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

    public static function obtenerTareasPorUsuario($conexion, $idusuario, $filtro = null) {
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

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idusuario);
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

$tareas = tarea::obtenerTareasPorUsuario($conexion, $idusuario, $filtro);
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
            <div class="logo">
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
            <div class="navegacionfechas">
                <div id="prevDate" class="flecha"> < </div>
                <div class="fecha" id="fecha"></div>
                <div id="nextDate" class="flecha"> > </div>
            </div>

            <div class="tareasopciones">
                <a href="../añadirtarea.html">
                    <div class="agregartarea">
                        <div>+ Agregar Tarea</div>
                    </div>
                </a>
                <div class="tareascompletadas">
                    <a href="?filtro=completadas"><div>Tareas Completadas</div></a>
                </div>
                <div class="favoritos">
                    <a href="?filtro=favoritos"><div>Favoritos</div></a>
                </div>
            </div>

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

                    <div class="overlay" id="overlay" style="display: none;"></div>

                    <div id="modalEliminar_<?php echo $tarea->ID; ?>" class="modal" style="display: none;">
                        <div class="contenido">
                            <h2>¿Estás seguro de que deseas eliminar esta tarea?</h2>
                            <div class="botones">
                                <button class="confirmarEliminar" data-tarea-id="<?php echo $tarea->ID; ?>">Sí</button>
                                <button class="cancelarEliminar">No</button>
                            </div>
                        </div>
                    </div>
                    
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>

    <script src="../JS/principal.js"></script>
</body>
</html>
