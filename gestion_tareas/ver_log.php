<?php
$archivo_log = 'registro_tareas.txt';
echo "<h1>Log de Tareas</h1>";
echo "<a href='lista_tareas.php'>Volver a la lista de tareas</a><br><br>";

if (file_exists($archivo_log)) {
    $contenido = file_get_contents($archivo_log);
    echo "<pre>" . htmlspecialchars($contenido) . "</pre>";
} else {
    echo "No se han registrado tareas aún.";
}
?>