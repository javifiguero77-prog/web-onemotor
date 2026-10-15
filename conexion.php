<?php

$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "one_motorcycles";

$conn = new mysqli(
    $servidor,
    $usuario,
    $contrasena,
    $base_datos
);

if ($conn->connect_error) {
    die("Error de conexión con la base de datos: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>