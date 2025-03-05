<?php 
// Incluye el archivo de la conexion a la base de datos
include_once './conexion.php';


// Define la clase tarea
class tarea {
    public $ID;
    public $nombre;
    public $fecha_inicio;
    public $fecha_finalizacion;
    public $prioridad;

    // Construye la clase tarea
    public function __construct($ID, $nombre, $fecha_inicio, $fecha_finalizacion, $prioridad) {
        $this->ID = $ID;
        $this->nombre = $nombre;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_finalizacion = $fecha_finalizacion;
        $this->prioridad = $prioridad;
    }

    // Crea un metodo obtenerTareas el cual servira para coger las tareas de la base de datos
    public static function obtenerTareas($conexion) {
        $sql = "SELECT ID, nombre, fecha_inicio, fecha_finalizacion, prioridad FROM tareas";
        $result = $conexion->query($sql);

        $tareas = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tareas[] = new tarea(
                    $row['ID'],
                    $row['nombre'],
                    $row['fecha_inicio'],
                    $row['fecha_finalizacion'],
                    $row['prioridad']
                );
            }
        }
        
        return $tareas;
    }
}

// Llama al metodo obtener tareas para la visualización
$tareas = tarea::obtenerTareas($conexion);

foreach ($tareas as $tarea) {
    echo "ID: " . $tarea->ID . ", Nombre: " . $tarea->nombre . ", Inicio: " . $tarea->fecha_inicio . ", Fin: " . $tarea->fecha_finalizacion . ", Prioridad: " . $tarea->prioridad . "<br>";
}
?>
