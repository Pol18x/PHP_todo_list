<?php
include 'conexion_bd.php';

$tareas = [];
try {
    $query = "SELECT td.id, td.Fecha_vencimiento, td.Estado, te.Titulo 
              FROM Tarea_data td
              JOIN Tarea_dataexten te ON td.id = te.Tarea_id";
    $stmt = $bd->query($query);
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor">
        <h1>Lista de Tareas</h1>
        <nav style="background: #f4f4f4; padding: 10px; margin-bottom: 20px;">
            <strong>Menú:</strong> 
            <a href="nueva_tarea.php">➕ Nueva Tarea</a> | 
            <a href="ver_log.php">📋 Ver Log de Actividad</a> | 
            <a href="configuracion.php">⚙️ Configurar Webhook</a>
        </nav>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Vencimiento</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tareas as $tarea): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tarea['Titulo'] ?? 'Sin título'); ?></td>
                    <td><?php echo $tarea['Fecha_vencimiento'] ?: 'Sin fecha'; ?></td>
                    <td><?php echo $tarea['Estado']; ?></td>
                    <td>
                        <a href="editar_tarea.php?id=<?php echo $tarea['id']; ?>">Editar</a>
                        <a href="eliminar_tarea.php?id=<?php echo $tarea['id']; ?>" 
                        onclick="return confirm('¿Seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>