<?php
    echo var_dump($loteJSON);
	/*
        Al cargar la página sin argumentos:
        - Boton: agregar vacuna
        - Mostrar vacunas

        Sector lote de vacunas:
        - Form de seleccionar la vacuna que se desea ver los lotes, con botón filtrar
        - Botón de agregar lote
        - Tabla: id lote, vacuna, info, botón eliminar, botón editar
    */

// Si no hay lote de vacuna seleccionada, se carga un array vacío para que la tabla no de error.
$resultado = $resultado ?? '[]'; 

$body = <<<HTML
<div class="container">
    <h1>Vacunas</h1>

    <div class="text-center mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newVacuna">
          Agregar nueva vacuna
        </button>
    </div>

    </div>
        <table id="tablaVacunas" class="table table-bordered bg-white">
            <thead class="table-light">
                <tr>
                    <th class="text-primary">ID</th>
                    <th class="text-primary">Nombre</th>
                    <th class="text-primary">Via Aplicacion</th>
                    <th class="text-primary">Marca</th>
                    <th class="text-primary">Enfermedad</th>
                    <th class="text-primary"></th>
                    <th class="text-primary"></th>
                </tr>
            </thead>
            <tbody id="vacunas">
                <!-- Los datos se insertarán aquí -->
            </tbody>
        </table>
    </div>

    <!-- Modal agregar Vacuna -->
    <div class="modal fade" id="newVacuna" tabindex="-1" aria-labelledby="newVacunaModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newVacunaModal">Agregar Vacuna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newVacunaForm" action="index.php?opt=vacunas" method="POST" class="needs-validation" novalidate>
                        <div class="mb-4">
                        <label for="nombre" class="form-label">Nombre comercial</label>
                        <input type="text" 
                               class="form-control" 
                               id="nombre" 
                               name="nombre" 
                               placeholder="Nombre"
                               minlength="3"
                               required>
                            <div class="invalid-feedback">
                                El nombre debe tener al menos 3 caracteres.
                            </div>
                        </div>
                        <div class="mb-4">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" 
                               class="form-control" 
                               id="marca" 
                               name="marca" 
                               placeholder="Marca"
                               minlength="3"
                               required>
                            <div class="invalid-feedback">
                                La marca debe tener al menos 3 carácteres.
                            </div>
                        </div>
                        <div class="mb-4">
                        <label for="enfermedad" class="form-label">Enfermedad</label>
                        <input type="text" 
                               class="form-control" 
                               id="enfermedad" 
                               name="enfermedad" 
                               placeholder="Enfermedad"
                               minlength="3"
                               required>
                            <div class="invalid-feedback">
                                La enfermedad debe tener al menos 3 carácteres.
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="viaAplic" class="form-label">Via de aplicación</label>
                            <select id="selectViaAplicacion" name="viaAplicacion" class="form-control">
                                <!-- Las opciones se agregarán aquí con JavaScript -->
                            </select>
                            <div class="invalid-feedback">
                                Seleccione una opción
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" name="btVacunas" value="newVacuna" form="newVacunaForm">Finalizar</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal editar Vacuna -->
    <div class="modal fade" id="editVacuna" tabindex="-1" aria-labelledby="editVacunaModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editVacunaModal">Editar Vacuna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editVacunaForm" action="index.php?opt=vacunas" method="POST" class="needs-validation" novalidate>
                        <div class="mb-4">
                        <label for="nombre" class="form-label">Nombre comercial</label>
                        <input type="text" 
                               class="form-control" 
                               id="nombre" 
                               name="nombre" 
                               placeholder="Nombre"
                               minlength="3"
                               required>
                            <div class="invalid-feedback">
                                El nombre debe tener al menos 3 caracteres.
                            </div>
                        </div>
                        <div class="mb-4">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" 
                               class="form-control" 
                               id="marca" 
                               name="marca" 
                               placeholder="Marca"
                               minlength="3"
                               required>
                            <div class="invalid-feedback">
                                La marca debe tener al menos 3 carácteres.
                            </div>
                        </div>
                        <div class="mb-4">
                        <label for="enfermedad" class="form-label">Enfermedad</label>
                        <input type="text" 
                               class="form-control" 
                               id="enfermedad" 
                               name="enfermedad" 
                               placeholder="Enfermedad"
                               minlength="3"
                               required>
                            <div class="invalid-feedback">
                                La enfermedad debe tener al menos 3 carácteres.
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="viaAplic" class="form-label">Via de aplicación</label>
                            <select id="selectViaAplicacionEdit" name="viaAplicacion" class="form-control">
                                <!-- Las opciones se agregarán aquí con JavaScript -->
                            </select>
                            <div class="invalid-feedback">
                                Seleccione una opción
                            </div>
                        </div>
                        <input type="hidden" id="idVacuna" name="idVacuna">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" name="btVacunas" value="editVacuna" form="editVacunaForm">Finalizar</button>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<div class="container">
    <h1>Lotes de vacunas</h1>
    <i>Seleccione una vacuna en la tabla superior para operar con sus lotes.</i>
    <div class="text-center mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newLoteVacuna">
          Agregar nuevo lote
        </button>
    </div>
    <table id="tablaLotesVacunas" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID</th>
                <th class="text-primary">Lote</th>
                <th class="text-primary">F.Compra</th>
                <th class="text-primary">Cantidad</th>
                <th class="text-primary">Vencimiento</th>
                <th class="text-primary">Nombre</th>
                <th class="text-primary"></th>
                <th class="text-primary"></th>
            </tr>
        </thead>
        <tbody id="loteVacunas">
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
                    <form id="newLoteVacunaForm" action="index.php?opt=vacunas" method="POST" class="needs-validation" novalidate>
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
                    <button type="submit" class="btn btn-primary" name="btVacunas" value="newLoteVacuna" form="newLoteVacunaForm">Finalizar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script JS para rellenar la tabla de vacunas -->
<script>
       var vacunasSQL = $vacunasJSON;
       var vacunasTbody = document.getElementById("vacunas");
       vacunasSQL.forEach(
           function(vacunasSQL) {
           var row = document.createElement("tr");
           row.className = "table-light";
           row.innerHTML = 
               '<td>' + vacunasSQL.idVacuna + '</td>' +
               '<td>' + vacunasSQL.nombre + '</td>' +
               '<td>' + vacunasSQL.via + '</td>' +
               '<td>' + vacunasSQL.marca + '</td>' +
               '<td>' + vacunasSQL.enfermedad + '</td>' +
               '<td>' +
               '<button type="button" ' +
                    'class="btn btn-warning btn-sm" ' +
                    'data-bs-toggle="modal" ' +
                    'data-bs-target="#editVacuna" ' +
                    'data-id="' + vacunasSQL.idVacuna + '" ' +
                    'data-nombre="' + vacunasSQL.nombre + '" ' +
                    'data-idViaAp="' + vacunasSQL.idViaAplicacion + '" ' +
                    'data-marca="' + vacunasSQL.marca + '" ' +
                    'data-enfermedad="' + vacunasSQL.enfermedad + '">' +
                    'Editar' +
                '</button>' +
                '</td>' +
                '<td>' +
                    '<a href="index.php?opt=vacunas&delete=true&idVacuna=' + vacunasSQL.idVacuna + '" ' +
                    'class="btn btn-danger btn-sm">' +
                        'Borrar' +
                    '</a>' +
                '</td>';
            vacunasTbody.appendChild(row);
        });
        $(document).ready(function() {
            $("#tablaVacunas").DataTable();
        });
</script>
 <!-- Script JS para rellenar la tabla de lotes de vacunas -->
    <script>
        var loteVacunasSQL = $loteJSON;
        var loteVacunasTbody = document.getElementById("loteVacunas");
        loteVacunasSQL.forEach(
            function(loteVacunasSQL) {
            var row = document.createElement("tr");
            row.className = "table-light";
            row.innerHTML = 
                '<td>' + loteVacunasSQL.idLoteVacuna + '</td>' +
                '<td>' + loteVacunasSQL.numeroLote + '</td>' +
                '<td>' + loteVacunasSQL.fechaCompra + '</td>' +
                '<td>' + loteVacunasSQL.cantidad + '</td>' +
                '<td>' + loteVacunasSQL.vencimiento + '</td>' +
                '<td>' + loteVacunasSQL.nombre + '</td>' +
                '<td>' +
                '<button type="button" ' +
                    'class="btn btn-warning btn-sm" ' +
                    'data-bs-toggle="modal" ' +
                    'data-bs-target="#editLoteVacuna" ' +
                    'data-id="' + loteVacunasSQL.idLoteVacuna + '" ' +
                    'data-numeroLote="' + loteVacunasSQL.numeroLote + '" ' +
                    'data-fechaCompra="' + loteVacunasSQL.fechaCompra + '" ' +
                    'data-cantidad="' + loteVacunasSQL.cantidad + '" ' +
                    'data-vencimiento="' + loteVacunasSQL.vencimiento + '" ' +
                    'data-nombre="' + loteVacunasSQL.nombre + '">' +
                    'Editar' +
                '</button>' +
                '</td>' +
                '<td>' +
                    '<a href="index.php?opt=vacunas&delete=true&idLoteVacuna=' + loteVacunasSQL.idLoteVacuna + '" ' +
                    'class="btn btn-danger btn-sm">' +
                        'Borrar' +
                    '</a>' +
                '</td>';
            loteVacunasTbody.appendChild(row);
        });
        $(document).ready(function() {
            $("#tablaLotesVacunas").DataTable();
        });

        
    </script>

<!-- Script JS para rellenar las opciones de via aplicacion en agregar y editar -->
<script>
function cargarSelectViaAplicacion() {
    var viaAplicacion = $viaAplicacionJSON;
    const selectViaAplicacion = document.getElementById('selectViaAplicacion');
    const selectViaAplicacionEdit = document.getElementById('selectViaAplicacionEdit');
    selectViaAplicacion.innerHTML = '';
    selectViaAplicacionEdit.innerHTML = '';
    const defaultOption = document.createElement('option');
    defaultOption.text = 'Seleccione';
    defaultOption.value = '';
    selectViaAplicacion.appendChild(defaultOption);
    const defaultOptionClonado = defaultOption.cloneNode(true);
    selectViaAplicacionEdit.appendChild(defaultOptionClonado);

    viaAplicacion.forEach(function (item) {
        const optionAgregar = document.createElement('option');
        optionAgregar.value = item.idViaAplicacion;
        optionAgregar.text = item.via;
        selectViaAplicacion.appendChild(optionAgregar);
        // Clonar opción para el segundo modal
        const optionAgregarClonada = optionAgregar.cloneNode(true);
        selectViaAplicacionEdit.appendChild(optionAgregarClonada);
    });
}
</script>

<!-- Script JS para rellenar el modal de edición -->
<script>
document.getElementById("editVacuna").addEventListener("show.bs.modal", function (event) {
    // Botón que activó el modal
    const button = event.relatedTarget;

    // Extraer datos del atributo data-*
    const idVacuna = button.getAttribute("data-id");
    const nombre = button.getAttribute("data-nombre");
    const idViaAplicacion = button.getAttribute("data-idViaAp");
    const marca = button.getAttribute("data-marca");
    const enfermedad = button.getAttribute("data-enfermedad");

    // Asignar los valores a los campos del formulario
    document.querySelector("#editVacunaForm #nombre").value = nombre;
    document.querySelector("#editVacunaForm #marca").value = marca;
    document.querySelector("#editVacunaForm #enfermedad").value = enfermedad;
    document.querySelector("#editVacunaForm #selectViaAplicacionEdit").value = idViaAplicacion;

    // Selecciona la opción correcta en el select
    const opcionesEditar = document.querySelector('#editVacunaForm #selectViaAplicacionEdit');
    opcionesEditar.value = idViaAplicacion; // Selecciona el valor correcto
    if (opcionesEditar.value !== idViaAplicacion) {
        // Si el valor no está presente, agrega la opción faltante
        const nuevaOpcion = document.createElement('option');
        nuevaOpcion.value = idViaAplicacion;
        nuevaOpcion.text = 'Opción desconocida';
        opcionesEditar.appendChild(nuevaOpcion);
        opcionesEditar.value = idViaAplicacion;
    }

    // Asigna el ID de la granja (oculto)
    document.querySelector('#editVacunaForm #idVacuna').value = idVacuna;
});
</script>

<script>
    // Carga de las funciones
    window.addEventListener('load', function() {
        cargarSelectViaAplicacion()
    });
</script>




HTML;
?>
