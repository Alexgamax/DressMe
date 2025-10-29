<?php
$uri = getenv('MONGO_URI') ?: 'mongodb://db:27017';
$dbName = 'DBProyecto';

try {
    $manager = new MongoDB\Driver\Manager($uri);

    $command = new MongoDB\Driver\Command(['listDatabases' => 1]);
    $databases = $manager->executeCommand('admin', $command)->toArray();

    $dbExists = false;
    foreach ($databases as $info) {
        foreach ($info->databases as $db) {
            if ($db->name === $dbName) {
                $dbExists = true;
                break 2;
            }
        }
    }

    if (!$dbExists) {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert(['_id' => new MongoDB\BSON\ObjectId(), 'init' => true]);
        $manager->executeBulkWrite("$dbName.init", $bulk);
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete([]);
        $manager->executeBulkWrite("$dbName.init", $bulk);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Error de conexión a MongoDB",
        "detalle" => $e->getMessage()
    ]);
    exit;
}
?>
