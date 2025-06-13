<?php
    require './header.php';
?>
<html>
    <link rel="stylesheet" href="./css/estilo_main.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@600;800&display=swap" rel="stylesheet">
<body>
    <div class="page-container">
        <div class="arriba">
            <div class="carousel-section">
                <div class="carousel-container" id="carouselContainer">
                    <div class="carousel-slide" id="carouselSlide">
                        <div class="carousel-item">
                            <div class="noticia">
                                <img src="imagenes/scrapenvases.svg" alt="Imagen de noticia">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="noticia">
                                <img src="imagenes/banerenvases.svg" alt="Imagen de noticia">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="noticia">
                                <img src="imagenes/banerdigital.svg" alt="Imagen de noticia">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="noticia">
                                <img src="imagenes/banerhumano.svg" alt="Imagen de noticia">
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>

        <div class="contenedor-principal">
            <p class="slogan">Dos soluciones, una misión: <span class="acento">la RAP conectada y bajo control.</span></p>

            <div class="contenedor-items">
                <!-- SCRAPP -->
                <div class="contenedor-item">
                    <a href="https://scrapp.es/" target="blank">
                    <div class="contenedor-img">
                        <img src="./imagenes/logoScrapp.png" alt="Logo Scrapp" class="logo-img izquierda">
                    </div>
                    <p class="descripcion">Trazabilidad inteligente para SCRAPs exigentes. La herramienta que convierte la gestión de la RAP en eficiencia operativa.</p>
                    </div>
                </a>

                <!-- PROBATUS -->
                <div class="contenedor-item">
                    <a href="https://probatus.es/" target="blank">
                        <div class="contenedor-img">
                            <img src="./imagenes/logoProbatus.png" alt="Logo Probatus" class="logo-img derecha">
                        </div>
                        <p class="descripcion">Plataforma digital especializada en la homologación, evaluación y seguimiento de proveedores.</p>
                        </div>
                    </a>
                </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slide = document.getElementById('carouselSlide');
            const container = document.getElementById('carouselContainer');
            let items = document.querySelectorAll('.carousel-item');

            // Clonar el primer item
            const firstClone = items[0].cloneNode(true);
            slide.appendChild(firstClone);

            items = document.querySelectorAll('.carousel-item'); // actualizar items

            let currentIndex = 0;
            const totalItems = items.length; // Ahora hay uno más
            let width = items[0].clientWidth;

            function moveToNext() {
                if (currentIndex >= totalItems - 1) return; // No avanzamos más que el último

                currentIndex++;
                slide.style.transition = 'transform 0.5s ease-in-out';
                slide.style.transform = `translateX(-${currentIndex * width}px)`;
            }

            slide.addEventListener('transitionend', () => {
                if (items[currentIndex].isEqualNode(firstClone)) {
                    // Sin transición, saltar al primer real
                    slide.style.transition = 'none';
                    currentIndex = 0;
                    slide.style.transform = `translateX(0px)`;
                }
            });

            setInterval(moveToNext, 10000);

            window.addEventListener('resize', function () {
                width = items[0].clientWidth;
                slide.style.transition = 'none'; // quitar transición al redimensionar
                slide.style.transform = `translateX(-${currentIndex * width}px)`;
            });
        });
    </script>
</body>
</html>
<?php
    require './footer.php';
?>
