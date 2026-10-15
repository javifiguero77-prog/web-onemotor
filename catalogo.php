<?php
require_once "conexion.php";

/* =========================
   FILTRO POR MARCA
========================= */

$marca_filtro = "";

if (isset($_GET["marca"])) {
    $marca_filtro = trim($_GET["marca"]);
}


/* =========================
   CONSULTA DE MOTOCICLETAS
========================= */

if ($marca_filtro !== "") {

    $sql = "SELECT
                m.id_motocicleta,
                m.modelo,
                m.anio,
                m.precio,
                m.color,
                m.cilindraje,
                m.estado,
                m.descripcion,
                m.especificaciones,
                m.imagen1,
                ma.nombre AS marca
            FROM motocicletas m
            INNER JOIN marcas ma
                ON m.id_marca = ma.id_marca
            WHERE ma.nombre = ?
            ORDER BY m.id_motocicleta DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $marca_filtro);
    $stmt->execute();

    $resultado = $stmt->get_result();

} else {

    $sql = "SELECT
                m.id_motocicleta,
                m.modelo,
                m.anio,
                m.precio,
                m.color,
                m.cilindraje,
                m.estado,
                m.descripcion,
                m.especificaciones,
                m.imagen1,
                ma.nombre AS marca
            FROM motocicletas m
            INNER JOIN marcas ma
                ON m.id_marca = ma.id_marca
            ORDER BY m.id_motocicleta DESC";

    $resultado = $conn->query($sql);
}


/* =========================
   OBTENER MARCAS
========================= */

$sql_marcas = "SELECT id_marca, nombre
               FROM marcas
               ORDER BY nombre ASC";

$resultado_marcas = $conn->query($sql_marcas);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/png" href="/one_motorcycles/img/Logo.png">

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>ONE MOTORCYCLES | Catálogo</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>


<!-- =========================
     HEADER
========================= -->

<header class="topbar">

    <a href="index.php" class="brand">

        <img src="img/onemoto.png"
             alt="ONE MOTORCYCLES"
             class="site-logo">

    </a>


    <nav class="main-nav">

        <a href="index.php">
            INICIO
        </a>

        <a href="catalogo.php"
           class="active">
            CATÁLOGO
        </a>

        <a href="marcas.php">
            MARCAS
        </a>

        <a href="nosotros.php">
            NOSOTROS
        </a>

        <a href="login.php">
            ACCESO
        </a>

    </nav>


    <a href="login.php"
       class="top-cta">

        ACCESO

    </a>

</header>



<!-- =========================
     ENCABEZADO CATÁLOGO
========================= -->

<main>

<section class="catalog-section">

    <div class="section-top">

        <div>

            <p class="kicker">
                ONE MOTORCYCLES
            </p>

            <h1>
                NUESTRO<br>
                <span>CATÁLOGO.</span>
            </h1>

            <p class="section-description">
                Explora nuestras motocicletas disponibles
                y encuentra la que más se adapte a ti.
            </p>

        </div>

    </div>


    <!-- =========================
         FILTROS
    ========================= -->

    <div class="catalog-filters">

        <a href="catalogo.php"
           class="<?php echo ($marca_filtro === "") ? "filter-active" : ""; ?>">

            TODAS

        </a>


        <?php if ($resultado_marcas && $resultado_marcas->num_rows > 0): ?>

            <?php while ($marca = $resultado_marcas->fetch_assoc()): ?>

                <a href="catalogo.php?marca=<?php echo urlencode($marca["nombre"]); ?>"
                   class="<?php echo ($marca_filtro === $marca["nombre"]) ? "filter-active" : ""; ?>">

                    <?php echo htmlspecialchars(strtoupper($marca["nombre"])); ?>

                </a>

            <?php endwhile; ?>

        <?php endif; ?>

    </div>



    <!-- =========================
         RESULTADO DEL FILTRO
    ========================= -->

    <?php if ($marca_filtro !== ""): ?>

        <div class="filter-title">

            <p>
                MOSTRANDO MOTOCICLETAS DE:
            </p>

            <h2>
                <?php echo htmlspecialchars(strtoupper($marca_filtro)); ?>
            </h2>

        </div>

    <?php endif; ?>



    <!-- =========================
         MOTOCICLETAS
    ========================= -->

    <div class="moto-grid">


        <?php if ($resultado && $resultado->num_rows > 0): ?>


            <?php while ($moto = $resultado->fetch_assoc()): ?>


                <article class="moto-card">


                    <!-- IMAGEN -->

                    <div class="moto-visual">


                        <?php

                        $imagen = $moto["imagen1"];

                        /*
                         * Si la base de datos solamente guarda
                         * el nombre de la imagen, agregamos
                         * la carpeta correspondiente.
                         */

                        if (!empty($imagen)) {

                            if (
                                strpos($imagen, "img/") !== 0 &&
                                strpos($imagen, "/") === false
                            ) {

                                $imagen = "img/motos/" . $imagen;

                            }

                        }

                        ?>


                        <?php if (!empty($imagen)): ?>

                            <img
                                src="<?php echo htmlspecialchars($imagen); ?>"
                                alt="<?php echo htmlspecialchars(
                                    $moto["marca"] . " " . $moto["modelo"]
                                ); ?>"
                                class="moto-image"
                            >

                        <?php else: ?>

                            <div class="no-image">

                                SIN IMAGEN

                            </div>

                        <?php endif; ?>


                        <!-- ESTADO -->

                        <?php if (
                            strtolower($moto["estado"]) === "disponible"
                        ): ?>

                            <span class="availability available">

                                DISPONIBLE

                            </span>

                        <?php else: ?>

                            <span class="availability sold">

                                <?php
                                echo htmlspecialchars(
                                    strtoupper($moto["estado"])
                                );
                                ?>

                            </span>

                        <?php endif; ?>


                    </div>



                    <!-- INFORMACIÓN -->

                    <div class="moto-info">


                        <p class="moto-brand">

                            <?php
                            echo htmlspecialchars(
                                strtoupper($moto["marca"])
                            );
                            ?>

                        </p>


                        <h3>

                            <?php
                            echo htmlspecialchars(
                                $moto["modelo"]
                            );
                            ?>

                        </h3>


                        <p class="moto-year">

                            Año
                            <?php
                            echo htmlspecialchars(
                                $moto["anio"]
                            );
                            ?>

                        </p>


                        <div class="moto-details">

                            <span>

                                <?php
                                echo htmlspecialchars(
                                    $moto["cilindraje"]
                                );
                                ?>
                                cc

                            </span>


                            <span>

                                <?php
                                echo htmlspecialchars(
                                    $moto["color"]
                                );
                                ?>

                            </span>

                        </div>



                        <!-- PRECIO -->

                        <div class="moto-bottom">


                            <strong>

                                $

                                <?php
                                echo number_format(
                                    $moto["precio"],
                                    2
                                );
                                ?>

                            </strong>


                            <button
                                type="button"
                                class="detail-btn"
                                onclick="mostrarDetalles(
                                    <?php echo htmlspecialchars(
                                        json_encode($moto),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                )">

                                VER DETALLES →

                            </button>


                        </div>


                    </div>

                </article>


            <?php endwhile; ?>


        <?php else: ?>


            <!-- SIN RESULTADOS -->

            <div class="empty-message">

                <h3>
                    No encontramos motocicletas.
                </h3>

                <p>
                    Actualmente no hay motocicletas
                    disponibles para esta selección.
                </p>


                <a href="catalogo.php"
                   class="btn red">

                    VER TODAS

                </a>

            </div>


        <?php endif; ?>


    </div>

</section>

</main>



<!-- =========================
     MODAL DE DETALLES
========================= -->
<!-- MODAL DE DETALLES DE LA MOTOCICLETA -->
<div id="motoModal" class="modal">

    <div class="modal-content">

        <button
            type="button"
            class="modal-close"
            onclick="cerrarModal()"
            aria-label="Cerrar detalles">
            &times;
        </button>

        <!-- IMAGEN -->
        <div class="modal-image-container">
            <img
                id="modalImagen"
                src=""
                alt="Motocicleta"
                class="modal-image">
        </div>

        <!-- INFORMACIÓN -->
        <div class="modal-info">

            <p class="modal-kicker" id="modalMarca"></p>

            <h2 id="modalModelo"></h2>

            <p class="modal-price" id="modalPrecio"></p>

            <!-- DATOS PRINCIPALES -->
            <div class="modal-specs">

                <div>
                    <span>AÑO</span>
                    <b id="modalAnio"></b>
                </div>

                <div>
                    <span>COLOR</span>
                    <b id="modalColor"></b>
                </div>

                <div>
                    <span>CILINDRAJE</span>
                    <b id="modalCilindraje"></b>
                </div>

                <div>
                    <span>ESTADO</span>
                    <b id="modalEstado"></b>
                </div>

            </div>

            <!-- DESCRIPCIÓN -->
            <div class="modal-description">

                <h3>DESCRIPCIÓN</h3>

                <p id="modalDescripcion"></p>

            </div>

            <!-- ESPECIFICACIONES -->
            <div class="modal-description">

                <h3>ESPECIFICACIONES</h3>

                <p id="modalEspecificaciones"></p>

            </div>

        </div>

    </div>

</div>


<script>
function mostrarDetalles(moto) {

    document.getElementById("modalMarca").textContent =
        moto.marca || "MOTOCICLETA";

    document.getElementById("modalModelo").textContent =
        moto.modelo || "Modelo no disponible";

    document.getElementById("modalPrecio").textContent =
        "$" + Number(moto.precio || 0).toLocaleString("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    document.getElementById("modalAnio").textContent =
        moto.anio || "No especificado";

    document.getElementById("modalColor").textContent =
        moto.color || "No especificado";

    document.getElementById("modalCilindraje").textContent =
        (moto.cilindraje || "No especificado") +
        (moto.cilindraje ? " cc" : "");

    document.getElementById("modalEstado").textContent =
        moto.estado || "No especificado";

    document.getElementById("modalDescripcion").textContent =
        moto.descripcion || "No hay descripción disponible.";

    document.getElementById("modalEspecificaciones").textContent =
        moto.especificaciones || "No hay especificaciones disponibles.";

    let imagen = moto.imagen1 || "";

    if (imagen && !imagen.includes("/")) {
        imagen = "img/motos/" + imagen;
    }

    const imagenModal = document.getElementById("modalImagen");

    if (imagen) {
        imagenModal.src = imagen;
        imagenModal.style.display = "block";
    } else {
        imagenModal.removeAttribute("src");
        imagenModal.style.display = "none";
    }

    document.getElementById("motoModal").classList.add("show");

    document.body.classList.add("modal-open");
}


function cerrarModal() {

    document.getElementById("motoModal").classList.remove("show");

    document.body.classList.remove("modal-open");
}


// Cerrar al pulsar fuera de la ventana
document.getElementById("motoModal").addEventListener("click", function(event) {

    if (event.target === this) {
        cerrarModal();
    }

});


// Cerrar con Escape
document.addEventListener("keydown", function(event) {

    if (event.key === "Escape") {
        cerrarModal();
    }

});
</script>

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



<!-- =========================
     JAVASCRIPT
========================= -->

<script>
function cerrarModal() {

    document.getElementById("motoModal")
        .classList.remove("show");

}


/* Cerrar haciendo clic fuera del modal */

window.addEventListener("click", function(event) {

    const modal =
        document.getElementById("motoModal");

    if (event.target === modal) {

        cerrarModal();

    }

});


/* Cerrar con ESC */

document.addEventListener("keydown", function(event) {

    if (event.key === "Escape") {

        cerrarModal();

    }

});

</script>


</body>

</html>