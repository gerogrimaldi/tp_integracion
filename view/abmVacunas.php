<?php

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

    <!-- Script JS para rellenar la tabla de vacunas -->
    <script>
        // Recupera el resultado de PHP en JSON y lo almacena en una variable JS.
        var vacunasSQL = $vacunasJSON;
        // Crea una variable Tbody que es igual elemento con ese ID en el HTML (la tabla en este caso).
        var vacunasTbody = document.getElementById("vacunas");
        // Realiza un recorrido por cada elemento del arreglo, y ejecuta la "function"
        vacunasSQL.forEach(
            function(vacunasSQL) {
                //Row: Fila. Damos forma a un "<tr>" que es una fila en HTML (table-row)
            var row = document.createElement("tr");
            // Asignamos los atributos en HTML del <tr>.
            row.className = "table-light";
            // Crea los <td> (columnas). Están completas de los datos de la variable mantenimientos, o botones.
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
                    'data-bs-target="#editarVacuna" ' +
                    'data-id="' + vacunasSQL.idVacuna + '" ' +
                    'data-nombre="' + vacunasSQL.nombre + '" ' +
                    'data-idViaAp="' + vacunasSQL.idViaAplicacion + '" ' +
                    'data-marca="' + vacunasSQL.marca + '" ' +
                    'data-enfermedad="' + vacunasSQL.enfermedad + '">' +
                    'Editar' +
                '</button>' +
                '</td>' +
                '<td>' +
                    '<a href="index.php?opt=vacunas&delete=vacuna&idVacuna=' + vacunasSQL.idVacuna + '" ' +
                    'class="btn btn-danger btn-sm">' +
                        'Borrar' +
                    '</a>' +
                '</td>';
            // Agrega al TBody la línea de HTML completa.
            vacunasTbody.appendChild(row);
        });

        // Inicializar DataTable - Es un "plugin" que le agrega la busqueda, ordenamiento, etc. a la tabla.
        // Se le pasa como parámetro la tabla HTML que creamos.
        $(document).ready(function() {
            $("#tablaVacunas").DataTable();
        });
    </script>

    <!-- Modal agregar Vacuna -->
    <!-- TO DO: SI NO SE FILTRA UNA GRANJA ANTES, DA ERROR EL SQL -->
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
                        <label for="nombreVacuna" class="form-label">Nombre de la vacuna</label>
                        <input type="datetime-local" class="form-control" 
                                id="fechaMant" name="fechaMantenimiento"
                                required>
                            <div class="invalid-feedback">
                                Seleccione una fecha y hora válidos.
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="viaAplic" class="form-label">Via de aplicación</label>
                            <select id="selectViaAplicacion" name="viaApliacion" class="form-control">
                                <!-- Las opciones se agregarán aquí con JavaScript -->
                            </select>
                            <div class="invalid-feedback">
                                La habilitación debe tener al menos 3 caracteres.
                            </div>
                        </div>
                        <input type="hidden" id="idViaAplicacion" name="idViaAplicacion">
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
</div>

HTML;
?>
