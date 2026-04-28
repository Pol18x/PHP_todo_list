<?php
include 'conexion_bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $estado = $_POST['estado'];
    $fecha_creacion = date("Y-m-d");

    try {
        $bd->beginTransaction();
        $stmt1 = $bd->prepare(
            "INSERT INTO Tarea_data (Fecha_creacion, Fecha_vencimiento, Estado, aux1) 
            VALUES (?, ?, ?, '')"
        );
        $stmt1->execute([$fecha_creacion, $fecha_vencimiento, $estado]);
        $id_tarea = $bd->lastInsertId();
        $stmt2 = $bd->prepare(
            "INSERT INTO Tarea_dataexten (Tarea_id, Titulo, Descripcion, aux1) 
            VALUES (?, ?, ?, '')"
        );
        $stmt2->execute([$id_tarea, $titulo, $descripcion]);
        $bd->commit();
        $log = "[" . date("Y-m-d H:i:s") . "] ACCIÓN: create | ID: $id_tarea | Tarea: $titulo\n";
        file_put_contents("registro_tareas.txt", $log, FILE_APPEND);
        header("Location: lista_tareas.php?mensaje=creado");
        exit();
    } catch (Exception $e) {
        $bd->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>