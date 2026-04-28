<?php
include 'conexion_bd.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nueva_url = $_POST['webhook_url'];
    $stmt = $bd->prepare(
        "UPDATE Configuracion 
        SET valor = :url 
        WHERE clave = 'webhook_url'"
    );
    $stmt->execute([':url' => $nueva_url]);
    echo "URL del Webhook actualizada";
}
$stmt = $bd->query(
    "SELECT valor 
    FROM Configuracion 
    WHERE clave = 'webhook_url'"
);
$url_actual = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor">
        <h1>Configuración del Sistema</h1>
        <form method="post">
            <label for="webhook_url">URL del Webhook (webhook.site):</label><br>
            <input type="text" id="webhook_url" name="webhook_url" value="<?php echo $url_actual; ?>">
            <input type="submit" value="Guardar Cambios">
        </form>
        <br>
        <a href="lista_tareas.php">Volver a la lista de tareas</a>
    </div>
</body>
</html>