<?php
session_start();
require_once "../conexion.php";

if (!isset($_SESSION["id_admin"])) {
    header("Location: login.php");
    exit;
}

$mensaje = "";
$tipoMensaje = "";

/* =========================
   ELIMINAR MOTOCICLETA
========================= */
if (isset($_GET["eliminar"])) {

    $id = intval($_GET["eliminar"]);

    $consulta = $conn->prepare("SELECT imagen1 FROM motocicletas WHERE id_motocicleta = ?");
    $consulta->bind_param("i", $id);
    $consulta->execute();
    $resultado = $consulta->get_result();
    $moto = $resultado->fetch_assoc();

    if ($moto) {

        $eliminar = $conn->prepare(
            "DELETE FROM motocicletas WHERE id_motocicleta = ?"
        );

        $eliminar->bind_param("i", $id);

        if ($eliminar->execute()) {

            if (!empty($moto["imagen1"])) {

                $archivo = "../img/motos/" . $moto["imagen1"];

                if (file_exists($archivo)) {
                    unlink($archivo);
                }
            }

            $mensaje = "Motocicleta eliminada correctamente.";
            $tipoMensaje = "exito";

        } else {

            $mensaje = "No se pudo eliminar la motocicleta.";
            $tipoMensaje = "error";
        }
    }
}

/* =========================
   GUARDAR / EDITAR
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = isset($_POST["id_motocicleta"])
        ? intval($_POST["id_motocicleta"])
        : 0;

    $id_marca = intval($_POST["id_marca"]);
    $modelo = trim($_POST["modelo"]);
    $anio = intval($_POST["anio"]);
    $cilindraje = intval($_POST["cilindraje"]);
    $precio = floatval($_POST["precio"]);
    $color = trim($_POST["color"]);
    $estado = trim($_POST["estado"]);
    $descripcion = trim($_POST["descripcion"]);
    $especificaciones = trim($_POST["especificaciones"]);

    $imagenNueva = "";

    /* =========================
       SUBIR IMAGEN
    ========================= */

    if (
        isset($_FILES["imagen1"]) &&
        $_FILES["imagen1"]["error"] === UPLOAD_ERR_OK
    ) {

        $nombreOriginal = $_FILES["imagen1"]["name"];
        $temporal = $_FILES["imagen1"]["tmp_name"];
        $tamano = $_FILES["imagen1"]["size"];

        $extension = strtolower(
            pathinfo($nombreOriginal, PATHINFO_EXTENSION)
        );

        $extensionesPermitidas = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];

        if (!in_array($extension, $extensionesPermitidas)) {

            $mensaje = "La imagen debe ser JPG, JPEG, PNG o WEBP.";
            $tipoMensaje = "error";

        } elseif ($tamano > 5 * 1024 * 1024) {

            $mensaje = "La imagen no puede superar los 5 MB.";
            $tipoMensaje = "error";

        } elseif (getimagesize($temporal) === false) {

            $mensaje = "El archivo seleccionado no es una imagen válida.";
            $tipoMensaje = "error";

        } else {

            $imagenNueva =
                "moto_" .
                uniqid() .
                "." .
                $extension;

            $destino = "../img/motos/" . $imagenNueva;

            if (!move_uploaded_file($temporal, $destino)) {

                $mensaje = "No se pudo guardar la imagen.";
                $tipoMensaje = "error";
                $imagenNueva = "";
            }
        }
    }

    /* =========================
       SI TODO ESTÁ CORRECTO
    ========================= */

    if ($mensaje === "") {

        /* =========================
           EDITAR
        ========================= */

        if ($id > 0) {

            if ($imagenNueva !== "") {

                /* Obtener imagen anterior */
                $consulta = $conn->prepare(
                    "SELECT imagen1 FROM motocicletas
                     WHERE id_motocicleta = ?"
                );

                $consulta->bind_param("i", $id);
                $consulta->execute();

                $resultado = $consulta->get_result();
                $anterior = $resultado->fetch_assoc();

                /* Actualizar con nueva imagen */
                $sql = "UPDATE motocicletas SET
                        id_marca = ?,
                        modelo = ?,
                        anio = ?,
                        cilindraje = ?,
                        precio = ?,
                        color = ?,
                        estado = ?,
                        descripcion = ?,
                        especificaciones = ?,
                        imagen1 = ?
                        WHERE id_motocicleta = ?";

                $stmt = $conn->prepare($sql);

                $stmt->bind_param(
                    "isiidsssssi",
                    $id_marca,
                    $modelo,
                    $anio,
                    $cilindraje,
                    $precio,
                    $color,
                    $estado,
                    $descripcion,
                    $especificaciones,
                    $imagenNueva,
                    $id
                );

                if ($stmt->execute()) {

                    /* Eliminar imagen anterior */
                    if (
                        $anterior &&
                        !empty($anterior["imagen1"])
                    ) {

                        $archivoAnterior =
                            "../img/motos/" .
                            $anterior["imagen1"];

                        if (file_exists($archivoAnterior)) {
                            unlink($archivoAnterior);
                        }
                    }

                    $mensaje =
                        "Motocicleta actualizada correctamente.";

                    $tipoMensaje = "exito";

                } else {

                    $mensaje =
                        "Error al actualizar la motocicleta.";

                    $tipoMensaje = "error";
                }

            } else {

                /* Editar sin cambiar imagen */

                $sql = "UPDATE motocicletas SET
                        id_marca = ?,
                        modelo = ?,
                        anio = ?,
                        cilindraje = ?,
                        precio = ?,
                        color = ?,
                        estado = ?,
                        descripcion = ?,
                        especificaciones = ?
                        WHERE id_motocicleta = ?";

                $stmt = $conn->prepare($sql);

                $stmt->bind_param(
                    "isiidssssi",
                    $id_marca,
                    $modelo,
                    $anio,
                    $cilindraje,
                    $precio,
                    $color,
                    $estado,
                    $descripcion,
                    $especificaciones,
                    $id
                );

                if ($stmt->execute()) {

                    $mensaje =
                        "Motocicleta actualizada correctamente.";

                    $tipoMensaje = "exito";

                } else {

                    $mensaje =
                        "Error al actualizar la motocicleta.";

                    $tipoMensaje = "error";
                }
            }

        } else {

            /* =========================
               AGREGAR
            ========================= */

            $sql = "INSERT INTO motocicletas
                    (
                        id_marca,
                        modelo,
                        anio,
                        cilindraje,
                        precio,
                        color,
                        estado,
                        descripcion,
                        especificaciones,
                        imagen1
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "isiidsssss",
                $id_marca,
                $modelo,
                $anio,
                $cilindraje,
                $precio,
                $color,
                $estado,
                $descripcion,
                $especificaciones,
                $imagenNueva
            );

            if ($stmt->execute()) {

                $mensaje =
                    "Motocicleta registrada correctamente.";

                $tipoMensaje = "exito";

            } else {

                $mensaje =
                    "Error al registrar la motocicleta.";

                $tipoMensaje = "error";
            }
        }
    }
}

/* =========================
   DATOS PARA EDITAR
========================= */

$editar = null;

if (isset($_GET["editar"])) {

    $idEditar = intval($_GET["editar"]);

    $stmt = $conn->prepare(
        "SELECT * FROM motocicletas
         WHERE id_motocicleta = ?"
    );

    $stmt->bind_param("i", $idEditar);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $editar = $resultado->fetch_assoc();
}

/* =========================
   MARCAS
========================= */

$marcas = $conn->query(
    "SELECT * FROM marcas ORDER BY nombre ASC"
);

/* =========================
   MOTOCICLETAS
========================= */

$sqlMotos = "
    SELECT
        m.*,
        ma.nombre AS marca
    FROM motocicletas m
    INNER JOIN marcas ma
        ON m.id_marca = ma.id_marca
    ORDER BY m.id_motocicleta DESC
";

$motocicletas = $conn->query($sqlMotos);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/png" href="/one_motorcycles/img/Logo.png">

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Motocicletas | ONE MOTORCYCLES
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>

<body class="admin-body">

<header class="admin-header">

    <div class="admin-logo">

        <img
            src="../img/onemoto.png"
            alt="ONE MOTORCYCLES"
        >

    </div>

    <div class="admin-title">

        <span>ADMINISTRACIÓN</span>

        <h1>
            MOTOCICLETAS
        </h1>

    </div>

    <nav class="admin-nav">

        <a href="panel.php">
            PANEL
        </a>

        <a href="clientes.php">
            CLIENTES
        </a>

        <a href="vendedores.php">
            VENDEDORES
        </a>

        <a href="ventas.php">
            VENTAS
        </a>

        <a href="logout.php" class="logout-link">
            CERRAR SESIÓN
        </a>

    </nav>

</header>


<main class="admin-container">

    <div class="admin-page-top">

        <div>

            <p class="kicker">
                GESTIÓN
            </p>

            <h2>
                <?php
                echo $editar
                    ? "Editar motocicleta"
                    : "Registrar motocicleta";
                ?>
            </h2>

        </div>

        <?php if ($editar): ?>

            <a
                href="motocicletas.php"
                class="btn outline"
            >
                NUEVA MOTOCICLETA
            </a>

        <?php endif; ?>

    </div>


    <?php if ($mensaje !== ""): ?>

        <div
            class="admin-message
            <?php echo $tipoMensaje; ?>"
        >
            <?php echo htmlspecialchars($mensaje); ?>
        </div>

    <?php endif; ?>


    <!-- =========================
         FORMULARIO
    ========================= -->

    <section class="admin-form-card">

        <form
            method="POST"
            enctype="multipart/form-data"
            class="admin-form"
        >

            <?php if ($editar): ?>

                <input
                    type="hidden"
                    name="id_motocicleta"
                    value="<?php echo $editar["id_motocicleta"]; ?>"
                >

            <?php endif; ?>


            <div class="form-grid">


                <!-- MARCA -->

                <div class="form-group">

                    <label>
                        MARCA
                    </label>

                    <select
                        name="id_marca"
                        required
                    >

                        <option value="">
                            Seleccionar marca
                        </option>

                        <?php while ($marca = $marcas->fetch_assoc()): ?>

                            <option
                                value="<?php echo $marca["id_marca"]; ?>"

                                <?php
                                if (
                                    $editar &&
                                    $editar["id_marca"]
                                    == $marca["id_marca"]
                                ) {
                                    echo "selected";
                                }
                                ?>
                            >

                                <?php
                                echo htmlspecialchars(
                                    $marca["nombre"]
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <!-- MODELO -->

                <div class="form-group">

                    <label>
                        MODELO
                    </label>

                    <input
                        type="text"
                        name="modelo"
                        placeholder="Ej. FZ 2.0"
                        value="<?php
                        echo $editar
                            ? htmlspecialchars($editar["modelo"])
                            : "";
                        ?>"
                        required
                    >

                </div>


                <!-- AÑO -->

                <div class="form-group">

                    <label>
                        AÑO
                    </label>

                    <input
                        type="number"
                        name="anio"
                        min="1900"
                        max="2100"
                        placeholder="2026"
                        value="<?php
                        echo $editar
                            ? $editar["anio"]
                            : "";
                        ?>"
                        required
                    >

                </div>


                <!-- CILINDRAJE -->

                <div class="form-group">

                    <label>
                        CILINDRAJE (CC)
                    </label>

                    <input
                        type="number"
                        name="cilindraje"
                        min="1"
                        placeholder="150"
                        value="<?php
                        echo $editar
                            ? $editar["cilindraje"]
                            : "";
                        ?>"
                        required
                    >

                </div>


                <!-- PRECIO -->

                <div class="form-group">

                    <label>
                        PRECIO
                    </label>

                    <input
                        type="number"
                        name="precio"
                        min="0"
                        step="0.01"
                        placeholder="3500.00"
                        value="<?php
                        echo $editar
                            ? $editar["precio"]
                            : "";
                        ?>"
                        required
                    >

                </div>


                <!-- COLOR -->

                <div class="form-group">

                    <label>
                        COLOR
                    </label>

                    <input
                        type="text"
                        name="color"
                        placeholder="Negro"
                        value="<?php
                        echo $editar
                            ? htmlspecialchars($editar["color"])
                            : "";
                        ?>"
                        required
                    >

                </div>


                <!-- ESTADO -->

                <div class="form-group">

                    <label>
                        ESTADO
                    </label>

                    <select
                        name="estado"
                        required
                    >

                        <option
                            value="Disponible"
                            <?php
                            if (
                                !$editar ||
                                $editar["estado"] == "Disponible"
                            ) {
                                echo "selected";
                            }
                            ?>
                        >
                            Disponible
                        </option>

                        <option
                            value="Vendida"
                            <?php
                            if (
                                $editar &&
                                $editar["estado"] == "Vendida"
                            ) {
                                echo "selected";
                            }
                            ?>
                        >
                            Vendida
                        </option>

                    </select>

                </div>


                <!-- IMAGEN -->

                <div class="form-group form-full">

                    <label>
                        IMAGEN DE LA MOTOCICLETA
                    </label>

                    <input
                        type="text"
                        id="nombre-imagen"
                        class="image-box"
                        readonly
                        value="<?php
                        if (
                            $editar &&
                            !empty($editar["imagen1"])
                        ) {
                            echo htmlspecialchars(
                                $editar["imagen1"]
                            );
                        } else {
                            echo "Doble clic para seleccionar una imagen";
                        }
                        ?>"
                    >

                    <input
                        type="file"
                        name="imagen1"
                        id="imagen1"
                        accept=".jpg,.jpeg,.png,.webp"
                        hidden
                    >

                    <small>
                        Doble clic en la caja para seleccionar
                        una imagen.
                        <?php if ($editar): ?>
                            Si no seleccionas otra imagen,
                            se conservará la actual.
                        <?php endif; ?>
                    </small>

                </div>


                <!-- DESCRIPCIÓN -->

                <div class="form-group form-full">

                    <label>
                        DESCRIPCIÓN
                    </label>

                    <textarea
                        name="descripcion"
                        rows="5"
                        placeholder="Descripción de la motocicleta..."
                    ><?php
                    echo $editar
                        ? htmlspecialchars($editar["descripcion"])
                        : "";
                    ?></textarea>

                </div>


                <!-- ESPECIFICACIONES -->

                <div class="form-group form-full">

                    <label>
                        ESPECIFICACIONES
                    </label>

                    <textarea
                        name="especificaciones"
                        rows="5"
                        placeholder="Motor, transmisión, frenos, suspensión..."
                    ><?php
                    echo $editar
                        ? htmlspecialchars(
                            $editar["especificaciones"]
                        )
                        : "";
                    ?></textarea>

                </div>

            </div>


            <?php if (
                $editar &&
                !empty($editar["imagen1"])
            ): ?>

                <div class="current-image">

                    <p>
                        IMAGEN ACTUAL
                    </p>

                    <img
                        src="../img/motos/<?php
                        echo htmlspecialchars(
                            $editar["imagen1"]
                        );
                        ?>"
                        alt="Imagen actual"
                    >

                </div>

            <?php endif; ?>


            <div class="form-actions">

                <button
                    type="submit"
                    class="btn red"
                >

                    <?php
                    echo $editar
                        ? "ACTUALIZAR MOTOCICLETA"
                        : "GUARDAR MOTOCICLETA";
                    ?>

                </button>

                <?php if ($editar): ?>

                    <a
                        href="motocicletas.php"
                        class="btn outline"
                    >
                        CANCELAR
                    </a>

                <?php endif; ?>

            </div>

        </form>

    </section>


    <!-- =========================
         LISTADO
    ========================= -->

    <section class="admin-list-section">

        <div class="section-top">

            <div>

                <p class="kicker">
                    INVENTARIO
                </p>

                <h2>
                    Motocicletas registradas.
                </h2>

            </div>

        </div>


        <div class="table-container">

            <table class="admin-table">

                <thead>

                    <tr>

                        <th>
                            IMAGEN
                        </th>

                        <th>
                            MARCA
                        </th>

                        <th>
                            MODELO
                        </th>

                        <th>
                            AÑO
                        </th>

                        <th>
                            CC
                        </th>

                        <th>
                            PRECIO
                        </th>

                        <th>
                            COLOR
                        </th>

                        <th>
                            ESTADO
                        </th>

                        <th>
                            ACCIONES
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if ($motocicletas->num_rows > 0): ?>

                        <?php while ($moto = $motocicletas->fetch_assoc()): ?>

                            <tr>

                                <td>

                                    <?php if (!empty($moto["imagen1"])): ?>

                                        <img
                                            src="../img/motos/<?php
                                            echo htmlspecialchars(
                                                $moto["imagen1"]
                                            );
                                            ?>"
                                            class="table-moto-img"
                                            alt="Motocicleta"
                                        >

                                    <?php else: ?>

                                        <span class="no-image">
                                            SIN IMAGEN
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $moto["marca"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $moto["modelo"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $moto["anio"];
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $moto["cilindraje"];
                                    ?> CC
                                </td>

                                <td>
                                    $
                                    <?php
                                    echo number_format(
                                        $moto["precio"],
                                        2
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $moto["color"]
                                    );
                                    ?>
                                </td>

                                <td>

                                    <span
                                        class="status
                                        <?php
                                        echo strtolower(
                                            $moto["estado"]
                                        );
                                        ?>"
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $moto["estado"]
                                        );
                                        ?>

                                    </span>

                                </td>

                                <td>

                                    <div class="table-actions">

                                        <a
                                            href="motocicletas.php?editar=<?php
                                            echo $moto["id_motocicleta"];
                                            ?>"
                                            class="action-edit"
                                        >
                                            EDITAR
                                        </a>

                                        <a
                                            href="motocicletas.php?eliminar=<?php
                                            echo $moto["id_motocicleta"];
                                            ?>"
                                            class="action-delete"
                                            onclick="return confirmarEliminacion();"
                                        >
                                            ELIMINAR
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="9"
                                class="empty-table"
                            >
                                No hay motocicletas registradas.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>


<footer class="admin-footer">

    <p>
        ONE MOTORCYCLES · SISTEMA ADMINISTRATIVO
    </p>

</footer>


<!-- ==================================================
     JAVASCRIPT
     ESTÁ DENTRO DEL PHP COMO PEDISTE
================================================== -->

<script>

/* ==========================================
   SELECCIONAR IMAGEN CON DOBLE CLIC
========================================== */

const cajaImagen =
    document.getElementById("nombre-imagen");

const inputImagen =
    document.getElementById("imagen1");


if (cajaImagen && inputImagen) {

    cajaImagen.addEventListener(
        "dblclick",
        function () {

            inputImagen.click();

        }
    );


    inputImagen.addEventListener(
        "change",
        function () {

            if (this.files.length > 0) {

                cajaImagen.value =
                    this.files[0].name;

            }

        }
    );

}


/* ==========================================
   CONFIRMAR ELIMINACIÓN
========================================== */

function confirmarEliminacion() {

    return confirm(
        "¿Estás seguro de que deseas eliminar esta motocicleta?"
    );

}

</script>

</body>

</html>