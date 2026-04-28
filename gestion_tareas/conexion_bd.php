<?php 
try {
    $bd = new PDO("mysql:host=localhost;dbname=todo_list", "root", "");
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "No se ha podido conectar a la base de datos " . $e->getMessage();
}
?>