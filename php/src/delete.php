<?php
header('Content-Type: application/json');
include '../db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(["error" => "Falta el parámetro id"]);
    exit;
}

$collection = $db->usuarios;
$result = $collection->deleteOne(["_id" => new MongoDB\BSON\ObjectId($id)]);

echo json_encode($result->getDeletedCount() > 0 ? ["mensaje" => "Usuario eliminado"] : ["error" => "No se encontró el usuario"]);
?>

