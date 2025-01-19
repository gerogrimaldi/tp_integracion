<?php

$error = $error ?? ''; // Definir $error como cadena vacía si no está definido

$body = <<<HTML
<div class="container">
    <h1>Galpones</h1>

    <div class="text-center mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarGalpon">
          Agregar galpón
        </button>
    </div>

    <table id="myTable" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID Galpon</th>
                <th class="text-primary">Identificacion</th>
                <th class="text-primary">Capacidad</th>
                <th class="text-primary">ID granja</th>
                <th class="text-primary">Ubicación</th> 
                <th class="text-primary"></th> 
                <th class="text-primary"></th> 
            </tr>
        </thead>
        <tbody id="galpon">
            <!-- Los datos se insertarán aquí -->
        </tbody>
    </table>
</div>

<script>
    var galpon = $resultado;
    
    // Procesar los datos y crear filas en la tabla
    var galponTbody = document.getElementById("galpon");
    
    galpon.forEach(function(galpon) {
        var row = document.createElement("tr");
        row.className = "table-light";
        row.innerHTML = 
            '<td>' + galpon.idgalpon + '</td>' +
            '<td>' + galpon.identificacion + '</td>' +
            '<td>' + galpon.idTipoAve + '</td>' +
            '<td>' + galpon.capacidad + '</td>' +
            '<td>' + galpon.idGranja + '</td>' +
            '<td>' +
                '<button type="button" ' +
                        'class="btn btn-warning btn-sm" ' +
                        'data-bs-toggle="modal" ' +
                        'data-bs-target="#editargalpon" ' +
                        'data-id="' + galpon.idgalpon + '" ' +
                        'data-identificacion="' + galpon.identificacion + '" ' +
                        'data-idTipoAve="' + galpon.idTipoAve + '" ' +
                        'data-capacidad="' + galpon.capacidad + '" ' +
                        'data-idGranja="' + galpon.idGranja + '">' +
                    'Editar' +
                '</button>' +
            '</td>' +
            '<td>' +
                '<a href="index.php?opt=galpons&delete=true&idgalpon=' + galpon.idGalpon + '" ' +
                   'class="btn btn-danger btn-sm">' +
                    'borrar' +
                '</a>' +
            '</td>';
        
        galponTbody.appendChild(row);
    });

    // Inicializar DataTable
    $(document).ready(function() {
        $("#myTable").DataTable();
    });
</script>


</script>
<!-- Modal popUp Agregar Galpon -->
<div class="modal fade" id="agregarGalpon" tabindex="-1" aria-labelledby="agregarGalponModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="agregarGalponModal">Agregar Galpon</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="agregrarGalponForm" action="index.php?opt=galpones" method="POST" class="needs-validation" novalidate>
    <div class="mb-4">
        <label for="identificacion" class="form-label">Identificacion del galpón</label>
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
        <label for="tipoAve" class="form-label">Tipo de Ave</label>
            <input type="text" 
                 class="form-control" 
                 id="idTipoAve" 
                 name="idTipoAve" 
                 placeholder="Tipo de Ave"
                 required>
        <div class="invalid-feedback">
                La habilitación debe tener al menos 3 caracteres.
        </div>
     </div>
    <div class="mb-4">
        <label for="ubicacion" class="form-label">Ubicación</label>
        <input type="text" 
               class="form-control" 
               id="ubicacion" 
               name="ubicacion" 
               placeholder="Localidad"
               minlength="3"
               required>
        <div class="invalid-feedback">
            La ubicación debe tener al menos 3 caracteres.
        </div>
    </div>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" name="btGranja" value="registrarGranja" form="agregrarGranjaForm">Agregar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal popUp editar Granja -->
<div class="modal fade" id="editarGranja" tabindex="-1" aria-labelledby="editarGranjaModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editarGranjaModal">Editar datos de la Granja</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <form id="editarGranjaForm" action="index.php?opt=granjas" method="POST" class="needs-validation" novalidate>
                <div class="mb-4">
                    <label for="editarGranjaFormTextNombre" class="form-label">Nombre de la granja</label>
                    <div class="input-group has-validation">
                        <input type="text" 
                               class="form-control" 
                               id="nombre" 
                               name="nombre" 
                               placeholder="Nombre"
                               minlength="3"
                               required>
                        <div class="invalid-feedback">
                            Nombre inválido (mínimo 3 caracteres)
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="editarGranjaFormTextSenasa" class="form-label">Número de habilitación de SENASA</label>
                    <div class="input-group has-validation">
                        <input type="text" 
                               class="form-control" 
                               id="habilitacion" 
                               name="habilitacion" 
                               placeholder="SENASA N°"
                               minlength="3"
                               required>
                        <div class="invalid-feedback">
                            Nombre inválido (mínimo 3 caracteres)
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="editarGranjaFormTextM2" class="form-label">Metros Cuadrados</label>
                    <div class="input-group has-validation">
                        <input type="number" 
                            class="form-control" 
                            id="metrosCuadrados" 
                            name="metrosCuadrados" 
                            placeholder="Tamaño de la granja"
                            min="1" 
                            required>
                            <div class="invalid-feedback">
                                <?php echo $error ?: 'Debe ser un número positivo.'; ?>
                            </div>

                    </div>
                </div>
                <div class="mb-4">
                    <label for="editarGranjaFormTextUbicacion" class="form-label">Ubicación</label>
                    <div class="input-group has-validation">
                        <input type="text" 
                               class="form-control" 
                               id="ubicacion" 
                               name="ubicacion" 
                               placeholder="Localidad"
                               minlength="3"
                               required>
                        <div class="invalid-feedback">
                            Nombre inválido (mínimo 3 caracteres)
                        </div>
                    </div>
                </div>
                <input type="hidden" id="idGranja" name="idGranja">
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" name="btGranja" value="editarGranja" form="editarGranjaForm">Finalizar</button>
      </div>
    </div>
  </div>
</div>



<script src="js/validar_abm.js"></script>
HTML;
;
?>