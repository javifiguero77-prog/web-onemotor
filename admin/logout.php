<?php

session_start();

// Eliminar los datos de la sesión
session_unset();
session_destroy();

// Regresar al login del administrador
header("Location: login.php");
exit;

?>