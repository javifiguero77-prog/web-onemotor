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
   ELIMINAR VENDEDOR
========================= */

if (isset($_GET["eliminar"])) {

    $id = intval($_GET["eliminar"]);

    $sql = "DELETE FROM vendedores WHERE id_vendedor = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $mensaje = "Vendedor eliminado correctamente.";
    } else {
        $error = "No se pudo eliminar el vendedor. Puede tener ventas asociadas.";
    }

    $stmt->close();
}


/* =========================
   REGISTRAR VENDEDOR
========================= */

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["accion"])
    && $_POST["accion"] === "registrar"
) {

    $nombre = trim($_POST["nombre_completo"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $correo = trim($_POST["correo"] ?? "");

    if ($nombre === "" || $telefono === "" || $correo === "") {

        $error = "Completa todos los campos.";

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

        $error = "Ingresa un correo electrónico válido.";

    } else {

        $sql = "INSERT INTO vendedores
                (
                    nombre_completo,
                    telefono,
                    correo
                )
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sss",
            $nombre,
            $telefono,
            $correo
        );

        if ($stmt->execute()) {

            $mensaje = "Vendedor registrado correctamente.";

        } else {

            $error = "No se pudo registrar el vendedor.";

        }

        $stmt->close();
    }
}


/* =========================
   OBTENER VENDEDORES
   ORDENADOS POR VENTAS
========================= */

$sql = "SELECT
            v.id_vendedor,
            v.nombre_completo,
            v.telefono,
            v.correo,
            COUNT(
                CASE
                    WHEN ve.estado = 'Completada'
                    THEN ve.id_venta
                END
            ) AS cantidad_ventas
        FROM vendedores v
        LEFT JOIN ventas ve
            ON v.id_vendedor = ve.id_vendedor
        GROUP BY
            v.id_vendedor,
            v.nombre_completo,
            v.telefono,
            v.correo
        ORDER BY cantidad_ventas DESC,
                 v.nombre_completo ASC";

$vendedores = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/png" href="/one_motorcycles/img/Logo.png">

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        ONE MOTORCYCLES | Vendedores
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
                Vendedores
            </h2>

        </div>

        <p>
            Administra los vendedores y consulta
            la cantidad de ventas completadas.
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


    <!-- =========================
         REGISTRAR VENDEDOR
    ========================== -->

    <div
        class="access-card"
        style="max-width: 700px; margin: 30px auto;"
    >

        <h3>
            Registrar vendedor
        </h3>


        <form
            method="POST"
            action="vendedores.php"
            class="form"
        >

            <input
                type="hidden"
                name="accion"
                value="registrar"
            >


            <label>

                NOMBRE COMPLETO

                <input
                    type="text"
                    name="nombre_completo"
                    placeholder="Nombre completo"
                    required
                >

            </label>


            <label>

                TELÉFONO

                <input
                    type="text"
                    name="telefono"
                    placeholder="Número de teléfono"
                    required
                >

            </label>


            <label>

                CORREO

                <input
                    type="email"
                    name="correo"
                    placeholder="correo@ejemplo.com"
                    required
                >

            </label>


            <button
                type="submit"
                class="btn red full"
            >
                REGISTRAR VENDEDOR
            </button>

        </form>

    </div>


    <!-- =========================
         LISTA DE VENDEDORES
    ========================== -->

    <div style="margin-top: 50px;">

        <h3>
            Vendedores registrados
        </h3>


        <p style="margin-top: 10px;">
            Los vendedores aparecen ordenados según
            la cantidad de ventas completadas.
        </p>


        <div
            style="
                overflow-x: auto;
                margin-top: 20px;
            "
        >

            <table
                style="
                    width: 100%;
                    border-collapse: collapse;
                    min-width: 800px;
                "
            >

                <thead>

                    <tr>

                        <th
                            style="
                                padding: 12px;
                                text-align: left;
                            "
                        >
                            POSICIÓN
                        </th>

                        <th
                            style="
                                padding: 12px;
                                text-align: left;
                            "
                        >
                            VENDEDOR
                        </th>

                        <th
                            style="
                                padding: 12px;
                                text-align: left;
                            "
                        >
                            TELÉFONO
                        </th>

                        <th
                            style="
                                padding: 12px;
                                text-align: left;
                            "
                        >
                            CORREO
                        </th>

                        <th
                            style="
                                padding: 12px;
                                text-align: left;
                            "
                        >
                            VENTAS
                        </th>

                        <th
                            style="
                                padding: 12px;
                                text-align: left;
                            "
                        >
                            ACCIÓN
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php if ($vendedores && $vendedores->num_rows > 0): ?>

                    <?php

                    $posicion = 1;

                    while ($vendedor = $vendedores->fetch_assoc()):

                    ?>


                        <tr>


                            <td style="padding: 12px;">

                                <?php
                                echo $posicion;
                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php

                                echo htmlspecialchars(
                                    $vendedor["nombre_completo"]
                                );

                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php

                                echo htmlspecialchars(
                                    $vendedor["telefono"]
                                );

                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php

                                echo htmlspecialchars(
                                    $vendedor["correo"]
                                );

                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <strong>

                                    <?php

                                    echo $vendedor["cantidad_ventas"];

                                    ?>

                                </strong>

                            </td>


                            <td style="padding: 12px;">

                                <a
                                    href="vendedores.php?eliminar=<?php echo $vendedor["id_vendedor"]; ?>"
                                    class="btn red"
                                    onclick="return confirm('¿Seguro que deseas eliminar este vendedor?');"
                                >
                                    ELIMINAR
                                </a>

                            </td>


                        </tr>


                    <?php

                    $posicion++;

                    endwhile;

                    ?>


                <?php else: ?>


                    <tr>

                        <td
                            colspan="6"
                            style="
                                padding: 30px;
                                text-align: center;
                            "
                        >

                            No hay vendedores registrados.

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
