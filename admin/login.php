<?php

session_start();

require_once "../conexion.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = trim($_POST["usuario"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

    if ($usuario === "" || $contrasena === "") {

        $error = "Completa todos los campos.";

    } else {

        $sql = "SELECT id_admin, usuario, contrasena
                FROM administradores
                WHERE usuario = ?
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {

            $admin = $resultado->fetch_assoc();

            if (password_verify($contrasena, $admin["contrasena"])) {

                $_SESSION["id_admin"] = $admin["id_admin"];
                $_SESSION["admin_usuario"] = $admin["usuario"];

                header("Location: panel.php");
                exit;

            } else {

                $error = "Usuario o contraseña incorrectos.";
            }

        } else {

            $error = "Usuario o contraseña incorrectos.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/png" href="/one_motorcycles/img/Logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ONE MOTORCYCLES | Administración</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<section class="access">

    <div>

        <p class="kicker">ONE MOTORCYCLES</p>

        <h2>
            Área de<br>
            <span>administración.</span>
        </h2>

        <p>
            Acceso exclusivo para personal autorizado.
        </p>

    </div>


    <div class="access-card">

        <div class="tabs">

            <button class="tab active">
                ADMINISTRADOR
            </button>

        </div>


        <?php if ($error !== ""): ?>

            <div class="form-message error">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>


        <form method="POST" action="login.php" class="form active">

            <label>

                USUARIO

                <input
                    type="text"
                    name="usuario"
                    placeholder="Usuario"
                    required
                >

            </label>


            <label>

                CONTRASEÑA

                <input
                    type="password"
                    name="contrasena"
                    placeholder="Contraseña"
                    required
                >

            </label>


            <button
                type="submit"
                class="btn red full"
            >
                INICIAR SESIÓN
            </button>

        </form>


        <p style="margin-top: 20px; text-align: center;">

            <a href="../index.php">
                ← Volver al inicio
            </a>

        </p>

    </div>

</section>

</body>

</html>