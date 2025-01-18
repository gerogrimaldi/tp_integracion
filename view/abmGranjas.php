<?php

$body = '
<div class="container">
    <h1>Granjas</h1>

    <div class="text-center mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarGranja">
          Agregar nueva granja
        </button>
    </div>

    <table id="myTable" class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th class="text-primary">ID Granja</th>
                <th class="text-primary">Nombre</th>
                <th class="text-primary">SENASA Nº</th>
                <th class="text-primary">m²</th>
                <th class="text-primary">Ubicación</th> 
                <th class="text-primary"></th> 
                <th class="text-primary"></th> 
            </tr>
        </thead>
        <tbody id="granja">
            <!-- Los datos se insertarán aquí -->
        </tbody>
    </table>
</div>

<script>
    // Obtener los datos de PHP
    const granja ='.$resultado.'
    // Procesar los datos y crear filas en la tabla
    const granjaTbody = document.getElementById("granja");
    granja.forEach(granja => {
        const row = document.createElement("tr");
        row.className = "table-light"; // Alternar el color de fondo
        
        row.innerHTML = `
            <td>${granja.idGranja}</td>
            <td>${granja.nombre}</td>
            <td>${granja.habilitacionSenasa}</td>
            <td>${granja.metrosCuadrados}</td>
            <td>${granja.ubicacion}</td>
            <td><button type="button"class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editarGranja"
            data-id="${granja.idGranja}" 
            data-nombre="${granja.nombre}" 
            data-habilitacion="${granja.habilitacionSenasa}" 
            data-metros="${granja.metrosCuadrados}" 
            data-ubicacion="${granja.ubicacion}">
                Editar
            </button></td>
            <td><a href="index.php?opt=granjas&delete=true&idGranja=${granja.idGranja}" class="btn btn-danger btn-sm">borrar</a></td>
        `;
        
        granjaTbody.appendChild(row);
    });

    // Inicializar DataTable
    $(document).ready(function() {
        $("#myTable").DataTable();
    });
</script>

<!-- Modal popUp Agregar Granja -->
<!-- Info de cómo lo hice: https://getbootstrap.com/docs/5.3/components/modal/ -->
<div class="modal fade" id="agregarGranja" tabindex="-1" aria-labelledby="agregarGranjaModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="agregarGranjaModal">Agregar Granja</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <form id="agregrarGranjaForm" action="index.php?opt=granjas" method="POST" class="needs-validation" novalidate>
                <div class="mb-4">
                    <label for="agregrarGranjaFormTextNombre" class="form-label">Nombre de la granja</label>
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
                    <label for="agregrarGranjaFormTextSenasa" class="form-label">Número de habilitación de SENASA</label>
                    <div class="input-group has-validation">
                        <input type="text" 
                               class="form-control" 
                               id="habilitacion" 
                               name="habilitacion" 
                               placeholder="SENASA N°"
                               required>
                        <div class="invalid-feedback">
                            Número inválido (mínimo 3 caracteres)
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="agregrarGranjaFormTextM2" class="form-label">Metros Cuadrados</label>
                    <div class="input-group has-validation">
                        <input type="number" 
                            class="form-control" 
                            id="metrosCuadrados" 
                            name="metrosCuadrados" 
                            placeholder="Tamaño de la granja"
                            min="1" 
                            required>
                            <div class="invalid-feedback">
                                <?php echo $error ?? Debe ser un número positivo.; ?>
                            </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="agregrarGranjaFormTextUbicacion" class="form-label">Ubicación</label>
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
                                <?php echo $error ?? Debe ser un número positivo.; ?>
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

<script>
document.getElementById("metrosCuadrados").addEventListener("input", function (e) {
    const input = e.target;
    if (input.value < 1) {
        input.setCustomValidity("El valor debe ser un número positivo.");
    } else {
        input.setCustomValidity(""); // Limpia el mensaje si el valor es válido
    }
});
</script>

<script>
document.getElementById("editarGranja").addEventListener("show.bs.modal", function (event) {
    // Botón que activó el modal
    const button = event.relatedTarget;

    // Extraer datos del atributo data-*
    const idGranja = button.getAttribute("data-id");
    const nombre = button.getAttribute("data-nombre");
    const habilitacion = button.getAttribute("data-habilitacion");
    const metros = button.getAttribute("data-metros");
    const ubicacion = button.getAttribute("data-ubicacion");

    // Asignar los valores a los campos del formulario
    document.querySelector("#editarGranjaForm #nombre").value = nombre;
    document.querySelector("#editarGranjaForm #habilitacion").value = habilitacion;
    document.querySelector("#editarGranjaForm #metrosCuadrados").value = metros;
    document.querySelector("#editarGranjaForm #ubicacion").value = ubicacion;
    document.querySelector("#editarGranjaForm #idGranja").value = idGranja;

    // Actualizar la acción del formulario
    const form = document.getElementById("editarGranjaForm");
    form.action = `index.php?opt=granjas&edit=true&idGranja=${idGranja}`;
});
</script>
';
?>