<?php
$uri = getenv('MONGO_URI') ?: 'mongodb://db:27017';
try {
    $manager = new MongoDB\Driver\Manager($uri); // Se ha completado la conexion
} catch (Exception $e) {
    echo "<h2>Error de conexión ❌</h2>";
    echo $e->getMessage();
}
?>