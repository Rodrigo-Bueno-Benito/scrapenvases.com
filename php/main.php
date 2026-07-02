<?php
    require './header.php';
    require_once 'modelo.php';
?>
<html>
    <link rel="stylesheet" href="../css/estiloss_Portada.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
<body>
    <div class="page-container">
        <div class="arriba">
            <div class="carousel-section">
            <h1>ACTUALIDAD</h1>
                <div class="carousel-container">
                    <div class="carousel-slide">
                        <div class="carousel-item">
                            <div class="noticia">
                                    <img src="../imagenes/scrapArriba.png" alt="Imagen de noticia">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="noticia">
                                    <img src="../imagenes/scrapArriba.png" alt="Imagen de noticia">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="noticia">
                                    <img src="../imagenes/scrapArriba.png" alt="Imagen de noticia">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="abajo">
            <div class="aIzquierda">
                <div class="imagen-con-texto">
                    <img src="../imagenes/logoScrapp.png" alt="">
                    <p class="descripcionIzq">Texto descriptivo de la imagen izquierda. Lorem ipsum elit. Repudiandae quisquam error architecto iusto sit quos veritatis. Vitae laborum mollitia iusto repudiandae. Atque nesciunt doloribus odio veniam qui harum sapiente earum?</p>
                </div>
            </div>
            <div class="aDerecha">
                <div class="imagen-con-texto">
                    <img src="../imagenes/logoProbatus.png" alt="">
                    <p class="descripcionDer">Texto descriptivo de la imagen derecha. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas sint vitae rem culpa et fuga unde eum, corporis quasi at animi fugiat adipisci tempora. Ipsam quae quos animi atque dolore.</p>
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
