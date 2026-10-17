# scrapenvases.com — portal de la RAP de envases

Portal del sector que presenta el **core** de la RAP de envases comerciales e industriales
(RD 1055/2022): **PROBATUS** (homologación), **INPROGEST** (control documental) y
**SCRAPP** (documentos e incentivos del poseedor).

**Todo el texto de la web es el de los documentos de `CORE SCRAP/`**, copiado literal, y
cada página sigue los apartados y el orden de su wireframe. Si cambia un texto, se cambia
primero en el `.docx` correspondiente y después en la página.

| Página | Ruta | Copy (texto) | Wireframe (apartados) |
|---|---|---|---|
| Home | `/` | `Copy_scrapenvases_Home_ParaTuSCRAP.docx` · A | `wireframe-home.html` |
| Para tu SCRAP | `/para-tu-scrap` | `Copy_scrapenvases_Home_ParaTuSCRAP.docx` · B | `wireframes-perfil.html` |
| Poseedores | `/poseedores` | `Copy_scrapenvases_Poseedores_Gestores.docx` · C | `wireframes-perfil.html` |
| Gestores | `/gestores` | `Copy_scrapenvases_Poseedores_Gestores.docx` · D | `wireframes-perfil.html` |
| Normativa | `/normativa` | Página existente que se mantiene (LER 15 01, obligaciones por perfil) | — |
| Contacto · OTS | `/contacto` | `Copy_scrapenvases_Contacto_OTS.docx` · F | `wireframe-contacto-ots.html` |

Además: `/aviso-legal`, `/politica-privacidad` y `/politica-cookies` (separadas, como pide
la nota de higiene), y el panel interno de consultas de la OTS en `/admin`.

Las rutas antiguas (`/scrap`, `/core`, `/ots`, `/noticias`, `/noticia/…`, `/demos`)
redirigen con 301.

## Estructura

```
app/
  pages/        una plantilla por página, con el copy literal
  layout/       cabecera (navegación) y pie
  partials.php  bloques comunes: hero, franja, tarjeta de herramienta, necesidad, banda OTS
  consultas.php formulario de la OTS: validación, guardado con consentimiento RGPD y aviso por correo
  admin.php     panel interno: /acceso, /admin, /salir
  db.php        PDO (SQLite por defecto, MySQL opcional) e instalación de tablas
public/
  index.php     front controller: mapa de páginas, <title> y descripción de cada una
  css/site.css  estilos
  js/site.js    menú móvil y movimiento ligado al scroll (portada, frases, fotos, herramientas)
  js/hero3d.js  portada en 3D (Three.js desde jsDelivr): favicon → logo → core; si falla, queda la versión plana
  js/apps3d.js  logos 3D de los apartados de PROBATUS, INPROGEST y SCRAPP (portada); si falla, queda el logo plano
  js/journey.js viaje por el core con escenas reales (portada): mapa → PROBATUS → INPROGEST → camión en ruta → SCRAPP, con la pantalla de cada herramienta encajada en su portátil; con "reducir movimiento", apartados apilados
  imagenes/web/ logos para la web (palabra oscura y blanca, y el logo completo con la red)
  media/        fotografías del portal; media/3d/ vectores de los logos y red de nodos para la escena 3D
                media/apps/: pantalla de cada herramienta (sacada del vídeo de lanzamiento y escalada con Magnific)
                media/viaje/: fotos del viaje (IA de Freepik) y ruta/, los fotogramas del camión
                media/perfil/: escenas del viaje con la pantalla de cada herramienta en el portátil (páginas de perfil)
```

PHP 8.1 o superior, sin dependencias.

## Arrancar en local

```bash
php -S localhost:8010 -t public public/router.php
```

## Configuración (`.env`)

Copia `.env.example` a `.env`. Lo importante:

- `OTS_EMAIL` — bandeja de la OTS que recibe cada consulta del formulario. Las consultas se
  guardan siempre en la base de datos (con fecha y hora del consentimiento RGPD), aunque el
  correo falle, y se consultan en `/admin`.
- `OTS_TEL` — teléfono de la OTS. Mientras no se defina, la página de contacto no muestra la línea de teléfono.
- `ADMIN_EMAIL` / `ADMIN_PASSWORD` — crean el primer usuario del panel en una instalación nueva.

## Imágenes reales

Los wireframes piden fotos reales (canal industrial, operativa de SCRAP, almacén del
poseedor, planta de gestión y las fotos de María y Rodrigo). Basta con dejarlas en
`public/imagenes/portal/` con estos nombres (`.webp`, `.jpg` o `.png`) y aparecen solas:

| Fichero | Dónde sale |
|---|---|
| `home` | Hero de la Home (sustituye al diagrama de las tres piezas) |
| `para-tu-scrap`, `poseedores`, `gestores`, `normativa` | Hero de cada página |
| `maria-izquierdo`, `rodrigo-bueno` | "Quién te atiende" en Contacto |

## Pendiente (de `CORE SCRAP/README.md`)

- Confirmar **email y teléfono de la OTS** (en el copy figuran como provisionales).
- **Logo de INPROGEST**: mientras no exista se muestra su nombre compuesto en texto.
- **Imágenes reales** (ver tabla anterior).
- Redes sociales: no hay perfiles reales confirmados, así que el pie no muestra iconos.
- **Escaparate de SCRAPs** (fase 3 de la hoja de ruta).
