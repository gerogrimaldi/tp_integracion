<?php
$body = <<<HTML
<div class="container">
    <h1>Compuestos</h1>
    
    <p class="d-inline-flex gap-1">
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#verCompuesto" aria-expanded="false" aria-controls="collapseExample">
            Ver compuestos
        </button>
    </p>

    <div class="collapse mb-4" id="verCompuesto">
        <div class="mb-3">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#agregarCompuesto" aria-expanded="false" aria-controls="collapseExample">
                Agregar compuesto
            </button>
        </div>
        
        <div class="collapse mb-4" id="agregarCompuesto">
            <div class="card card-body text-dark">
                <form id="agregarCompuestoForm" class="needs-validation" novalidate>
                    <div class="mb-2">
                        <label for="nombre" class="form-label">Compuesto</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ejemplo: bolsa de soja 10kg" min="1" required>
                        <div class="invalid-feedback">Debe contar con al menos 3 letras.</div>
                    </div>
                    <div class="mb-2">
                        <label for="proveedor" class="form-label">Proveedor</label>
                        <input type="text" class="form-control" id="proveedor" name="proveedor" placeholder="Ejemplo: Axonutra" min="1" required>
                        <div class="invalid-feedback">Debe contar con al menos 3 letras.</div>
                    </div>
                    <button type="button" class="btn btn-primary" id="btnAgregarCompuesto">Agregar</button>
                </form>
            </div>
        </div>
        
        <!-- Tabla de compuestos -->
        <div class="card shadow-sm rounded-3 mb-3">
            <div class="card-body table-responsive">
                <table id="tablaCompuesto" class="table table-striped table-hover align-middle mb-0 bg-white">
                    <thead class="table-light">
                        <tr>
                            <th class="text-primary">ID</th>
                            <th class="text-primary">Nombre</th>
                            <th class="text-primary">Proveedor</th>
                            <th class="text-primary">✏</th>
                        </tr>
                    </thead>
                    <tbody id="compuestos">
                        <!-- Los datos se insertarán aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!------------------------------------------------> 
<!-- MODAL: EDITAR TIPO DE MANTENIMIENTO -->
<!------------------------------------------------> 
<div class="modal fade" id="editarCompuesto" tabindex="-1" aria-labelledby="editarCompuestoModal" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarCompuestoModal">Editar Compuesto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarCompuestoForm" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <label for="nombreEdit" class="form-label">Compuesto</label>
                        <input type="text" class="form-control" id="nombreEdit" name="nombreEdit" placeholder="Ejemplo: soja" min="1" required>
                        <div class="invalid-feedback">Debe contar con al menos 3 carácteres.</div>
                    </div>
                    <div class="mb-4">
                        <label for="proveedorEdit" class="form-label">Proveedor</label>
                        <input type="text" class="form-control" id="proveedorEdit" name="proveedorEdit" placeholder="Proveedor" min="1" required>
                        <div class="invalid-feedback">Debe contar con al menos 3 carácteres.</div>
                    </div>
                    <input type="hidden" id="idCompuesto" name="idCompuesto">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnEditarCompuesto">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<script>
<!----------------------------------->
<!-- Sección JavaScript Compuestos -->
<!-----------------------------------> 
<!--- RELLENAR FORM EDICIÓN ---> 
<!-----------------------------> 
document.addEventListener('click', function (event) {
    if (event.target && event.target.matches('.btn-warning')) {
        const button = event.target;
        const idCompuesto = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');
        const proveedor = button.getAttribute('data-proveedor');
        document.querySelector('#editarCompuestoForm #nombreEdit').value = nombre;
        document.querySelector('#editarCompuestoForm #proveedorEdit').value = proveedor;
        document.querySelector('#editarCompuestoForm #idCompuesto').value = idCompuesto;
    }
});
<!----------------------------------> 
<!-- CAPTAR EL FORMULARIO AGREGAR -->  
<!----------------------------------> 
document.getElementById('btnAgregarCompuesto').addEventListener('click', function() {
    agregarCompuesto();
});
document.getElementById('agregarCompuestoForm').addEventListener('submit', function(event) {
    event.preventDefault(); 
    agregarCompuesto();
});
<!----------------------------------> 
<!-- ACCION FORMULARIO DE EDICIÓN -->  
<!----------------------------------> 
document.getElementById('btnEditarCompuesto').addEventListener('click', function() {
    editarCompuesto();
});
document.getElementById('editarCompuestoForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    editarCompuesto();
});
<!---------------------------------> 
<!------- AGREGAR COMPUESTO ------->  
<!---------------------------------> 
function agregarCompuesto() {
    const nombre = document.getElementById('nombre').value;
    const proveedor = document.getElementById('proveedor').value;

    fetch('index.php?opt=compuestos&ajax=addCompuesto', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'nombre=' + encodeURIComponent(nombre) +
        '&proveedor=' + encodeURIComponent(proveedor)
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                // Recargar la tabla
                recargarCompuestos();
                // Cerrar el modal
                showToastOkay(data.msg);
            } else {
                showToastError(data.msg);
            }
        });
    })
    .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
        showToastError('Error en la solicitud AJAX: ' + error.message);
    });
}

function editarCompuesto() {
    const idCompuesto = document.getElementById('idCompuesto').value;
    const nombreEdit = document.getElementById('nombreEdit').value;
    const proveedorEdit = document.getElementById('proveedorEdit').value;

    fetch('index.php?opt=compuestos&ajax=editCompuesto', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'idCompuesto=' + encodeURIComponent(idCompuesto) +
        '&nombre=' + encodeURIComponent(nombreEdit) +
        '&proveedor=' + encodeURIComponent(proveedorEdit)
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                recargarCompuestos();
                showToastOkay(data.msg);
                $('#editarCompuesto').modal('hide');
            } else {
                showToastError(data.msg);
            }
        });
    })
    .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
        showToastError('Error desconocido.');
    });
}

function eliminarCompuesto(idCompuesto) {
    fetch('index.php?opt=compuestos&ajax=delCompuesto&idCompuesto=' + idCompuesto, {
        method: 'GET'
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                // Si la eliminación fue exitosa, recargar la tabla y los select
                recargarCompuestos();
                showToastOkay(data.msg);
            } else {
                showToastError(data.msg);
            }
        });
    })
    .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
        showToastError('Error desconocido.');
    });
}

<!-------------------------------------------------> 
<!----- TIPO DE MANTENIMIENTO - CARGAR SELECT ----->
<!-------------------------------------------------> 
function cargarSelectTipoMant(select) {
    //Cargar opción por default.
    const selectTipoMant = document.getElementById(select);
    selectTipoMant.innerHTML = '';
    const defaultOption = document.createElement('option');
        defaultOption.text = 'Seleccione el tipo de mantenimiento';
        defaultOption.value = '';
        selectTipoMant.appendChild(defaultOption);

    fetch('index.php?opt=mantenimientos&ajax=getTipoMant')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        data.forEach(tipoMant => {
            const optionAgregar = document.createElement('option');
            optionAgregar.value = tipoMant.idTipoMantenimiento;
            optionAgregar.text = tipoMant.nombre;
            selectTipoMant.appendChild(optionAgregar);
        });
    })
    .catch(error => {
        console.error('Error al cargar tipos de mantenimiento:', error);
        showToastError('Error al cargar tipos de mantenimiento');
    });
}

function recargarCompuestos() {
    //Recargar secciones de la página cuando se cambia un tipo mant.
    cargarTablaCompuestos();
    //cargarTablaMantGalpon();
    cargarTablaMantGranja()
}

<!-- JS Para rellenar tabla tipo mantenimientos -->
function cargarTablaCompuestos() {
    // Vaciar tabla
    if ($.fn.DataTable.isDataTable('#tablaCompuesto')) {
            $('#tablaCompuesto').DataTable().destroy();
    }
    var tablaCompuestoTbody = document.getElementById("compuestos");
    tablaCompuestoTbody.innerHTML = '';

    // Realizar la solicitud AJAX
    fetch('index.php?opt=compuestos&ajax=getCompuestos')
    .then(response => {
        if (!response.ok) { throw new Error('Error en la solicitud: ' + response.statusText); }
        return response.json();
    })
    .then(data => {
        // Recorrer los datos y crear las filas de la tabla
        data.forEach(comp => {
            var row = document.createElement("tr");
            row.className = "table-light";
            row.innerHTML = 
                '<td>' + comp.idCompuesto + '</td>' +
                '<td>' + comp.nombre + '</td>' +
                '<td>' + comp.proveedor + '</td>' +
                '<td>' +
                    '<button type="button" ' +
                        'class="btn btn-warning btn-sm" ' +
                        'data-bs-toggle="modal" ' +
                        'data-bs-target="#editarCompuesto" ' +
                        'data-id="' + comp.idCompuesto + '" ' +
                        'data-nombre="' + comp.nombre + '"' +
                        'data-proveedor="' + comp.proveedor + '">' +
                        'Editar' +
                    '</button>' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="eliminarCompuesto(' + comp.idCompuesto + ')">Borrar</button>' +
                '</td>'
            tablaCompuestoTbody.appendChild(row);
        });
        $('#tablaCompuesto').DataTable();

    })
    .catch(error => {
        console.error('Error al cargar los tipos de mantenimiento:', error);
        $('#tablaTiposMant').DataTable();
    });
}

</script>
HTML;
$body .= <<<HTML
<div class="container">
    <h2>Granjas</h2>

    <div class="input-group mb-3">
        <select id="selectGranja" name="selectGranja" class="form-select rounded-start" required>
            <!-- opciones -->
        </select>
        <button type="button" class="btn btn-primary rounded-end" data-bs-toggle="modal" data-bs-target="#newMantGranja">
            Agregar compra
        </button>
        <div class="invalid-feedback">
            Debe elegir una opción.
        </div>
    </div>

    <!-- Filtros de fechas + botones -->
    <div class="row mb-3 g-2"> <!-- g-2 agrega un gap entre columnas -->
       
       
        <div class="col-12 col-md-3 d-flex align-items-end">
            <button id="btnFiltrar" class="btn btn-primary w-100">Filtrar</button>
        </div>
        <div class="col-12 col-md-3 d-flex align-items-end">
            <button id="btnReporte" class="btn btn-success w-100">Generar Reporte</button>
        </div>
    </div>
    <!-- Tabla de mantenimiento de granjas -->
    <div class="card shadow-sm rounded-3 mb-3">
        <div class="card-body table-responsive">
            <table id="tablaMantGranja" class="table table-striped table-hover align-middle mb-0 bg-white">
                <thead class="table-light">
                    <tr>
                        <th class="text-primary">ID</th>
                        <th class="text-primary">Compuesto</th>
                        <th class="text-primary">Cantidad</th>
                        <th class="text-primary">Precio</th>
                        <th class="text-primary">❌</th>
                    </tr>
                </thead>
                <tbody id="comprasCompuesto">
                    <!-- Los datos se insertarán aquí -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal agregar Mantenimiento GRANJA -->
<!-- TO DO: SI NO SE FILTRA UNA GRANJA ANTES, DA ERROR EL SQL -->
<div class="modal fade" id="newMantGranja" tabindex="-1" aria-labelledby="newMantGranjaModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="newMantGranjaModal">Agregar nueva compra de compuesto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newMantGranjaForm" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <label for="tipoMant" class="form-label">Compras de compuesto</label>
                        <select id="selectTipoMantGranja" name="tipoMantenimiento" class="form-control">
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
                <button type="submit" class="btn btn-primary" id="btnAgregarMantGranja">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<script>
<!-------------------------------------------------->
<!-- Sección JavaScript - Mantenimiento de Granja -->
<!--------------------------------------------------> 
<!------ CARGAR GRANJAS DISPONIBLES EN SELECT ------> 
<!-------------------------------------------------->
function cargarSelectGranja() {
    //Iniciar tabla, cargar opción por default.
    const selectFiltrarGranja = document.getElementById('selectGranja');
    selectFiltrarGranja.innerHTML = '';
    const defaultOption = document.createElement('option');
        defaultOption.text = 'Seleccione una granja';
        defaultOption.value = '';
        selectFiltrarGranja.appendChild(defaultOption);

    // Realizar la solicitud AJAX para obtener las granjas
    fetch('index.php?opt=granjas&ajax=getGranjas')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        // Agregar las granjas desde la API
        data.forEach(granja => {
            const optionAgregar = document.createElement('option');
            optionAgregar.value = granja.idGranja;
            optionAgregar.text = granja.nombre;
            selectFiltrarGranja.appendChild(optionAgregar);
        });

        // Si hay un valor previamente seleccionado, restaurarlo y cargar los galpones
        const previouslySelected = selectGranja.getAttribute('data-selected');
        if (previouslySelected) {
            selectFiltrarGranja.value = previouslySelected;
            cargarTablaGalpones();
        }
    })
    .catch(error => {
        console.error('Error al cargar granjas:', error);
        showToastError('Error al cargar las granjas');
    });
}
<!-- Listado GRANJAS - eliminar datos de la tabla al presionar el select -->
document.getElementById('selectGranja').addEventListener('change', function(e) {
    if ($.fn.DataTable.isDataTable('#tablaMantGranja')) {
        $('#tablaMantGranja').DataTable().clear().draw();
    }
});
<!-------------------------------------------------> 
<!------ RELLENAR TABLA MANT GRANJAS - AJAX ------->
<!-------------------------------------------------> 
function cargarTablaMantGranja() {
$('#tablaMantGranja').DataTable();
}
<!-----------------------------------------------------> 
<!--------- MANT GRANJAS - FORMULARIO AGREGAR --------->  
<!-----------------------------------------------------> 
<!--- Pasar al formulario el ID Granja seleccionado --->  
<!------- y presentar error si no hay seleccion ------->  
document.getElementById("newMantGranja").addEventListener("show.bs.modal", function (event) {
    // Get the currently selected granja ID
    const selectedGranjaId = document.getElementById('selectGranja').value;
    if (!selectedGranjaId) {
        event.preventDefault();
        showToastError('Debe seleccionar una granja primero');
        return;
    }
    // Set the hidden input value
    document.querySelector("#newMantGranjaForm #idGranja").value = selectedGranjaId;
    cargarSelectTipoMant('selectTipoMantGranja');
});
<!---- Cambiar la acción del botón enviar y enter ---->  
document.getElementById('btnAgregarMantGranja').addEventListener('click', function() {
    agregarMantGranja();
});
document.getElementById('newMantGranjaForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    agregarMantGranja();
});
<!-----------------------------------------------> 
<!-------- MANT GRANJAS - AGREGAR NUEVO --------->  
<!-----------------------------------------------> 
function agregarMantGranja() {
    const fechaMant = document.getElementById('fechaMant').value;
    const tipoMantenimiento = document.getElementById('selectTipoMantGranja').value;
    const idGranja = document.getElementById('idGranja').value;

    fetch('index.php?opt=mantenimientos&ajax=newMantGranja', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'fechaMant=' + encodeURIComponent(fechaMant) +
              '&tipoMantenimiento=' + encodeURIComponent(tipoMantenimiento) +
              '&idGranja=' + encodeURIComponent(idGranja)
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                document.getElementById("btnFiltrar").click();
                $('#newMantGranja').modal('hide');
                showToastOkay(data.msg);
            } else {
                showToastError(data.msg);
            }
        });
    })
    .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
        showToastError('Error en la solicitud AJAX: ' + error.message);
    });
}
<!-----------------------------------------------> 
<!----------- MANT GRANJAS - ELIMINAR ----------->  
<!-----------------------------------------------> 
function eliminarMantGranja(idMantenimientoGranja) {
    // Realizar la solicitud AJAX
    fetch('index.php?opt=mantenimientos&ajax=delMantGranja&idMantenimientoGranja=' + idMantenimientoGranja, {
        method: 'GET'
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                // Si la eliminación fue exitosa, recargar la tabla y los select
                document.getElementById("btnFiltrar").click();
                showToastOkay(data.msg);
            } else {
                showToastError(data.msg);
            }
        });
    })
    .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
        showToastError('Error desconocido.');
    });
}
// === FILTRAR POR FECHAS ===
document.getElementById("btnFiltrar").addEventListener("click", function() {
    var idGranja = document.getElementById("selectGranja").value;
    var desde = document.getElementById("fechaDesde").value;
    var hasta = document.getElementById("fechaHasta").value;

    if (!idGranja) {
        showToastError("Debe seleccionar una granja primero");
        return;
    }
    if (!desde || !hasta) {
        showToastError("Debe seleccionar fechas Desde y Hasta");
        return;
    }

    // Vaciar y recargar la tabla con las fechas
    if ($.fn.DataTable.isDataTable('#tablaMantGranja')) {
        $('#tablaMantGranja').DataTable().destroy();
    }
    document.getElementById("mantGranja").innerHTML = "";

    fetch('index.php?opt=mantenimientos&ajax=getMantGranja&idGranja=' + encodeURIComponent(idGranja) +
          '&desde=' + encodeURIComponent(desde) +
          '&hasta=' + encodeURIComponent(hasta))
    .then(function(res) { return res.json(); })
    .then(function(data) {
        data.forEach(function(m) {
            var row = '<tr class="table-light">' +
                        '<td>' + m.idMantenimientoGranja + '</td>' +
                        '<td>' + m.fecha + '</td>' +
                        '<td>' + m.nombre + '</td>' +
                        '<td>' +
                            '<button type="button" class="btn btn-danger btn-sm" onclick="eliminarMantGranja(' + m.idMantenimientoGranja + ')">Borrar</button>' +
                        '</td>' +
                      '</tr>';
            document.getElementById("mantGranja").insertAdjacentHTML("beforeend", row);
        });
        $('#tablaMantGranja').DataTable();
    })
    .catch(function(err) {
        console.error("Error:", err);
        $('#tablaMantGranja').DataTable();
    });
});

// === GENERAR REPORTE IMPRIMIBLE ===
document.getElementById("btnReporte").addEventListener("click", function() {
    var desde = document.getElementById("fechaDesde").value;
    var hasta = document.getElementById("fechaHasta").value;
    var granjaNombre = document.querySelector("#selectGranja option:checked").text;

    if (!desde || !hasta) {
        showToastError("Debe seleccionar fechas Desde y Hasta");
        return;
    }

    var rows = "";
    document.querySelectorAll("#mantGranja tr").forEach(function(tr) {
        var tds = tr.querySelectorAll("td");
        rows += '<tr>' +
                    '<td>' + tds[0].innerText + '</td>' +
                    '<td>' + tds[1].innerText + '</td>' +
                    '<td>' + tds[2].innerText + '</td>' +
                '</tr>';
    });

    var reporte = '<html>' +
                   '<head>' +
                   '<title>Reporte Mantenimientos</title>' +
                   '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">' +
                   '<style>' +
                     'body { padding: 20px; }' +
                     'h2, h4 { text-align: center; margin-bottom: 20px; }' +
                     'table { width: 100%; border-collapse: collapse; margin-top: 20px; }' +
                     'th, td { border: 1px solid #000; padding: 8px; text-align: left; }' +
                   '</style>' +
                   '</head>' +
                   '<body>' +
                   '<h2>' + granjaNombre + '</h2>' +
                   '<h4>Listado de mantenimientos de granja</h4>' +
                   '<p><strong>Desde:</strong> ' + desde + ' &nbsp;&nbsp; <strong>Hasta:</strong> ' + hasta + '</p>' +
                   '<table>' +
                   '<thead>' +
                     '<tr><th>ID</th><th>Fecha</th><th>Descripción</th></tr>' +
                   '</thead>' +
                   '<tbody>' + rows + '</tbody>' +
                   '</table>' +
                   '</body>' +
                   '</html>';

    var ventana = window.open("", "_blank");
    ventana.document.write(reporte);
    ventana.document.close();
    ventana.print();
});

window.addEventListener('load', function() {
    cargarSelectGranja();
    cargarTablaCompuestos();
    cargarTablaMantGranja();

    // === Configurar fechas por defecto ===
    const fechaHasta = new Date();
    const fechaDesde = new Date();
    fechaDesde.setMonth(fechaHasta.getMonth() - 1);

    function formatDate(d) {
        return d.toISOString().split('T')[0]; // yyyy-mm-dd
    }
    document.getElementById('fechaDesde').value = formatDate(fechaDesde);
    document.getElementById('fechaHasta').value = formatDate(fechaHasta);
});

</script>

HTML;

// Agregar las funciones y el contenedor de los toast
// Para mostrar notificaciones
include 'view/toast.php';
$body .= $toast;
?>

