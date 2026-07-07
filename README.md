# ScrapEnvases — web editorial + panel de noticias

Reconstrucción de **scrapenvases.com** con estilo **moderno editorial** (inspirado en el proyecto PDR),
manteniendo las secciones originales (SCRAP, Gestores, Poseedores, Normativa) y añadiendo un
**panel de administración** para publicar **noticias del mundo de los envases**.

## Stack

- **PHP 8.2** vanilla, sin frameworks ni dependencias (cero `composer install`).
- Front controller + router de rutas limpias (`/noticias`, `/noticia/<slug>`…).
- **PDO** con capa *fail-soft*: por defecto **SQLite** (cero configuración); opcional **MySQL** vía `.env`.
- CSS propio con **tokens de diseño** (paleta teal + naranja de la marca, estilo comercial).
- Tipografía: **Bricolage Grotesque** (titulares), **Hanken Grotesk** (cuerpo), **Barlow Condensed** (etiquetas).
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

Desde el panel puedes crear, editar, destacar, despublicar y borrar noticias, con subida de imagen
(JPG/PNG/WEBP/GIF, máx. 6 MB, se guardan en `public/uploads/`).

## Estructura

```
public/
  index.php        Front controller (?p=<vista>)
  router.php       Rutas limpias para el servidor embebido
  css/             tokens · base · layout · components · pages · admin
  js/main.js       Nav móvil, reveal on scroll, cookies, preview de imagen
  imagenes/        Assets de marca (copiados del original)
  uploads/         Imágenes subidas desde el panel
app/
  env.php · db.php · auth.php · helpers.php
  data/            install.php (esquema + seed) · noticias_db.php
  views/           Páginas públicas + panel + partials (header/footer/newscard)
data/              scrap.sqlite (autogenerado, ignorado por git)
```

## Secciones

| Ruta | Contenido |
|------|-----------|
| `/` | Home editorial: hero, ticker de colaboradores, plataformas SCRAPP/PROBATUS, accesos y últimas noticias |
| `/scrap` `/gestores` `/poseedores` | Páginas de servicios con el contenido original |
| `/normativa` | Códigos LER (familia 15) + obligaciones RD 1055/2022 en acordeón accesible |
| `/noticias` | Listado con noticia destacada y filtro por categoría |
| `/noticia/<slug>` | Detalle de noticia + relacionadas |
| `/acceso` `/admin` | Login y panel de administración |
| `/aviso-legal` `/politica-cookies` `/politica-privacidad` | Páginas legales |

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

## Notas de seguridad

- Contraseñas con `password_hash` (bcrypt); tokens **CSRF** en todos los formularios.
- Subidas validadas por MIME real (`finfo`) y renombradas.
- El **cuerpo** de las noticias admite HTML (lo escribe el admin). Si en el futuro hay editores
  de menor confianza, conviene sanear el HTML (p. ej. con una allowlist de etiquetas).
