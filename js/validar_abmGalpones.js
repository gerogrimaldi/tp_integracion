document.getElementById("metrosCuadrados").addEventListener("input", function (e) {
    const input = e.target;
    if (input.value < 1) {
        input.setCustomValidity("El valor debe ser un número positivo.");
    } else {
        input.setCustomValidity(""); // Limpia el mensaje si el valor es válido
    }
});

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
    document.querySelector("#editarGalponForm #identificacion").value = identificacion;
    document.querySelector("#editarGalponForm #capacidad").value = capacidad;
    document.querySelector("#editarGalponForm #idTipoAve").value = idTipoAve;
    document.querySelector("#editarGalponForm #idGalpon").value = idGalpon;

    // Asignar el valor del campo hidden idGranja
    document.querySelector("#editarGalponForm #idGranja").value = idGranja;

    // Actualizar la acción del formulario si es necesario
    // Aquí te aseguras de que la acción esté correctamente configurada
    const form = document.getElementById("editarGalponForm");
    form.action = `index.php?opt=galpon&edit=true&idGalpon=${idGalpon}`;
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
