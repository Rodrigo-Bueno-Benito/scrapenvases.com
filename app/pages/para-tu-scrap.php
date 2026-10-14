<?php
// Copy: CORE SCRAP/Copy_scrapenvases_Home_ParaTuSCRAP.docx · página B
// Estructura: CORE SCRAP/wireframes-perfil.html
hero('para-tu-scrap',
    'Para Sistemas Colectivos (SCRAP)',
    'Todo lo que un SCRAP de envases necesita para operar',
    'El core reúne las tres funciones críticas de un SCRAP —homologación de gestores, control documental e incentivos del poseedor— en un ecosistema ya operativo, sin necesidad de construir una plataforma propia desde cero.',
    [['Hablar con la OTS', '/contacto', 'primary']]);

franja('Un SCRAP de envases comerciales e industriales coordina a muchos actores, maneja grandes volúmenes de documentación y responde ante la Administración. El core cubre esas tres necesidades con herramientas que ya funcionan juntas.');
?>

<section class="sec">
  <div class="wrap">
    <header class="sec__head">
      <h2>Homologar, controlar documentación y gestionar incentivos</h2>
    </header>
    <div class="needs">
      <?php
      necesidad('Homologar y controlar a tus gestores', 'PROBATUS · homologación',
          'Un SCRAP trabaja con decenas o cientos de gestores, cada uno con su propia documentación. Homologarlos y auditarlos de forma homogénea —y que el dato reportado sea defendible ante inspección— es un trabajo continuo.',
          'PROBATUS evalúa, homologa y hace seguimiento de proveedores de forma homogénea, transparente y auditable.',
          [['probatus', '→ probatus.es']], 'probatus');
      necesidad('Controlar la documentación de cada servicio', 'INPROGEST · control documental',
          'Cada servicio genera documentación —documento de identificación, ficha de seguimiento, certificado de reciclado efectivo— que hay que recopilar, validar, conservar y, además, reportar por partida doble: al propio SCRAP y al MITECO.',
          'INPROGEST coordina a los agentes implicados y centraliza el control documental de los servicios contratados.',
          [['inprogest', '→ inprogest.com']], 'inprogest');
      necesidad('Gestionar documentos e incentivos del poseedor', 'SCRAPP · trazabilidad',
          'El incentivo por trazabilidad corresponde por ley al poseedor final, mientras que la documentación la genera el gestor. Ordenar ese flujo —miles de veces al año y con calidad auditable— es el núcleo operativo del sistema.',
          'SCRAPP traza el flujo entre poseedor, gestor y SCRAP y ordena la documentación y los incentivos.',
          [['scrapp', '→ scrapp.es']], 'scrapp');
      ?>
    </div>
  </div>
</section>

<?php banda_ots('Tres herramientas, un mismo ecosistema',
    'Con el acompañamiento técnico de la Oficina Técnica de SCRAPs (OTS).',
    'Hablar con la OTS'); ?>
