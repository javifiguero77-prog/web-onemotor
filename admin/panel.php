<?php

session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: login.php");
    exit;
}

$admin_usuario = $_SESSION["admin_usuario"];

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

<header class="topbar">

    <a href="../index.php" class="brand">

        <img
            src="../img/onemoto.png"
            alt="ONE MOTORCYCLES"
            class="site-logo"
        >

    </a>


    <nav>

        <a href="panel.php">
            ADMINISTRACIÓN
        </a>

    </nav>


    <a href="logout.php" class="top-cta">
        CERRAR SESIÓN
    </a>

</header>


<main>

<section class="catalog-section">

    <div class="section-top">

        <div>

            <p class="kicker">
                PANEL ADMINISTRATIVO
            </p>

            <h2>
                Bienvenido,
                <span>
                    <?php echo htmlspecialchars($admin_usuario); ?>
                </span>
            </h2>

        </div>


        <p>
            Desde este panel puedes administrar la información
            de ONE MOTORCYCLES.
        </p>

    </div>


    <div class="about-grid">


        <!-- MOTOCICLETAS -->

        <div>

            <span class="number">
                01
            </span>

            <h3>
                Motocicletas
            </h3>

            <p>
                Registrar, editar, eliminar y cambiar el estado
                de las motocicletas.
            </p>

            <a
                href="motocicletas.php"
                class="btn red"
            >
                ADMINISTRAR MOTOS →
            </a>

        </div>


        <!-- CLIENTES -->

        <div>

            <span class="number">
                02
            </span>

            <h3>
                Clientes
            </h3>

            <p>
                Consultar los clientes registrados
                en el sistema.
            </p>

            <a
                href="clientes.php"
                class="btn outline"
            >
                VER CLIENTES →
            </a>

        </div>


        <!-- VENDEDORES -->

        <div>

            <span class="number">
                03
            </span>

            <h3>
                Vendedores
            </h3>

            <p>
                Administrar los vendedores registrados
                y consultar sus ventas.
            </p>

            <a
                href="vendedores.php"
                class="btn outline"
            >
                VER VENDEDORES →
            </a>

        </div>


        <!-- VENTAS -->

        <div>

            <span class="number">
                04
            </span>

            <h3>
                Ventas
            </h3>

            <p>
                Consultar y registrar las ventas
                realizadas.
            </p>

            <a
                href="ventas.php"
                class="btn outline"
            >
                VER VENTAS →
            </a>

        </div>


    </div>

</section>

</main>


<footer>

    <div class="brand">

        <img
            src="../img/onemoto.png"
            alt="ONE MOTORCYCLES"
            class="site-logo"
        >

    </div>

    <p>
        Panel administrativo · ONE MOTORCYCLES
    </p>

</footer>

</body>

</html>