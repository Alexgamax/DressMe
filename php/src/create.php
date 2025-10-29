<?php
header('Content-Type: application/json');
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['nombre']) || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["error" => "Faltan campos obligatorios"]);
    exit;
}

//arrays de IDs a ObjectId
function toObjectIdArray($arr){
    $result = [];
    foreach($arr as $id){
        try {
            $result[] = new MongoDB\BSON\ObjectId($id);
        } catch(Exception $e){
        }
    }
    return $result;
}

$documento = [
    "nombre" => $data['nombre'],
    "email" => $data['email'],
    "password" => $data['password'],
    "genero" => $data['genero'] ?? '',
    "estilos_preferidos" => $data['estilos_preferidos'] ?? [],
    "prendas_armario" => toObjectIdArray($data['prendas_armario'] ?? []),
    "fecha_registro" => new MongoDB\BSON\UTCDateTime(),
    "seguidores" => toObjectIdArray($data['seguidores'] ?? []),
    "siguiendo" => toObjectIdArray($data['siguiendo'] ?? [])
];

$bulk = new MongoDB\Driver\BulkWrite;
$id = $bulk->insert($documento);

try {
    $manager->executeBulkWrite('DBProyecto.usuarios', $bulk);
    echo json_encode(["inserted_id" => (string)$id]);
} catch (Exception $e){
    echo json_encode(["error" => $e->getMessage()]);
}
?>


