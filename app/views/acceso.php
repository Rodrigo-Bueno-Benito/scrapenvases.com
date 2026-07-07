<?php
// Si ya está logado, al panel
if (is_logged_in()) { redirect('/admin'); }
$error = flash_get('login_error');
$old = flash_get('login_email', '');
?>
<div class="auth-wrap wrap">
  <div class="auth-card" data-reveal>
    <span class="kicker">Área privada</span>
    <h1>Acceso al panel</h1>
    <p class="sub">Introduce tus credenciales para gestionar las noticias.</p>

    <?php if ($error): ?>
      <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="/auth-login">
      <?= csrf_field() ?>
      <div class="field">
        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" value="<?= e($old) ?>" required autofocus autocomplete="username">
      </div>
      <div class="field">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">
      </div>
      <button class="btn btn--primary" type="submit" style="width:100%; justify-content:center">Entrar</button>
    </form>
    <p class="auth-hint">Acceso restringido al equipo de ScrapEnvases.</p>
  </div>
</div>
