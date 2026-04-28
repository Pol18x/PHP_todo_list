<?php
include 'conexion_bd.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $bd->beginTransaction();

        $stmt1 = $bd->prepare(
            "DELETE FROM Tarea_dataexten 
            WHERE Tarea_id = :id"
        );
        $stmt1->execute([':id' => $id]);

        $stmt2 = $bd->prepare(
            "DELETE FROM Tarea_data 
            WHERE id = :id"
        );
        $stmt2->execute([':id' => $id]);

        $bd->commit();

        $fecha_hora = date("Y-m-d H:i:s");
        $linea_log = "[$fecha_hora] ACCIÓN: delete | ID: $id | Info: Borrado\n";
        file_put_contents("registro_tareas.txt", $linea_log, FILE_APPEND);

        header("Location: lista_tareas.php?mensaje=eliminado");

    } catch (PDOException $e) {
        $bd->rollBack();
        echo "Error : " . $e->getMessage();
    }
}
?>