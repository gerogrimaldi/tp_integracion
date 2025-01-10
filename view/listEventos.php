<?php

$body = '
<div class="container">
    <h1>Eventos</h1>

    <div class="text-center mb-3">
        <a href="index.php?opt=addEvent" class="btn btn-primary btn-lg">Agregar Evento</a>
    </div>

    <table id="myTable" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID Evento</th>
                <th class="text-primary">Nombre Evento</th>
                <th class="text-primary">Fecha Evento</th>
                <th class="text-primary">Lugar Evento</th>
                <th class="text-primary"></th> 
                <th class="text-primary"></th> 
            </tr>
        </thead>
        <tbody id="eventos">
            <!-- Los datos se insertarán aquí -->
        </tbody>
    </table>
</div>

<script>
    // Obtener los datos de PHP
    const eventos ='.$resultado.'
    // Procesar los datos y crear filas en la tabla
    const eventosTbody = document.getElementById("eventos");
    eventos.forEach(evento => {
        const row = document.createElement("tr");
        row.className = "table-light"; // Alternar el color de fondo
        
        row.innerHTML = `
            <td>${evento.idEvento}</td>
            <td>${evento.nombreEvento}</td>
            <td>${evento.fechaEvento}</td>
            <td>${evento.lugarEvento}</td>
            <td><a href="index.php?opt=edit&idEvento=${evento.idEvento}" class="btn btn-warning btn-sm">editar</a></td>
            <td><a href="index.php?opt=delete&idEvento=${evento.idEvento}" class="btn btn-danger btn-sm">borrar</a></td>
        `;
        
        eventosTbody.appendChild(row);
    });

    // Inicializar DataTable
    $(document).ready(function() {
        $("#myTable").DataTable();
    });
</script>


';
?>





