<?php
header('Content-Type: application/json');
include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(["error" => "Falta el parámetro id"]);
    exit;
}

$bulk = new MongoDB\Driver\BulkWrite;
try {
    $bulk->delete(['_id' => new MongoDB\BSON\ObjectId($id)], ['limit' => 1]);
    $result = $manager->executeBulkWrite('DBProyecto.usuarios', $bulk);

    if ($result->getDeletedCount() > 0) {
        echo json_encode(["mensaje" => "Usuario eliminado"]);
    } else {
        echo json_encode(["error" => "No se encontró el usuario"]);
    }
} catch (Exception $e){
    echo json_encode(["error" => $e->getMessage()]);
}
?>


