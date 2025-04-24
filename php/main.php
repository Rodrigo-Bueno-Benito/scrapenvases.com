<?php
    require './header.php';
    require_once 'modelo.php';
?>
<html>
    <link rel="stylesheet" href="../css/estilos_main.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@600;800&display=swap" rel="stylesheet">
<body>
    <div class="page-container">
        <div class="arriba">
            <div class="carousel-section">
                <div class="carousel-container">
                    <div class="carousel-slide">
                        <div class="carousel-item">
                            <div class="noticia">
                                    <img src="../imagenes/scrapenvases.svg" alt="Imagen de noticia">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="noticia">
                                    <img src="../imagenes/banerenvases.svg" alt="Imagen de noticia">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="noticia">
                                    <img src="../imagenes/banerdigital.svg" alt="Imagen de noticia">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="noticia">
                                    <img src="../imagenes/banerhumano.svg" alt="Imagen de noticia">
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
                <div class="contenedor-img">
                    <img src="../imagenes/logoScrapp.png" alt="Logo Scrapp" class="logo-img izquierda">
                </div>
                <p class="descripcion">Trazabilidad inteligente para SCRAPs exigentes. La herramienta que convierte la gestión de la RAP en eficiencia operativa.</p>
                </div>

                <!-- PROBATUS -->
                <div class="contenedor-item">
                <div class="contenedor-img">
                    <img src="../imagenes/logoProbatus.png" alt="Logo Probatus" class="logo-img derecha">
                </div>
                <p class="descripcion">Plataforma digital especializada en la homologación, evaluación y seguimiento de proveedores.</p>
                </div>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slide = document.querySelector('.carousel-slide');
            const items = document.querySelectorAll('.carousel-item');

            let currentIndex = 0; // Índice del slide actual
            const totalItems = items.length; // Total de items en el carrusel

            function moveToNext() {
                const width = items[0].clientWidth;

                // Avanzar al siguiente slide
                currentIndex = (currentIndex + 1) % totalItems; // Regresa al inicio cuando llega al final
                slide.style.transform = `translateX(-${currentIndex * width}px)`;
            }

            // Configurar el carrusel para moverse automáticamente cada 5 segundos
            setInterval(moveToNext, 5000);

            // Recalcular posición al redimensionar ventana
            window.addEventListener('resize', function () {
                const width = items[0].clientWidth;
                slide.style.transform = `translateX(-${currentIndex * width}px)`;
            });
        });
    </script>
</body>
</html>
<?php
    require './footer.php';
?>
