<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    flash_set('login_error', 'Sesión expirada. Inténtalo de nuevo.');
    redirect('/acceso');
}
$correo = trim($_POST['correo'] ?? '');
$pass   = (string)($_POST['password'] ?? '');

$user = attempt_login($correo, $pass);
if (!$user) {
    flash_set('login_error', 'Correo o contraseña incorrectos.');
    flash_set('login_email', $correo);
    redirect('/acceso');
}
login_user($user);
redirect('/admin');
