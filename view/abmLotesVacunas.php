<?php
$body = <<<HTML
<div class="container">
    <h1>Lotes de vacuna</h1>
    <div class="text-center mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newLoteVacuna">
          Agregar nuevo lote
        </button>
    </div>
    <table id="tablaLotesVacuna" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID</th>
                <th class="text-primary">Lote</th>
                <th class="text-primary">F.Compra</th>
                <th class="text-primary">Cantidad</th>
                <th class="text-primary">Vencimiento</th>
                <th class="text-primary">✏</th>
                <th class="text-primary">❌</th>
            </tr>
        </thead>
        <tbody id="lotesVacuna">
            <!-- Los datos se insertarán aquí -->
        </tbody>
    </table>
</div>

<!-- Modal agregar Lote de vacuna -->
<div class="modal fade" id="newLoteVacuna" tabindex="-1" aria-labelledby="newLoteVacunaModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newLoteVacunaModal">Agregar Lote de vacuna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="agregarLoteVacunaForm" class="needs-validation" novalidate>
                        <div class="mb-4">
                        <label for="nombre" class="form-label">Número de lote</label>
                        <input type="text" 
                               class="form-control" 
                               id="numeroLote" 
                               name="numeroLote" 
                               placeholder="Identificación del lote"
                               minlength="3"
                               required>
                            <div class="invalid-feedback">
                                El nombre debe tener al menos 3 caracteres.
                            </div>
                        </div>
                        <div class="mb-4">
                        <label for="fechaCompra" class="form-label">Fecha de compra</label>
                        <input type="date" class="form-control" 
                               id="fechaCompra" name="fechaCompra"
                               required>
                            <div class="invalid-feedback">
                                Seleccione una fecha válida.
                            </div>
                        </div>
                        <div class="mb-4">
                        <label for="fechaVencimiento" class="form-label">Fecha de vencimiento</label>
                        <input type="date" class="form-control" 
                               id="fechaVencimiento" name="fechaVencimiento"
                               required>
                            <div class="invalid-feedback">
                                Seleccione una fecha válida.
                            </div>
                        </div>
                        <div class="mb-4">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" 
                                class="form-control" 
                                id="cantidad" 
                                name="cantidad" 
                                placeholder="Lotes adquiridos"
                                min="1"
                                required>
                            <div class="invalid-feedback">
                                El valor debe ser un número positivo.
                            </div>
                        </div>
                        <input type="hidden" id="idVacuna" name="idVacuna">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" name="btnAgregarLoteVacuna">Finalizar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

<!------------------------------------------------->
<!--------- JavaScript de Lotes de Vacunas -------->
<!-------------------------------------------------> 
<!------- RELLENAR TABLA DE VACUNAS - AJAX -------->
<!-------------------------------------------------> 
function cargarTablaLotesVacuna(idVacuna) {
    //Vaciar la tabla
    if ($.fn.DataTable.isDataTable('#tablaLoteVacunas')) {
        $('#tablaLoteVacunas').DataTable().destroy();
    }
    var tablaLotesVacunaTbody = document.getElementById("lotesVacuna");
    tablaLotesVacunaTbody.innerHTML = '';

    // Realizar la solicitud AJAX
    fetch('index.php?opt=vacunas&ajax=getLotesVacuna&idVacuna=' + idVacuna)
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        // Recorrer los datos y crear las filas de la tabla
        data.forEach(lotevacuna => {
            var row = document.createElement("tr");
            row.className = "table-light";
            row.innerHTML = 
               '<td>' + lotevacuna.idLoteVacuna + '</td>' +
               '<td>' + lotevacuna.numeroLote + '</td>' +
               '<td>' + lotevacuna.fechaCompra + '</td>' +
               '<td>' + lotevacuna.cantidad + '</td>' +
               '<td>' + lotevacuna.vencimiento + '</td>' +
               '<td>' +
               '<button type="button" ' +
                    'class="btn btn-warning btn-sm" ' +
                    'data-bs-toggle="modal" ' +
                    'data-bs-target="#editarLoteVacuna" ' +
                    'data-id="' + lotevacuna.idLoteVacuna + '" ' +
                    'data-numeroLote="' + lotevacuna.numeroLote + '" ' +
                    'data-fechaCompra="' + lotevacuna.fechaCompra + '" ' +
                    'data-cantidad="' + lotevacuna.cantidad + '" ' +
                    'data-vencimiento="' + lotevacuna.vencimiento + '">' +
                    'Editar' +
                '</button>' +
            '</td>' +
            '<td>' +
                '<button type="button" class="btn btn-danger btn-sm" onclick="eliminarLoteVacuna(' + lotevacuna.idLoteVacuna + ')">Borrar</button>' +
            '</td>';
            tablaLotesVacunaTbody.appendChild(row);
        })
        $('#tablaVacunas').DataTable();
    })
    .catch(error => {
        console.error('Error al cargar vacunas:', error);
        $('#tablaVacunas').DataTable();
    });
}

window.addEventListener('load', function() {
    $('#tablaLotesVacuna').DataTable();
});
</script>
HTML;


include 'view/toast.php';
$body .= $toast;
?>