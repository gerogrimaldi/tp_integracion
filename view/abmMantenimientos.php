<?php

	/*
        Al cargar la página sin argumentos: Un colapse para los tipos de mantenimiento.
        Luego, Opción de seleccionar la granja que se desea ver los mantenimientos.
        Debajo: la tabla de las granjas (sin datos mientras no se seleccione nada)
        Repetir lo superior pero con la lista de los galpones, sin diferenciar por granja.
    */
$idGranja = isset($_GET['idGranja']) ? $_GET['idGranja'] : '';

$body = <<<HTML
<div class="container">
    <h1>Mantenimientos</h1>

    
<p class="d-inline-flex gap-1">
  <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#agregarMant" aria-expanded="false" aria-controls="collapseExample">
    Agregar tipos de Mantenimientos
  </button>
  <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#verMant" aria-expanded="false" aria-controls="collapseExample">
    Ver tipos de mantenimientos
  </button>
</p>
<div class="collapse" id="agregarMant">
  <div class="card card-body">
    <form id="agregrarTipoMantenimiento" action="index.php?opt=mantenimientos" method="POST" class="needs-validation" novalidate>
        <div class="mb-4">
            <label for="nombreMant" class="form-label">Tipo de mantenimiento</label>
            <input type="text" class="form-control" 
                id="nombreMant" name="nombreMant"
                placeholder="Ejemplo: Corte de césped"
                min="1" required>
            <div class="invalid-feedback">
                Debe contar con al menos 3 letras.
            </div>
        </div>
        <button type="submit" class="btn btn-primary" name="btMantenimientos" value="addTipoMant" form="agregrarTipoMantenimiento">Agregar</button>
    </form>
  </div>
</div>
<div class="collapse" id="verMant">
  <div class="card card-body">
    <table id="tablaTiposMant" class="table table-bordered bg-white">
            <thead class="table-light">
                <tr>
                    <th class="text-primary">ID</th>
                    <th class="text-primary">Descripción</th>
                    <th class="text-primary"></th>
                    <th class="text-primary"></th>
                </tr>
            </thead>
            <tbody id="tipoMant">
                <!-- Los datos se insertarán aquí -->
            </tbody>
        </table>
  </div>
</div>

<!-- JS Para rellenar tabla tipo mantenimientos -->
<script>
    var tipoMant = $tiposMant;
    var tipoMantTbody = document.getElementById("tipoMant");
    tipoMant.forEach(
        function(tipoMant) {
        var row = document.createElement("tr");
        row.className = "table-light";
        row.innerHTML = 
    '<td>' + tipoMant.idTipoMantenimiento + '</td>' +
    '<td>' + tipoMant.nombre + '</td>' +
    '<td>' +
        '<button type="button" ' +
            'class="btn btn-warning btn-sm" ' +
            'data-bs-toggle="modal" ' +
            'data-bs-target="#editarTipoMant" ' +
            'data-id="' + tipoMant.idTipoMantenimiento + '" ' +
            'data-nombre="' + tipoMant.nombre + '">' +
            'Editar' +
        '</button>' +
    '</td>' +
    '<td>' +
        '<a href="index.php?opt=mantenimientos&deletetm=true&idTipoMant=' + tipoMant.idTipoMantenimiento + '" ' +
            'class="btn btn-danger btn-sm">' +
            'Borrar' +
        '</a>' +
    '</td>';

        tipoMantTbody.appendChild(row);
    });
    $(document).ready(function() {
        $("#tablaTiposMant").DataTable();
    });
</script>

<!-- Modal editar Tipo Mantenimiento -->
<div class="modal fade" id="editarTipoMant" tabindex="-1" aria-labelledby="editarTipoMantModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarTipoMantModal">Editar descripción del mantenimiento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarTipoMantForm" action="index.php?opt=mantenimientos" method="POST" class="needs-validation" novalidate>
                <div class="mb-4">
                    <label for="nombreMant" class="form-label">Tipo de mantenimiento</label>
                    <input type="text" class="form-control" 
                        id="nombreMantEdit" name="nombreMantEdit"
                        placeholder="Ejemplo: Corte de césped"
                        min="1" required>
                    <div class="invalid-feedback">
                        Debe contar con al menos 3 letras.
                    </div>
                </div>
                    <input type="hidden" id="idTipoMant" name="idTipoMant">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" name="btMantenimientos" value="editTipoMant" form="editarTipoMantForm">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<!-- JS para rellenar el campo al editar un tipo de mantenimiento -->
<script>
document.addEventListener('click', function (event) {
    if (event.target && event.target.matches('.btn-warning')) {
        const button = event.target;
        // Extrae los datos del botón
        const idTipoMant = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');
        // Rellena los campos del formulario en el modal
        document.querySelector('#editarTipoMantForm #nombreMantEdit').value = nombre;
        document.querySelector('#editarTipoMantForm #idTipoMant').value = idTipoMant;
    }
});
</script>


</div>

    <table id="tablaMantenimientos" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID</th>
                <th class="text-primary">Fecha</th>
                <th class="text-primary">Mantenimiento</th>
                <th class="text-primary">Granja</th>
                <th class="text-primary"></th>
                <th class="text-primary"></th>
            </tr>
        </thead>
        <tbody id="mantenimientos">
            <!-- Los datos se insertarán aquí -->
        </tbody>
    </table>
</div>

<script>
    // Comentado para evitar el uso de GPT y empezar a razonar mejor el JS.
    // Recupera el resultado de PHP en JSON y lo almacena en una variable JS.
    var mantenimientos = $resultado;
    // Crea una variable Tbody que es igual elemento con ese ID en el HTML (la tabla en este caso).
    var mantenimientosTbody = document.getElementById("mantenimientos");
    // Realiza un recorrido por cada elemento del arreglo, y ejecuta la "function"
    mantenimientos.forEach(
        function(mantenimientos) {
            //Row: Fila. Damos forma a un "<tr>" que es una fila en HTML (table-row)
        var row = document.createElement("tr");
        // Asignamos los atributos en HTML del <tr>.
        row.className = "table-light";
        // Crea los <td> (columnas). Están completas de los datos de la variable mantenimientos, o botones.
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
        // Agrega al TBody la línea de HTML completa.
        galponTbody.appendChild(row);
    });

    // Inicializar DataTable - Es un "plugin" que le agrega la busqueda, ordenamiento, etc. a la tabla.
    // Se le pasa como parámetro la tabla HTML que creamos.
    $(document).ready(function() {
        $("#tablaMantenimientos").DataTable();
    });
</script>
HTML;

?>
