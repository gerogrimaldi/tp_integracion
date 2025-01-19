
document.getElementById("metrosCuadrados").addEventListener("input", function (e) {
    const input = e.target;
    if (input.value < 1) {
        input.setCustomValidity("El valor debe ser un número positivo.");
    } else {
        input.setCustomValidity(""); // Limpia el mensaje si el valor es válido
    }
});



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
