<?php

require_once "conexion.php";

/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN
|--------------------------------------------------------------------------
*/

mysqli_report(MYSQLI_REPORT_OFF);

$marcas = [];
$errorMarcas = false;

/*
|--------------------------------------------------------------------------
| FUNCIÓN DE ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapar($valor)
{
    return htmlspecialchars(
        (string)($valor ?? ""),
        ENT_QUOTES,
        "UTF-8"
    );
}

/*
|--------------------------------------------------------------------------
| CONSULTAR MARCAS Y CONTAR MOTOCICLETAS
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        ma.id_marca,
        ma.nombre,
        COUNT(m.id_motocicleta) AS cantidad
    FROM marcas ma
    LEFT JOIN motocicletas m
        ON ma.id_marca = m.id_marca
    GROUP BY
        ma.id_marca,
        ma.nombre
    ORDER BY ma.nombre ASC
";

$resultado = $conn->query($sql);

if ($resultado === false) {

    $errorMarcas = true;

    error_log(
        "Error al consultar marcas: " . $conn->error
    );

} else {

    while ($fila = $resultado->fetch_assoc()) {
        $marcas[] = $fila;
    }

    $resultado->free();
}

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

    <meta
        name="description"
        content="Descubre las marcas de motocicletas disponibles en ONE MOTORCYCLES."
    >

    <title>ONE MOTORCYCLES | Marcas</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<!-- =====================================================
     HEADER
===================================================== -->

<header class="topbar">

    <a href="index.php" class="brand">

        <img
            src="img/onemoto.png"
            alt="ONE MOTORCYCLES"
            class="site-logo"
        >

    </a>

    <nav class="main-nav" aria-label="Navegación principal">

        <a href="index.php">
            INICIO
        </a>

        <a href="catalogo.php">
            CATÁLOGO
        </a>

        <a href="marcas.php" class="active" aria-current="page">
            MARCAS
        </a>

        <a href="nosotros.php">
            NOSOTROS
        </a>

        <a href="login.php">
            ACCESO
        </a>

    </nav>

    <a href="catalogo.php" class="top-cta">
        EXPLORAR MOTOS
        <span aria-hidden="true">↗</span>
    </a>

</header>


<main>

    <!-- =================================================
         HERO DE MARCAS
    ================================================== -->

    <section class="brands-hero">

        <div class="brands-hero-background"></div>

        <div class="brands-hero-content">

            <div class="brands-eyebrow">

                <span class="brands-eyebrow-line"></span>

                <p class="kicker">
                    ONE MOTORCYCLES
                </p>

            </div>

            <h1>
                MARCAS QUE<br>
                <span>DEFINEN TU CAMINO.</span>
            </h1>

            <p class="brands-hero-description">

                Descubre diferentes fabricantes,
                estilos y propuestas para encontrar
                la motocicleta que encaja contigo.

            </p>

            <div class="brands-hero-actions">

                <a href="#marcas-disponibles" class="btn red">

                    EXPLORAR MARCAS

                    <span aria-hidden="true">↘</span>

                </a>

                <a href="catalogo.php" class="btn outline">

                    VER CATÁLOGO

                    <span aria-hidden="true">↗</span>

                </a>

            </div>

            <div class="brands-hero-meta">

                <span>ESTILO</span>

                <span class="brands-meta-separator"></span>

                <span>RENDIMIENTO</span>

                <span class="brands-meta-separator"></span>

                <span>LIBERTAD</span>

            </div>

        </div>

        <div class="brands-hero-decoration" aria-hidden="true">
            ONE
        </div>

    </section>


    <!-- =================================================
         MARCAS DISPONIBLES
    ================================================== -->

    <section
        class="brands-section"
        id="marcas-disponibles"
    >

        <div class="brands-section-heading">

            <div>

                <p class="kicker">
                    EXPLORA NUESTRAS OPCIONES
                </p>

                <h2>
                    NUESTROS<br>
                    <span>FABRICANTES.</span>
                </h2>

            </div>

            <div class="brands-heading-side">

                <p>
                    Cada marca tiene su propia personalidad.
                    Explora sus modelos y encuentra el que
                    mejor se adapte a tus necesidades.
                </p>

                <div class="brands-total">

                    <span class="brands-total-number">

                        <?php
                        echo str_pad(
                            count($marcas),
                            2,
                            "0",
                            STR_PAD_LEFT
                        );
                        ?>

                    </span>

                    <span class="brands-total-label">
                        MARCAS REGISTRADAS
                    </span>

                </div>

            </div>

        </div>


        <?php if ($errorMarcas): ?>

            <div class="brands-message brands-message-error">

                <span class="brands-message-icon" aria-hidden="true">
                    !
                </span>

                <h3>
                    No pudimos cargar las marcas.
                </h3>

                <p>
                    Se produjo un problema al consultar la
                    información. Intenta nuevamente más tarde.
                </p>

                <a href="marcas.php" class="btn outline">
                    VOLVER A INTENTAR
                </a>

            </div>

        <?php elseif (count($marcas) > 0): ?>

            <div class="brands-grid-premium">

                <?php foreach ($marcas as $indice => $marca): ?>

                    <?php

                    $cantidad = (int)$marca["cantidad"];

                    $nombreMarca = (string)$marca["nombre"];

                    $numero = str_pad(
                        $indice + 1,
                        2,
                        "0",
                        STR_PAD_LEFT
                    );

                    ?>

                    <a
                        href="catalogo.php?marca=<?php
                            echo urlencode($nombreMarca);
                        ?>"
                        class="brand-card-premium"
                        style="--card-index: <?php echo (int)$indice; ?>"
                    >

                        <div class="brand-card-top">

                            <span class="brand-card-number">

                                <?php echo $numero; ?>

                            </span>

                            <span
                                class="brand-card-arrow"
                                aria-hidden="true"
                            >
                                ↗
                            </span>

                        </div>

                        <div class="brand-card-main">

                            <span class="brand-card-line"></span>

                            <h3>

                                <?php
                                echo escapar(
                                    mb_strtoupper(
                                        $nombreMarca,
                                        "UTF-8"
                                    )
                                );
                                ?>

                            </h3>

                            <p class="brand-card-caption">
                                EXPLORA LA MARCA
                            </p>

                        </div>

                        <div class="brand-card-bottom">

                            <div class="brand-card-count">

                                <strong>
                                    <?php echo $cantidad; ?>
                                </strong>

                                <span>

                                    <?php
                                    echo $cantidad === 1
                                        ? "MOTOCICLETA"
                                        : "MOTOCICLETAS";
                                    ?>

                                </span>

                            </div>

                            <span class="brand-card-link">

                                VER MODELOS

                                <span aria-hidden="true">→</span>

                            </span>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="brands-message">

                <span class="brands-message-icon" aria-hidden="true">
                    +
                </span>

                <h3>
                    Próximamente nuevas marcas.
                </h3>

                <p>
                    Todavía no hay marcas registradas.
                    Cuando se agreguen desde el sistema,
                    aparecerán automáticamente aquí.
                </p>

                <a href="catalogo.php" class="btn red">
                    EXPLORAR CATÁLOGO
                </a>

            </div>

        <?php endif; ?>

    </section>


    <!-- =================================================
         SECCIÓN INFORMATIVA
    ================================================== -->

    <section class="brands-discover">

        <div class="brands-discover-inner">

            <div class="brands-discover-copy">

                <p class="kicker">
                    TU PRÓXIMA EXPERIENCIA COMIENZA AQUÍ
                </p>

                <h2>
                    ENCUENTRA<br>
                    <span>TU ESTILO.</span>
                </h2>

                <p>

                    Una motocicleta es mucho más que
                    transporte. Explora los modelos,
                    compara sus características y descubre
                    las opciones disponibles en nuestro catálogo.

                </p>

                <a href="catalogo.php" class="btn red">

                    DESCUBRIR MOTOCICLETAS

                    <span aria-hidden="true">↗</span>

                </a>

            </div>

            <div class="brands-discover-visual">

                <div class="brands-discover-ring"></div>

                <div class="brands-discover-mark">
                    ONE
                </div>

                <div class="brands-discover-caption">

                    <span class="brands-discover-dot"></span>

                    <span>
                        TU CAMINO, TU ELECCIÓN
                    </span>

                </div>

            </div>

        </div>

    </section>

</main>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="site-footer">

    <div class="footer-content">

        <div class="footer-brand">

            <a href="index.php" class="brand">

                <img
                    src="img/onemoto.png"
                    alt="ONE MOTORCYCLES"
                    class="site-logo"
                >

            </a>

            <p>
                Motocicletas para todos los caminos.
            </p>

        </div>


        <div class="footer-column">

            <h4>NAVEGACIÓN</h4>

            <a href="index.php">Inicio</a>

            <a href="catalogo.php">Catálogo</a>

            <a href="marcas.php">Marcas</a>

            <a href="nosotros.php">Nosotros</a>

            <a href="login.php">Acceso</a>

        </div>


        <div class="footer-column">

            <h4>ONE MOTORCYCLES</h4>

            <p>El Salvador</p>

            <p>Calidad, estilo y rendimiento.</p>

        </div>

    </div>


    <div class="footer-bottom">

        <p>
            © <?php echo date("Y"); ?> ONE MOTORCYCLES
        </p>

        <p>
            TODOS LOS DERECHOS RESERVADOS.
        </p>

    </div>

</footer>

</body>
</html>