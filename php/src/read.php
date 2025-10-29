<?php
header('Content-Type: application/json');
include 'db.php';

$query = new MongoDB\Driver\Query([]);
$cursor = $manager->executeQuery('DBProyecto.usuarios', $query);

$usuarios = [];
foreach ($cursor as $u) {
    $usuarios[] = [
        "_id" => (string)$u->_id,
        "nombre" => $u->nombre ?? '',
        "email" => $u->email ?? '',
        "password" => $u->password ?? '',
        "genero" => $u->genero ?? '',
        "estilos_preferidos" => $u->estilos_preferidos ?? [],
        "prendas_armario" => array_map(function($id){ return (string)$id; }, $u->prendas_armario ?? []),
        "fecha_registro" => isset($u->fecha_registro) ? $u->fecha_registro->toDateTime()->format('Y-m-d H:i:s') : '',
        "seguidores" => array_map(function($id){ return (string)$id; }, $u->seguidores ?? []),
        "siguiendo" => array_map(function($id){ return (string)$id; }, $u->siguiendo ?? [])
    ];
}

echo json_encode($usuarios);
?>