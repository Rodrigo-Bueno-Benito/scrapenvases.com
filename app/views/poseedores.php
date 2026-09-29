<!-- 1 · HERO — tono acompañante, no de alarma -->
<section class="pagehero">
  <div class="wrap pagehero__inner" data-reveal>
    <span class="kicker">Para poseedores de residuos de envases</span>
    <h1 data-split>Tus residuos de envases, en orden y a disposición de tu SCRAP</h1>
    <p>Como poseedor final, eres quien responde ante la Administración de la trazabilidad de tus residuos de envases comerciales e industriales. scrapenvases.com te da el canal para organizar esa documentación, ponerla a disposición de tu SCRAP y cumplir el RD 1055/2022 —sin cambiar de gestores.</p>
    <div class="pagehero__actions">
      <a class="btn btn--primary btn--lg" href="/contacto">Consultar con la OTS</a>
      <a class="btn btn--light" href="#calculadora">Calcular mi contribución</a>
    </div>
  </div>
</section>

<!-- 2 · CONTEXTO — la doble obligación y el incentivo poco conocido -->
<section class="contextband">
  <div class="wrap-narrow" data-reveal>
    <p>Desde 2022 ya tienes la obligación de gestionar tus residuos de envases a través de un gestor autorizado y de conservar su trazabilidad. Las nuevas obligaciones de la RAP <strong>no te eximen de las tuyas: conviven</strong>. Y por aportar bien esa trazabilidad te corresponde un incentivo económico que muchos poseedores ni saben que pueden cobrar.</p>
  </div>
</section>

<!-- 3 · TRES NECESIDADES → SOLUCIÓN (protagonista SCRAPP) -->
<?php
$needsKicker = 'Tus obligaciones, resueltas';
$needsTitulo = 'Ordena, comparte y cumple';
$needsLead   = 'Lo que hoy te cuesta tiempo, y cómo queda resuelto.';
$needs = [
    [
        'marca'    => '1',
        'titulo'   => 'Tu documentación, en un solo sitio',
        'tool'     => 'SCRAPP · gestión documental',
        'dolor'    => 'Cada retirada genera papel —documento de identificación, ficha de seguimiento, certificado de reciclado efectivo— que hoy persigues a tu gestor para reunir, y que hay que codificar bien (LER 15 01, no el grupo 20).',
        'solucion' => 'Reúne y ordena esa documentación en un único lugar, clasificada y lista, sin depender de correos sueltos ni carpetas dispersas.',
        'enlace'   => ['texto' => 'vía SCRAPP', 'url' => 'https://scrapp.es/'],
    ],
    [
        'marca'    => '2',
        'titulo'   => 'A disposición de tu SCRAP, sin teclear de más',
        'tool'     => 'SCRAPP · trazabilidad',
        'dolor'    => 'Cada SCRAP tiene su propia plataforma. Aprender una distinta por cada uno, y volver a subir lo mismo, es la fricción diaria que más desgasta al poseedor.',
        'solucion' => 'Pon tu trazabilidad a disposición de tu SCRAP desde un solo canal, con la documentación ya validada.',
        'enlace'   => ['texto' => 'vía SCRAPP', 'url' => 'https://scrapp.es/'],
    ],
    [
        'marca'    => '3',
        'titulo'   => 'Cumple y cobra tu incentivo',
        'tool'     => 'SCRAPP · incentivos',
        'dolor'    => 'El incentivo por trazabilidad (entre 3 y 5 €/t) corresponde por ley al poseedor final —no al gestor—. Sin la documentación en regla, ni se cumple ni se cobra.',
        'solucion' => 'Cumple el RD 1055/2022 y reclama el incentivo que te corresponde, con la prueba que lo justifica.',
        'enlace'   => ['texto' => 'vía SCRAPP', 'url' => 'https://scrapp.es/'],
    ],
];
require __DIR__ . '/partials/needs.php';
?>

<!-- DEMO EN VIVO -->
<section class="section demoshow">
  <div class="wrap">
    <div class="sectionhead" data-reveal>
      <div>
        <span class="kicker">Demo en vivo</span>
        <h2>Tu portal de poseedor, por dentro</h2>
      </div>
      <p>Esta es la pantalla que verías cada mes: tus centros con NIMA, las toneladas por material, el tope de tu tarifa y lo que se liquida. Puedes cambiar de centro y editar toneladas.</p>
    </div>
    <div data-reveal>
      <?php require __DIR__ . '/partials/demo-scrapp.php'; ?>
      <p class="demonote">Simulación con datos de ejemplo. Nada de lo que hagas aquí sale de tu navegador.</p>
    </div>
  </div>
</section>

<!-- CALCULADORA de contribución por material -->
<section class="section section--alt" id="calculadora">
  <div class="wrap">
    <div class="sectionhead" data-reveal>
      <div>
        <span class="kicker">Herramienta</span>
        <h2>Calculadora de contribución por material</h2>
      </div>
      <p>Introduce las toneladas de cada material y calcula tu aportación estimada.</p>
    </div>

    <div class="calc" data-calc data-reveal>
      <div class="calc__head">
        <span>Material</span><span>Tarifa</span><span>Toneladas</span><span class="calc__ar">Subtotal</span>
      </div>

      <div class="calc__row" data-rate="31.5">
        <div class="calc__mat"><span class="calc__dot" style="background:#c9a26b"></span>Papel y cartón</div>
        <div class="calc__rate">31,50 €/t</div>
        <div class="calc__qty">
          <input type="number" min="0" step="0.01" value="0" inputmode="decimal" aria-label="Toneladas de papel y cartón">
        </div>
        <div class="calc__sub calc__ar" data-sub>0,00 €</div>
      </div>

      <div class="calc__row" data-rate="49">
        <div class="calc__mat"><span class="calc__dot" style="background:var(--brand-orange)"></span>Plástico</div>
        <div class="calc__rate">49,00 €/t</div>
        <div class="calc__qty">
          <input type="number" min="0" step="0.01" value="0" inputmode="decimal" aria-label="Toneladas de plástico">
        </div>
        <div class="calc__sub calc__ar" data-sub>0,00 €</div>
      </div>

      <div class="calc__row" data-rate="13">
        <div class="calc__mat"><span class="calc__dot" style="background:#8a6d3b"></span>Madera</div>
        <div class="calc__rate">13,00 €/t</div>
        <div class="calc__qty">
          <input type="number" min="0" step="0.01" value="0" inputmode="decimal" aria-label="Toneladas de madera">
        </div>
        <div class="calc__sub calc__ar" data-sub>0,00 €</div>
      </div>

      <div class="calc__total">
        <span>Total estimado</span>
        <strong data-total>0,00 €</strong>
      </div>

      <p class="calc__note">Tarifas orientativas: Papel y cartón 31,50 €/t · Plástico 49,00 €/t · Madera 13,00 €/t. Cálculo estimativo y no vinculante.</p>
    </div>
  </div>
</section>

<!-- 4 · CIERRE · OTS — "sigues con tus gestores" -->
<?php
$otsTitulo = 'Mantén tus gestores de siempre';
$otsTexto  = 'Mantén tus acuerdos actuales con tus gestores. Nosotros ponemos el orden documental, con el acompañamiento de la Oficina Técnica de SCRAPs (OTS).';
require __DIR__ . '/partials/ots-band.php';
?>
