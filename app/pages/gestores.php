<?php
// Copy: CORE SCRAP/Copy_scrapenvases_Poseedores_Gestores.docx · página D
// Estructura: CORE SCRAP/wireframes-perfil.html
cabecera('gestores',
    'Tu papel', 'en el nuevo modelo de RAP de envases',
    'El sistema te pide entregar la documentación de cada servicio en el formato que necesitan los SCRAP y reportar su trazabilidad. scrapenvases.com te ayuda a homologarte, integrarte y reportar sin multiplicar el trabajo por cada SCRAP.',
    ['Hablar con la OTS', '/contacto']);

franja('Con la RAP de envases comerciales e industriales, tus clientes poseedores necesitan de ti una documentación limpia —documento de identificación, ficha de seguimiento, certificado de reciclado efectivo— para reportar al SCRAP y cobrar su incentivo. Entregarla bien y reportar por apoderamiento <strong>refuerza tu relación con el cliente</strong> y tu posición frente a gestores no homologados.');
?>

<section class="sec sec--needs">
  <div class="wrap">
    <header class="sec__head">
      <h2><?= resaltar('Homológate, entrega y reporta', 'reporta') ?></h2>
    </header>
    <div class="needs">
      <?php
      necesidad('Homológate una vez', 'PROBATUS · homologación',
          'Cada SCRAP homologa a los gestores con los que trabaja: requisitos legales, técnicos, ambientales y administrativos. Repetir ese proceso, uno por uno, multiplica el trabajo.',
          'Homológate de forma homogénea y mantén tu documentación al día en un solo sistema.',
          [['probatus', '→ vía PROBATUS']], 'probatus', 'perfil/probatus');
      necesidad('Entrega la documentación limpia', 'INPROGEST · control documental',
          'La documentación de cada servicio tiene que llegar bien codificada (LER 15 01) y en el formato que el SCRAP puede procesar. Los errores generan devoluciones y retrasos.',
          'Genera y entrega la documentación de cada servicio, validada y lista para el SCRAP.',
          [['inprogest', '→ vía INPROGEST']], 'inprogest', 'perfil/inprogest');
      necesidad('Reporta por apoderamiento', 'INPROGEST · SCRAPP · reporte',
          'Puedes ofrecer a tus clientes reportar su trazabilidad al SCRAP en su nombre, como servicio de valor. Hoy eso implica operar en una plataforma distinta por cada SCRAP.',
          'Reporta en nombre de tus clientes desde un único canal, sin duplicar homologaciones ni plataformas.',
          [['inprogest', '→ vía INPROGEST'], ['scrapp', '+ SCRAPP']], 'scrapp', 'perfil/scrapp');
      ?>
    </div>
  </div>
</section>

<?php banda_ots('Un solo sistema, no una homologación y una plataforma por cada SCRAP.',
    'Con el acompañamiento técnico de la Oficina Técnica de SCRAPs (OTS).',
    'Hablar con la OTS'); ?>
