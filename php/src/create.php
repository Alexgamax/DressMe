<?php
header('Content-Type: application/json');
include '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['nombre']) || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["error" => "Faltan campos obligatorios"]);
    exit;
}

$collection = $db->usuarios;

$nuevo = [
    "nombre" => $data['nombre'],
    "email" => $data['email'],
    "password" => $data['password'],
    "genero" => $data['genero'] ?? '',
    "estilos_preferidos" => $data['estilos_preferidos'] ?? [],
    "prendas_armario" => array_map(function($id){ return new MongoDB\BSON\ObjectId($id); }, $data['prendas_armario'] ?? []),
    "fecha_registro" => new MongoDB\BSON\UTCDateTime(),
    "seguidores" => array_map(function($id){ return new MongoDB\BSON\ObjectId($id); }, $data['seguidores'] ?? []),
    "siguiendo" => array_map(function($id){ return new MongoDB\BSON\ObjectId($id); }, $data['siguiendo'] ?? [])
];

$result = $collection->insertOne($nuevo);

echo json_encode(["inserted_id" => (string)$result->getInsertedId()]);
?>

