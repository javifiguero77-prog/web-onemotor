<?php

session_start();

// Destruir la sesión
session_unset();
session_destroy();

// Regresar al inicio
header("Location: index.php");
exit;

?>