<?php
header("Content-Type: application/json");
include '../conexion_bd.php';

$metodo = $_SERVER['REQUEST_METHOD'];

switch($metodo) {
    case 'GET':
        try {
            $stmt = $bd->query(
                "SELECT td.id, td.Fecha_creacion, td.Fecha_vencimiento, td.Estado, te.Titulo, te.Descripcion, td.aux1 
                FROM Tarea_data td
                JOIN Tarea_dataexten te ON td.id = te.Tarea_id"
            );
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode([
                "saludo" => "holi",
                "tareas" => $tareas
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error al obtener tareas: " . $e->getMessage()]);
        }
        break;
    
    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['titulo'], $input['descripcion'], $input['fecha_vencimiento'], $input['estado'])) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos obligatorios", "saludo" => "holi"]);
            break;
        }

        $fecha_creacion = date("Y-m-d");
        $fecha_hora_accion = date("Y-m-d H:i:s");

        try {
            $bd->beginTransaction();

            $stmt1 = $bd->prepare(
                "INSERT INTO Tarea_data (Fecha_creacion, Fecha_vencimiento, Estado, aux1) 
                VALUES (:creacion, :vencimiento, :estado, '')"
            );
            $stmt1->execute([
                ':creacion' => $fecha_creacion,
                ':vencimiento' => $input['fecha_vencimiento'],
                ':estado' => $input['estado']
            ]);

            $id_tarea = $bd->lastInsertId();

            $stmt2 = $bd->prepare(
                "INSERT INTO Tarea_dataexten (Tarea_id, Titulo, Descripcion, aux1) 
                VALUES (:id, :titulo, :descripcion, '')"
            );
            $stmt2->execute([
                ':id' => $id_tarea,
                ':titulo' => $input['titulo'],
                ':descripcion' => $input['descripcion']
            ]);

            $bd->commit();

            $linea_log = "[$fecha_hora_accion] ACCIÓN: create_api | ID: $id_tarea | Tarea: {$input['titulo']}\n";
            file_put_contents("../registro_tareas.txt", $linea_log, FILE_APPEND);

            $stmt_url = $bd->query(
                "SELECT valor 
                FROM Configuracion 
                WHERE clave = 'webhook_url'"
            );
            $url_webhook = $stmt_url->fetchColumn();

            if ($url_webhook) {
                $datos_webhook = [
                    "tipo" => "create",
                    "fecha" => $fecha_hora_accion,
                    "datos" => $input
                ];
                $ch = curl_init($url_webhook);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos_webhook));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
                curl_exec($ch);
                curl_close($ch);
            }

            http_response_code(201);
            echo json_encode([
                "mensaje" => "Tarea creada con éxito",
                "id" => $id_tarea,
                "saludo" => "holi"
            ]);

        } catch (PDOException $e) {
            $bd->rollBack();
            http_response_code(500);
            echo json_encode(["error" => "Error al crear: " . $e->getMessage()]);
        }
        break;

    case 'PUT':
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "ID de tarea no proporcionado", "saludo" => "holi"]);
            break;
        }

        $id = $_GET['id'];
        $input = json_decode(file_get_contents("php://input"), true);
        $fecha_hora_accion = date("Y-m-d H:i:s");

        try {
            $bd->beginTransaction();

            $stmt1 = $bd->prepare(
                "UPDATE Tarea_data 
                SET Fecha_vencimiento = :vencimiento, Estado = :estado 
                WHERE id = :id"
            );
            $stmt1->execute([
                ':vencimiento' => $input['fecha_vencimiento'],
                ':estado' => $input['estado'],
                ':id' => $id
            ]);

            $stmt2 = $bd->prepare(
                "UPDATE Tarea_dataexten 
                SET Titulo = :titulo, Descripcion = :descripcion 
                WHERE Tarea_id = :id"
            );
            $stmt2->execute([
                ':titulo' => $input['titulo'],
                ':descripcion' => $input['descripcion'],
                ':id' => $id
            ]);

            $bd->commit();

            $linea_log = "[$fecha_hora_accion] ACCIÓN: update_api | ID: $id | Datos: " . json_encode($input) . "\n";
            file_put_contents("../registro_tareas.txt", $linea_log, FILE_APPEND);

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
                    "datos" => array_merge(["id" => $id], $input)
                ];
                $ch = curl_init($url_webhook);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos_webhook));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
                curl_exec($ch);
                curl_close($ch);
            }

            http_response_code(200);
            echo json_encode([
                "mensaje" => "Tarea actualizada correctamente",
                "saludo" => "holi"
            ]);

        } catch (PDOException $e) {
            $bd->rollBack();
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar: " . $e->getMessage()]);
        }
        break;    

    case 'DELETE':
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "ID de tarea no proporcionado", "saludo" => "holi"]);
            break;
        }

        $id = $_GET['id'];
        $fecha_hora_accion = date("Y-m-d H:i:s");

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

            $linea_log = "[$fecha_hora_accion] ACCIÓN: delete_api | ID: $id | Info: Tarea eliminada vía API\n";
            file_put_contents("../registro_tareas.txt", $linea_log, FILE_APPEND);

            $stmt_url = $bd->query(
                "SELECT valor 
                FROM Configuracion 
                WHERE clave = 'webhook_url'"
            );
            $url_webhook = $stmt_url->fetchColumn();

            if ($url_webhook) {
                $datos_webhook = [
                    "tipo" => "delete",
                    "fecha" => $fecha_hora_accion,
                    "id_tarea" => $id
                ];
                $ch = curl_init($url_webhook);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos_webhook));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
                curl_exec($ch);
                curl_close($ch);
            }

            http_response_code(200);
            echo json_encode([
                "mensaje" => "Tarea eliminada correctamente",
                "saludo" => "holi"
            ]);

        } catch (PDOException $e) {
            $bd->rollBack();
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar: " . $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["mensaje" => "Método no soportado"]);
        break;
}
?>