document.querySelectorAll(".check-container").forEach(function (container) {
    container.addEventListener("click", function () {
        let circuloCheck = container.querySelector(".check-icon");
        let check = container.querySelector(".check");

        if (circuloCheck.style.display !== "none") {
            circuloCheck.style.display = "none";
            check.style.display = "flex";
        } else {
            check.style.display = "none";
            circuloCheck.style.display = "flex";
        }
    });
});

document.querySelectorAll(".acciones").forEach(function (acciones) {
    let estrellaTransparente = acciones.querySelector(".estrella-transparente");
    let estrellaAmarilla = acciones.querySelector(".estrella-amarilla");

    estrellaTransparente.addEventListener("click", function () {
        estrellaTransparente.style.display = "none";
        estrellaAmarilla.style.display = "flex";
    });

    estrellaAmarilla.addEventListener("click", function () {
        estrellaAmarilla.style.display = "none";
        estrellaTransparente.style.display = "flex";
    });
});

let overlay = document.getElementById("overlay");
let modalEliminar = document.getElementById("modalEliminar");
let tareaAEliminar = null;

document.querySelectorAll(".eliminar").forEach(function (button) {
    button.addEventListener("click", function (e) {
        e.preventDefault();
        tareaAEliminar = button.getAttribute("data-tarea-id");
        modalEliminar.style.display = "block";
        overlay.style.display = "block";
    });
});

document.querySelector(".cancelarEliminar").addEventListener("click", function () {
    modalEliminar.style.display = "none";
    overlay.style.display = "none";
});

document.querySelector(".confirmarEliminar").addEventListener("click", function () {
    if (tareaAEliminar) {
        window.location.href = `../PHP/eliminartarea.php?id=${tareaAEliminar}`;
    }
});

overlay.addEventListener("click", function () {
    modalEliminar.style.display = "none";
    overlay.style.display = "none";
});

let container = document.querySelector(".container");
let tablaTareas = document.querySelector(".tareas tbody");
let tareas = tablaTareas ? tablaTareas.querySelectorAll("tr") : [];

function ajustarAlturaContainer() {
    if (tareas.length < 4) {
        container.classList.remove("auto-height");
        container.classList.add("full-height");
    } else {
        container.classList.remove("full-height");
        container.classList.add("auto-height");
    }
}

ajustarAlturaContainer();

document.querySelectorAll(".check-container").forEach(function (checkContainer) {
    let checkIcon = checkContainer.querySelector(".check-icon");
    let checkMark = checkContainer.querySelector(".check");
    let tareaId = checkContainer.getAttribute("data-id");

    checkIcon.addEventListener("click", function () {
        checkIcon.style.display = "none";
        checkMark.style.display = "flex";

        fetch("../PHP/actualizarEstado.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${tareaId}&estado=1`,
        })
            .then((response) => response.text())
            .then((data) => {
                if (data === "success") {
                    checkIcon.style.display = "none";
                    checkMark.style.display = "flex";
                } else {
                    checkIcon.style.display = "flex";
                    checkMark.style.display = "none";
                }
            })
            .catch((error) => {
                console.error("Hubo un error con la solicitud:", error);
                checkIcon.style.display = "flex";
                checkMark.style.display = "none";
            });
    });

    checkMark.addEventListener("click", function () {
        checkMark.style.display = "none";
        checkIcon.style.display = "flex";

        fetch("../PHP/actualizarEstado.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${tareaId}&estado=0`,
        })
            .then((response) => response.text())
            .then((data) => {
                if (data === "success") {
                    checkMark.style.display = "none";
                    checkIcon.style.display = "flex";
                } else {
                    checkMark.style.display = "flex";
                    checkIcon.style.display = "none";
                }
            })
            .catch((error) => {
                console.error("Hubo un error con la solicitud:", error);
                checkMark.style.display = "flex";
                checkIcon.style.display = "none";
            });
    });
});

document.querySelectorAll(".acciones").forEach(function (acciones) {
    let estrellaTransparente = acciones.querySelector(".estrella-transparente");
    let estrellaAmarilla = acciones.querySelector(".estrella-amarilla");
    let tareaId = acciones.closest("tr").querySelector(".check-container").getAttribute("data-id");

    estrellaTransparente.addEventListener("click", function () {
        estrellaTransparente.style.display = "none";
        estrellaAmarilla.style.display = "flex";

        fetch("../PHP/actualizarFavorito.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${tareaId}&estado=1`,
        })
            .then((response) => response.text())
            .then((data) => console.log(data));
    });

    estrellaAmarilla.addEventListener("click", function () {
        estrellaAmarilla.style.display = "none";
        estrellaTransparente.style.display = "flex";

        fetch("../PHP/actualizarFavorito.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${tareaId}&estado=0`,
        })
            .then((response) => response.text())
            .then((data) => console.log(data));
    });
});

document.getElementById('menu-btn').addEventListener('click', function() {
    document.getElementById('offcanvas').classList.add('open');
});

document.getElementById('close-btn').addEventListener('click', function() {
    document.getElementById('offcanvas').classList.remove('open');
});