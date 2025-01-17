<?php

$body = '
<div class="container">
    <h1>Eventos</h1>

    <div class="text-center mb-3">
        <a href="index.php?opt=addEvent" class="btn btn-primary btn-lg">Agregar nueva granja</a>
    </div>

    <table id="myTable" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID Granja</th>
                <th class="text-primary">Nombre</th>
                <th class="text-primary">SENASA Nº</th>
                <th class="text-primary">m2</th>
                <th class="text-primary">Ubicación</th> 
                <th class="text-primary"></th> 
            </tr>
        </thead>
        <tbody id="granja">
            <!-- Los datos se insertarán aquí -->
        </tbody>
    </table>
</div>

<script>
    // Obtener los datos de PHP
    const granja ='.$resultado.'
    // Procesar los datos y crear filas en la tabla
    const granjaTbody = document.getElementById("granja");
    granja.forEach(granja => {
        const row = document.createElement("tr");
        row.className = "table-light"; // Alternar el color de fondo
        
        row.innerHTML = `
            <td>${granja.idGranja}</td>
            <td>${granja.nombre}</td>
            <td>${granja.habilitacionSenasa}</td>
            <td>${granja.metrosCuadrados}</td>
            <td>${granja.ubicacion}</td>
            <td><a href="index.php?opt=edit&idEvento=${granja.idGranja}" class="btn btn-warning btn-sm">editar</a></td>
            <td><a href="index.php?opt=delete&idEvento=${granja.idGranja}" class="btn btn-danger btn-sm">borrar</a></td>
        `;
        
        granjaTbody.appendChild(row);
    });

    // Inicializar DataTable
    $(document).ready(function() {
        $("#myTable").DataTable();
    });
</script>


';
?>





