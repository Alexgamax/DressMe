<?php
$uri = getenv('MONGO_URI') ?: 'mongodb://db:27017';
try {
    $manager = new MongoDB\Driver\Manager($uri);

    echo "<h2>Conexión a MongoDB exitosa ✅</h2>";

    $query = new MongoDB\Driver\Query([]);
    $cursor = $manager->executeQuery('DBProyecto.usuarios', $query);


    echo "<h3>Usuarios:</h3><ul>";
    foreach ($cursor as $doc) {
        echo "<li>" . $doc->nombre. "</li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    echo "<h2>Error de conexión ❌</h2>";
    echo $e->getMessage();
}
?>
