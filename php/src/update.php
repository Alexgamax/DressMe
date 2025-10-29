<?php
header('Content-Type: application/json');
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["error" => "Falta el parámetro id"]);
    exit;
}

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

$id = $data['id'];

$updateData = [];
if(isset($data['nombre'])) $updateData['nombre'] = $data['nombre'];
if(isset($data['email'])) $updateData['email'] = $data['email'];
if(isset($data['password'])) $updateData['password'] = $data['password'];
if(isset($data['genero'])) $updateData['genero'] = $data['genero'];
if(isset($data['estilos_preferidos'])) $updateData['estilos_preferidos'] = $data['estilos_preferidos'];
if(isset($data['prendas_armario'])) $updateData['prendas_armario'] = toObjectIdArray($data['prendas_armario']);
if(isset($data['seguidores'])) $updateData['seguidores'] = toObjectIdArray($data['seguidores']);
if(isset($data['siguiendo'])) $updateData['siguiendo'] = toObjectIdArray($data['siguiendo']);

if(empty($updateData)){
    echo json_encode(["error" => "No hay campos para actualizar"]);
    exit;
}

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(
    ['_id' => new MongoDB\BSON\ObjectId($id)],
    ['$set' => $updateData],
    ['multi' => false, 'upsert' => false]
);

try {
    $result = $manager->executeBulkWrite('DBProyecto.usuarios', $bulk);
    if ($result->getModifiedCount() > 0) {
        echo json_encode(["mensaje" => "Usuario actualizado"]);
    } else {
        echo json_encode(["mensaje" => "No se modificó el usuario o no se encontró"]);
    }
} catch(Exception $e){
    echo json_encode(["error" => $e->getMessage()]);
}
?>
