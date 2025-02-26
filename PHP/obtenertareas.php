<?php 
include_once './conexion.php';

class tarea {
    public $ID;
    public $nombre;
    public $fecha_inicio;
    public $fecha_finalizacion;
    public $prioridad;

    public function __construct($ID, $nombre, $fecha_inicio, $fecha_finalizacion, $prioridad) {
        $this->ID = $ID;
        $this->nombre = $nombre;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_finalizacion = $fecha_finalizacion;
        $this->prioridad = $prioridad;
    }

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

$tareas = tarea::obtenerTareas($conexion);

foreach ($tareas as $tarea) {
    echo "ID: " . $tarea->ID . ", Nombre: " . $tarea->nombre . ", Inicio: " . $tarea->fecha_inicio . ", Fin: " . $tarea->fecha_finalizacion . ", Prioridad: " . $tarea->prioridad . "<br>";
}
?>
