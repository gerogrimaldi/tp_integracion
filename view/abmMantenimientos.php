<?php
$body = <<<HTML
<div class="container">
    <h1>Mantenimientos</h1>
    
    <p class="d-inline-flex gap-1">
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#verMant" aria-expanded="false" aria-controls="collapseExample">
            Ver tipos de mantenimientos
        </button>
    </p>

    <div class="collapse mb-4" id="verMant">
        <!-- Removed d-inline-flex from this div -->
        <div class="mb-3">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#agregarMant" aria-expanded="false" aria-controls="collapseExample">
                Agregar tipos de Mantenimientos
            </button>
        </div>
        
        <div class="collapse mb-4" id="agregarMant">
            <div class="card card-body text-dark">
                <form id="agregarTipoMantForm" class="needs-validation" novalidate>
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
                    <button type="button" class="btn btn-primary" id="btnAgregarTipoMant">Agregar</button>
                </form>
            </div>
        </div>
        
        <div class="card card-body text-dark">
            <table id="tablaTiposMant" class="table table-bordered bg-white">
                <thead class="table-light">
                    <tr>
                        <th class="text-primary">ID</th>
                        <th class="text-primary">Descripción</th>
                        <th class="text-primary">✏</th>
                        <th class="text-primary">❌</th>
                    </tr>
                </thead>
                <tbody id="tipoMant">
                    <!-- Los datos se insertarán aquí -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!------------------------------------------------> 
<!-- MODAL: EDITAR TIPO DE MANTENIMIENTO -->
<!------------------------------------------------> 
<div class="modal fade" id="editarTipoMant" tabindex="-1" aria-labelledby="editarTipoMantModal" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarTipoMantModal">Editar descripción del mantenimiento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarTipoMantForm" class="needs-validation" novalidate>
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
                <button type="button" class="btn btn-primary" id="btnEditarTipoMant">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<script>
<!-------------------------------------------------->
<!-- Sección JavaScript de Tipos de Mantenimiento -->
<!--------------------------------------------------> 
<!--- TIPO MANTENIMIENTO - RELLENAR FORM EDICIÓN ---> 
<!--------------------------------------------------> 
document.addEventListener('click', function (event) {
    if (event.target && event.target.matches('.btn-warning')) {
        const button = event.target;
        const idTipoMant = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');
        document.querySelector('#editarTipoMantForm #nombreMantEdit').value = nombre;
        document.querySelector('#editarTipoMantForm #idTipoMant').value = idTipoMant;
    }
});
<!--------------------------------------------------------> 
<!-- TIPO MANTENIMIENTOS - CAPTAR EL FORMULARIO AGREGAR -->  
<!--------------------------------------------------------> 
document.getElementById('btnAgregarTipoMant').addEventListener('click', function() {
    agregarTipoMant();
});
document.getElementById('agregarTipoMantForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    agregarTipoMant();
});
<!--------------------------------------------------------> 
<!-- TIPO MANTENIMIENTOS - ACCION FORMULARIO DE EDICIÓN -->  
<!--------------------------------------------------------> 
document.getElementById('btnEditarTipoMant').addEventListener('click', function() {
    editarTipoMant();
});
document.getElementById('editarTipoMantForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    editarTipoMant();
});
<!--------------------------------------------------------> 
<!------- TIPOS DE MANTENIMIENTOS - RESOLUCIÓN ABM ------->  
<!--------------------------------------------------------> 
function agregarTipoMant() {
    const nombreMant = document.getElementById('nombreMant').value;

    fetch('index.php?opt=mantenimientos&ajax=addTipoMant', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'nombreMant=' + encodeURIComponent(nombreMant)
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                // Recargar la tabla
                recargarTipoMant();
                // Cerrar el modal
                $('#agregarTipoMant').modal('hide');
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

function editarTipoMant() {
    const idTipoMant = document.getElementById('idTipoMant').value;
    const nombreMantEdit = document.getElementById('nombreMantEdit').value;

    fetch('index.php?opt=mantenimientos&ajax=editTipoMant', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'idTipoMant=' + encodeURIComponent(idTipoMant) +
        '&nombreMantEdit=' + encodeURIComponent(nombreMantEdit)
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                recargarTipoMant();
                showToastOkay(data.msg);
                $('#editarTipoMant').modal('hide');
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

function eliminarTipoMantenimiento(idTipoMant) {
    // Realizar la solicitud AJAX
    fetch('index.php?opt=mantenimientos&ajax=delTipoMant&idTipoMant=' + idTipoMant, {
        method: 'GET'
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                // Si la eliminación fue exitosa, recargar la tabla y los select
                recargarTipoMant();
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

function recargarTipoMant() {
    //Recargar secciones de la página cuando se cambia un tipo mant.
    cargarTablaTipoMant();
    //cargarTablaMantGalpon();
    cargarTablaMantGranja()
}

<!-- JS Para rellenar tabla tipo mantenimientos -->
function cargarTablaTipoMant() {
    // Vaciar tabla
    if ($.fn.DataTable.isDataTable('#tablaTiposMant')) {
            $('#tablaTiposMant').DataTable().destroy();
    }
    var tipoMantTbody = document.getElementById("tipoMant");
    tipoMantTbody.innerHTML = '';

    // Realizar la solicitud AJAX
    fetch('index.php?opt=mantenimientos&ajax=getTipoMant')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        // Recorrer los datos y crear las filas de la tabla
        data.forEach(tipoMant => {
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
                    '<button type="button" class="btn btn-danger btn-sm" onclick="eliminarTipoMantenimiento(' + tipoMant.idTipoMantenimiento + ')">Borrar</button>' +
                '</td>'
            tipoMantTbody.appendChild(row);
        });
        $('#tablaTiposMant').DataTable();

    })
    .catch(error => {
        console.error('Error al cargar los tipos de mantenimiento:', error);
        $('#tablaTiposMant').DataTable();
    });
}

</script>


<div class="container">
    <h2>Granjas</h2>
    <form id="selectGranjaForm" class="needs-validation" novalidate>
        <div class="mb-4">
            <label for="selectGranja" class="form-label">Seleccione la granja para ver mantenimientos realizados.</label>
            <div class="input-group">
                <select id="selectGranja" name="selectGranja" class="form-control" required>
                    <!-- Las opciones se agregan con JavaScript -->
                </select>
                <button type="button" class="btn btn-primary rounded ms-2" data-bs-toggle="modal" data-bs-target="#newMantGranja">
                    Agregar mantenimiento
                </button>
                <div class="invalid-feedback">
                    Debe elegir una opción.
                </div>
            </div>
        </div>
    </form>

    <table id="tablaMantGranja" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID</th>
                <th class="text-primary">Fecha</th>
                <th class="text-primary">Mantenimiento</th>
                <th class="text-primary">❌</th>
            </tr>
        </thead>
        <tbody id="mantGranja">
            <!-- Los datos se insertarán aquí -->
        </tbody>
    </table>
</div>

<!-- Modal agregar Mantenimiento GRANJA -->
<!-- TO DO: SI NO SE FILTRA UNA GRANJA ANTES, DA ERROR EL SQL -->
<div class="modal fade" id="newMantGranja" tabindex="-1" aria-labelledby="newMantGranjaModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="newMantGranjaModal">Agregar mantenimiento realizado</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newMantGranjaForm" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <label for="fechaMant" class="form-label">Fecha y hora de realización</label>
                        <input type="datetime-local" class="form-control" 
                            id="fechaMant" name="fechaMantenimiento"
                            required>
                        <div class="invalid-feedback">
                            Seleccione una fecha y hora válidos.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="tipoMant" class="form-label">Tipo de mantenimiento</label>
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
        const previouslySelected = selectFiltrarGranja.getAttribute('data-selected');
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
<!-- Listado GRANJAS - Filtrar al presionar opción del select -->
document.getElementById('selectGranja').addEventListener('change', function(e) {
    this.setAttribute('data-selected', e.target.value);
    if (e.target.value) {
        cargarTablaMantGranja();
    } else {
        // Limpiar la tabla si no hay granja seleccionada
        if ($.fn.DataTable.isDataTable('#tablaGalpones')) {
            $('#tablaGalpones').DataTable().clear().draw();
        }
    }
});
<!-------------------------------------------------> 
<!------ RELLENAR TABLA MANT GRANJAS - AJAX ------->
<!-------------------------------------------------> 
function cargarTablaMantGranja() {
    //Vaciar la tabla
    if ($.fn.DataTable.isDataTable('#tablaMantGranja')) {
        $('#tablaMantGranja').DataTable().destroy();
    }
    var tablaMantGranjaTbody = document.getElementById("mantGranja");
    tablaMantGranjaTbody.innerHTML = '';

    // Realizar la solicitud AJAX
    fetch('index.php?opt=mantenimientos&ajax=getMantGranja&idGranja=' + document.getElementById('selectGranja').value)
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        // Recorrer los datos y crear las filas de la tabla
        data.forEach(mantenimientos => {
            var row = document.createElement("tr");
            row.className = "table-light";
            row.innerHTML = 
            '<td>' + mantenimientos.idMantenimientoGranja + '</td>' +
            '<td>' + mantenimientos.fecha + '</td>' +
            '<td>' + mantenimientos.nombre + '</td>' +
            '</td>' +
            '<td>' +
                '<button type="button" class="btn btn-danger btn-sm" onclick="eliminarMantGranja(' + mantenimientos.idMantenimientoGranja + ')">Borrar</button>' +
            '</td>';
            tablaMantGranjaTbody.appendChild(row);
        })
        $('#tablaMantGranja').DataTable();
    })
    .catch(error => {
        console.error('Error al cargar galpones:', error);
        $('#tablaMantGranja').DataTable();
    });
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
                cargarTablaMantGranja();
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
                cargarTablaMantGranja();
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
</script>

<div class="container">
    <h2> Galpones</h2>

    <form id="selectGalponForm" class="needs-validation" novalidate>
        <div class="mb-4">
            <label for="selectGalpon" class="form-label">Seleccione el galpón para ver mantenimientos realizados.</label>
            <div class="input-group">
                <select id="selectGalpon" name="selectGalpon" class="form-control" required>
                    <!-- Las opciones se agregan con JavaScript -->
                </select>
                <button type="button" class="btn btn-primary rounded ms-2" data-bs-toggle="modal" data-bs-target="#newMantGalpon">
                    Agregar mantenimiento
                </button>
                <div class="invalid-feedback">
                    Debe elegir una opción.
                </div>
            </div>
        </div>
    </form>

    <table id="tablaMantGalpon" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID</th>
                <th class="text-primary">Fecha</th>
                <th class="text-primary">Mantenimiento</th>
                <th class="text-primary">❌</th>
            </tr>
        </thead>
        <tbody id="mantGalpon">
            <!-- Los datos se insertarán aquí -->
        </tbody>
    </table>
</div>

<!-- Modal agregar Mantenimiento Galpon -->
<!-- TO DO: SI NO SE FILTRA UN GALPON ANTES, DA ERROR EL SQL -->
<div class="modal fade" id="newMantGalpon" tabindex="-1" aria-labelledby="newMantGalponModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="newMantGalponModal">Agregar mantenimiento realizado</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newMantGalponForm" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <label for="fechaMantGalpon" class="form-label">Fecha y hora de realización</label>
                        <input type="datetime-local" class="form-control" 
                            id="fechaMantGalpon" name="fechaMantenimiento"
                            required>
                        <div class="invalid-feedback">
                            Seleccione una fecha y hora válidos.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="tipoMant" class="form-label">Tipo de mantenimiento</label>
                        <select id="selectTipoMantGalpon" name="tipoMantenimiento" class="form-control">
                            <!-- Las opciones se agregarán aquí con JavaScript -->
                        </select>
                        <div class="invalid-feedback">
                            La habilitación debe tener al menos 3 caracteres.
                        </div>
                    </div>
                    <input type="hidden" id="idGalpon" name="idGalpon">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnAgregarMantGalpon" >Finalizar</button>
            </div>
        </div>
    </div>
</div>
</div>

<script>
<!-------------------------------------------------->
<!-- Sección JavaScript - Mantenimiento de Galpon -->
<!--------------------------------------------------> 
<!------ CARGAR GALPONES DISPONIBLES EN SELECT -----> 
<!-------------------------------------------------->
function cargarSelectGalpon() {
    //Iniciar tabla, cargar opción por default.
    const selectFiltrarGalpon = document.getElementById('selectGalpon');
    selectFiltrarGalpon.innerHTML = '';
    const defaultOption = document.createElement('option');
        defaultOption.text = 'Seleccione un galpón';
        defaultOption.value = '';
        selectFiltrarGalpon.appendChild(defaultOption);

    fetch('index.php?opt=galpones&ajax=getAllGalpones')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {

        data.forEach(galpones => {
            const optionAgregar = document.createElement('option');
            optionAgregar.value = galpones.idGalpon;
            optionAgregar.text = galpones.identificacion + " - " + galpones.nombre;
            selectFiltrarGalpon.appendChild(optionAgregar);
        });

        // Si hay un valor previamente seleccionado, restaurarlo y cargar los galpones
        const previouslySelected = selectFiltrarGalpon.getAttribute('data-selected');
        if (previouslySelected) {
            selectFiltrarGalpon.value = previouslySelected;
            cargarTablaGalpones();
        }
    })
    .catch(error => {
        console.error('Error al cargar galpones:', error);
        showToastError('Error al cargar galpones');
    });
}
<!-- Listado Galpon - Filtrar al presionar opción del select -->
document.getElementById('selectGalpon').addEventListener('change', function(e) {
    this.setAttribute('data-selected', e.target.value);
    if (e.target.value) {
        cargarTablaMantGalpon();
    } else {
        // Limpiar la tabla si no hay Galpon seleccionado
        if ($.fn.DataTable.isDataTable('#tablaGalpones')) {
            $('#tablaGalpones').DataTable().clear().draw();
        }
    }
});
<!-------------------------------------------------> 
<!------ RELLENAR TABLA MANT. GALPON - AJAX ------->
<!-------------------------------------------------> 
function cargarTablaMantGalpon() {
    //Vaciar la tabla
    if ($.fn.DataTable.isDataTable('#tablaMantGalpon')) {
        $('#tablaMantGalpon').DataTable().destroy();
    }
    var tablaMantGalponTbody = document.getElementById("mantGalpon");
    tablaMantGalponTbody.innerHTML = '';

    // Realizar la solicitud AJAX
    fetch('index.php?opt=mantenimientos&ajax=getMantGalpon&idGalpon=' + document.getElementById('selectGalpon').value)
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        // Recorrer los datos y crear las filas de la tabla
        data.forEach(mantenimientos => {
            var row = document.createElement("tr");
            row.className = "table-light";
            row.innerHTML = 
            '<td>' + mantenimientos.idMantenimientoGalpon + '</td>' +
            '<td>' + mantenimientos.fecha + '</td>' +
            '<td>' + mantenimientos.nombre + '</td>' +
            '</td>' +
            '<td>' +
                '<button type="button" class="btn btn-danger btn-sm" onclick="eliminarMantGalpon(' + mantenimientos.idMantenimientoGalpon + ')">Borrar</button>' +
            '</td>';
            tablaMantGalponTbody.appendChild(row);
        })
        $('#tablaMantGalpon').DataTable();
    })
    .catch(error => {
        console.error('Error al cargar galpones:', error);
        $('#tablaMantGalpon').DataTable();
    });
}
<!----------------------------------------------------->
<!-----------------------------------------------------> 
<!--------- MANT. GALPON - FORMULARIO AGREGAR --------->  
<!-----------------------------------------------------> 
<!--- Pasar al formulario el ID Galpon seleccionado --->  
<!------- y presentar error si no hay seleccion ------->  
document.getElementById("newMantGalpon").addEventListener("show.bs.modal", function (event) {
    // Get the currently selected Galpon ID
    const selectedGalponId = document.getElementById('selectGalpon').value;
    if (!selectedGalponId) {
        event.preventDefault();
        showToastError('Debe seleccionar un galpón primero');
        return;
    }
    // Set the hidden input value
    document.querySelector("#newMantGalponForm #idGalpon").value = selectedGalponId;
    cargarSelectTipoMant('selectTipoMantGalpon');
});
<!---- Cambiar la acción del botón enviar y enter ---->  
document.getElementById('btnAgregarMantGalpon').addEventListener('click', function() {
    agregarMantGalpon();
});
document.getElementById('newMantGalponForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    agregarMantGalpon();
});
<!-----------------------------------------------> 
<!-------- MANT. Galpon - AGREGAR NUEVO --------->  
<!-----------------------------------------------> 
function agregarMantGalpon() {
    const fechaMant = document.getElementById('fechaMantGalpon').value;
    const tipoMantenimiento = document.getElementById('selectTipoMantGalpon').value;
    const idGalpon = document.getElementById('idGalpon').value;

    fetch('index.php?opt=mantenimientos&ajax=newMantGalpon', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'fechaMant=' + encodeURIComponent(fechaMant) +
              '&tipoMantenimiento=' + encodeURIComponent(tipoMantenimiento) +
              '&idGalpon=' + encodeURIComponent(idGalpon)
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                cargarTablaMantGalpon();
                $('#newMantGalpon').modal('hide');
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
<!----------- MANT. Galpon - ELIMINAR ----------->  
<!-----------------------------------------------> 
function eliminarMantGalpon(idMantenimientoGalpon) {
    // Realizar la solicitud AJAX
    fetch('index.php?opt=mantenimientos&ajax=delMantGalpon&idMantenimientoGalpon=' + idMantenimientoGalpon, {
        method: 'GET'
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                // Si la eliminación fue exitosa, recargar la tabla y los select
                cargarTablaMantGalpon();
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
window.addEventListener('load', function() {
    cargarSelectGranja();
    cargarTablaTipoMant();
    cargarTablaMantGranja();
    cargarSelectGalpon();
    cargarTablaMantGalpon()
});

</script>

HTML;
// Agregar las funciones y el contenedor de los toast
// Para mostrar notificaciones
include 'view/toast.php';
$body .= $toast;
?>

