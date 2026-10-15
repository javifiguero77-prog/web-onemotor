<?php

require_once "conexion.php";

mysqli_report(MYSQLI_REPORT_OFF);

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
        content="Conoce la historia, misión, visión y valores de ONE MOTORCYCLES."
    >

    <title>ONE MOTORCYCLES | Nosotros</title>

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

        <a href="marcas.php">
            MARCAS
        </a>

        <a
            href="nosotros.php"
            class="active"
            aria-current="page"
        >
            NOSOTROS
        </a>

        <a href="login.php">
            ACCESO
        </a>

    </nav>

    <a href="catalogo.php" class="top-cta">
        VER MOTOS
        <span aria-hidden="true">↗</span>
    </a>

</header>


<main>

    <!-- =================================================
         HERO INSTITUCIONAL
    ================================================== -->

    <section class="about-hero">

        <div class="about-hero-glow"></div>

        <div class="about-hero-content">

            <div class="brands-eyebrow">

                <span class="brands-eyebrow-line"></span>

                <p class="kicker">
                    CONOCE ONE MOTORCYCLES
                </p>

            </div>

            <h1>
                MÁS QUE<br>
                <span>MOTOCICLETAS.</span>
            </h1>

            <p class="about-hero-description">

                Creemos en la libertad de elegir,
                en la emoción de cada recorrido y
                en encontrar una motocicleta que
                se adapte a tu manera de vivir.

            </p>

            <div class="about-hero-actions">

                <a href="#nuestra-historia" class="btn red">

                    CONÓCENOS

                    <span aria-hidden="true">↓</span>

                </a>

                <a href="catalogo.php" class="btn outline">

                    EXPLORAR CATÁLOGO

                    <span aria-hidden="true">↗</span>

                </a>

            </div>

        </div>

        <div class="about-hero-decoration" aria-hidden="true">
            OM
        </div>

        <div class="about-hero-bottom">

            <span>IDENTIDAD</span>

            <span class="about-hero-separator"></span>

            <span>PASIÓN</span>

            <span class="about-hero-separator"></span>

            <span>MOVIMIENTO</span>

        </div>

    </section>


    <!-- =================================================
         NUESTRA HISTORIA
    ================================================== -->

    <section
        class="about-story"
        id="nuestra-historia"
    >

        <div class="about-story-heading">

            <p class="kicker">
                NUESTRA HISTORIA
            </p>

            <h2>
                EL COMIENZO<br>
                <span>DE UN CAMINO.</span>
            </h2>

        </div>

        <div class="about-story-content">

            <div class="about-story-accent">

                <span class="about-story-accent-number">
                    01
                </span>

                <span class="about-story-accent-line"></span>

                <span class="about-story-accent-label">
                    NUESTRA ESENCIA
                </span>

            </div>

            <div class="about-story-text">

                <p class="about-story-lead">

                    ONE MOTORCYCLES nace con la idea
                    de ofrecer un espacio donde descubrir
                    diferentes opciones de motocicletas
                    sea una experiencia sencilla.

                </p>

                <p>

                    Nuestro sitio reúne información de
                    distintas marcas y modelos para que
                    los usuarios puedan consultar precios,
                    características y disponibilidad desde
                    un mismo lugar.

                </p>

                <p>

                    Queremos que cada visitante pueda
                    explorar las opciones disponibles,
                    conocer sus diferencias y encontrar
                    una motocicleta que se ajuste a sus
                    necesidades y preferencias.

                </p>

            </div>

        </div>

    </section>


    <!-- =================================================
         MISIÓN Y VISIÓN
    ================================================== -->

    <section class="about-purpose">

        <div class="about-purpose-heading">

            <div>

                <p class="kicker">
                    NUESTRO PROPÓSITO
                </p>

                <h2>
                    LO QUE NOS<br>
                    <span>IMPULSA.</span>
                </h2>

            </div>

            <p>

                Nuestra identidad se construye a partir
                de lo que ofrecemos y de la experiencia
                que queremos brindar a cada usuario.

            </p>

        </div>


        <div class="about-purpose-grid">

            <!-- MISIÓN -->

            <article class="purpose-card">

                <div class="purpose-card-top">

                    <span class="purpose-number">
                        01
                    </span>

                    <span class="purpose-symbol" aria-hidden="true">
                        ◎
                    </span>

                </div>

                <div class="purpose-card-content">

                    <p class="kicker">
                        NUESTRO PRESENTE
                    </p>

                    <h3>
                        MISIÓN
                    </h3>

                    <p>

                        Ofrecer una plataforma sencilla
                        y organizada para consultar
                        motocicletas de diferentes marcas,
                        proporcionando información clara
                        sobre sus características, precios
                        y disponibilidad.

                    </p>

                </div>

                <div class="purpose-card-footer">

                    <span>ONE MOTORCYCLES</span>

                    <span>01 / 02</span>

                </div>

            </article>


            <!-- VISIÓN -->

            <article class="purpose-card purpose-card-featured">

                <div class="purpose-card-top">

                    <span class="purpose-number">
                        02
                    </span>

                    <span class="purpose-symbol" aria-hidden="true">
                        ↗
                    </span>

                </div>

                <div class="purpose-card-content">

                    <p class="kicker">
                        HACIA DÓNDE VAMOS
                    </p>

                    <h3>
                        VISIÓN
                    </h3>

                    <p>

                        Ser una plataforma reconocida
                        por facilitar la búsqueda de
                        motocicletas y brindar una
                        experiencia clara, accesible
                        y organizada a nuestros usuarios.

                    </p>

                </div>

                <div class="purpose-card-footer">

                    <span>ONE MOTORCYCLES</span>

                    <span>02 / 02</span>

                </div>

            </article>

        </div>

    </section>


    <!-- =================================================
         VALORES
    ================================================== -->

    <section class="about-values">

        <div class="about-values-heading">

            <div>

                <p class="kicker">
                    NUESTROS VALORES
                </p>

                <h2>
                    LO QUE NOS<br>
                    <span>DEFINE.</span>
                </h2>

            </div>

            <p>

                Los principios que orientan nuestra
                manera de presentar las motocicletas
                y de organizar la experiencia digital.

            </p>

        </div>


        <div class="about-values-grid">

            <article class="value-card">

                <span class="value-number">
                    01
                </span>

                <span class="value-icon" aria-hidden="true">
                    ◈
                </span>

                <h3>
                    CALIDAD
                </h3>

                <p>

                    Presentamos la información de
                    forma clara y organizada para
                    facilitar su consulta.

                </p>

                <span class="value-card-line"></span>

            </article>


            <article class="value-card">

                <span class="value-number">
                    02
                </span>

                <span class="value-icon" aria-hidden="true">
                    ⌾
                </span>

                <h3>
                    CONFIANZA
                </h3>

                <p>

                    Buscamos que los usuarios puedan
                    consultar las características de
                    cada motocicleta con facilidad.

                </p>

                <span class="value-card-line"></span>

            </article>


            <article class="value-card">

                <span class="value-number">
                    03
                </span>

                <span class="value-icon" aria-hidden="true">
                    ◉
                </span>

                <h3>
                    VARIEDAD
                </h3>

                <p>

                    Reunimos diferentes marcas
                    y modelos para facilitar
                    la exploración de opciones.

                </p>

                <span class="value-card-line"></span>

            </article>


            <article class="value-card">

                <span class="value-number">
                    04
                </span>

                <span class="value-icon" aria-hidden="true">
                    ↗
                </span>

                <h3>
                    PASIÓN
                </h3>

                <p>

                    Nos inspira el mundo de las
                    motocicletas y todo lo que
                    representa para sus aficionados.

                </p>

                <span class="value-card-line"></span>

            </article>

        </div>

    </section>


    <!-- =================================================
         LLAMADA A LA ACCIÓN
    ================================================== -->

    <section class="about-cta">

        <div class="about-cta-background" aria-hidden="true">
            ONE
        </div>

        <div class="about-cta-content">

            <p class="kicker">
                TU PRÓXIMO RECORRIDO COMIENZA AQUÍ
            </p>

            <h2>
                ENCUENTRA<br>
                <span>TU MOTOCICLETA.</span>
            </h2>

            <p>

                Explora las marcas, descubre los modelos
                y conoce las opciones disponibles
                en nuestro catálogo.

            </p>

            <div class="about-cta-actions">

                <a href="catalogo.php" class="btn red">

                    EXPLORAR CATÁLOGO

                    <span aria-hidden="true">↗</span>

                </a>

                <a href="marcas.php" class="btn outline">

                    CONOCER LAS MARCAS

                    <span aria-hidden="true">→</span>

                </a>

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