<?php
session_start();
require_once "conexion.php";

$mensaje = "";
$tipo_mensaje = "";

/* =========================
   PROCESAR REGISTRO
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre_completo"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $usuario = trim($_POST["usuario"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";
    $confirmar = $_POST["confirmar_contrasena"] ?? "";


    /* =========================
       VALIDAR CAMPOS
    ========================= */

    if (
        $nombre === "" ||
        $telefono === "" ||
        $correo === "" ||
        $usuario === "" ||
        $contrasena === "" ||
        $confirmar === ""
    ) {

        $mensaje = "Completa todos los campos.";
        $tipo_mensaje = "error";

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

        $mensaje = "Ingresa un correo electrónico válido.";
        $tipo_mensaje = "error";

    } elseif ($contrasena !== $confirmar) {

        $mensaje = "Las contraseñas no coinciden.";
        $tipo_mensaje = "error";

    } elseif (strlen($contrasena) < 6) {

        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
        $tipo_mensaje = "error";

    } else {


        /* =========================
           COMPROBAR USUARIO
        ========================= */

        $sql = "SELECT id_cliente
                FROM clientes
                WHERE usuario = ?
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();

        $resultado = $stmt->get_result();


        if ($resultado->num_rows > 0) {

            $mensaje = "Ese usuario ya está registrado.";
            $tipo_mensaje = "error";

            $stmt->close();

        } else {

            $stmt->close();


            /* =========================
               COMPROBAR CORREO
            ========================= */

            $sql = "SELECT id_cliente
                    FROM clientes
                    WHERE correo = ?
                    LIMIT 1";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $correo);
            $stmt->execute();

            $resultado = $stmt->get_result();


            if ($resultado->num_rows > 0) {

                $mensaje = "Ese correo ya está registrado.";
                $tipo_mensaje = "error";

                $stmt->close();

            } else {

                $stmt->close();


                /* =========================
                   ENCRIPTAR CONTRASEÑA
                ========================= */

                $contrasena_hash =
                    password_hash(
                        $contrasena,
                        PASSWORD_DEFAULT
                    );


                /* =========================
                   INSERTAR CLIENTE
                ========================= */

                $sql = "INSERT INTO clientes
                        (
                            nombre_completo,
                            telefono,
                            correo,
                            usuario,
                            contrasena
                        )
                        VALUES (?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);

                $stmt->bind_param(
                    "sssss",
                    $nombre,
                    $telefono,
                    $correo,
                    $usuario,
                    $contrasena_hash
                );


                if ($stmt->execute()) {

                    $mensaje =
                        "Cuenta creada correctamente. Ya puedes iniciar sesión.";

                    $tipo_mensaje = "success";

                    /*
                     * Limpiamos los campos después
                     * de registrar correctamente.
                     */

                    $nombre = "";
                    $telefono = "";
                    $correo = "";
                    $usuario = "";

                } else {

                    $mensaje =
                        "Ocurrió un error al crear la cuenta.";

                    $tipo_mensaje = "error";
                }


                $stmt->close();
            }
        }
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

    <title>ONE MOTORCYCLES | Registro</title>

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
     REGISTRO
========================= -->

<main>

<section class="access">


    <div class="access-intro">

        <p class="kicker">
            ONE MOTORCYCLES
        </p>


        <h1>
            CREA TU<br>
            <span>CUENTA.</span>
        </h1>


        <p>
            Regístrate para formar parte
            de ONE MOTORCYCLES.
        </p>


        <p>
            ¿Ya tienes una cuenta?
            <a href="login.php">
                Inicia sesión aquí.
            </a>
        </p>

    </div>



    <div class="access-card">


        <div class="tabs">

            <span class="tab active">
                REGISTRO
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
            action="registro.php"
            class="form"
        >


            <!-- NOMBRE -->

            <label>

                NOMBRE COMPLETO

                <input
                    type="text"
                    name="nombre_completo"
                    placeholder="Nombre completo"
                    value="<?php
                        echo htmlspecialchars(
                            $nombre ?? ""
                        );
                    ?>"
                    required
                >

            </label>



            <!-- TELÉFONO -->

            <label>

                TELÉFONO

                <input
                    type="text"
                    name="telefono"
                    placeholder="Teléfono"
                    value="<?php
                        echo htmlspecialchars(
                            $telefono ?? ""
                        );
                    ?>"
                    required
                >

            </label>



            <!-- CORREO -->

            <label>

                CORREO ELECTRÓNICO

                <input
                    type="email"
                    name="correo"
                    placeholder="correo@ejemplo.com"
                    value="<?php
                        echo htmlspecialchars(
                            $correo ?? ""
                        );
                    ?>"
                    required
                >

            </label>



            <!-- USUARIO -->

            <label>

                USUARIO

                <input
                    type="text"
                    name="usuario"
                    placeholder="Nombre de usuario"
                    value="<?php
                        echo htmlspecialchars(
                            $usuario ?? ""
                        );
                    ?>"
                    required
                >

            </label>



            <!-- CONTRASEÑA -->

            <label>

                CONTRASEÑA

                <input
                    type="password"
                    name="contrasena"
                    placeholder="Mínimo 6 caracteres"
                    required
                    minlength="6"
                >

            </label>



            <!-- CONFIRMAR CONTRASEÑA -->

            <label>

                CONFIRMAR CONTRASEÑA

                <input
                    type="password"
                    name="confirmar_contrasena"
                    placeholder="Repite tu contraseña"
                    required
                    minlength="6"
                >

            </label>



            <!-- BOTÓN -->

            <button
                type="submit"
                class="btn red full">

                CREAR CUENTA

            </button>


        </form>



        <div class="register-link">

            <p>
                ¿Ya tienes una cuenta?
            </p>


            <a
                href="login.php"
                class="btn outline full">

                INICIAR SESIÓN

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