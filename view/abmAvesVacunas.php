<?php
$idLoteAves = isset($_GET['idLoteAves']) ? (int)$_GET['idLoteAves'] : 0;
$body = <<<HTML
<div class="container">
    <h1>Aplicación de Vacunas</h1>

    <!-- Seleccionar Lote de Aves -->
    <div class="input-group mb-3">
        <select id="selectLote" name="selectLote" class="form-select rounded-start" style="width:70%" required>
            <!-- opciones cargadas por JS (Select2) -->
        </select>
        <button type="button" class="btn btn-primary rounded-end" data-bs-toggle="modal" data-bs-target="#modalAplicarVacuna">
            Aplicar Vacuna
        </button>
        <div class="invalid-feedback">Debe elegir un lote.</div>
    </div>

    <!-- Card con datos del lote seleccionado -->
    <div id="cardLote" class="card mb-4 d-none">
        <div class="card-body">
            <h5 class="card-title">Datos del Lote</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Último peso registrado (kg):</strong> <span id="datoUltimoPeso"></span></li>
                <li class="list-group-item"><strong>Cantidad de Aves Compradas:</strong> <span id="datoCantidadOriginal"></span></li>
                <li class="list-group-item"><strong>Cantidad Actual:</strong> <span id="datoCantidadActual"></span></li>
                <li class="list-group-item"><strong>Tipo de Ave:</strong> <span id="datoTipoAve"></span></li>
                <li class="list-group-item"><strong>Fecha de Nacimiento:</strong> <span id="datoFechaNacimiento"></span></li>
                <li class="list-group-item"><strong>Fecha de Compra:</strong> <span id="datoFechaCompra"></span></li>
                <li class="list-group-item"><strong>Granja:</strong> <span id="datoGranja"></span></li>
                <li class="list-group-item"><strong>Galpón:</strong> <span id="datoGalpon"></span></li>
            </ul>
        </div>
    </div>

    <!-- Tabla de aplicaciones de vacunas -->
    <div class="card shadow-sm rounded-3">
        <div class="card-body table-responsive">
            <table id="tablaVacunas" class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-primary">ID</th>
                        <th class="text-primary">Vacuna</th>
                        <th class="text-primary">Lote</th>
                        <th class="text-primary">Fecha</th>
                        <th class="text-primary">Cantidad</th>
                        <th class="text-primary">Acciones</th>
                    </tr>
                </thead>
                <tbody id="aplicacionesVacunas"></tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Aplicar Vacuna -->
<div class="modal fade" id="modalAplicarVacuna" tabindex="-1" aria-labelledby="modalAplicarVacunaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAplicarVacunaLabel">Aplicar Vacuna</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formAplicarVacuna" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="selectVacunaModal" class="form-label">Vacuna</label>
                        <select id="selectVacunaModal" class="form-select" required>
                            <option value="">Seleccione una vacuna...</option>
                        </select>
                        <div class="invalid-feedback">Seleccione una vacuna.</div>
                    </div>
                    <div class="mb-3">
                        <label for="selectLoteVacunaModal" class="form-label">Lote de Vacuna</label>
                        <select id="selectLoteVacunaModal" class="form-select" disabled required>
                            <option value="">Seleccione una vacuna primero...</option>
                        </select>
                        <div class="invalid-feedback">Seleccione un lote de vacuna.</div>
                    </div>
                    <div class="mb-3">
                        <label for="fechaVacuna" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fechaVacuna" required>
                        <div class="invalid-feedback">Seleccione una fecha válida (no futura).</div>
                    </div>
                    <div class="mb-3">
                        <label for="cantidadVacuna" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidadVacuna" min="1" required>
                        <div class="invalid-feedback">Ingrese una cantidad válida.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnGuardarVacuna">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
var idLoteAves = $idLoteAves;

//------------------------------------------------
// Cargar Lotes de Aves en select principal
//------------------------------------------------
function cargarLotes() {
    return fetch('index.php?opt=lotesAves&ajax=getAllLotesAves')
    .then(r => r.json())
    .then(data => {
        var select = $('#selectLote');
        select.empty().append('<option value="">Seleccione un lote...</option>');
        data.forEach(function(l) {
            var isSelected = (l.idLoteAves == idLoteAves);
            select.append(new Option(l.identificador, l.idLoteAves, isSelected, isSelected));
        });
        select.trigger('change');
    });
}

//------------------------------------------------
// Cargar datos del lote
//------------------------------------------------
function cargarDatosLote(idLote) {
    fetch('index.php?opt=lotesAves&ajax=getLoteAvesById&idLoteAves=' + idLote)
    .then(r => r.json())
    .then(l => {
        document.getElementById('datoUltimoPeso').textContent = l.ultimoPeso ?? 'Sin registro';
        document.getElementById('datoCantidadOriginal').textContent = l.cantidadAves;
        document.getElementById('datoCantidadActual').textContent = l.cantidadActual ?? l.cantidadAves;
        document.getElementById('datoTipoAve').textContent = l.tipoAveNombre;
        document.getElementById('datoFechaNacimiento').textContent = l.fechaNacimiento;
        document.getElementById('datoFechaCompra').textContent = l.fechaCompra;
        document.getElementById('datoGranja').textContent = l.granjaNombre;
        document.getElementById('datoGalpon').textContent = l.galponIdentificacion;
        $('#cardLote').removeClass('d-none');
    });
}

//------------------------------------------------
// Cargar tabla de aplicaciones de vacunas
//------------------------------------------------
function cargarAplicacionesVacunas(idLote) {
    if ($.fn.DataTable.isDataTable('#tablaVacunas')) {
            $('#tablaVacunas').DataTable().destroy();
    }
    var tablaVacunasTbody = document.getElementById("aplicacionesVacunas");
    tablaVacunasTbody.innerHTML = '';
    fetch('index.php?opt=lotesAves&ajax=getVacunas&idLoteAves=' + idLote)
    .then(r => r.json())
    .then(data => {
        console.log(data);
        var tbody = $('#aplicacionesVacunas');
        tbody.empty();
        data.forEach(v => {
            var row = '<tr>' +
                '<td>' + v.idloteVacuna_loteAve + '</td>' +
                '<td>' + v.vacunaNombre + '</td>' +
                '<td>' + v.numeroLote + '</td>' +
                '<td>' + v.fecha + '</td>' +
                '<td>' + v.cantidad + '</td>' +
                '<td>' +
                    //'<button class="btn btn-sm btn-warning btn-edit" data-id="' + v.idloteVacuna_loteAve + '">Editar</button> ' +
                    '<button class="btn btn-sm btn-danger btn-delete" data-id="' + v.idloteVacuna_loteAve + '">Eliminar</button>' +
                '</td>' +
                '</tr>';
            tbody.append(row);
        });
        $('#tablaVacunas').DataTable();
    });
}

// Listener delegado para eliminar aplicación de vacuna
$(document).on('click', '.btn-delete', function () {
    const idAplicacion = $(this).data('id');
    fetch('index.php?opt=lotesAves&ajax=delVacuna&idAplicacion=' + idAplicacion, {
        method: 'DELETE'
    })
    .then(r => r.json())
    .then(data => {
        if (data.msg) showToastOkay(data.msg);
        // recargar tabla
        cargarAplicacionesVacunas($('#selectLote').val());
    })
    .catch(err => {
        console.error(err);
        showToastError('Error al eliminar');
    });
});

//------------------------------------------------
// Inicialización Select2 y eventos
//------------------------------------------------
$(document).ready(function() {
    $('#selectLote').select2({ theme: 'bootstrap-5', placeholder: "Seleccione un lote...", width: 'resolve' });
    $('#selectVacunaModal').select2({ theme: 'bootstrap-5', placeholder: "Seleccione una vacuna...", width: '100%' });
    $('#selectLoteVacunaModal').select2({ theme: 'bootstrap-5', placeholder: "Seleccione un lote...", width: '100%' });

    cargarLotes();

    $('#selectLote').on('change', function() {
        const idLote = $(this).val();
        if (idLote) {
            cargarDatosLote(idLote);
            cargarAplicacionesVacunas(idLote);
            $('#cardLote').show();
        } else {
            $('#cardLote').hide();
        }
    });

    // Fecha por defecto
    const today = new Date().toISOString().split('T')[0];
    $('#fechaVacuna').val(today);


    //A partir de aca los Listener de los modales y los botones
    //------------------------------------------------
    // Abrir modal aplicar vacuna -> cargar vacunas
    //------------------------------------------------
    $('#modalAplicarVacuna').on('show.bs.modal', function () {
        const idLote = $('#selectLote').val();
        if (!idLote) {
            showToastError('Debe seleccionar un lote primero');
            return;
        }

        // Limpiar selects
        $('#selectVacunaModal').empty().append('<option value="">Seleccione una vacuna...</option>');
        $('#selectLoteVacunaModal').empty().append('<option value="">Seleccione una vacuna primero...</option>').prop('disabled', true);

        // Cargar vacunas
        fetch('index.php?opt=vacunas&ajax=getVacunas')
        .then(r => r.json())
        .then(vacunas => {
            vacunas.forEach(v => $('#selectVacunaModal').append(new Option(v.nombre, v.idVacuna)));
            $('#selectVacunaModal').trigger('change');
        });
    });

    //------------------------------------------------
    // Selección de vacuna -> habilitar lotes
    //------------------------------------------------
    $('#selectVacunaModal').on('change', function() {
        const idVacuna = $(this).val();
        const selectLote = $('#selectLoteVacunaModal');
        selectLote.empty().append('<option value="">Seleccione un lote...</option>');
        if (idVacuna) {
            fetch('index.php?opt=vacunas&ajax=getLotesVacuna&idVacuna=' + idVacuna)
            .then(r => r.json())
            .then(lotes => {
                console.log("Vacunas recibidas:", lotes); // debug
                lotes.forEach(l => selectLote.append(new Option(l.numeroLote, l.idLoteVacuna)));
                selectLote.prop('disabled', false).trigger('change');
            });
        } else {
            selectLote.prop('disabled', true);
        }
    });

    //------------------------------------------------
    // Guardar aplicación de vacuna
    //------------------------------------------------
    $('#btnGuardarVacuna').on('click', function() {
        const idLoteAves = $('#selectLote').val(); // ✅ ahora toma el lote de aves
        const idLoteVacuna = $('#selectLoteVacunaModal').val(); // este es el lote de vacuna seleccionado
        const fecha = $('#fechaVacuna').val();
        const cantidad = $('#cantidadVacuna').val();

        if (!idLoteAves || !idLoteVacuna || !fecha || !cantidad) {
            showToastError('Complete todos los campos');
            return;
        }

        fetch('index.php?opt=lotesAves&ajax=addVacuna', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'idLoteAves=' + encodeURIComponent(idLoteAves) +
                '&idLoteVacuna=' + encodeURIComponent(idLoteVacuna) +
                '&fecha=' + encodeURIComponent(fecha) +
                '&cantidad=' + encodeURIComponent(cantidad)
        })
        .then(r => r.json())
        .then(data => {
            if (data.msg) showToastOkay(data.msg);

            // Cerrar modal correctamente en Bootstrap 5
            var modalVacuna = bootstrap.Modal.getInstance(document.getElementById('modalAplicarVacuna'));
            modalVacuna.hide();

            // Recargar tabla con el lote correcto
            cargarAplicacionesVacunas(idLoteAves);
        });
    });
});
</script>
HTML;

include 'view/toast.php';
$body .= $toast;

// Incluir el archivo de validaciones
$body .= '<script src="js/clientValidation.js"></script>';
?>
