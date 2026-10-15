<?php

require_once "../conexion.php";

$usuario = "Javier";
$contrasena = "Figueroa.2023";

$hash = password_hash($contrasena, PASSWORD_DEFAULT);

$sql = "INSERT INTO administradores (usuario, contrasena)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ss",
    $usuario,
    $hash
);

if ($stmt->execute()) {

    echo "<h2>Administrador creado correctamente</h2>";
    echo "<p>Usuario: <strong>Javier</strong></p>";
    echo "<p>Contraseña: <strong>Figueroa.2023</strong></p>";
    echo "<p>Ahora elimina el archivo crear_admin.php por seguridad.</p>";

} else {

    echo "Error al crear administrador: " . $conn->error;

}

$stmt->close();
$conn->close();

?>