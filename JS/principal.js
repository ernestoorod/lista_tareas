// Agrega un evento de clic a todos los contenedores con la clase ".check-container"
document.querySelectorAll(".check-container").forEach(function (container) {
    container.addEventListener("click", function () {
        let circuloCheck = container.querySelector(".check-icon"); // Obtiene el ícono de la marca de verificación
        let check = container.querySelector(".check"); // Obtiene la marca de verificación

        // Si el círculo de verificación es visible, lo oculta y muestra la marca de verificación
        if (circuloCheck.style.display !== "none") {
            circuloCheck.style.display = "none"; // Oculta el círculo de verificación
            check.style.display = "flex"; // Muestra la marca de verificación
        } else {
            check.style.display = "none"; // Oculta la marca de verificación
            circuloCheck.style.display = "flex"; // Muestra el círculo de verificación
        }
    });
});

// Agrega eventos a los íconos de las estrellas para marcar las tareas como favoritas
document.querySelectorAll(".acciones").forEach(function (acciones) {
    let estrellaTransparente = acciones.querySelector(".estrella-transparente"); // Estrella vacía
    let estrellaAmarilla = acciones.querySelector(".estrella-amarilla"); // Estrella amarilla

    // Cuando se hace clic en la estrella transparente (vacía), cambia a amarilla
    estrellaTransparente.addEventListener("click", function () {
        estrellaTransparente.style.display = "none"; // Oculta la estrella vacía
        estrellaAmarilla.style.display = "flex"; // Muestra la estrella amarilla
    });

    // Cuando se hace clic en la estrella amarilla, vuelve a ser transparente
    estrellaAmarilla.addEventListener("click", function () {
        estrellaAmarilla.style.display = "none"; // Oculta la estrella amarilla
        estrellaTransparente.style.display = "flex"; // Muestra la estrella vacía
    });
});

// Modal para eliminar una tarea
let overlay = document.getElementById("overlay"); // Superposición del fondo
let modalEliminar = document.getElementById("modalEliminar"); // Modal de confirmación de eliminación
let tareaAEliminar = null; // Variable para almacenar la tarea a eliminar

// Agrega evento a los botones de eliminar tareas
document.querySelectorAll(".eliminar").forEach(function (button) {
    button.addEventListener("click", function (e) {
        e.preventDefault(); // Previene la acción por defecto (enlace)
        tareaAEliminar = button.getAttribute("data-tarea-id"); // Obtiene el ID de la tarea
        modalEliminar.style.display = "block"; // Muestra el modal de confirmación
        overlay.style.display = "block"; // Muestra la superposición del fondo
    });
});

// Función para cancelar la eliminación y cerrar el modal
document.querySelector(".cancelarEliminar").addEventListener("click", function () {
    modalEliminar.style.display = "none"; // Cierra el modal
    overlay.style.display = "none"; // Cierra la superposición
});

// Función para confirmar la eliminación de la tarea
document.querySelector(".confirmarEliminar").addEventListener("click", function () {
    if (tareaAEliminar) {
        window.location.href = `../PHP/eliminartarea.php?id=${tareaAEliminar}`; // Redirige a la página de eliminación
    }
});

// Permite cerrar el modal si se hace clic en la superposición (overlay)
overlay.addEventListener("click", function () {
    modalEliminar.style.display = "none"; // Cierra el modal
    overlay.style.display = "none"; // Cierra la superposición
});

// Ajusta la altura del contenedor dependiendo del número de tareas
let container = document.querySelector(".container");
let tablaTareas = document.querySelector(".tareas tbody");
let tareas = tablaTareas ? tablaTareas.querySelectorAll("tr") : [];

// Función que ajusta la altura del contenedor según la cantidad de tareas
function ajustarAlturaContainer() {
    if (tareas.length < 4) {
        container.classList.remove("auto-height"); // Remueve la clase "auto-height"
        container.classList.add("full-height"); // Agrega la clase "full-height"
    } else {
        container.classList.remove("full-height"); // Remueve la clase "full-height"
        container.classList.add("auto-height"); // Agrega la clase "auto-height"
    }
}

// Llama a la función para ajustar la altura del contenedor
ajustarAlturaContainer();

// Maneja los eventos de cambio de estado de las tareas
document.querySelectorAll(".check-container").forEach(function (checkContainer) {
    let checkIcon = checkContainer.querySelector(".check-icon"); // Círculo de verificación
    let checkMark = checkContainer.querySelector(".check"); // Marca de verificación
    let tareaId = checkContainer.getAttribute("data-id"); // ID de la tarea

    // Evento para marcar la tarea como completada
    checkIcon.addEventListener("click", function () {
        checkIcon.style.display = "none"; // Oculta el círculo de verificación
        checkMark.style.display = "flex"; // Muestra la marca de verificación

        // Envía una solicitud AJAX para actualizar el estado de la tarea
        fetch("../PHP/actualizarEstado.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${tareaId}&estado=1`, // Envia la tarea como completada
        })
            .then((response) => response.text())
            .then((data) => {
                if (data === "success") {
                    checkIcon.style.display = "none"; // Confirma que la tarea fue marcada
                    checkMark.style.display = "flex";
                } else {
                    checkIcon.style.display = "flex"; // Si ocurre un error, revertir los cambios
                    checkMark.style.display = "none";
                }
            })
            .catch((error) => {
                console.error("Hubo un error con la solicitud:", error); // Manejo de errores
                checkIcon.style.display = "flex"; // Revertir los cambios en caso de error
                checkMark.style.display = "none";
            });
    });

    // Evento para desmarcar la tarea como completada
    checkMark.addEventListener("click", function () {
        checkMark.style.display = "none"; // Oculta la marca de verificación
        checkIcon.style.display = "flex"; // Muestra el círculo de verificación

        // Envía una solicitud AJAX para actualizar el estado de la tarea a "no completada"
        fetch("../PHP/actualizarEstado.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${tareaId}&estado=0`, // Envia la tarea como no completada
        })
            .then((response) => response.text())
            .then((data) => {
                if (data === "success") {
                    checkMark.style.display = "none"; // Confirma que la tarea fue desmarcada
                    checkIcon.style.display = "flex";
                } else {
                    checkMark.style.display = "flex"; // Si ocurre un error, revertir los cambios
                    checkIcon.style.display = "none";
                }
            })
            .catch((error) => {
                console.error("Hubo un error con la solicitud:", error); // Manejo de errores
                checkMark.style.display = "flex"; // Revertir los cambios en caso de error
                checkIcon.style.display = "none";
            });
    });
});

// Maneja los eventos de marcar una tarea como favorita
document.querySelectorAll(".acciones").forEach(function (acciones) {
    let estrellaTransparente = acciones.querySelector(".estrella-transparente"); // Estrella vacía
    let estrellaAmarilla = acciones.querySelector(".estrella-amarilla"); // Estrella amarilla
    let tareaId = acciones.closest("tr").querySelector(".check-container").getAttribute("data-id"); // ID de la tarea

    // Marca la tarea como favorita
    estrellaTransparente.addEventListener("click", function () {
        estrellaTransparente.style.display = "none"; // Oculta la estrella vacía
        estrellaAmarilla.style.display = "flex"; // Muestra la estrella amarilla

        // Envia una solicitud AJAX para marcar la tarea como favorita
        fetch("../PHP/actualizarFavorito.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${tareaId}&estado=1`, // Marca la tarea como favorita
        })
            .then((response) => response.text())
            .then((data) => console.log(data)); // Maneja la respuesta
    });

    // Desmarca la tarea como favorita
    estrellaAmarilla.addEventListener("click", function () {
        estrellaAmarilla.style.display = "none"; // Oculta la estrella amarilla
        estrellaTransparente.style.display = "flex"; // Muestra la estrella vacía

        // Envia una solicitud AJAX para desmarcar la tarea como favorita
        fetch("../PHP/actualizarFavorito.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${tareaId}&estado=0`, // Desmarca la tarea como favorita
        })
            .then((response) => response.text())
            .then((data) => console.log(data)); // Maneja la respuesta
    });
});
