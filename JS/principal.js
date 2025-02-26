let actualizarFecha = function(fecha) {
    let fechaElemento = document.getElementById("fecha");
    fechaElemento.textContent = fecha.toLocaleDateString("es-ES", {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

let fechaActual = new Date();
actualizarFecha(fechaActual);

document.getElementById("prevDate").addEventListener("click", function () {
    fechaActual.setDate(fechaActual.getDate() - 1);
    actualizarFecha(fechaActual);
});

document.getElementById("nextDate").addEventListener("click", function () {
    fechaActual.setDate(fechaActual.getDate() + 1);
    actualizarFecha(fechaActual);
});

document.querySelectorAll(".check-container").forEach(function(container) {
    container.addEventListener("click", function () {
        let circulocheck = container.querySelector(".check-icon");
        let check = container.querySelector(".check");

        if (circulocheck.style.display !== 'none') {
            circulocheck.style.display = 'none';
            check.style.display = 'flex';
        } else {
            check.style.display = 'none';
            circulocheck.style.display = 'flex';
        }
    });
});

document.querySelectorAll(".acciones").forEach(function(acciones) {
    let estrellatransparente = acciones.querySelector(".estrella-transparente");
    let estrellamarilla = acciones.querySelector(".estrella-amarilla");

    estrellatransparente.addEventListener("click", function () {
        estrellatransparente.style.display = 'none';
        estrellamarilla.style.display = 'flex';
    });

    estrellamarilla.addEventListener("click", function () {
        estrellamarilla.style.display = 'none';
        estrellatransparente.style.display = 'flex';
    });
});

let tareaAEliminar = null;
let overlay = document.getElementById('overlay');

let abrirModal = function(tareaId) {
    tareaAEliminar = tareaId;
    let modal = document.getElementById(`modalEliminar_${tareaId}`);
    
    modal.style.display = "block";
    overlay.style.display = "block";
}

let cerrarModal = function(tareaId) {
    let modal = document.getElementById(`modalEliminar_${tareaId}`);
    
    modal.style.display = "none";
    overlay.style.display = "none";
}

document.querySelectorAll('.eliminar').forEach(function(button) {
    button.addEventListener('click', function () {
        let tareaId = button.getAttribute('data-tarea-id');
        abrirModal(tareaId);
    });
});

document.querySelectorAll('.cancelarEliminar').forEach(function(button) {
    button.addEventListener('click', function () {
        let tareaId = button.closest('.modal').id.split('_')[1];
        cerrarModal(tareaId);
    });
});

document.querySelectorAll('.confirmarEliminar').forEach(function(button) {
    button.addEventListener('click', function () {
        let tareaId = button.getAttribute('data-tarea-id');
        window.location.href = `../PHP/eliminartarea.php?id=${tareaId}`;
        cerrarModal(tareaId);
    });
});

document.getElementById('overlay').addEventListener('click', function () {
    if (tareaAEliminar) {
        cerrarModal(tareaAEliminar);
    }
});

let container = document.querySelector('.container');
let tablaTareas = document.querySelector('.tareas tbody');
let tareas = tablaTareas ? tablaTareas.querySelectorAll('tr') : [];

function ajustarAlturaContainer() {
    if (tareas.length < 4) {
        container.classList.remove('auto-height');
        container.classList.add('full-height');
    } else {
        container.classList.remove('full-height');
        container.classList.add('auto-height');
    }
}

ajustarAlturaContainer();

document.querySelectorAll('.confirmarEliminar').forEach(function(button) {
    button.addEventListener('click', function () {
        window.location.href = `../PHP/eliminartarea.php?id=${button.getAttribute('data-tarea-id')}`;
        setTimeout(ajustarAlturaContainer, 500)
    });
});

document.querySelectorAll(".check-container").forEach(function(checkContainer) {
    let checkIcon = checkContainer.querySelector(".check-icon");
    let checkMark = checkContainer.querySelector(".check");
    let tareaId = checkContainer.getAttribute("data-id");

    checkIcon.addEventListener("click", function () {
        checkIcon.style.display = 'none';
        checkMark.style.display = 'flex';

        fetch('../PHP/actualizarEstado.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${tareaId}&estado=1`
        })
        .then(response => response.text())
        .then(data => {
            if (data === "success") {
                checkIcon.style.display = 'none';
                checkMark.style.display = 'flex';
            } else {
                checkIcon.style.display = 'flex';
                checkMark.style.display = 'none';
            }
        })
        .catch(error => {
            console.error("Hubo un error con la solicitud:", error);
            checkIcon.style.display = 'flex';
            checkMark.style.display = 'none';
        });
    });

    checkMark.addEventListener("click", function () {
        checkMark.style.display = 'none';
        checkIcon.style.display = 'flex';

        fetch('../PHP/actualizarEstado.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${tareaId}&estado=0`
        })
        .then(response => response.text())
        .then(data => {
            if (data === "success") {
                checkMark.style.display = 'none';
                checkIcon.style.display = 'flex';
            } else {
                checkMark.style.display = 'flex';
                checkIcon.style.display = 'none';
            }
        })
        .catch(error => {
            console.error("Hubo un error con la solicitud:", error);
            checkMark.style.display = 'flex';
            checkIcon.style.display = 'none';
        });
    });
});

document.querySelectorAll(".acciones").forEach(function(acciones) {
    let estrellatransparente = acciones.querySelector(".estrella-transparente");
    let estrellamarilla = acciones.querySelector(".estrella-amarilla");
    let tareaId = acciones.closest('tr').querySelector('.check-container').getAttribute("data-id");

    estrellatransparente.addEventListener("click", function () {
        estrellatransparente.style.display = 'none';
        estrellamarilla.style.display = 'flex';

        fetch('../PHP/actualizarFavorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${tareaId}&estado=1`
        }).then(response => response.text())
          .then(data => console.log(data));
    });

    estrellamarilla.addEventListener("click", function () {
        estrellamarilla.style.display = 'none';
        estrellatransparente.style.display = 'flex';

        fetch('../PHP/actualizarFavorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${tareaId}&estado=0`
        }).then(response => response.text())
          .then(data => console.log(data));
    });
});





