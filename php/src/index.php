<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>CRUD Usuarios</title>
<style>
body{font-family:Arial;margin:20px;} table{border-collapse:collapse;width:100%;margin-top:20px;} th,td{border:1px solid #ccc;padding:8px;text-align:left;} th{background:#f4f4f4;} input,button{padding:6px;margin:3px;}
</style>
</head>
<body>

<h2>Usuarios</h2>

<form id="formAgregar">
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="email" name="email" placeholder="Correo" required>
    <input type="text" name="password" placeholder="Contraseña" required>
    <input type="text" name="genero" placeholder="Género">
    <input type="text" name="estilos_preferidos" placeholder="Estilos (separados por coma)">
    <input type="text" name="prendas_armario" placeholder="IDs prendas (coma)">
    <input type="text" name="seguidores" placeholder="IDs seguidores (coma)">
    <input type="text" name="siguiendo" placeholder="IDs siguiendo (coma)">
    <button type="submit">Agregar</button>
</form>

<table id="tablaUsuarios">
    <thead>
        <tr>
            <th>Nombre</th><th>Email</th><th>Género</th><th>Estilos</th>
            <th>Prendas</th><th>Seguidores</th><th>Siguiendo</th><th>Fecha Registro</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
async function cargarUsuarios(){
    const res = await fetch('read.php');
    const data = await res.json();
    const tbody = document.querySelector('#tablaUsuarios tbody');
    tbody.innerHTML = '';
    data.forEach(u=>{
        tbody.innerHTML+=`<tr>
            <td>${u.nombre}</td>
            <td>${u.email}</td>
            <td>${u.genero||'-'}</td>
            <td>${u.estilos_preferidos.join(', ')||'-'}</td>
            <td>${u.prendas_armario.join(', ')||'-'}</td>
            <td>${u.seguidores.join(', ')||'-'}</td>
            <td>${u.siguiendo.join(', ')||'-'}</td>
            <td>${u.fecha_registro||'-'}</td>
            <td><button onclick="eliminarUsuario('${u._id}')">Eliminar</button></td>
        </tr>`;
    });
}

async function eliminarUsuario(id){
    if(!confirm('¿Eliminar este usuario?')) return;
    await fetch(`delete.php?id=${id}`);
    cargarUsuarios();
}

document.querySelector('#formAgregar').addEventListener('submit', async e=>{
    e.preventDefault();
    const form = e.target;
    const data = Object.fromEntries(new FormData(form));
    // convertir inputs separados por coma a array
    ['estilos_preferidos','prendas_armario','seguidores','siguiendo'].forEach(k=>{
        if(data[k]) data[k] = data[k].split(',').map(i=>i.trim());
    });
    await fetch('create.php',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify(data)
    });
    form.reset();
    cargarUsuarios();
});

cargarUsuarios();
</script>

</body>
</html>
