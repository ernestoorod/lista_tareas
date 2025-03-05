<?php
// Inicia la sesion
session_start();

// Destruye la sesion
session_destroy();

// Destruye la cokkie con la sesion
setcookie(session_name(), '', time() - 3600, '/');

// Si todo funciona bien te lleva a index.html
header("Location: ../index.html");
exit();
?>
