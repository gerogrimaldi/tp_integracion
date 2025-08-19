<?php
$body = <<<HTML
<div class="container">
    <h1>Lotes de aves</h1>
    
    <p class="d-inline-flex gap-1">
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#verTipoAve" aria-expanded="false" aria-controls="collapseExample">
            Ver tipos de aves
        </button>
    </p>

    <div class="collapse mb-4" id="verTipoAve">
        <div class="mb-3">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#agregarTipoAve" aria-expanded="false" aria-controls="collapseExample">
                Agregar tipos de Aves
            </button>
        </div>
        
        <div class="collapse mb-4" id="agregarTipoAve">
            <div class="card card-body text-dark">
                <form id="agregarTipoAveForm" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <label for="nombreTipo" class="form-label">Tipo de Ave</label>
                        <input type="text" class="form-control" 
                            id="nombreTipo" name="nombreTipo"
                            placeholder="Ejemplo: ponedora cuello pelado semipesada "
                            min="1" required>
                        <div class="invalid-feedback">
                            Debe contar con al menos 3 letras.
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="btnAgregarTipoAve">Agregar</button>
                </form>
            </div>
        </div>
        
        <div class="card card-body text-dark">
            <table id="tablaTiposAve" class="table table-bordered bg-white">
                <thead class="table-light">
                    <tr>
                        <th class="text-primary">ID</th>
                        <th class="text-primary">Descripción</th>
                        <th class="text-primary">✏</th>
                        <th class="text-primary">❌</th>
                    </tr>
                </thead>
                <tbody id="tipoAve">
                    <!-- Los datos se insertarán aquí -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!------------------------------------------------> 
<!-- MODAL: EDITAR TIPO DE Ave -->
<!------------------------------------------------> 
<div class="modal fade" id="editarTipoMant" tabindex="-1" aria-labelledby="editarTipoAveModal" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarTipoAveModal">Editar descripción del tipo de ave</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarTipoAveForm" class="needs-validation" novalidate>
                <div class="mb-4">
                    <label for="nombreMant" class="form-label">Tipo de ave</label>
                    <input type="text" class="form-control" 
                        id="nombreTipoEdit" name="nombreTipoEdit"
                        placeholder="Ejemplo: ponedora barrada semipesada"
                        min="1" required>
                    <div class="invalid-feedback">
                        Debe contar con al menos 3 letras.
                    </div>
                </div>
                    <input type="hidden" id="idTipoAve" name="idTipoAve">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnEditarTipoAve">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<script>
//------------------------------------------------
// Captar botones de agregar
//------------------------------------------------
document.getElementById('btnAgregarTipoAve').addEventListener('click', function() {
    agregarTipoAve();
});
document.getElementById('agregarTipoAveForm').addEventListener('submit', function(event) {
    event.preventDefault();
    agregarTipoAve();
});

//------------------------------------------------
// Captar botones de edición
//------------------------------------------------
document.getElementById('btnEditarTipoAve').addEventListener('click', function() {
    editarTipoAve();
});
document.getElementById('editarTipoAveForm').addEventListener('submit', function(event) {
    event.preventDefault();
    editarTipoAve();
});

//------------------------------------------------
// Rellenar modal de edición
//------------------------------------------------
document.addEventListener('click', function (event) {
    if (event.target && event.target.matches('.btn-warning')) {
        const button = event.target;
        const idTipoAve = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');
        document.querySelector('#editarTipoAveForm #nombreTipoEdit').value = nombre;
        document.querySelector('#editarTipoAveForm #idTipoAve').value = idTipoAve;
    }
});

//------------------------------------------------
// Funciones ABM
//------------------------------------------------
function agregarTipoAve() {
    const nombreTipo = document.getElementById('nombreTipo').value;

    fetch('index.php?opt=lotesAves&ajax=addTipoAve', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'nombre=' + encodeURIComponent(nombreTipo)
    })
    .then(response => response.json().then(data => {
        if (response.ok) {
            recargarTipoAve();
            $('#agregarTipoAve').collapse('hide');
            showToastOkay(data.msg);
        } else {
            showToastError(data.msg);
        }
    }))
    .catch(error => {
        console.error('Error AJAX:', error);
        showToastError('Error en la solicitud: ' + error.message);
    });
}

function editarTipoAve() {
    const idTipoAve = document.getElementById('idTipoAve').value;
    const nombreTipoEdit = document.getElementById('nombreTipoEdit').value;

    fetch('index.php?opt=lotesAves&ajax=editTipoAve', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'idTipoAve=' + encodeURIComponent(idTipoAve) +
              '&nombre=' + encodeURIComponent(nombreTipoEdit)
    })
    .then(response => response.json().then(data => {
        if (response.ok) {
            recargarTipoAve();
            showToastOkay(data.msg);
            $('#editarTipoMant').modal('hide');
        } else {
            showToastError(data.msg);
        }
    }))
    .catch(error => {
        console.error('Error AJAX:', error);
        showToastError('Error desconocido.');
    });
}

function eliminarTipoAve(idTipoAve) {
    fetch('index.php?opt=lotesAves&ajax=delTipoAve&idTipoAve=' + idTipoAve, {
        method: 'GET'
    })
    .then(response => response.json().then(data => {
        if (response.ok) {
            recargarTipoAve();
            showToastOkay(data.msg);
        } else {
            showToastError(data.msg);
        }
    }))
    .catch(error => {
        console.error('Error AJAX:', error);
        showToastError('Error desconocido.');
    });
}

function recargarTipoAve() {
    cargarTablaTipoAve();
}

function cargarTablaTipoAve() {
    if ($.fn.DataTable.isDataTable('#tablaTiposAve')) {
        $('#tablaTiposAve').DataTable().destroy();
    }
    var tbody = document.getElementById("tipoAve");
    tbody.innerHTML = '';

    fetch('index.php?opt=lotesAves&ajax=getTipoAve')
    .then(response => response.json())
    .then(data => {
        data.forEach(tipoAve => {
            var row = document.createElement("tr");
            row.className = "table-light";
            row.innerHTML = 
                '<td>' + tipoAve.idTipoAve + '</td>' +
                '<td>' + tipoAve.nombre + '</td>' +
                '<td><button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editarTipoMant" data-id="' + tipoAve.idTipoAve + '" data-nombre="' + tipoAve.nombre + '">Editar</button></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarTipoAve(' + tipoAve.idTipoAve + ')">Borrar</button></td>';
            tbody.appendChild(row);
        });
        $('#tablaTiposAve').DataTable();
    })
    .catch(error => {
        console.error('Error al cargar tipos de aves:', error);
        $('#tablaTiposAve').DataTable();
    });
}

window.addEventListener('load', function() {
    cargarTablaTipoAve();
});
</script>
HTML;
// Agregar las funciones y el contenedor de los toast
// Para mostrar notificaciones
include 'view/toast.php';
$body .= $toast;
?>

