document.addEventListener("DOMContentLoaded", function() {
    // Elementos del DOM
    let contrasena = document.getElementById("contrasena"); // Campo de contraseña
    let ojocontainer = document.querySelector(".ojo"); // Contenedor del ícono de ojo (para mostrar/ocultar contraseña)
    let ojocerrado = document.querySelector(".ojo-cerrado"); // Ícono de ojo cerrado (mostrar contraseña oculta)
    let ojoabierto = document.querySelector(".ojo-abierto"); // Ícono de ojo abierto (mostrar contraseña visible)

    // Funcionalidad para mostrar/ocultar la contraseña al hacer clic en el ícono de ojo
    ojocontainer.addEventListener("click", function() {
        // Si la contraseña está oculta (input type="password"), cambiar a texto (type="text")
        if (contrasena.type === "password") {
            contrasena.type = "text"; // Muestra la contraseña
            ojocerrado.style.display = "none"; // Oculta el ícono del ojo cerrado
            ojoabierto.style.display = "inline"; // Muestra el ícono del ojo abierto
        } else {
            contrasena.type = "password"; // Oculta la contraseña
            ojocerrado.style.display = "inline"; // Muestra el ícono del ojo cerrado
            ojoabierto.style.display = "none"; // Oculta el ícono del ojo abierto
        }
    });

    // Elementos para el modal de error y superposición
    let modalError = document.getElementById("modal-error"); // Modal de error
    let overlay = document.getElementById("overlay"); // Superposición del fondo
    let aceptarBoton = document.querySelector(".aceptarmodal"); // Botón de aceptar en el modal
    let usuarioInput = document.getElementById("nombreusuario"); // Campo del nombre de usuario

    // Función para cerrar el modal y limpiar los campos
    function cerrarYLimpiar() {
        modalError.style.display = "none"; // Oculta el modal de error
        overlay.style.display = "none"; // Oculta la superposición
        usuarioInput.value = ""; // Limpia el campo de nombre de usuario
        contrasena.value = ""; // Limpia el campo de contraseña
    }

    // Event listener para el botón de aceptar y la superposición (overlay)
    aceptarBoton.addEventListener("click", cerrarYLimpiar); // Al hacer clic en "Aceptar", se cierra el modal y se limpian los campos
    overlay.addEventListener("click", cerrarYLimpiar); // Al hacer clic en el overlay (fondo gris), también se cierra el modal y se limpian los campos

    // Verifica si hay un parámetro 'error=1' en la URL
    if (window.location.search.includes("error=1")) {
        // Si existe el parámetro 'error=1', muestra el modal de error
        modalError.style.display = "block";
        overlay.style.display = "block";
    }
});
