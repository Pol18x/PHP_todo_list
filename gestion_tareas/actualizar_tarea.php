<?php
include 'conexion_bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $estado = $_POST['estado'];
    $fecha_hora_accion = date("Y-m-d H:i:s");

    try {
        $bd->beginTransaction();

        $stmt1 = $bd->prepare(
            "UPDATE Tarea_data 
            SET Fecha_vencimiento = :vencimiento, Estado = :estado 
            WHERE id = :id"
        );
        
        $stmt1->execute([
            ':vencimiento' => $fecha_vencimiento,
            ':estado' => $estado,
            ':id' => $id
        ]);

        $stmt2 = $bd->prepare(
            "UPDATE Tarea_dataexten 
            SET Titulo = :titulo, Descripcion = :descripcion 
            WHERE Tarea_id = :id"
        );

        $stmt2->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':id' => $id
        ]);

        $bd->commit();

        $linea_log = "[$fecha_hora_accion] ACCIÓN: update | ID: $id | Info: Título: $titulo, Estado: $estado\n";
        file_put_contents("registro_tareas.txt", $linea_log, FILE_APPEND);

        $stmt_url = $bd->query(
            "SELECT valor 
            FROM Configuracion
            WHERE clave = 'webhook_url'"
        );

        $url_webhook = $stmt_url->fetchColumn();

        if ($url_webhook) {
            $datos_webhook = [
                "tipo" => "update",
                "fecha" => $fecha_hora_accion,
                "datos" => [
                    "id" => $id,
                    "titulo" => $titulo,
                    "descripcion" => $descripcion,
                    "estado" => $estado
                ]
            ];

            $ch = curl_init($url_webhook);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos_webhook));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_exec($ch);
            curl_close($ch);
        }

        header("Location: lista_tareas.php?mensaje=actualizado");

    } catch (PDOException $e) {
        $bd->rollBack();
        echo "Error: " . $e->getMessage();
    }   
}
?>