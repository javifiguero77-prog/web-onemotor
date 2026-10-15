<?php
require_once "conexion.php";

// Obtener algunas motocicletas para mostrar en el inicio
$sqlHero = "
    SELECT
        m.id_motocicleta,
        m.modelo,
        m.anio,
        m.precio,
        m.color,
        m.cilindraje,
        m.estado,
        m.imagen1,
        ma.nombre AS marca
    FROM motocicletas m
    INNER JOIN marcas ma
        ON m.id_marca = ma.id_marca
    WHERE m.estado = 'Disponible'
    ORDER BY m.id_motocicleta DESC
";

$resultHero = $conn->query($sqlHero);

$motosHero = [];

while ($moto = $resultHero->fetch_assoc()) {
    $motosHero[] = $moto;
}

$resultHero->free();

/* =========================
   MOTOCICLETAS DESTACADAS
========================= */

$sqlDestacadas = "
    SELECT
        m.id_motocicleta,
        m.modelo,
        m.anio,
        m.precio,
        m.color,
        m.cilindraje,
        m.estado,
        m.imagen1,
        ma.nombre AS marca
    FROM motocicletas m
    INNER JOIN marcas ma
        ON m.id_marca = ma.id_marca
    WHERE LOWER(TRIM(m.estado)) = 'disponible'
    ORDER BY m.id_motocicleta DESC
    LIMIT 6
";

$resultado = $conn->query($sqlDestacadas);

if (!$resultado) {
    error_log(
        "Error al consultar motocicletas destacadas: "
        . $conn->error
    );
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/png" href="/one_motorcycles/img/Logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ONE MOTORCYCLES | Inicio</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- =========================
     ENCABEZADO
========================= -->

<header class="topbar">

    <a href="index.php" class="brand">
        <img src="img/onemoto.png"
             alt="ONE MOTORCYCLES"
             class="site-logo">
    </a>

    <nav class="main-nav">

        <a href="index.php" class="active">
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

        <a href="login.php">
            ACCESO
        </a>

    </nav>

    <a href="catalogo.php" class="top-cta">
        VER MOTOS
    </a>

</header>


<!-- =========================
     HERO
========================= -->

<main>

<section class="hero-carousel">

    <div class="hero-background" id="hero-background"></div>

    <div class="hero-overlay"></div>

    <div class="hero-content">

        <p class="kicker">ONE MOTORCYCLES</p>

        <h1 id="hero-title">
            TU CAMINO.<br>
            TU <span>MOTOCICLETA.</span>
        </h1>

        <p class="hero-text" id="hero-text">
            Encuentra la motocicleta que se adapta a tu estilo.
        </p>

        <div class="hero-buttons">

            <a href="catalogo.php" class="btn red">
                VER CATÁLOGO →
            </a>

            <a href="marcas.php" class="btn outline">
                VER MARCAS
            </a>

        </div>

    </div>


    <div class="hero-bike-display">

        <img
            id="hero-moto-image"
            src=""
            alt="Motocicleta"
        >

        <div class="hero-bike-shadow"></div>

    </div>


    <div class="hero-info">

        <span id="hero-brand">
            YAMAHA
        </span>

        <strong id="hero-model">
            FZ 2.0
        </strong>

        <small id="hero-specs">
            150 CC · 2025
        </small>

    </div>


    <button
        class="hero-arrow hero-prev"
        id="hero-prev"
        type="button">
        ←
    </button>

    <button
        class="hero-arrow hero-next"
        id="hero-next"
        type="button">
        →
    </button>


    <div class="hero-counter">

        <span id="hero-current">01</span>

        <div class="hero-progress">
            <div id="hero-progress-bar"></div>
        </div>

        <span id="hero-total">01</span>

    </div>

</section>


<!-- =========================
     CATÁLOGO DESTACADO
========================= -->

<section class="catalog-section">

    <div class="section-top">

        <div>

            <p class="kicker">
                NUESTRO CATÁLOGO
            </p>

            <h2>
                MOTOS<br>
                <span>DESTACADAS.</span>
            </h2>

        </div>

        <a href="catalogo.php" class="btn outline">
            VER TODO →
        </a>

    </div>


<div class="moto-grid featured-grid">

    <?php if ($resultado && $resultado->num_rows > 0): ?>

        <?php while ($moto = $resultado->fetch_assoc()): ?>

            <?php
                $imagenMoto = '';

                if (!empty($moto['imagen1'])) {
                    $imagenMoto = trim($moto['imagen1']);

                    if (
                        strpos($imagenMoto, 'http://') !== 0 &&
                        strpos($imagenMoto, 'https://') !== 0 &&
                        strpos($imagenMoto, 'img/') !== 0
                    ) {
                        $imagenMoto = 'img/motos/' . $imagenMoto;
                    }
                }
            ?>

            <article class="moto-card featured-card">

                <div class="moto-visual featured-visual">

                    <?php if ($imagenMoto !== ''): ?>

                        <img
                            src="<?= htmlspecialchars(
                                $imagenMoto,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            alt="<?= htmlspecialchars(
                                $moto['marca'] . ' ' . $moto['modelo'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            class="moto-image"
                            loading="lazy"
                        >

                    <?php else: ?>

                        <div class="no-image">
                            <span>ONE MOTORCYCLES</span>
                            <strong>IMAGEN PRÓXIMAMENTE</strong>
                        </div>

                    <?php endif; ?>

                    <span class="availability">
                        DISPONIBLE
                    </span>

                    <span class="featured-tag">
                        SELECCIÓN ONE
                    </span>

                </div>

                <div class="moto-info featured-info">

                    <p class="moto-brand">
                        <?= htmlspecialchars(
                            $moto['marca'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>

                    <h3>
                        <?= htmlspecialchars(
                            $moto['modelo'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </h3>

                    <div class="featured-specs">

                        <span>
                            <small>CILINDRADA</small>

                            <strong>
                                <?= htmlspecialchars(
                                    $moto['cilindraje'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?> CC
                            </strong>
                        </span>

                        <span>
                            <small>AÑO</small>

                            <strong>
                                <?= htmlspecialchars(
                                    $moto['anio'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </strong>
                        </span>

                    </div>

                    <div class="moto-bottom">

                        <div class="featured-price">

                            <small>PRECIO</small>

                            <strong>
                                $<?= number_format(
                                    (float) $moto['precio'],
                                    2
                                ) ?>
                            </strong>

                        </div>

                        <a
                            href="catalogo.php"
                            class="detail-btn"
                            aria-label="Ver catálogo de motocicletas"
                        >
                            EXPLORAR
                            <span aria-hidden="true">↗</span>
                        </a>

                    </div>

                </div>

            </article>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="empty-message">

            <p>
                Estamos preparando nuestra próxima selección
                de motocicletas destacadas.
            </p>

            <a href="catalogo.php" class="btn red">
                EXPLORAR CATÁLOGO →
            </a>

        </div>

    <?php endif; ?>

</div>

</section>


<!-- =========================
     MARCAS
========================= -->

<section class="brands-section">

    <div class="section-top">

        <div>

            <p class="kicker">
                TRABAJAMOS CON
            </p>

            <h2>
                MARCAS<br>
                <span>RECONOCIDAS.</span>
            </h2>

        </div>

        <a href="marcas.php" class="btn outline">
            VER MARCAS →
        </a>

    </div>


    <div class="brands-grid">

        <a href="catalogo.php?marca=Yamaha" class="brand-card">
            <span>01</span>
            <h3>YAMAHA</h3>
            <p>Potencia y tecnología.</p>
        </a>

        <a href="catalogo.php?marca=Honda" class="brand-card">
            <span>02</span>
            <h3>HONDA</h3>
            <p>Calidad y confiabilidad.</p>
        </a>

        <a href="catalogo.php?marca=Suzuki" class="brand-card">
            <span>03</span>
            <h3>SUZUKI</h3>
            <p>Diseño y rendimiento.</p>
        </a>

        <a href="catalogo.php?marca=Kawasaki" class="brand-card">
            <span>04</span>
            <h3>KAWASAKI</h3>
            <p>Fuerza y carácter.</p>
        </a>

    </div>

</section>


<!-- =========================
     NOSOTROS
========================= -->

<section class="about-section">

    <div class="about-grid">

        <div>

            <p class="kicker">
                SOBRE NOSOTROS
            </p>

            <h2>
                MÁS QUE UNA<br>
                <span>MOTOCICLETA.</span>
            </h2>

        </div>

        <div>

            <p>
                En ONE MOTORCYCLES buscamos ofrecer motocicletas
                de diferentes marcas para que cada cliente encuentre
                la opción que mejor se adapte a sus necesidades.
            </p>

            <p>
                Nuestro objetivo es brindar información clara sobre
                cada motocicleta y facilitar la búsqueda de tu próxima
                moto.
            </p>

            <a href="nosotros.php" class="btn red">
                CONÓCENOS →
            </a>

        </div>

    </div>

</section>


<!-- =========================
     ACCESO
========================= -->

<section class="access-preview">

    <div>

        <p class="kicker">
            ¿YA TIENES UNA CUENTA?
        </p>

        <h2>
            ACCEDE A<br>
            <span>ONE MOTORCYCLES.</span>
        </h2>

        <p>
            Inicia sesión para acceder a tu cuenta.
        </p>

    </div>

    <div class="access-buttons">

        <a href="login.php" class="btn red">
            INICIAR SESIÓN
        </a>

        <a href="registro.php" class="btn outline">
            CREAR CUENTA
        </a>

    </div>

</section>

</main>


<!-- =========================
     PIE DE PÁGINA
========================= -->

<footer>

    <div class="footer-content">

        <div>

            <img src="img/onemoto.png"
                 alt="ONE MOTORCYCLES"
                 class="site-logo">

            <p>
                Motocicletas para todos los caminos.
            </p>

        </div>

        <div>

            <h4>NAVEGACIÓN</h4>

            <a href="index.php">Inicio</a>
            <a href="catalogo.php">Catálogo</a>
            <a href="marcas.php">Marcas</a>
            <a href="nosotros.php">Nosotros</a>
            <a href="login.php">Acceso</a>

        </div>

        <div>

            <h4>CONTACTO</h4>

            <p>
                ONE MOTORCYCLES
            </p>

            <p>
                El Salvador
            </p>

        </div>

    </div>

    <div class="footer-bottom">

        <p>
            © <?php echo date("Y"); ?> ONE MOTORCYCLES
        </p>

        <p>
            Todos los derechos reservados.
        </p>

    </div>

</footer>


<script>
const heroMotos = <?= json_encode(
    $motosHero,
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
) ?>;
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    if (!heroMotos || heroMotos.length === 0) {
        return;
    }

    const background = document.getElementById("hero-background");
    const image = document.getElementById("hero-moto-image");

    const brand = document.getElementById("hero-brand");
    const model = document.getElementById("hero-model");
    const specs = document.getElementById("hero-specs");

    const current = document.getElementById("hero-current");
    const total = document.getElementById("hero-total");

    const progress = document.getElementById("hero-progress-bar");

    const previous = document.getElementById("hero-prev");
    const next = document.getElementById("hero-next");

    let index = 0;

    const duration = 6500;

    let timer;
    let progressTimer;


    total.textContent = String(heroMotos.length).padStart(2, "0");


    function getImage(moto) {

        if (!moto.imagen1) {
            return "";
        }

        if (
            moto.imagen1.startsWith("http://") ||
            moto.imagen1.startsWith("https://")
        ) {
            return moto.imagen1;
        }

        if (moto.imagen1.startsWith("img/")) {
            return moto.imagen1;
        }

        return "img/motos/" + moto.imagen1;
    }


    function showMoto(newIndex) {

        index = newIndex;

        if (index < 0) {
            index = heroMotos.length - 1;
        }

        if (index >= heroMotos.length) {
            index = 0;
        }

        const moto = heroMotos[index];

        const motoImage = getImage(moto);


        /*
         * Reiniciamos animación
         */

        image.style.animation = "none";

        void image.offsetWidth;

        image.style.animation =
            "heroMotoIn 0.9s cubic-bezier(.22,.61,.36,1)";


        /*
         * Actualizamos imagen
         */

        image.src = motoImage;

        image.alt =
            moto.marca + " " + moto.modelo;


        /*
         * Fondo desenfocado
         */

        background.style.backgroundImage =
            "url('" + motoImage + "')";


        /*
         * Información
         */

        brand.textContent =
            moto.marca.toUpperCase();

        model.textContent =
            moto.modelo.toUpperCase();

        specs.textContent =
            moto.cilindraje +
            " CC · " +
            moto.anio;


        current.textContent =
            String(index + 1).padStart(2, "0");


        restartProgress();
    }


    function restartProgress() {

        clearInterval(progressTimer);

        progress.style.width = "0%";

        const start = Date.now();

        progressTimer = setInterval(function () {

            const elapsed =
                Date.now() - start;

            const percentage =
                Math.min(
                    (elapsed / duration) * 100,
                    100
                );

            progress.style.width =
                percentage + "%";

            if (percentage >= 100) {
                clearInterval(progressTimer);
            }

        }, 30);
    }


    function startTimer() {

        clearInterval(timer);

        timer = setInterval(function () {

            showMoto(index + 1);

        }, duration);
    }


    previous.addEventListener("click", function () {

        showMoto(index - 1);

        startTimer();

    });


    next.addEventListener("click", function () {

        showMoto(index + 1);

        startTimer();

    });


    /*
     * Primera moto
     */

    showMoto(0);

    startTimer();


    /*
     * Pausar cuando el usuario
     * deja la pestaña.
     */

    document.addEventListener(
        "visibilitychange",
        function () {

            if (document.hidden) {

                clearInterval(timer);
                clearInterval(progressTimer);

            } else {

                startTimer();
                restartProgress();

            }

        }
    );

});
</script>

</body>
</html>