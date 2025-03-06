<?php
$body = <<<HTML
<div class="container">
    <h1>Galpones</h1>
    <form id="selectGranjaForm" class="needs-validation" novalidate>
        <div class="mb-4">
            <label for="selectGranja" class="form-label">Seleccione una granja para ver sus galpones.</label>
            <div class="input-group">
                <select id="selectGranja" name="selectGranja" class="form-control" required>
                    <!-- Listado de granjas -->
                </select>
                <div class="invalid-feedback">
                    Debe elegir una opción.
                </div>
            </div>
        </div>
    </form>

    <div class="text-center mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarGalpon">
          Agregar galpón
        </button>
    </div>
    
    <table id="tablaGalpones" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID</th>
                <th class="text-primary">Identificación</th>
                <th class="text-primary">Tipo de Aves</th>
                <th class="text-primary">Capacidad</th>
                <th class="text-primary">Editar</th>
                <th class="text-primary">Borrar</th>
            </tr>
        </thead>
        <tbody id="galpones">
            <!-- Los datos se insertarán aquí -->
        </tbody>
    </table>
</div>

<!-- Modal popUp Agregar Galpon -->
<div class="modal fade" id="agregarGalpon" tabindex="-1" aria-labelledby="agregarGalponModal" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="agregarGalponModal">Agregar Galpon</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregarGalponForm" class="needs-validation" novalidate>
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
                        <label for="idTipoAve" class="form-label">Tipo de aves</label>
                        <select id="idTipoAve" name="idTipoAve" class="form-control">
                            <!-- Las opciones se agregarán aquí con JavaScript -->
                        </select>
                        <div class="invalid-feedback">
                            Ingrese un tipo de aves válido.
                        </div>
                    </div>
                    <input type="hidden" id="idGranja" name="idGranja">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnAgregarGalpon">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal popUp editar Galpon -->
<div class="modal fade" id="editarGalpon" tabindex="-1" aria-labelledby="editarGalponModal" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarGalponModal">Editar datos del galpón</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarGalponForm" class="needs-validation" novalidate>
                <div class="mb-4">
                        <label for="identificacionEditar" class="form-label">Identificador del galpón</label>
                        <input type="select" 
                               class="form-control" 
                               id="identificacionEditar" 
                               name="identificacion" 
                               placeholder="Identificador"
                               min="1"
                               required>
                        <div class="invalid-feedback">
                            El valor debe ser un número positivo.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="capacidadEditar" class="form-label">Capacidad</label>
                        <input type="number" 
                               class="form-control" 
                               id="capacidadEditar" 
                               name="capacidad" 
                               placeholder="Capacidad de aves"
                               min="1"
                               required>
                        <div class="invalid-feedback">
                            El valor debe ser un número positivo.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="idTipoAveEditar" class="form-label">Tipo de aves</label>
                        <select id="idTipoAveEditar" name="idTipoAveEditar" class="form-control">
                            <!-- Las opciones se agregarán aquí con JavaScript -->
                        </select>
                        <div class="invalid-feedback">
                            Debe seleccionar un tipo de ave.
                        </div>
                    </div>
                    <input type="hidden" id="idGranjaEditar" name="idGranja">
                    <input type="hidden" id="idGalponEditar" name="idGalpon">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnEditarGalpon">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<script>
<!------------------------------------------------->
<!--------- Sección JavaScript de Galpones -------->
<!-------------------------------------------------> 
<!------- RELLENAR TABLA DE GRANJAS - AJAX -------->
<!-------------------------------------------------> 
function cargarTablaGalpones() {
    //Vaciar la tabla
    if ($.fn.DataTable.isDataTable('#tablaGalpones')) {
        $('#tablaGalpones').DataTable().destroy();
    }
    var tablaGalponesTbody = document.getElementById("galpones");
    tablaGalponesTbody.innerHTML = '';

    // Realizar la solicitud AJAX
    fetch('index.php?opt=galpones&ajax=getGalponesGranja&idGranja=' + document.getElementById('selectGranja').value)
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        // Recorrer los datos y crear las filas de la tabla
        data.forEach(galpon => {
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
                '<button type="button" class="btn btn-danger btn-sm" onclick="eliminarGalpon(' + galpon.idGalpon + ')">Borrar</button>' +
            '</td>';
            tablaGalponesTbody.appendChild(row);
        })
        $('#tablaGalpones').DataTable();
    })
    .catch(error => {
        console.error('Error al cargar galpones:', error);
        $('#tablaGalpones').DataTable();
    });
}
<!-----------------------------------------------------> 
<!----------- GALPONES - FORMULARIO AGREGAR ----------->  
<!-----------------------------------------------------> 
<!--- Pasar al formulario el ID Granja seleccionado --->  
<!------- y presentar error si no hay seleccion ------->  
document.getElementById("agregarGalpon").addEventListener("show.bs.modal", function (event) {
    // Get the currently selected granja ID
    const selectedGranjaId = document.getElementById('selectGranja').value;
    if (!selectedGranjaId) {
        event.preventDefault();
        showToastError('Debe seleccionar una granja primero');
        return;
    }
    // Set the hidden input value
    document.querySelector("#agregarGalponForm #idGranja").value = selectedGranjaId;
    document.querySelector("#editarGalponForm #idGranja").value = selectedGranjaId;
});
<!---- Cambiar la acción del botón enviar y enter ---->  
document.getElementById('btnAgregarGalpon').addEventListener('click', function() {
    agregarGalpon();
});
document.getElementById('agregarGalponForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    agregarGalpon();
});
<!-----------------------------------------------------> 
<!--------- GALPONES - FORMULARIO DE EDICIÓN ---------->  
<!-----------------------------------------------------> 
document.getElementById('btnEditarGalpon').addEventListener('click', function() {
   editarGalpon();
});
document.getElementById('editarGalponForm').addEventListener('submit', function(event) {
   event.preventDefault(); // Prevent the default form submission
   editarGalpon();
});
<!-----------------------------------------------> 
<!------------- GALPONES - ELIMINAR ------------->  
<!-----------------------------------------------> 
function eliminarGalpon(idGalpon) {
    // Realizar la solicitud AJAX
    fetch('index.php?opt=galpones&ajax=delGalpon&idGalpon=' + idGalpon, {
        method: 'GET'
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                // Si la eliminación fue exitosa, recargar la tabla y los select
                cargarTablaGalpones();
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
<!-----------------------------------------------> 
<!---------- GALPONES - AGREGAR NUEVO ----------->  
<!-----------------------------------------------> 
function agregarGalpon() {
    const identificacion = document.getElementById('identificacion').value;
    const capacidad = document.getElementById('capacidad').value;
    const idGranja = document.getElementById('idGranja').value;
    const idTipoAve = document.getElementById('idTipoAve').value;

    fetch('index.php?opt=galpones&ajax=addGalpon', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'identificacion=' + encodeURIComponent(identificacion) +
              '&capacidad=' + encodeURIComponent(capacidad) +
              '&idGranja=' + encodeURIComponent(idGranja) +
              '&idTipoAve=' + encodeURIComponent(idTipoAve)
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                cargarTablaGalpones();
                $('#agregarGalpon').modal('hide');
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
<!-------------------------------------------------> 
<!-------------- GALPONES - EDITAR ---------------->
<!-------------------------------------------------> 
function editarGalpon() {
    const identificacion = document.getElementById('identificacionEditar').value;
    const capacidad = document.getElementById('capacidadEditar').value;
    const idGranja = document.getElementById('idGranjaEditar').value;
    const idTipoAve = document.getElementById('idTipoAveEditar').value;
    const idGalpon = document.getElementById('idGalponEditar').value;

    fetch('index.php?opt=galpones&ajax=editGalpon', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'identificacion=' + encodeURIComponent(identificacion) +
              '&capacidad=' + encodeURIComponent(capacidad) +
              '&idGranja=' + encodeURIComponent(idGranja) +
              '&idTipoAve=' + encodeURIComponent(idTipoAve)+
              '&idGalpon=' + encodeURIComponent(idGalpon)
    })
    .then(response => {
        return response.json().then(data => {
            if (response.ok) {
                cargarTablaGalpones();
                $('#editarGalpon').modal('hide');
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
<!-------------------------------------------------> 
<!------- GRANJAS - CARGAR LISTADO SELECT   ------->
<!-------------------------------------------------> 
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
    .then(granjas => {
        // Agregar las granjas desde la API
        granjas.forEach(granja => {
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
        cargarTablaGalpones();
    } else {
        // Limpiar la tabla si no hay granja seleccionada
        if ($.fn.DataTable.isDataTable('#tablaGalpones')) {
            $('#tablaGalpones').DataTable().clear().draw();
        }
    }
});
<!-------------------------------------------------> 
<!--------- TIPOS DE AVES - CARGAR SELECT --------->
<!-------------------------------------------------> 
function cargarSelectTipoAves(select) {
    //Iniciar tabla, cargar opción por default.
    const selectTipoAves = document.getElementById(select);
    selectTipoAves.innerHTML = '';
    const defaultOption = document.createElement('option');
        defaultOption.text = 'Seleccione el tipo de ave';
        defaultOption.value = '';
        selectTipoAves.appendChild(defaultOption);

    fetch('index.php?opt=galpones&ajax=getTipoAves')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }
        return response.json();
    })
    .then(tipoAves => {
        tipoAves.forEach(tipoAve => {
            const optionAgregar = document.createElement('option');
            optionAgregar.value = tipoAve.idTipoAve;
            optionAgregar.text = tipoAve.nombre;
            selectTipoAves.appendChild(optionAgregar);
        });
    })
    .catch(error => {
        console.error('Error al cargar tipos de aves:', error);
        showToastError('Error al cargar tipos de aves');
    });
}
<!-------------------------------------------------> 
<!------- RELLENAR FORMULARIO DE EDICION   -------->
<!-------------------------------------------------> 
document.getElementById("editarGalpon").addEventListener("show.bs.modal", function (event) {
    // Botón que activó el modal
    const button = event.relatedTarget;
    // Extraer datos del atributo data-* del botón que abrió el modal
    const idGalpon = button.getAttribute("data-id");
    const identificacion = button.getAttribute("data-identificacion");
    const idTipoAve = button.getAttribute("data-idTipoAve");
    const capacidad = button.getAttribute("data-capacidad");
    const idGranja = button.getAttribute("data-idGranja");  // Asegúrate de tener este atributo en el botón
    // Asignar los valores a los campos del formulario
    document.querySelector("#editarGalponForm #identificacionEditar").value = identificacion;
    document.querySelector("#editarGalponForm #capacidadEditar").value = capacidad;
    document.querySelector("#editarGalponForm #idTipoAveEditar").value = idTipoAve;
    document.querySelector("#editarGalponForm #idGalponEditar").value = idGalpon;
    // Asignar el valor del campo hidden idGranja
    document.querySelector("#editarGalponForm #idGranjaEditar").value = idGranja;
});
<!-------------------------------> 
<!------- CARGAR EL VIEW -------->
<!-------------------------------> 
window.addEventListener('load', function() {
    cargarSelectGranja();
    cargarTablaGalpones();
    cargarSelectTipoAves('idTipoAve');
    cargarSelectTipoAves('idTipoAveEditar');
});

<!-----------------------------------------> 
<!------------ VALIDACIONES   ------------->
<!-----------------------------------------> 
// Activar validaciones al enviar el formulario
document.querySelectorAll('.needs-validation').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
});

// Validar campos en tiempo real
document.querySelectorAll('input, select, textarea').forEach(function (input) {
    input.addEventListener('input', function () {
        if (input.checkValidity()) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        }
    });
});
</script>

HTML;

// Agregar las funciones y el contenedor de los toast
// Para mostrar notificaciones
include 'view/toast.php';
$body .= $toast;
?>
