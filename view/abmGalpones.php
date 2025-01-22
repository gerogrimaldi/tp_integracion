<?php

$error = $error ?? ''; // Definir $error como cadena vacía si no está definido
$idGranja = isset($_GET['idGranja']) ? $_GET['idGranja'] : '';

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
                <th class="text-primary">Tipo de Ave</th>
                <th class="text-primary">Capacidad</th>
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
    var idGranjaJava = $idGranja;
    // Procesar los datos y crear filas en la tabla
    var galponTbody = document.getElementById("galpon");
    
    galpon.forEach(function(galpon) {
        var row = document.createElement("tr");
        row.className = "table-light";
        row.innerHTML = 
            '<td>' + galpon.idGalpon + '</td>' +
            '<td>' + galpon.identificacion + '</td>' +
            '<td>' + galpon.nombre + '</td>' +
            '<td>' + galpon.capacidad + '</td>' +
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
                <form id="editarGalponForm" action="index.php?opt=galpones" method="POST" class="needs-validation" novalidate>
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
                        <select id="opcionesEditar" name="opcionesEditar" class="form-control">
                            <!-- Las opciones se agregarán aquí con JavaScript -->
                        </select>
                        <div class="invalid-feedback">
                            La habilitación debe tener al menos 3 caracteres.
                        </div>
                    </div>
                    <input type="hidden" id="idGranja" name="idGranja">
                    <input type="hidden" id="idGalpon" name="idGalpon">
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
    document.getElementById('idGranja').value = idGranjaJava;
    var tiposAves = $tiposAves;
    function cargarOpciones() {
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

<script>
function cargarOpciones() {
    // Obtener los dos selects: uno para agregar y otro para editar
    const selectAgregar = document.getElementById('opciones');
    const selectEditar = document.getElementById('opcionesEditar');

    // Limpiar las opciones actuales
    selectAgregar.innerHTML = '';
    selectEditar.innerHTML = '';

    // Agregar la opción por defecto
    const defaultOption = document.createElement('option');
    defaultOption.text = 'Selecciona una opción';
    defaultOption.value = '';
    selectAgregar.appendChild(defaultOption);
    selectEditar.appendChild(defaultOption.cloneNode(true));

    // Agregar las opciones desde el array de tipos de aves (tiposAves)
    tiposAves.forEach(function (item) {
        const optionAgregar = document.createElement('option');
        const optionEditar = document.createElement('option');

        optionAgregar.value = item.idTipoAve;
        optionAgregar.text = item.nombre;

        optionEditar.value = item.idTipoAve;
        optionEditar.text = item.nombre;

        selectAgregar.appendChild(optionAgregar);
        selectEditar.appendChild(optionEditar);
    });
}

// Llama a cargarOpciones cuando la página cargue
window.onload = cargarOpciones;

// Lógica para rellenar el modal de edición
document.addEventListener('click', function (event) {
    if (event.target && event.target.matches('.btn-warning')) {
        const button = event.target;

        // Extrae los datos del botón
        const idGalpon = button.getAttribute('data-id');
        const identificacion = button.getAttribute('data-identificacion');
        const idTipoAve = button.getAttribute('data-idTipoAve');
        const capacidad = button.getAttribute('data-capacidad');
        const idGranja = button.getAttribute('data-idGranja');

        // Rellena los campos del formulario en el modal
        document.querySelector('#editarGalponForm #identificacion').value = identificacion;
        document.querySelector('#editarGalponForm #capacidad').value = capacidad;

        // Selecciona la opción correcta en el select
        const opcionesEditar = document.querySelector('#editarGalponForm #opcionesEditar');
        opcionesEditar.value = idTipoAve; // Selecciona el valor correcto
        if (opcionesEditar.value !== idTipoAve) {
            // Si el valor no está presente, agrega la opción faltante
            const nuevaOpcion = document.createElement('option');
            nuevaOpcion.value = idTipoAve;
            nuevaOpcion.text = 'Opción desconocida';
            opcionesEditar.appendChild(nuevaOpcion);
            opcionesEditar.value = idTipoAve;
        }

        // Asigna el ID de la granja (oculto)
        document.querySelector('#editarGalponForm #idGranja').value = idGranja;
        document.querySelector('#editarGalponForm #idGalpon').value = idGalpon;
    }
});
</script>

<script src="js/validar_abmGalpones.js"></script>
HTML;

?>
