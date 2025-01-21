<?php

$error = $error ?? ''; // Definir $error como cadena vacía si no está definido

$body = <<<HTML
<div class="container">
    <h1>Galpones</h1>

    <div class="text-center mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarGalpon">
            Agregar galpón
        </button>
    </div>

    <table id="myTable" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID Galpon</th>
                <th class="text-primary">Identificación</th>
                <th class="text-primary">ID Tipo Ave</th>
                <th class="text-primary">Capacidad</th>
                <th class="text-primary">ID Granja</th>
                <th class="text-primary"></th>
                <th class="text-primary"></th>
            </tr>
        </thead>
        <tbody id="galpon">
            <!-- Los datos se insertarán aquí -->
        </tbody>
    </table>
</div>

<script>
    var galpon = $resultado;
    // Procesar los datos y crear filas en la tabla
    var galponTbody = document.getElementById("galpon");
    
    galpon.forEach(function(galpon) {
        var row = document.createElement("tr");
        row.className = "table-light";
        row.innerHTML = 
            '<td>' + galpon.idGalpon + '</td>' +
            '<td>' + galpon.identificacion + '</td>' +
            '<td>' + galpon.idTipoAve + '</td>' +
            '<td>' + galpon.capacidad + '</td>' +
            '<td>' + galpon.idGranja + '</td>' +
            '<td>' +
                '<button type="button" ' +
                    'class="btn btn-warning btn-sm" ' +
                    'data-bs-toggle="modal" ' +
                    'data-bs-target="#editarGalpon" ' +
                    'data-id="' + galpon.idGalpon + '" ' +
                    'data-identificacion="' + galpon.identificacion + '" ' +
                    'data-idTipoAve="' + galpon.idTipoAve + '" ' +
                    'data-capacidad="' + galpon.capacidad + '" ' +
                    'data-idGranja="' + galpon.idGranja + '">' +
                    'Editar' +
                '</button>' +
            '</td>' +
            '<td>' +
                '<a href="index.php?opt=galpones&delete=true&idGalpon=' + galpon.idGalpon + ' + &idGranja=' + galpon.idGranja + '" ' +
                   'class="btn btn-danger btn-sm">' +
                    'Borrar' +
                '</a>' +
            '</td>';
        
        galponTbody.appendChild(row);
    });

    // Inicializar DataTable
    $(document).ready(function() {
        $("#myTable").DataTable();
    });
</script>

<!-- Modal popUp Agregar Galpon -->
<div class="modal fade" id="agregarGalpon" tabindex="-1" aria-labelledby="agregarGalponModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="agregarGalponModal">Agregar Galpon</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregrarGalponForm" action="index.php?opt=galpones" method="POST" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <label for="identificacion" class="form-label">Identificador del galpón</label>
                        <input type="select" 
                               class="form-control" 
                               id="identificacion" 
                               name="identificacion" 
                               placeholder="Identificador"
                               min="1"
                               required>
                        <div class="invalid-feedback">
                            El valor debe ser un número positivo.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="capacidad" class="form-label">Capacidad</label>
                        <input type="number" 
                               class="form-control" 
                               id="capacidad" 
                               name="capacidad" 
                               placeholder="Capacidad de aves"
                               min="1"
                               required>
                        <div class="invalid-feedback">
                            El valor debe ser un número positivo.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="opciones" class="form-label">Tipo de aves</label>
                        <select id="opciones" name="opciones" class="form-control">
                            <!-- Las opciones se agregarán aquí con JavaScript -->
                        </select>
                        <div class="invalid-feedback">
                            La habilitación debe tener al menos 3 caracteres.
                        </div>
                    </div>
                    <input type="hidden" id="idGranja" name="idGranja">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" name="btGalpon" value="registrarGalpon" form="agregrarGalponForm">Agregar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal popUp editar Galpon -->
<div class="modal fade" id="editarGalpon" tabindex="-1" aria-labelledby="editarGalponModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarGalponModal">Editar datos del galpón</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarGalponForm" action="index.php?opt=galpon" method="POST" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <label for="identificacionG" class="form-label">Identificacion del galpón</label>
                        <input type="select" 
                               class="form-control" 
                               id="identificacion" 
                               name="identificacion" 
                               placeholder="Identificador"
                               min="1"
                               required>
                        <div class="invalid-feedback">
                            El valor debe ser un número positivo.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="capacidadG" class="form-label">Capacidad</label>
                        <input type="number" 
                               class="form-control" 
                               id="capacidad" 
                               name="capacidad" 
                               placeholder="Capacidad de aves"
                               min="1"
                               required>
                        <div class="invalid-feedback">
                            El valor debe ser un número positivo.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="tipoAveG" class="form-label">Tipo de Ave</label>
                        <input type="text" 
                               class="form-control" 
                               id="idTipoAve" 
                               name="idTipoAve" 
                               placeholder="Tipo de Ave"
                               required>
                        <div class="invalid-feedback">
                            La habilitación debe tener al menos 3 caracteres.
                        </div>
                    </div>
                    <input type="hidden" id="idGranja" name="idGranja" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" name="btGalpon" value="editarGalpon" form="editarGalponForm">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var tiposAves = $tiposAves;
    function cargarOpciones() {
        console.log(tiposAves);
        const select = document.getElementById('opciones'); // Obtener el select

        // Limpiar las opciones actuales
        select.innerHTML = '';

        // Agregar la opción por defecto
        const defaultOption = document.createElement('option');
        defaultOption.text = 'Selecciona una opción';
        select.appendChild(defaultOption);

        // Agregar las opciones que vienen de PHP
        tiposAves.forEach(function(item) {
            const option = document.createElement('option');
            option.value = item.idTipoAve; // Asignar el ID de tipo ave
            option.text = item.nombre; // Mostrar el nombre del tipo de ave
            select.appendChild(option); // Añadir la opción al select
        });
    }
    // Llamar a la función para cargar las opciones al cargar la página
    window.onload = cargarOpciones;
</script>

<script src="js/validar_abmGalpones.js"></script>
HTML;

?>
