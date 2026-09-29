<?php
require_admin();
require_once __DIR__ . '/../data/noticias_db.php';
require_once __DIR__ . '/../data/demos_db.php';

$view = $_GET['view'] ?? 'list';
$view = in_array($view, ['list', 'editor', 'demos'], true) ? $view : 'list';

$user = current_user();
$ok = flash_get('admin_ok');
$err = flash_get('admin_err');

// Datos para el editor
$editing = null;
if ($view === 'editor' && isset($_GET['id'])) {
    $editing = noticia_by_id((int)$_GET['id']);
    if (!$editing) { flash_set('admin_err', 'La noticia no existe.'); redirect('/admin'); }
}
$categoriasSug = ['Envases', 'Normativa', 'Digitalización', 'Gestión', 'Economía circular', 'Sector'];
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel · ScrapEnvases</title>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Hanken+Grotesk:wght@400;500;700;800&family=Barlow+Condensed:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/tokens.css">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/pages.css">
  <link rel="stylesheet" href="/css/admin.css">
  <link rel="icon" href="/imagenes/logoScrap.png">
</head>
<body class="admin-body">
<div class="admin-shell">
  <aside class="admin-side">
    <div class="admin-side__brand">
      <img src="/imagenes/logoScrap.png" alt="">
      <span>Panel</span>
    </div>
    <nav class="admin-nav">
      <a href="/admin" class="<?= $view === 'list' ? 'active' : '' ?>">📰 Noticias</a>
      <a href="/admin?view=editor" class="<?= ($view === 'editor' && !$editing) ? 'active' : '' ?>">✍️ Nueva noticia</a>
      <a href="/admin?view=demos" class="<?= $view === 'demos' ? 'active' : '' ?>">🎬 Demo (app)</a>
      <a href="/" target="_blank">🌐 Ver web ↗</a>
    </nav>
    <div class="admin-side__foot">
      <div style="color:#fff; font-weight:700"><?= e($user['nombre']) ?></div>
      <div><?= e($user['correo']) ?></div>
      <a href="/logout">Cerrar sesión</a>
    </div>
  </aside>

  <main class="admin-main">
    <?php if ($ok): ?><div class="alert alert--ok" style="max-width:none"><?= e($ok) ?></div><?php endif; ?>
    <?php if ($err): ?><div class="alert alert--error" style="max-width:none"><?= e($err) ?></div><?php endif; ?>

    <?php if ($view === 'list'): ?>
      <?php
        $stats = noticias_stats();
        $noticias = noticias_all_admin();
      ?>
      <div class="admin-topbar">
        <h1>Noticias</h1>
        <a class="btn btn--primary" href="/admin?view=editor">+ Nueva noticia</a>
      </div>

      <div class="stat-grid">
        <div class="stat"><div class="stat__num"><?= $stats['total'] ?></div><div class="stat__label">Total</div></div>
        <div class="stat"><div class="stat__num"><?= $stats['publicada'] ?></div><div class="stat__label">Publicadas</div></div>
        <div class="stat"><div class="stat__num"><?= $stats['borrador'] ?></div><div class="stat__label">Borradores</div></div>
      </div>

      <div class="card">
        <div class="card__head"><h2>Todas las noticias</h2></div>
        <?php if (!$noticias): ?>
          <div class="empty"><h3>Sin noticias todavía</h3><p>Crea la primera con “Nueva noticia”.</p></div>
        <?php else: ?>
        <div class="table-wrap">
          <table class="data">
            <thead>
              <tr><th></th><th>Título</th><th>Categoría</th><th>Estado</th><th>Fecha</th><th></th></tr>
            </thead>
            <tbody>
              <?php foreach ($noticias as $n): ?>
              <tr>
                <td><img class="thumb" src="<?= e(noticia_img_src($n['imagen'])) ?>" alt=""></td>
                <td>
                  <strong><?= e($n['titulo']) ?></strong>
                  <?php if ((int)$n['destacada'] === 1): ?><span class="tag" style="margin-left:.4rem">★ Destacada</span><?php endif; ?>
                </td>
                <td><?= e($n['categoria']) ?></td>
                <td>
                  <?php if ($n['estado'] === 'publicada'): ?>
                    <span class="badge badge--pub">Publicada</span>
                  <?php else: ?>
                    <span class="badge badge--draft">Borrador</span>
                  <?php endif; ?>
                </td>
                <td><?= e(fecha_es($n['fecha_publicacion'])) ?></td>
                <td>
                  <div class="rowactions">
                    <a class="btn-sm" href="/noticia/<?= e($n['slug']) ?>" target="_blank">Ver</a>
                    <a class="btn-sm primary" href="/admin?view=editor&id=<?= (int)$n['id'] ?>">Editar</a>
                    <form method="post" action="/admin-action" onsubmit="return confirm('¿Eliminar esta noticia? Esta acción no se puede deshacer.');" style="display:inline">
                      <?= csrf_field() ?>
                      <input type="hidden" name="op" value="delete">
                      <input type="hidden" name="id" value="<?= (int)$n['id'] ?>">
                      <button class="btn-sm danger" type="submit">Borrar</button>
                    </form>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>

    <?php elseif ($view === 'editor'): /* editor */ ?>
      <div class="admin-topbar">
        <h1><?= $editing ? 'Editar noticia' : 'Nueva noticia' ?></h1>
        <a class="btn-sm" href="/admin">← Volver al listado</a>
      </div>

      <form method="post" action="/admin-action" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="op" value="<?= $editing ? 'update' : 'create' ?>">
        <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>

        <div class="editor-grid">
          <div class="card">
            <div class="field">
              <label for="titulo">Título</label>
              <input type="text" id="titulo" name="titulo" required maxlength="220" value="<?= e($editing['titulo'] ?? '') ?>">
            </div>
            <div class="field">
              <label for="extracto">Extracto <span style="color:var(--ink-muted);font-weight:400">(resumen para tarjetas y portada)</span></label>
              <textarea id="extracto" name="extracto" style="min-height:90px" maxlength="400"><?= e($editing['extracto'] ?? '') ?></textarea>
            </div>
            <div class="field">
              <label for="cuerpo">Cuerpo <span style="color:var(--ink-muted);font-weight:400">(admite HTML básico: &lt;p&gt;, &lt;h2&gt;, &lt;strong&gt;, &lt;ul&gt;…)</span></label>
              <textarea id="cuerpo" name="cuerpo" style="min-height:280px"><?= e($editing['cuerpo'] ?? '') ?></textarea>
            </div>
          </div>

          <div>
            <div class="card" style="margin-bottom:1.5rem">
              <div class="field">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                  <option value="publicada" <?= (($editing['estado'] ?? 'publicada') === 'publicada') ? 'selected' : '' ?>>Publicada</option>
                  <option value="borrador" <?= (($editing['estado'] ?? '') === 'borrador') ? 'selected' : '' ?>>Borrador</option>
                </select>
              </div>
              <div class="field">
                <label for="categoria">Categoría</label>
                <input type="text" id="categoria" name="categoria" list="cats" value="<?= e($editing['categoria'] ?? 'Envases') ?>">
                <datalist id="cats">
                  <?php foreach ($categoriasSug as $c): ?><option value="<?= e($c) ?>"><?php endforeach; ?>
                </datalist>
              </div>
              <div class="field" style="margin-bottom:0">
                <label style="display:flex; align-items:center; gap:.5rem; font-weight:600">
                  <input type="checkbox" name="destacada" value="1" style="width:auto" <?= ((int)($editing['destacada'] ?? 0) === 1) ? 'checked' : '' ?>>
                  Destacar en portada
                </label>
              </div>
            </div>

            <div class="card">
              <div class="field">
                <label for="imagen">Imagen</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" data-imgfield>
              </div>
              <div class="imgprev" data-imgpreview>
                <?php if (!empty($editing['imagen'])): ?>
                  <img src="<?= e(noticia_img_src($editing['imagen'])) ?>" alt="Imagen actual">
                <?php else: ?>
                  <div class="placeholder">Sin imagen — se usará una genérica</div>
                <?php endif; ?>
              </div>
              <?php if ($editing): ?>
                <input type="hidden" name="imagen_actual" value="<?= e($editing['imagen']) ?>">
              <?php endif; ?>
            </div>

            <button class="btn btn--primary" type="submit" style="width:100%; justify-content:center; margin-top:1.5rem">
              <?= $editing ? 'Guardar cambios' : 'Publicar noticia' ?>
            </button>
          </div>
        </div>
      </form>

    <?php else: /* demos */ ?>
      <?php $demos = demos_all(); ?>
      <div class="admin-topbar">
        <h1>Demo de la aplicación</h1>
        <a class="btn-sm" href="/scrap#demo" target="_blank">Ver en la web ↗</a>
      </div>

      <div class="editor-grid">
        <!-- Subir nueva demo -->
        <div class="card">
          <div class="card__head"><h2>Añadir vídeo o captura</h2></div>
          <form method="post" action="/admin-action" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="op" value="demo_create">
            <div class="field">
              <label for="d_titulo">Título</label>
              <input type="text" id="d_titulo" name="titulo" required maxlength="200" placeholder="Ej.: Panel de trazabilidad">
            </div>
            <div class="field">
              <label for="d_desc">Descripción <span style="color:var(--ink-muted);font-weight:400">(opcional)</span></label>
              <input type="text" id="d_desc" name="descripcion" maxlength="300" placeholder="Breve descripción de lo que se ve">
            </div>
            <div class="field">
              <label for="d_seccion">Sección</label>
              <select id="d_seccion" name="seccion">
                <?php foreach (DEMO_SECCIONES as $val => $lbl): ?>
                  <option value="<?= e($val) ?>"><?= e($lbl) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="field">
              <label for="d_tipo">Tipo</label>
              <select id="d_tipo" name="media_tipo">
                <option value="imagen">Captura (imagen)</option>
                <option value="video">Vídeo</option>
              </select>
            </div>
            <div class="field">
              <label for="d_archivo">Archivo <span style="color:var(--ink-muted);font-weight:400">(imagen JPG/PNG/WEBP/GIF o vídeo MP4/WEBM)</span></label>
              <input type="file" id="d_archivo" name="archivo" accept="image/*,video/mp4,video/webm,video/ogg" data-imgfield>
              <div class="imgprev" data-imgpreview style="margin-top:.75rem"><div class="placeholder">Sin archivo seleccionado</div></div>
            </div>
            <div class="field">
              <label for="d_url">…o URL de vídeo <span style="color:var(--ink-muted);font-weight:400">(YouTube, Vimeo o enlace directo)</span></label>
              <input type="url" id="d_url" name="url" placeholder="https://www.youtube.com/watch?v=…">
            </div>
            <button class="btn btn--primary" type="submit" style="width:100%; justify-content:center">Añadir a la demo</button>
            <p style="font-size:var(--step--1); color:var(--ink-muted); margin:.85rem 0 0">
              Nota: para vídeos pesados usa una URL de YouTube/Vimeo (la subida de archivos está limitada por la configuración de PHP).
            </p>
          </form>
        </div>

        <!-- Listado -->
        <div class="card">
          <div class="card__head"><h2>En la demo (<?= count($demos) ?>)</h2></div>
          <?php if (!$demos): ?>
            <div class="empty"><h3>Vacío</h3><p>Añade la primera captura o vídeo con el formulario.</p></div>
          <?php else: ?>
            <div class="table-wrap">
              <table class="data">
                <thead><tr><th></th><th>Título</th><th>Sección</th><th>Tipo</th><th></th></tr></thead>
                <tbody>
                  <?php foreach ($demos as $d): $m = demo_media($d); ?>
                    <tr>
                      <td>
                        <?php if ($m['kind'] === 'image'): ?>
                          <img class="thumb" src="<?= e($m['src']) ?>" alt="">
                        <?php else: ?>
                          <span class="thumb" style="display:grid;place-items:center;background:var(--paper-alt);color:var(--ink-muted)">▶</span>
                        <?php endif; ?>
                      </td>
                      <td><strong><?= e($d['titulo']) ?></strong></td>
                      <td><span class="tag"><?= e(DEMO_SECCIONES[$d['seccion']] ?? $d['seccion']) ?></span></td>
                      <td><span class="badge <?= $d['media_tipo']==='video'?'badge--draft':'badge--pub' ?>"><?= e($d['media_tipo']) ?></span></td>
                      <td>
                        <form method="post" action="/admin-action" onsubmit="return confirm('¿Eliminar este elemento de la demo?');" style="display:inline">
                          <?= csrf_field() ?>
                          <input type="hidden" name="op" value="demo_delete">
                          <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                          <button class="btn-sm danger" type="submit">Borrar</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </main>
</div>
<script src="/js/main.js" defer></script>
</body>
</html>
