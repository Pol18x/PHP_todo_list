<?php
include "conexion_bd.php";

if (!isset($_GET["id"])) {
    header("Location: lista_tareas.php");
    exit();
}

$id = $_GET["id"];

try {
    $stmt = $bd->prepare("
        SELECT td.id, td.Fecha_vencimiento, td.Estado, te.Titulo, te.Descripcion 
        FROM Tarea_data td
        JOIN Tarea_dataexten te ON td.id = te.Tarea_id
        WHERE td.id = :id
    ");

    $stmt->execute([":id" => $id]);
    $tarea = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tarea) {
        echo "Tarea no encontrada.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Tarea</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor">
        <h1>Crear Nueva Tarea</h1>
        <form action="guardar_tarea.php" method="post">
            <label for="titulo">Título de la tarea:</label>
            <input type="text" id="titulo" name="titulo" required>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>
            <label for="fecha_vencimiento">Fecha de vencimiento:</label>
            <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" required>
            <label for="estado">Estado:</label>
            <select id="estado" name="estado">
                <option value="pendiente">Pendiente</option>
                <option value="en_progreso">En progreso</option>
                <option value="completada">Completada</option>
            </select>
            <input type="submit" value="Guardar tarea">
        </form>
        <br>
        <a href="lista_tareas.php">⬅️ Volver al listado</a>
    </div>
</body>
</html>