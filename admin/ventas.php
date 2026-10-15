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
   REGISTRAR VENTA
========================= */

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["accion"])
    && $_POST["accion"] === "registrar"
) {

    $id_motocicleta = intval($_POST["id_motocicleta"] ?? 0);
    $id_cliente = intval($_POST["id_cliente"] ?? 0);
    $id_vendedor = intval($_POST["id_vendedor"] ?? 0);
    $fecha_venta = $_POST["fecha_venta"] ?? "";
    $precio_venta = floatval($_POST["precio_venta"] ?? 0);
    $estado = trim($_POST["estado"] ?? "");

    if (
        $id_motocicleta <= 0 ||
        $id_cliente <= 0 ||
        $id_vendedor <= 0 ||
        $fecha_venta === "" ||
        $precio_venta <= 0 ||
        $estado === ""
    ) {

        $error = "Completa todos los campos.";

    } else {

        $sql = "INSERT INTO ventas
                (
                    id_motocicleta,
                    id_cliente,
                    id_vendedor,
                    fecha_venta,
                    precio_venta,
                    estado
                )
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "iiisds",
            $id_motocicleta,
            $id_cliente,
            $id_vendedor,
            $fecha_venta,
            $precio_venta,
            $estado
        );

        if ($stmt->execute()) {

            if ($estado === "Completada") {

                $sql_moto = "UPDATE motocicletas
                             SET estado = 'Vendida'
                             WHERE id_motocicleta = ?";

                $stmt_moto = $conn->prepare($sql_moto);
                $stmt_moto->bind_param("i", $id_motocicleta);
                $stmt_moto->execute();
                $stmt_moto->close();
            }

            $mensaje = "Venta registrada correctamente.";

        } else {

            $error = "No se pudo registrar la venta.";

        }

        $stmt->close();
    }
}


/* =========================
   OBTENER MOTOCICLETAS
========================= */

$sql_motos = "SELECT
                id_motocicleta,
                modelo,
                precio
              FROM motocicletas
              WHERE estado = 'Disponible'
              ORDER BY modelo ASC";

$motocicletas = $conn->query($sql_motos);


/* =========================
   OBTENER CLIENTES
========================= */

$sql_clientes = "SELECT
                    id_cliente,
                    nombre_completo
                 FROM clientes
                 ORDER BY nombre_completo ASC";

$clientes = $conn->query($sql_clientes);


/* =========================
   OBTENER VENDEDORES
========================= */

$sql_vendedores = "SELECT
                      id_vendedor,
                      nombre_completo
                   FROM vendedores
                   ORDER BY nombre_completo ASC";

$vendedores = $conn->query($sql_vendedores);


/* =========================
   OBTENER VENTAS
========================= */

$sql_ventas = "SELECT
                  ve.id_venta,
                  ve.fecha_venta,
                  ve.precio_venta,
                  ve.estado,

                  m.modelo,
                  m.anio,

                  c.nombre_completo AS cliente,

                  v.nombre_completo AS vendedor,

                  ma.nombre AS marca

               FROM ventas ve

               INNER JOIN motocicletas m
                   ON ve.id_motocicleta = m.id_motocicleta

               INNER JOIN clientes c
                   ON ve.id_cliente = c.id_cliente

               INNER JOIN vendedores v
                   ON ve.id_vendedor = v.id_vendedor

               INNER JOIN marcas ma
                   ON m.id_marca = ma.id_marca

               ORDER BY ve.id_venta DESC";

$ventas = $conn->query($sql_ventas);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/png" href="/one_motorcycles/img/Logo.png">

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        ONE MOTORCYCLES | Ventas
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
                Ventas
            </h2>

        </div>

        <p>
            Registra y consulta las ventas
            realizadas en ONE MOTORCYCLES.
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
         REGISTRAR VENTA
    ========================== -->

    <div
        class="access-card"
        style="
            max-width: 800px;
            margin: 30px auto;
        "
    >

        <h3>
            Registrar venta
        </h3>


        <form
            method="POST"
            action="ventas.php"
            class="form"
        >

            <input
                type="hidden"
                name="accion"
                value="registrar"
            >


            <label>

                MOTOCICLETA

                <select
                    name="id_motocicleta"
                    required
                >

                    <option value="">
                        Selecciona una motocicleta
                    </option>

                    <?php if ($motocicletas): ?>

                        <?php while ($moto = $motocicletas->fetch_assoc()): ?>

                            <option
                                value="<?php echo $moto["id_motocicleta"]; ?>"
                            >

                                <?php
                                echo htmlspecialchars(
                                    $moto["modelo"]
                                );
                                ?>

                                -

                                $
                                <?php
                                echo number_format(
                                    $moto["precio"],
                                    2
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    <?php endif; ?>

                </select>

            </label>


            <label>

                CLIENTE

                <select
                    name="id_cliente"
                    required
                >

                    <option value="">
                        Selecciona un cliente
                    </option>

                    <?php if ($clientes): ?>

                        <?php while ($cliente = $clientes->fetch_assoc()): ?>

                            <option
                                value="<?php echo $cliente["id_cliente"]; ?>"
                            >

                                <?php
                                echo htmlspecialchars(
                                    $cliente["nombre_completo"]
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    <?php endif; ?>

                </select>

            </label>


            <label>

                VENDEDOR

                <select
                    name="id_vendedor"
                    required
                >

                    <option value="">
                        Selecciona un vendedor
                    </option>

                    <?php if ($vendedores): ?>

                        <?php while ($vendedor = $vendedores->fetch_assoc()): ?>

                            <option
                                value="<?php echo $vendedor["id_vendedor"]; ?>"
                            >

                                <?php
                                echo htmlspecialchars(
                                    $vendedor["nombre_completo"]
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    <?php endif; ?>

                </select>

            </label>


            <label>

                FECHA DE VENTA

                <input
                    type="date"
                    name="fecha_venta"
                    value="<?php echo date("Y-m-d"); ?>"
                    required
                >

            </label>


            <label>

                PRECIO DE VENTA

                <input
                    type="number"
                    name="precio_venta"
                    step="0.01"
                    min="0"
                    placeholder="2200.00"
                    required
                >

            </label>


            <label>

                ESTADO

                <select
                    name="estado"
                    required
                >

                    <option value="Completada">
                        Completada
                    </option>

                    <option value="Pendiente">
                        Pendiente
                    </option>

                    <option value="Cancelada">
                        Cancelada
                    </option>

                </select>

            </label>


            <button
                type="submit"
                class="btn red full"
            >
                REGISTRAR VENTA
            </button>

        </form>

    </div>


    <!-- =========================
         LISTA DE VENTAS
    ========================== -->

    <div style="margin-top: 50px;">

        <h3>
            Ventas registradas
        </h3>


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
                    min-width: 1000px;
                "
            >

                <thead>

                    <tr>

                        <th style="padding: 12px; text-align: left;">
                            ID
                        </th>

                        <th style="padding: 12px; text-align: left;">
                            FECHA
                        </th>

                        <th style="padding: 12px; text-align: left;">
                            MOTOCICLETA
                        </th>

                        <th style="padding: 12px; text-align: left;">
                            CLIENTE
                        </th>

                        <th style="padding: 12px; text-align: left;">
                            VENDEDOR
                        </th>

                        <th style="padding: 12px; text-align: left;">
                            PRECIO
                        </th>

                        <th style="padding: 12px; text-align: left;">
                            ESTADO
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php if ($ventas && $ventas->num_rows > 0): ?>


                    <?php while ($venta = $ventas->fetch_assoc()): ?>


                        <tr>


                            <td style="padding: 12px;">

                                <?php
                                echo $venta["id_venta"];
                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php
                                echo htmlspecialchars(
                                    $venta["fecha_venta"]
                                );
                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php

                                echo htmlspecialchars(
                                    $venta["marca"]
                                );

                                echo " ";

                                echo htmlspecialchars(
                                    $venta["modelo"]
                                );

                                echo " ";

                                echo "(";

                                echo htmlspecialchars(
                                    $venta["anio"]
                                );

                                echo ")";

                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php
                                echo htmlspecialchars(
                                    $venta["cliente"]
                                );
                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php
                                echo htmlspecialchars(
                                    $venta["vendedor"]
                                );
                                ?>

                            </td>


                            <td style="padding: 12px;">

                                $

                                <?php
                                echo number_format(
                                    $venta["precio_venta"],
                                    2
                                );
                                ?>

                            </td>


                            <td style="padding: 12px;">

                                <?php
                                echo htmlspecialchars(
                                    $venta["estado"]
                                );
                                ?>

                            </td>


                        </tr>


                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td
                            colspan="7"
                            style="
                                padding: 30px;
                                text-align: center;
                            "
                        >

                            No hay ventas registradas.

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