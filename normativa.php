<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado LER</title>
    <link rel="stylesheet" href="./css/esti_normativa.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <script>
        function toggleContent(id) {
            var content = document.getElementById(id);
            content.classList.toggle('show');
        }

        function expandColumn(clickedColumn) {
            const columns = document.querySelectorAll('.info-expandible .column');
            const isExpanded = clickedColumn.classList.contains('expanded');

            // Restablece todas
            columns.forEach(col => {
                col.classList.remove('expanded');
                col.style.display = 'block';
            });

            // Si ya estaba expandida, salir
            if (isExpanded) return;

            // Expandir la clicada y ocultar el resto
            clickedColumn.classList.add('expanded');
            columns.forEach(col => {
                if (col !== clickedColumn) {
                    col.style.display = 'none';
                }
            });
        }
    </script>
</head>
<body>
    <?php require './header.php'; ?>

    <div class="container"> 
        <div class="main-columns">
            <!-- Columna izquierda -->
            <div class="content-container">
                <h1>LER (Familia envases)</h1>
                <h2>15 Residuos de envases; absorbentes, trapos de limpieza, materiales de filtración y ropas de protección no especificados en otra categoría.</h2>
                <ul>
                    <li><strong>15 01 01:</strong> Envases de papel y cartón</li>
                    <li><strong>15 01 02:</strong> Envases de plástico</li>
                    <li><strong>15 01 03:</strong> Envases de madera</li>
                    <li><strong>15 01 04:</strong> Envases metálicos</li>
                    <li><strong>15 01 05:</strong> Envases compuestos</li>
                    <li><strong>15 01 06:</strong> Envases mezclados</li>
                    <li><strong>15 01 07:</strong> Envases de vidrio</li>
                    <li><strong>15 01 09:</strong> Envases textiles</li>
                    <li><strong>15 01 10*:</strong> Envases que contienen restos de sustancias peligrosas o están contaminados por ellas</li>
                    <li><strong>15 01 11*:</strong> Envases metálicos, incluidos los recipientes a presión vacíos, que contienen una matriz sólida y porosa peligrosa (por ejemplo, amianto)</li>
                    <li><strong>15 02 02*:</strong> Absorbentes, materiales de filtración (incluidos los filtros de aceite no especificados en otra categoría), trapos de limpieza y ropas protectoras contaminados por sustancias peligrosas</li>
                    <li><strong>15 02 03:</strong> Absorbentes, materiales de filtración, trapos de limpieza y ropas protectoras distintos de los especificados en el código 15 02 02</li>
                </ul>
            </div>

            <!-- Columna derecha: info-expandible -->
            <div class="right-column">
                <h1>OBLIGACIONES</h1>
                <h2>Real Decreto 1055/2022, de 27 de diciembre, de envases y residuos de envases</h2>
                <p class="subtitulo"><a href="https://www.boe.es/buscar/act.php?id=BOE-A-2022-22690" target="blank">Enlace BOE</a></p>
                <div class="info-expandible">
                    <!-- 🏭 Productores -->
                    <div class="column" onclick="expandColumn(this)">
                        <h3>Productores de producto</h3>
                        <div class="content">
                            <p>Obligaciones de quienes envasan o importan productos:</p>
                            <ul class="lista-productores">
                                <li class="item-productores">• Registro en el RPP del MITECO.</li>
                                <li class="item-productores">• Adhesión a SCRAP o creación de SIRAP antes del 31/12/2024.</li>
                                <li class="item-productores">• Declaración anual de envases puestos en el mercado.</li>
                                <li class="item-productores">• Contribución financiera a la gestión de residuos.</li>
                                <li class="item-productores">• Diseño para facilitar reciclado y marcado informativo.</li>
                                <li class="item-productores">• Inclusión del número de envasador en facturas.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- ♻️ SCRAP -->
                    <div class="column" onclick="expandColumn(this)">
                        <h3>SCRAP</h3>
                        <div class="content">
                            <p>Responsabilidades de los sistemas colectivos:</p>
                            <ul class="lista-scrap">
                                <li class="item-scrap">• Organización y financiación del tratamiento de residuos.</li>
                                <li class="item-scrap">• Firmar convenios con administraciones y gestores.</li>
                                <li class="item-scrap">• Implementación de sistemas SDDR si corresponde.</li>
                                <li class="item-scrap">• Informes a comunidades autónomas y transparencia web.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- 🧱 Gestores -->
                    <div class="column" onclick="expandColumn(this)">
                        <h3>Gestores de residuos</h3>
                        <div class="content">
                            <p>Obligaciones de empresas autorizadas:</p>
                            <ul class="lista-gestores">
                                <li class="item-gestores">• Gestión conforme a normativa y buenas prácticas.</li>
                                <li class="item-gestores">• Acuerdos con SCRAPs y productores.</li>
                                <li class="item-gestores">• Registro y trazabilidad de residuos.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- 🏢 Poseedores -->
                    <div class="column" onclick="expandColumn(this)">
                        <h3>Poseedores de residuos</h3>
                        <div class="content">
                            <p>Responsabilidades de grandes generadores y operadores:</p>
                            <ul class="lista-poseedores">
                                <li class="item-poseedores">• Separar y entregar residuos correctamente.</li>
                                <li class="item-poseedores">• Posibles acuerdos con SCRAPs para facilitar gestión.</li>
                                <li class="item-poseedores">• Informes anuales de trazabilidad del producto envasado.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> <!-- /.right-column -->
        </div> <!-- /.main-columns -->
    </div> <!-- /.container -->

    <div class="banner"><img src="./imagenes/banernormativa.svg" alt=""></div>

    <?php require './footer.php'; ?>
</body>
</html>
