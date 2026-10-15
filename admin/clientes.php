<?php

session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../conexion.php";

$mensaje = "";
$error = "";


/* =========================
   ELIMINAR CLIENTE
========================= */

if (isset($_GET["eliminar"])) {

    $id = intval($_GET["eliminar"]);

    $sql = "DELETE FROM clientes WHERE id_cliente = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $mensaje = "Cliente eliminado correctamente.";
    } else {
        $error = "No se pudo eliminar el cliente.";
    }

    $stmt->close();
}


/* =========================
   OBTENER CLIENTES
========================= */

$sql = "SELECT
            id_cliente,
            nombre_completo,
            telefono,
            correo,
            usuario
        FROM clientes
        ORDER BY id_cliente DESC";

$clientes = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/png" href="/one_motorcycles/img/Logo.png">
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        ONE MOTORCYCLES | Clientes
    </title>

    <link rel="stylesheet"
          href="../css/style.css">

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
                ADMINISTRACIÓN
            </p>

            <h2>
                Clientes
            </h2>

        </div>

        <p>
            Consulta los clientes registrados
            en ONE MOTORCYCLES.
        </p>

    </div>


    <?php if ($mensaje !== ""): ?>

        <div class="form-message success">

            <?php echo htmlspecialchars($mensaje); ?>

        </div>

    <?php endif; ?>


    <?php if ($error !== ""): ?>

        <div class="form-message error">

            <?php echo htmlspecialchars($error); ?>

        </div>

    <?php endif; ?>


    <div style="margin-top: 40px;">

        <h3>
            Clientes registrados
        </h3>


        <div style="overflow-x: auto; margin-top: 20px;">

            <table
                style="
                    width: 100%;
                    border-collapse: collapse;
                    min-width: 750px;
                "
            >

                <thead>

                    <tr>

                        <th style="padding: 12px; text-align: left;">
                            ID
                        </th>

                        <th style="padding: 12px; text-align: left;">
                            NOMBRE COMPLETO
                        </th>

                        <th style="padding: 12px; text-align: left;">
                            TELÉFONO
                        </th>

                        <th style="padding: 12px; text-align: left;">
                            CORREO
                        </th>

                        <th style="padding: 12px; text-align: left;">
                            USUARIO
                        </th>

                        <th style="padding: 12px; text-align: left;">
                            ACCIÓN
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php if ($clientes && $clientes->num_rows > 0): ?>


                    <?php while ($cliente = $clientes->fetch_assoc()): ?>


                        <tr>


                            <td style="padding: 12px;">

                                <?php
                                echo $cliente["id_cliente"];
                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php
                                echo htmlspecialchars(
                                    $cliente["nombre_completo"]
                                );
                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php
                                echo htmlspecialchars(
                                    $cliente["telefono"]
                                );
                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php
                                echo htmlspecialchars(
                                    $cliente["correo"]
                                );
                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php
                                echo htmlspecialchars(
                                    $cliente["usuario"]
                                );
                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <a
                                    href="clientes.php?eliminar=<?php echo $cliente["id_cliente"]; ?>"
                                    class="btn red"
                                    onclick="return confirm('¿Seguro que deseas eliminar este cliente?');"
                                >
                                    ELIMINAR
                                </a>

                            </td>


                        </tr>


                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td
                            colspan="6"
                            style="
                                padding: 30px;
                                text-align: center;
                            "
                        >

                            No hay clientes registrados.

                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>

            </table>

        </div>

    </div>


    <div style="margin-top: 35px;">

        <a
            href="panel.php"
            class="btn outline"
        >
            ← VOLVER AL PANEL
        </a>

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
        Administración · ONE MOTORCYCLES
    </p>

</footer>


</body>

</html>