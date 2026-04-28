<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Nueva Tarea</title>
        <link rel="stylesheet" href="estilos.css">
    </head>
    <body>
        <div class="contenedor">
            <form action="guardar_tarea.php" method="post">
                <label for="tarea">Nombre de la tarea:</label>
                <input type="text" id="tarea" name="titulo" required>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"></textarea>
                <label for="fecha">Fecha de vencimiento:</label>
                <input type="date" id="fecha" name="fecha_vencimiento" required>
                <label for="estado">Estado:</label>
                <select id="estado" name="estado">
                    <option value="pendiente">Pendiente</option>
                    <option value="en_progreso">En progreso</option>
                    <option value="completada">Completada</option>
                </select>
                <input type="submit" value="Guardar tarea">
            </form>
        </div>
    </body>
</html>
