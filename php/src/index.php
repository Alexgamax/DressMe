<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>CRUD Usuarios</title>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap");

    :root {
        --rosa-principal: #ec4899;
        --rosa-secundario: #d81b60;
        --rosa-claro: #fdf2f8;
        --rosa-fondo: #fff0f5;
        --texto-oscuro: #333;
        --borde-claro: #fce7f3;
    }

    body {
        font-family: "Poppins", Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: var(--rosa-fondo);
        color: var(--texto-oscuro);
    }

    .container {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        padding: 30px 40px;
        max-width: 1200px;
        margin: 20px auto;
        border-top: 5px solid var(--rosa-principal);
    }

    h2 {
        color: var(--rosa-secundario);
        font-weight: 600;
        margin-top: 0;
        border-bottom: 2px solid var(--borde-claro);
        padding-bottom: 10px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        border: 1px solid var(--borde-claro);
        padding: 12px 15px;
        text-align: left;
        font-size: 0.95em;
    }

    th {
        background-color: var(--rosa-claro);
        color: var(--rosa-secundario);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85em;
        letter-spacing: 0.5px;
    }

    tbody tr:hover {
        background-color: var(--rosa-claro);
    }

    input[type="text"],
    input[type="email"] {
        padding: 10px;
        margin: 3px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-family: "Poppins", sans-serif;
        font-size: 0.95em;
        width: calc(100% - 24px);
    }

    form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
        margin-bottom: 20px;
    }

    button {
        padding: 10px 15px;
        margin: 3px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
    }

    form button[type="submit"] {
        background-color: var(--rosa-principal);
        color: white;
        grid-column: 1 / -1;
        font-size: 1.1em;
    }

    form button[type="submit"]:hover {
        background-color: var(--rosa-secundario);
    }

    td button {
        padding: 5px 10px;
        font-size: 0.9em;
    }

    button[onclick*="eliminar"] {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    button[onclick*="eliminar"]:hover {
        background-color: #fca5a5;
    }

    button[onclick*="editar"] {
        background-color: #e5e7eb;
        color: #374151;
    }

    button[onclick*="editar"]:hover {
        background-color: #d1d5db;
    }

</style>
</head>
<body>

<div class="container">
    <h2>Agregar Usuario</h2>
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
</div>

<div class="container">
    <h2>Lista de Usuarios</h2>
    <table id="tablaUsuarios">
        <thead>
            <tr>
                <th>Nombre</th><th>Email</th><th>Género</th><th>Estilos</th>
                <th>Prendas</th><th>Seguidores</th><th>Siguiendo</th><th>Fecha Registro</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<script>
async function cargarUsuarios(){
    const dateFormatter = new Intl.DateTimeFormat('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });

    try {
        const res = await fetch('read.php');
        if (!res.ok) throw new Error('Error al cargar datos');
        
        const data = await res.json();
        const tbody = document.querySelector('#tablaUsuarios tbody');
        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;">No se encontraron usuarios.</td></tr>`;
            return;
        }

        data.forEach(u => {
            const estilos = (u.estilos_preferidos && u.estilos_preferidos.length > 0) ? u.estilos_preferidos.join(', ') : '-';
            const prendas = (u.prendas_armario && u.prendas_armario.length > 0) ? u.prendas_armario.length : '0';
            const seguidores = (u.seguidores && u.seguidores.length > 0) ? u.seguidores.length : '0';
            const siguiendo = (u.siguiendo && u.siguiendo.length > 0) ? u.siguiendo.length : '0';
            
            let fechaReg = '-';
            if (u.fecha_registro && u.fecha_registro.$date) {
                fechaReg = dateFormatter.format(new Date(u.fecha_registro.$date));
            } else if (u.fecha_registro) {
                 fechaReg = u.fecha_registro;
            }

            tbody.innerHTML += `<tr id="user-${u._id}">
                <td>${u.nombre}</td>
                <td>${u.email}</td>
                <td>${u.genero || '-'}</td>
                <td>${estilos}</td>
                <td>${prendas}</td>
                <td>${seguidores}</td>
                <td>${siguiendo}</td>
                <td>${fechaReg}</td>
                <td>
                    <button onclick="eliminarUsuario('${u._id}')">Eliminar</button>
                    <button onclick="editarUsuario('${u._id}', '${u.nombre}')">Editar</button>
                </td>
            </tr>`;
        });
    } catch (error) {
        console.error(error);
        const tbody = document.querySelector('#tablaUsuarios tbody');
        tbody.innerHTML = `<tr><td colspan="9" style="text-align:center; color:red;">Error al cargar datos.</td></tr>`;
    }
}

async function eliminarUsuario(id){
    if(!confirm('¿Estás segura de que quieres eliminar este usuario?')) return;
    
    try {
        const res = await fetch('delete.php?id=' + id, { method: 'DELETE' });
        if (!res.ok) throw new Error('Error en el servidor');
        
        document.getElementById(`user-${id}`).remove();
    } catch (error) {
        console.error(error);
        alert('No se pudo eliminar el usuario.');
    }
}

async function editarUsuario(id, nombreActual){
    const nombre = prompt('Introduce el nuevo nombre:', nombreActual);
    
    if(nombre && nombre !== nombreActual){
        try {
            const res = await fetch('update.php',{
                method:'PUT',
                headers:{'Content-Type':'application/json'},
                body: JSON.stringify({id, nombre})
            });
            if (!res.ok) throw new Error('Error en el servidor');

            document.querySelector(`#user-${id} td:first-child`).textContent = nombre;
            
        } catch (error) {
            console.error(error);
            alert('No se pudo actualizar el usuario.');
        }
    }
}

document.querySelector('#formAgregar').addEventListener('submit', async e => {
    e.preventDefault();
    const form = e.target;
    const data = Object.fromEntries(new FormData(form));

    ['estilos_preferidos','prendas_armario','seguidores','siguiendo'].forEach(k => {
        if(data[k]) {
            data[k] = data[k].split(',').map(i => i.trim()).filter(i => i);
        } else {
            data[k] = [];
        }
    });

    try {
        const res = await fetch('create.php',{
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify(data)
        });
        if (!res.ok) throw new Error('Error en el servidor');
        
        form.reset();
        cargarUsuarios();
    } catch (error) {
        console.error(error);
        alert('No se pudo crear el usuario.');
    }
});

cargarUsuarios();
</script>