<?php
session_start();
require_once "conexion.php";

$mensaje = "";
$tipo_mensaje = "";

/* =========================
   PROCESAR LOGIN
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = trim($_POST["usuario"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

    if ($usuario === "" || $contrasena === "") {

        $mensaje = "Completa todos los campos.";
        $tipo_mensaje = "error";

    } else {

        $sql = "SELECT
                    id_cliente,
                    nombre_completo,
                    usuario,
                    contrasena
                FROM clientes
                WHERE usuario = ?
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {

            $cliente = $resultado->fetch_assoc();

            /*
             * La contraseña debe estar guardada
             * usando password_hash().
             */

            if (password_verify($contrasena, $cliente["contrasena"])) {

                $_SESSION["id_cliente"] =
                    $cliente["id_cliente"];

                $_SESSION["nombre_cliente"] =
                    $cliente["nombre_completo"];

                $_SESSION["usuario_cliente"] =
                    $cliente["usuario"];


                header("Location: index.php");
                exit;

            } else {

                $mensaje = "Usuario o contraseña incorrectos.";
                $tipo_mensaje = "error";

            }

        } else {

            $mensaje = "Usuario o contraseña incorrectos.";
            $tipo_mensaje = "error";

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

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>ONE MOTORCYCLES | Acceso</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>


<!-- =========================
     HEADER
========================= -->

<header class="topbar">

    <a href="index.php" class="brand">

        <img
            src="img/onemoto.png"
            alt="ONE MOTORCYCLES"
            class="site-logo">

    </a>


    <nav class="main-nav">

        <a href="index.php">
            INICIO
        </a>

        <a href="catalogo.php">
            CATÁLOGO
        </a>

        <a href="marcas.php">
            MARCAS
        </a>

        <a href="nosotros.php">
            NOSOTROS
        </a>

        <a href="login.php"
           class="active">
            ACCESO
        </a>

    </nav>


    <a href="catalogo.php"
       class="top-cta">

        VER MOTOS

    </a>

</header>



<!-- =========================
     LOGIN
========================= -->

<main>

<section class="access">


    <div class="access-intro">

        <p class="kicker">
            ONE MOTORCYCLES
        </p>


        <h1>
            BIENVENIDO<br>
            <span>DE NUEVO.</span>
        </h1>


        <p>
            Inicia sesión para acceder
            a tu cuenta de ONE MOTORCYCLES.
        </p>


        <p>
            ¿No tienes una cuenta?
            <a href="registro.php">
                Regístrate aquí.
            </a>
        </p>

    </div>



    <div class="access-card">


        <div class="tabs">

            <span class="tab active">
                INICIAR SESIÓN
            </span>

        </div>



        <?php if ($mensaje !== ""): ?>

            <div class="form-message <?php
                echo htmlspecialchars($tipo_mensaje);
            ?>">

                <?php
                echo htmlspecialchars($mensaje);
                ?>

            </div>

        <?php endif; ?>



        <form
            method="POST"
            action="login.php"
            class="form"
        >


            <label>

                USUARIO

                <input
                    type="text"
                    name="usuario"
                    placeholder="Ingresa tu usuario"
                    required
                    autocomplete="username"
                >

            </label>



            <label>

                CONTRASEÑA

                <input
                    type="password"
                    name="contrasena"
                    placeholder="Ingresa tu contraseña"
                    required
                    autocomplete="current-password"
                >

            </label>



            <button
                type="submit"
                class="btn red full">

                INICIAR SESIÓN

            </button>


        </form>



        <div class="register-link">

            <p>
                ¿Todavía no tienes una cuenta?
            </p>


            <a
                href="registro.php"
                class="btn outline full">

                CREAR CUENTA

            </a>

        </div>


    </div>

</section>

</main>



<!-- =========================
     FOOTER
========================= -->

<footer>


    <div class="footer-content">


        <div>

            <img
                src="img/onemoto.png"
                alt="ONE MOTORCYCLES"
                class="site-logo">


            <p>
                Motocicletas para todos los caminos.
            </p>

        </div>



        <div>

            <h4>
                NAVEGACIÓN
            </h4>


            <a href="index.php">
                Inicio
            </a>


            <a href="catalogo.php">
                Catálogo
            </a>


            <a href="marcas.php">
                Marcas
            </a>


            <a href="nosotros.php">
                Nosotros
            </a>


            <a href="login.php">
                Acceso
            </a>

        </div>



        <div>

            <h4>
                ONE MOTORCYCLES
            </h4>


            <p>
                El Salvador
            </p>


            <p>
                Calidad, estilo y rendimiento.
            </p>

        </div>


    </div>



    <div class="footer-bottom">

        <p>
            © <?php echo date("Y"); ?>
            ONE MOTORCYCLES
        </p>


        <p>
            Todos los derechos reservados.
        </p>

    </div>


</footer>


</body>

</html>