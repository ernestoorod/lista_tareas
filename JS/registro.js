document.addEventListener("DOMContentLoaded", function() {
    let camposContrasena = document.querySelectorAll(".campo-contrasena");

    camposContrasena.forEach(campo => {
        let input = campo.querySelector("input");
        let ojocontainer = campo.querySelector(".ojo");
        let ojocerrado = campo.querySelector(".ojo-cerrado");
        let ojoabierto = campo.querySelector(".ojo-abierto");

        ojocontainer.addEventListener("click", function() {
            if (input.type === "password") {
                input.type = "text";
                ojocerrado.style.display = "none";
                ojoabierto.style.display = "inline";
            } else {
                input.type = "password";
                ojocerrado.style.display = "inline";
                ojoabierto.style.display = "none";
            }
        });
    });

    let modalError = document.getElementById("modal-error");
    let overlay = document.getElementById("overlay");
    let aceptarBoton = document.querySelector(".aceptarmodal");
    let mensajeError = document.querySelector("#modal-error .contenido p");

    function cerrarYLimpiar() {
        modalError.style.display = "none";
        overlay.style.display = "none";
    }

    aceptarBoton.addEventListener("click", cerrarYLimpiar);
    overlay.addEventListener("click", cerrarYLimpiar);

    let params = new URLSearchParams(window.location.search);
    let error = params.get("error");

    if (error) {
        switch (error) {
            case "1":
                mensajeError.textContent = "El nombre de usuario ya está siendo utilizado.";
                break;
            case "2":
                mensajeError.textContent = "El correo electrónico no tiene el formato correcto.";
                break;
            case "3":
                mensajeError.textContent = "El correo ya está siendo utilizado.";
                break;
            case "4":
                mensajeError.textContent = "Las contraseñas no coinciden.";
                break;
            default:
                mensajeError.textContent = "Ha ocurrido un error desconocido.";
        }
        modalError.style.display = "block";
        overlay.style.display = "block";
    }
});
