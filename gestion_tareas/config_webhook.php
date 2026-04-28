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
    echo "URL del webhook actualizada.";
}
$stmt = $bd->query(
    "SELECT valor 
    FROM Configuracion
    WHERE clave = 'webhook_url'"
);
$config = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<form method="post">
    <label for="webhook_url">URL del Webhook:</label>
    <input type="text" name="webhook_url" value="<?php echo $config['valor']; ?>">
    <input type="submit" value="Guardar Configuración">
</form>
<a href="lista_tareas.php">Volver a la lista de tareas</a>