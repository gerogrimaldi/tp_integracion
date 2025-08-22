<?php
$idLoteAves = isset($_GET['idLoteAves']) ? (int)$_GET['idLoteAves'] : 0;
$body = <<<HTML
<div class="container">
    <h1>Registro de Mortandad</h1>

    <!-- Seleccionar Lote -->
    <div class="input-group mb-3">
        <select id="selectLote" name="selectLote" class="form-select rounded-start" style="width:70%" required>
            <!-- opciones cargadas por JS (Select2) -->
        </select>
        <button type="button" class="btn btn-primary rounded-end" data-bs-toggle="modal" data-bs-target="#modalMortandad">
            Registrar Mortandad
        </button>
        <div class="invalid-feedback">Debe elegir un lote.</div>
    </div>

    <!-- Card con datos del lote seleccionado -->
    <div id="cardLote" class="card mb-4 d-none">
        <div class="card-body">
            <h5 class="card-title">Datos del Lote</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Cantidad Original:</strong> <span id="datoCantidadOriginal"></span></li>
                <li class="list-group-item"><strong>Cantidad Actual:</strong> <span id="datoCantidadActual"></span></li>
                <li class="list-group-item"><strong>Tipo de Ave:</strong> <span id="datoTipoAve"></span></li>
                <li class="list-group-item"><strong>Fecha de Nacimiento:</strong> <span id="datoFechaNacimiento"></span></li>
                <li class="list-group-item"><strong>Fecha de Compra:</strong> <span id="datoFechaCompra"></span></li>
                <li class="list-group-item"><strong>Granja:</strong> <span id="datoGranja"></span></li>
                <li class="list-group-item"><strong>Galpón:</strong> <span id="datoGalpon"></span></li>
            </ul>
        </div>
    </div>

    <!-- Tabla de registros de mortandad -->
    <table id="tablaMortandad" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID</th>
                <th class="text-primary">Fecha</th>
                <th class="text-primary">Causa</th>
                <th class="text-primary">Cantidad</th>
                <th class="text-primary">Acciones</th>
            </tr>
        </thead>
        <tbody id="mortandadAves"></tbody>
    </table>
</div>

<!-- Modal Registrar Mortandad -->
<div class="modal fade" id="modalMortandad" tabindex="-1" aria-labelledby="modalMortandadLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalMortandadLabel">Registrar Mortandad</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formMortandad" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <label for="fechaMortandad" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fechaMortandad" name="fecha" required>
                        <div class="invalid-feedback">Seleccione una fecha válida.</div>
                    </div>
                    <div class="mb-4">
                        <label for="causaMortandad" class="form-label">Causa</label>
                        <input type="text" class="form-control" id="causaMortandad" name="causa" maxlength="100" required>
                        <div class="invalid-feedback">Ingrese una causa válida.</div>
                    </div>
                    <div class="mb-4">
                        <label for="cantidadMortandad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidadMortandad" name="cantidad" min="1" required>
                        <div class="invalid-feedback">Ingrese una cantidad válida.</div>
                    </div>
                    <input type="hidden" id="idLoteSeleccionado" name="idLoteAves">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnGuardarMortandad">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Mortandad -->
<div class="modal fade" id="modalEditarMortandad" tabindex="-1" aria-labelledby="modalEditarMortandadLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarMortandadLabel">Editar Mortandad</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarMortandad" class="needs-validation" novalidate>
                    <input type="hidden" id="editIdMortandad" name="idMortandad">
                    <div class="mb-4">
                        <label for="editFechaMortandad" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="editFechaMortandad" name="fecha" required>
                        <div class="invalid-feedback">Seleccione una fecha válida.</div>
                    </div>
                    <div class="mb-4">
                        <label for="editCausaMortandad" class="form-label">Causa</label>
                        <input type="text" class="form-control" id="editCausaMortandad" name="causa" maxlength="100" required>
                        <div class="invalid-feedback">Ingrese una causa válida.</div>
                    </div>
                    <div class="mb-4">
                        <label for="editCantidadMortandad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="editCantidadMortandad" name="cantidad" min="1" required>
                        <div class="invalid-feedback">Ingrese una cantidad válida.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnActualizarMortandad">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<script>
var idLotePreseleccion = <?php echo $idLoteAves; ?>;
//------------------------------------------------
// Inicialización: cargar lotes en el select
//------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    cargarLotes();
});

//------------------------------------------------
// Listeners de botones principales
//------------------------------------------------
document.getElementById('btnGuardarMortandad').addEventListener('click', function() {
    agregarMortandad();
});
document.getElementById('btnActualizarMortandad').addEventListener('click', function() {
    editarMortandad();
});

//------------------------------------------------
// Cambiar lote -> cargar datos + mortandad
//------------------------------------------------
document.getElementById('selectLote').addEventListener('change', function () {
    const idLote = this.value;
    if (idLote) {
        document.getElementById('idLoteSeleccionado').value = idLote;
        cargarDatosLote(idLote);
        cargarMortandad(idLote);
    }
});

//------------------------------------------------
// Rellenar modal de edición
//------------------------------------------------
document.addEventListener('click', function (event) {
    if (event.target && event.target.matches('.btn-edit')) {
        const id = event.target.getAttribute('data-id');
        const fecha = event.target.getAttribute('data-fecha');
        const causa = event.target.getAttribute('data-causa');
        const cantidad = event.target.getAttribute('data-cantidad');

        document.getElementById('editIdMortandad').value = id;
        document.getElementById('editFechaMortandad').value = fecha;
        document.getElementById('editCausaMortandad').value = causa;
        document.getElementById('editCantidadMortandad').value = cantidad;

        const modal = new bootstrap.Modal(document.getElementById('modalEditarMortandad'));
        modal.show();
    }
});

//------------------------------------------------
// Funciones AJAX
//------------------------------------------------
function cargarLotes() {
    fetch('index.php?opt=lotesAves&ajax=getAllLotes')
    .then(r => r.json())
    .then(data => {
        var select = document.getElementById('selectLote');
        select.innerHTML = '<option value="">Seleccione un lote...</option>';

        data.forEach(function(l){
            var selected = (idLotePreseleccion && idLotePreseleccion == l.idLoteAves) ? ' selected' : '';
            select.innerHTML += '<option value="' + l.idLoteAves + '"' + selected + '>' + l.identificador + '</option>';
        });

        // Si venía preseleccionado desde PHP, cargo mortandad
        if (idLotePreseleccion) {
            cargarMortandad(idLotePreseleccion);
        }

        // Si usás Select2, refrescarlo
        if ($(select).data('select2')) {
            $(select).trigger('change.select2');
        }
    })
    .catch(err => console.error('Error cargando lotes:', err));
}

function cargarDatosLote(idLote) {
    fetch('index.php?opt=lotesAves&ajax=getById&idLoteAves=' + idLote)
    .then(r => r.json())
    .then(l => {
        document.getElementById('datoCantidadOriginal').textContent = l.cantidadAves;
        document.getElementById('datoCantidadActual').textContent = l.cantidadActual ?? l.cantidadAves;
        document.getElementById('datoTipoAve').textContent = l.tipoAveNombre;
        document.getElementById('datoFechaNacimiento').textContent = l.fechaNacimiento;
        document.getElementById('datoFechaCompra').textContent = l.fechaCompra;
        document.getElementById('datoGranja').textContent = l.granjaNombre;
        document.getElementById('datoGalpon').textContent = l.galponIdentificacion;
        document.getElementById('cardLote').classList.remove('d-none');
    })
    .catch(err => console.error('Error cargando datos de lote:', err));
}

function cargarMortandad(idLote) {
    fetch('index.php?opt=lotesAves&ajax=getMuertes&idLoteAves=' + idLote)
    .then(r => r.json())
    .then(data => {
        var tbody = document.getElementById('mortandadAves');
        tbody.innerHTML = '';
        data.forEach(function(m){
            var btn = '<button class="btn btn-sm btn-warning btn-edit" ' +
                      'data-id="' + m.idMortandad + '" ' +
                      'data-fecha="' + m.fecha + '" ' +
                      'data-causa="' + m.causa + '" ' +
                      'data-cantidad="' + m.cantidad + '">Editar</button>';

            var row = '<tr>'
                    + '<td>' + m.idMortandad + '</td>'
                    + '<td>' + m.fecha + '</td>'
                    + '<td>' + m.causa + '</td>'
                    + '<td>' + m.cantidad + '</td>'
                    + '<td>' + btn + '</td>'
                    + '</tr>';

            tbody.insertAdjacentHTML('beforeend', row);
        });
    })
    .catch(err => console.error('Error cargando mortandad:', err));
}

function agregarMortandad() {
    const idLote = document.getElementById('idLoteSeleccionado').value;
    const fecha = document.getElementById('fechaMortandad').value;
    const causa = document.getElementById('causaMortandad').value;
    const cantidad = document.getElementById('cantidadMortandad').value;

    fetch('index.php?opt=lotesAves&ajax=addMuertes', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'idLoteAves=' + encodeURIComponent(idLote) +
              '&fecha=' + encodeURIComponent(fecha) +
              '&causa=' + encodeURIComponent(causa) +
              '&cantidad=' + encodeURIComponent(cantidad)
    })
    .then(r => r.json().then(data => {
        if (r.ok) {
            showToastOkay(data.msg);
            $('#modalMortandad').modal('hide');
            cargarMortandad(idLote);
        } else {
            showToastError(data.msg);
        }
    }))
    .catch(err => showToastError('Error en AJAX: ' + err.message));
}

function editarMortandad() {
    const idLote = document.getElementById('idLoteSeleccionado').value;
    const idMortandad = document.getElementById('editIdMortandad').value;
    const fecha = document.getElementById('editFechaMortandad').value;
    const causa = document.getElementById('editCausaMortandad').value;
    const cantidad = document.getElementById('editCantidadMortandad').value;

    fetch('index.php?opt=lotesAves&ajax=editMuertes', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'idLoteAves=' + encodeURIComponent(idLote) +
              '&idMortandad=' + encodeURIComponent(idMortandad) +
              '&fecha=' + encodeURIComponent(fecha) +
              '&causa=' + encodeURIComponent(causa) +
              '&cantidad=' + encodeURIComponent(cantidad)
    })
    .then(r => r.json().then(data => {
        if (r.ok) {
            showToastOkay(data.msg);
            $('#modalEditarMortandad').modal('hide');
            cargarMortandad(idLote);
        } else {
            showToastError(data.msg);
        }
    }))
    .catch(err => showToastError('Error en AJAX: ' + err.message));
}
</script>


HTML;

include 'view/toast.php';
$body .= $toast;
?>
