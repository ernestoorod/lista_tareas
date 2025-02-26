document.addEventListener("DOMContentLoaded", function() {
    let contrasena = document.getElementById("contrasena");
    let ojocontainer = document.querySelector(".ojo");
    let ojocerrado = document.querySelector(".ojo-cerrado");
    let ojoabierto = document.querySelector(".ojo-abierto");

    ojocontainer.addEventListener("click", function() {
        if (contrasena.type === "password") {
            contrasena.type = "text";
            ojocerrado.style.display = "none";
            ojoabierto.style.display = "inline";
        } else {
            contrasena.type = "password";
            ojocerrado.style.display = "inline";
            ojoabierto.style.display = "none";
        }
    });

    let modalError = document.getElementById("modal-error");
    let overlay = document.getElementById("overlay");
    let aceptarBoton = document.querySelector(".aceptarmodal");
    let usuarioInput = document.getElementById("nombreusuario");

    function cerrarYLimpiar() {
        modalError.style.display = "none";
        overlay.style.display = "none";
        usuarioInput.value = "";
        passwordInput.value = "";
    }

    aceptarBoton.addEventListener("click", cerrarYLimpiar);
    overlay.addEventListener("click", cerrarYLimpiar);

    if (window.location.search.includes("error=1")) {
        modalError.style.display = "block";
        overlay.style.display = "block";
    }
});
