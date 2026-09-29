# ScrapEnvases — portal de la RAP de envases

Implementación del rediseño documentado en **`CORE SCRAP/`**: scrapenvases.com pasa de
ser una web informativa sobre dos herramientas a un **portal del sector** que presenta
el ecosistema del **core** —**PROBATUS** (homologación), **INPROGEST** (control
documental) y **SCRAPP** (documentos e incentivos)— con tono neutro de portal.

Incluye además el **panel de administración** para publicar noticias, gestionar la demo
y atender las consultas que llegan a la Oficina Técnica de SCRAPs (OTS).

## Stack

- **PHP 8.2** vanilla, sin frameworks ni dependencias (cero `composer install`).
- Front controller + router de rutas limpias (`/el-core`, `/noticia/<slug>`…).
- **PDO** con capa *fail-soft*: por defecto **SQLite** (cero configuración); opcional **MySQL** vía `.env`.
- CSS propio con **tokens de diseño**: escala teal completa (50→900), un único acento
  naranja reservado a la acción, neutros de una sola familia y superficies profundas.
- Tipografía de tres voces: **Archivo** (titulares, señalética industrial, eje de anchura
  variable), **Public Sans** (cuerpo, tipografía de documento oficial) y **Spline Sans Mono**
  (dato, etiqueta y código LER, con cifras tabulares).
- **Demos en vivo** de las tres herramientas, jugables en el navegador (`public/js/demos.js`).
- Motor de animación propio (`public/js/effects.js`): red de nodos en canvas, reveals por scroll,
  contadores, botones magnéticos, tilt 3D y transiciones de página — todo respeta `prefers-reduced-motion`.

## Arrancar en local

```bash
php -S localhost:8000 -t public public/router.php
```

Abre <http://localhost:8000>. En el primer arranque se crea la base de datos SQLite
(`data/scrap.sqlite`), un usuario admin y 4 noticias de ejemplo automáticamente.

## Acceso al panel

- URL: <http://localhost:8000/acceso>
- Usuario: `admin@scrapenvases.com`
- Contraseña: `admin1234`  ⚠️ **cámbiala antes de publicar en producción.**

Desde el panel se gestionan **noticias** (crear, editar, destacar, despublicar, borrar,
con subida de imagen), los elementos de la **demo** y la bandeja de **consultas de la OTS**.

## Mapa del portal

| Ruta | Contenido |
|------|-----------|
| `/` | Home: claim del core, franja de contexto, las tres piezas, accesos por perfil, últimas noticias y cierre OTS |
| `/el-core` | **Página bandera**: el ecosistema, las tres piezas en bento, el flujo 01→02→03, la matriz por actor y las demos |
| `/demos` | **Demos en vivo**: PROBATUS, INPROGEST y SCRAPP funcionando con datos de ejemplo |
| `/para-tu-scrap` | Perfil prioritario: homologar · controlar documentación · gestionar incentivos |
| `/poseedores` | Canal para cumplir + incentivo (3–5 €/t) + calculadora de contribución |
| `/gestores` | Homologación, entrega de documentación y reporte por apoderamiento + demo |
| `/normativa` | Códigos LER (familia 15) + obligaciones RD 1055/2022 en acordeón accesible |
| `/noticias` · `/noticia/<slug>` | Listado con destacada y filtro por categoría · detalle + relacionadas |
| `/contacto` | Oficina Técnica de SCRAPs: formulario, contacto directo, cara visible y FAQ |
| `/acceso` `/admin` | Login y panel de administración |
| `/aviso-legal` `/politica-cookies` `/politica-privacidad` | Páginas legales |

Rutas anteriores al rediseño que se conservan con **301**: `/scrap` → `/para-tu-scrap`,
`/core` → `/el-core`, `/ots` → `/contacto`.

## Estructura

```
public/
  index.php        Front controller (?p=<vista>) + tabla de redirecciones 301
  router.php       Rutas limpias para el servidor embebido
  .htaccess        Reglas de producción para Apache (rewrite, 301, seguridad, caché)
  css/             tokens · base · layout · components · pages · portal · app-demo · motion · admin
  js/main.js       Nav móvil, reveal on scroll, cookies, preview de imagen, calculadora
  js/effects.js    Red de nodos, split-text, contadores, tilt, spotlight de cursor, cinta
  js/demos.js      Lógica de las tres demos en vivo
  imagenes/        Assets de marca
  uploads/         Imágenes y vídeos subidos desde el panel
app/
  env.php · db.php · auth.php · helpers.php
  data/            install.php (esquema + seed) · noticias_db · demos_db · consultas_db
  views/           Páginas públicas + panel + partials
    partials/      header · footer · core-tools · needs · ots-band · demo-section
                   demo-probatus · demo-inprogest · demo-scrapp · demo-switch
docs/
  nginx.conf.ejemplo   Equivalente del .htaccess para Nginx + PHP-FPM
data/              scrap.sqlite y centinela de esquema (autogenerados, ignorados por git)
CORE SCRAP/        Material del rediseño: estrategia, copy y wireframes
```

Los partials evitan duplicar el copy compartido: `core-tools.php` pinta las tres
herramientas (en orden de Home o de flujo), `needs.php` los bloques
«necesidad → herramienta» de las tres páginas de perfil y `ots-band.php` el cierre.

### Sistema visual

Cada sección usa una composición distinta, en lugar de repetir la misma rejilla de
tres columnas:

| Bloque | Composición |
|--------|-------------|
| Hero | Superficie profunda con retícula, red de nodos en canvas (solo ≥700 px) y panel de cristal |
| Las tres piezas (Home) | Índice en filas, con barra de acento al pasar el cursor |
| Las tres piezas (`/el-core`) | Bento asimétrico: la primera pieza ocupa la columna alta |
| Accesos por perfil | Bento: el perfil prioritario en oscuro, a doble altura |
| Flujo 01→02→03 | Riel horizontal con hitos; en móvil pasa a riel vertical |
| Necesidad → herramienta | Zig-zag a dos columnas: las filas pares invierten el orden |
| Qué aporta a cada actor | Tabla de definiciones, no tarjetas |
| Demos en vivo | Ventana de aplicación sobre superficie profunda, con conmutador |

Las tres herramientas se identifican con **marca tipográfica** (un tono de la escala
teal cada una) porque sus logos tienen proporciones muy dispares y juntos quedaban
descompensados; los logos reales se usan en el panel del hero y en el pie.

## Despliegue en producción

El `router.php` **solo sirve para el servidor embebido de PHP**. En producción:

- **Apache**: apunta el DocumentRoot a `public/` — `public/.htaccess` ya trae las reglas
  de reescritura, los 301, las cabeceras de seguridad y la caché de estáticos.
  Requiere `mod_rewrite` y `AllowOverride All`.
- **Nginx**: copia `docs/nginx.conf.ejemplo` y ajusta `server_name`, `root` y el socket de PHP-FPM.

Comprueba que `public/uploads/` y `data/` tengan permiso de escritura para el usuario del
servidor web, y que `data/` **no** sea accesible por HTTP (con el DocumentRoot en `public/`
queda fuera por construcción).

## Formulario de la OTS

Las consultas de `/contacto` se guardan en la tabla `consultas` junto con la **prueba del
consentimiento RGPD** (marca de tiempo ISO-8601 e IP) y se leen desde el panel.

Si defines `OTS_EMAIL` en `.env`, además se envía un aviso por correo con `Reply-To` del
remitente. Sin esa variable no se pierde nada: la consulta queda en el panel y la página
muestra el email y el teléfono como *(por confirmar)*.

El formulario lleva token CSRF, validación de campos, trampa antispam oculta y un límite
de un envío cada 30 segundos por sesión.

## Usar MySQL en lugar de SQLite

Edita `.env`:

```
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_NAME=scrapenvases
DB_USER=tu_usuario
DB_PASS=tu_password
```

El esquema se crea solo (compatible SQLite/MySQL) en el primer arranque.

## Esquema de base de datos

`app/data/install.php` es idempotente y usa un **centinela** (`data/.schema-v<N>`) para
no ejecutar ningún `CREATE`/`ALTER` en las visitas normales. Al cambiar el esquema, sube
`SCHEMA_VERSION`: en el siguiente arranque se aplican las migraciones y se reescribe el
centinela.

Tablas: `usuarios`, `noticias` (con índice único en `slug`), `demos` y `consultas`.

## Notas de seguridad

- Contraseñas con `password_hash` (bcrypt); tokens **CSRF** en todos los formularios.
- Cookie de sesión con `HttpOnly`, `SameSite=Lax` y `Secure` automático bajo HTTPS.
- `require_admin()` exige **rol de administrador**, no solo sesión iniciada.
- Subidas validadas por MIME real (`finfo`) y renombradas; en `/uploads` no se ejecuta código.
- El **cuerpo** de las noticias admite HTML (lo escribe el admin). Si en el futuro hay editores
  de menor confianza, conviene sanear el HTML (p. ej. con una allowlist de etiquetas).

## Pendiente (viene de `CORE SCRAP/README.md`)

- **Email y teléfono de la OTS** — hoy se muestran como *(por confirmar)*; se activan con `OTS_EMAIL` y `OTS_TEL`.
- **Logo de INPROGEST** — no existe en `public/imagenes/`; se usa una marca tipográfica provisional.
- **Confirmar `inprogest.com`** como dominio definitivo (los enlaces ya apuntan ahí).
- **Imágenes reales** del canal industrial (bidones, IBC/GRG, palés, big bags, films) y fotos de María y Rodrigo.
- **Escaparate de SCRAPs** (fase 3 de la hoja de ruta).
- **Verificar las cifras** del pulso del sector en fuente oficial (MITECO) antes de publicarlas.


## Demos en vivo

Tres simulaciones jugables de las herramientas del core, en JavaScript sin dependencias
(`public/js/demos.js`). No son las aplicaciones reales: reproducen su trabajo con datos de
ejemplo del sector, y cada una lo declara en pantalla.

| Demo | Pantalla que reproduce | Qué puede hacer el visitante |
|------|------------------------|------------------------------|
| **PROBATUS** | — (sin repositorio) | Abrir el expediente de un gestor, ver sus requisitos y vigencias, y homologarlo. La puntuación se calcula, el estado cambia y las métricas se actualizan. |
| **INPROGEST** · dashboard interactivo | `scrapp/dashboard_scrapp.php` de **inprogetrecyclia** | Filtrar por año, mes, operativa, agrupación y proveedor; recorrer los accesos rápidos; y **pulsar un mes en el gráfico de barras para cruzarlo con todo lo demás** —los cinco KPIs, el donut de documentados y el alcance se recalculan—, tal como anuncia la propia pantalla. |
| **SCRAPP Poseedores** · gestión de documentos | `documentos.php` de **scrappposedores** | Filtrar los 24 registros por pago, archivo, tipo, DI, poseedor, material o LER —los filtros rápidos y los de la cabecera están sincronizados, como anuncia la propia pantalla—, validar un documento, eliminarlo y ver el total recalcularse. |

### La piel es la de las aplicaciones

`public/css/app-skin.css` reproduce el aspecto de los portales en producción. **Cada
aplicación tiene la suya**, porque no comparten paleta:

| Aplicación | Paleta | Rasgos |
|------------|--------|--------|
| **SCRAPP Poseedores** (`.skin`) | Verde `#14532d` → `#0f3d22`, acento naranja `#ff7d00` | Activo del menú en naranja, cabecera de tabla por bloques de color (`sec-0`…`sec-4`), botones de acción redondeados (validar verde, editar naranja, eliminar rojo, anexo azul), total en píldora naranja |
| **INPROGEST** (`.skin--ipg`) | Azul `#00527a` → teal `#007a6e`, acento teal `#009889` | Activo del menú en blanco con texto teal, submenú desplegado, fondo `#f4f8fa` con radiales, cabecera de tabla en gradiente azul-teal, gráficos de barras y donut |

Los valores salen de `scrappposedores/documentos.php` y de
`inprogetrecyclia/scrapp/dashboard_scrapp.php` respectivamente.

Los gráficos son CSS puro, sin librería: barras con `<div>` de altura proporcional y donut
con `conic-gradient` sobre `@property --p`, que permite animar el arco.

Como en la aplicación, **las tablas no se comprimen: se desplazan en horizontal**. Meter
once columnas en el ancho disponible partía cada celda en tres líneas.

Las demos se construyeron leyendo los repositorios de producción, así que la
terminología y el modelo de cálculo son los reales:

- **Tarifa tipo 1** del portal del poseedor: precio por tonelada **hasta el tope de cada
  material**; el resto es excedente. Cada centro tiene su propio precio y sus propios topes.
- Materiales del sistema: cartón, plástico, madera, metal, vidrio (y textiles/otros en tarifa).
- Un servicio se considera verificado cuando sus **cuatro** documentos lo están; los pendientes
  de RAMON cuentan aparte, como en el dashboard real.

Detalles de implementación:

- Cada demo **arranca cuando se acerca al viewport** (o al abrir su pestaña), nunca antes.
- El estado vive en memoria: no hay BBDD, no hay red, nada sale del navegador.
- La ventana se adapta a su hueco con **container queries**: en el hero va reducida, en
  `/demos` a ancho completo, y por debajo de 560 px la tabla se convierte en fichas.
- El expediente de PROBATUS usa **View Transitions** para el morphing entre gestores.

Están incrustadas en `/demos` (las tres), la home (PROBATUS en el hero + conmutador),
`/el-core` y `/para-tu-scrap` (las tres), `/gestores` (PROBATUS) y `/poseedores` (SCRAPP).

### Enlace a las aplicaciones reales

El pie de cada demo enlaza a la herramienta con `plataforma_url()`. Por defecto son los
dominios de producto (`scrapp.es`, `inprogest.com`, `probatus.es`).

Si algún día existe un **entorno de demostración** con datos ficticios y usuario de invitado,
basta con declararlo en `.env` y el pie pasa a ofrecer «Abrir la aplicación real» en primer
plano, sin tocar código:

```
DEMO_SCRAPP=https://demo.scrapp.es/
DEMO_INPROGEST=https://demo.inprogest.com/
```

**No se enlazan instancias de cliente** (del tipo `<cliente>.scrapp.es`). Son paneles de
producción: un visitante solo vería un formulario de acceso que no puede pasar, y se estaría
dirigiendo tráfico frío al entorno de un tercero con sus datos dentro.

## Movimiento

`public/css/motion.css` concentra las animaciones dirigidas por scroll
(`animation-timeline: view()`), con mejora progresiva: donde no hay soporte —Firefox hoy— el
diseño estático se mantiene íntegro. El hero se hunde al salir, las filas del core entran una
a una, el riel del flujo se dibuja y la ventana de aplicación aterriza en perspectiva.

El resto lo aporta `effects.js`: reveals por scroll, split de titulares, contadores y el
header que se esconde al bajar.

**Movimiento retirado a propósito** al reorientar el portal a grandes cuentas: la red de
nodos en canvas, la cinta marquee, el spotlight de cursor, el tilt 3D y los botones
magnéticos. Ante un comité de compras, el efectismo resta credibilidad. En su lugar hay una
banda de marco normativo estática y un bloque de capacidad y garantías.

Todo el movimiento está tras `prefers-reduced-motion: no-preference`, con alternativa estática.

## Versionado de estáticos

`asset('/css/base.css')` añade la marca de tiempo del fichero (`?v=<filemtime>`), de modo que
el navegador recoge los cambios al instante sin dejar de cachear el resto del tiempo. Úsalo
siempre que añadas una hoja o un script.
