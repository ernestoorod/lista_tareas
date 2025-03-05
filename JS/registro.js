document.addEventListener("DOMContentLoaded", function() {
    // Selecciona todos los campos que tienen la clase 'campo-contrasena'
    let camposContrasena = document.querySelectorAll(".campo-contrasena");

    // Recorre cada uno de los campos de contraseña
    camposContrasena.forEach(campo => {
        let input = campo.querySelector("input"); // Encuentra el input dentro del campo
        let ojocontainer = campo.querySelector(".ojo"); // Encuentra el contenedor del ojo
        let ojocerrado = campo.querySelector(".ojo-cerrado"); // Encuentra el ojo cerrado
        let ojoabierto = campo.querySelector(".ojo-abierto"); // Encuentra el ojo abierto

        // Agrega un evento de clic al contenedor del ojo
        ojocontainer.addEventListener("click", function() {
            // Si el tipo de input es 'password', cambia el tipo a 'text' para mostrar la contraseña
            if (input.type === "password") {
                input.type = "text";
                ojocerrado.style.display = "none"; // Oculta el ojo cerrado
                ojoabierto.style.display = "inline"; // Muestra el ojo abierto
            } else {
                input.type = "password"; // Vuelve a poner el input como 'password'
                ojocerrado.style.display = "inline"; // Muestra el ojo cerrado
                ojoabierto.style.display = "none"; // Oculta el ojo abierto
            }
        });
    });

    // Selecciona el modal de error y la superposición del fondo
    let modalError = document.getElementById("modal-error");
    let overlay = document.getElementById("overlay");
    let aceptarBoton = document.querySelector(".aceptarmodal"); // Selecciona el botón para aceptar el modal
    let mensajeError = document.querySelector("#modal-error .contenido p"); // Selecciona el párrafo que mostrará el mensaje de error

    // Función para cerrar el modal y limpiar el fondo de la superposición
    function cerrarYLimpiar() {
        modalError.style.display = "none"; // Oculta el modal
        overlay.style.display = "none"; // Oculta la superposición
    }

    // Evento para cerrar el modal cuando se hace clic en el botón "aceptar"
    aceptarBoton.addEventListener("click", cerrarYLimpiar);

    // Evento para cerrar el modal si se hace clic en el fondo (overlay)
    overlay.addEventListener("click", cerrarYLimpiar);

    // Obtiene los parámetros de la URL
    let params = new URLSearchParams(window.location.search);
    let error = params.get("error"); // Obtiene el valor del parámetro 'error'

    // Si existe el parámetro 'error', muestra un mensaje específico
    if (error) {
        switch (error) {
            case "1":
                mensajeError.textContent = "El nombre de usuario ya está siendo utilizado."; // Error 1
                break;
            case "2":
                mensajeError.textContent = "El correo electrónico no tiene el formato correcto."; // Error 2
                break;
            case "3":
                mensajeError.textContent = "El correo ya está siendo utilizado."; // Error 3
                break;
            case "4":
                mensajeError.textContent = "Las contraseñas no coinciden."; // Error 4
                break;
            default:
                mensajeError.textContent = "Ha ocurrido un error desconocido."; // Caso predeterminado para errores no identificados
        }
        // Muestra el modal de error y el fondo de superposición
        modalError.style.display = "block";
        overlay.style.display = "block";
    }
});
