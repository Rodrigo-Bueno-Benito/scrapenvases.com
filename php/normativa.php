<?php
// Array con los datos del índice y sus descripciones
$listado = [
    "15" => [
        "titulo" => "Residuos de envases; absorbentes, trapos de limpieza; materiales de filtración y ropas de protección no especificados en otra categoría",
        "contenido" => [
            "15 01" => "Envases (incluidos los residuos de envases de la recogida selectiva municipal)",
            "15 01 01" => "Envases de papel y cartón",
            "15 01 02" => "Envases de plástico",
            "15 01 03" => "Envases de madera",
            "15 01 04" => "Envases metálicos",
            "15 01 05" => "Envases compuestos",
            "15 01 06" => "Envases mezclados",
            "15 01 07" => "Envases de vidrio",
            "15 01 09" => "Envases textiles",
            "15 01 10*" => "Envases que contienen restos de sustancias peligrosas o están contaminados por ellas",
            "15 01 11*" => "Envases metálicos, incluidos los recipientes a presión vacíos, que contienen una matriz sólida y porosa peligrosa (por ejemplo, amianto)",
            "15 02" => "Absorbentes, materiales de filtración, trapos de limpieza y ropas protectoras",
            "15 02 02*" => "Absorbentes, materiales de filtración (incluidos los filtros de aceite no especificados en otra categoría), trapos de limpieza y ropas protectoras contaminados por sustancias peligrosas",
            "15 02 03" => "Absorbentes, materiales de filtración, trapos de limpieza y ropas protectoras distintos de los especificados en el código 15 02 02"
        ]
    ]
];

// Obtener el índice seleccionado (si existe)
$indiceSeleccionado = isset($_GET['indice']) ? $_GET['indice'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado LER</title>
    <link rel="stylesheet" href="../css/estilo_Normativa.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <script>
        function toggleContent(id) {
            var content = document.getElementById(id);
            content.classList.toggle('show');
        }
    </script>
</head>
<body>
    <?php require './header.php'; ?>
    <div class="banner">
        <img class="banner" src="../imagenes/banner_fusionado.png" alt="Banner LER">
    </div>
    <div class="container"> 
        <h1>LER (Listado Europeo de Residuos)</h1>
        <p class="subtitulo">(Decisión 2014/955/CE de la Comisión de 18 de diciembre de 2014 por la que se modifica la Decisión 2000/532/CE...)</p>
        <!-- NUEVO CONTENEDOR DE COLUMNAS -->
        <div class="main-columns">
            <!-- Columna izquierda -->
            <div class="content-container">
                <?php if ($indiceSeleccionado && isset($listado[$indiceSeleccionado])): ?>
                    <h2>Contenido de: <?php echo $listado[$indiceSeleccionado]['titulo']; ?></h2>
                    <ul>
                        <?php foreach ($listado[$indiceSeleccionado]['contenido'] as $subindice => $subcontenido): ?>
                            <li>
                                <a href="javascript:void(0);" onclick="toggleContent('<?php echo $subindice; ?>')">
                                    <?php echo $subindice; ?>
                                </a>
                                <div id="<?php echo $subindice; ?>" class="subcontent">
                                    <?php echo $subcontenido; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="listadoLer.php" class="back-link">Volver al índice</a>
                <?php else: ?>
                    <ul>
                        <?php foreach ($listado as $indice => $descripcion): ?>
                            <li>
                                <a href="javascript:void(0);" onclick="toggleContent('<?php echo $indice; ?>')">
                                    <?php echo $indice; ?> - 
                                    <?php echo is_array($descripcion) ? $descripcion['titulo'] : $descripcion; ?>
                                </a>
                                <div id="<?php echo $indice; ?>" class="subcontent">
                                    <?php 
                                    if (is_array($descripcion)) {
                                        foreach ($descripcion['contenido'] as $subindice => $subcontenido) {
                                            echo "<p><strong>$subindice:</strong> $subcontenido</p>";
                                        }
                                    } else {
                                        echo "<p>$descripcion</p>";
                                    }
                                    ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Columna derecha -->
            <div class="right-column">
                <div class="agenda">
                    <div id="calendario"></div>
                    <div id="eventoDetalle">
                        <span class="close" onclick="cerrarEvento()">&times;</span>
                        <h3>Detalles del Evento</h3>
                        <p id="eventoTitulo"></p>
                        <p id="eventoDescripcion"></p>
                        <p id="eventoFechas"></p>
                    </div>
                </div>
                <div class="promo">
                    <img src="./imagenes/gif-Inpronet.gif" alt="">
                    <img src="./imagenes/OTR-gift-residuos-profesional.gif" alt="">
                    <img src="./imagenes/gif-Inpronet.gif" alt="">
                    <img src="./imagenes/OTR-gift-residuos-profesional.gif" alt="">
                </div>
            </div>
        </div> <!-- fin .main-columns -->
    </div> <!-- fin .container -->

    <!-- Scripts del calendario -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarioEl = document.getElementById('calendario');

            var calendario = new FullCalendar.Calendar(calendarioEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                firstDay: 1,
                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                events: [
                    {
                        title: 'Evento 1',
                        start: '2025-01-10',
                        end: '2025-01-12',
                        description: 'Este es un evento de ejemplo',
                        backgroundColor: '#1f775a'
                    },
                    {
                        title: 'Evento 2',
                        start: '2025-01-15',
                        description: 'Otro evento de ejemplo'
                    },
                    {
                        title: 'Declaración Residuos',
                        start: '2025-02-28',
                        description: 'Finaliza el plazo de presentación de la declaración de residuos en Andalucía',
                        backgroundColor: '#1f775a'
                    }
                ],
                dateClick: function(info) {
                    var fechaSeleccionada = info.dateStr;
                    var evento = calendario.getEvents().find(function(event) {
                        return event.startStr === fechaSeleccionada || (event.endStr && event.endStr === fechaSeleccionada);
                    });

                    if (evento) {
                        document.getElementById('eventoTitulo').textContent = evento.title;
                        document.getElementById('eventoDescripcion').textContent = evento.extendedProps.description || 'No hay descripción disponible.';
                        document.getElementById('eventoFechas').textContent = 'Desde: ' + evento.start.toLocaleDateString() + ' Hasta: ' + (evento.end ? evento.end.toLocaleDateString() : evento.start.toLocaleDateString());
                        document.getElementById('eventoDetalle').style.display = 'block';
                    } else {
                        alert('No hay eventos en esta fecha.');
                        document.getElementById('eventoDetalle').style.display = 'none';
                    }
                }
            });

            calendario.render();
        });

        function cerrarEvento() {
            document.getElementById("eventoDetalle").style.display = "none";
        }
    </script>
</body>
</html>
<?php 
require './footer.php'; 
?>