<?php
/**
 * Conexión PDO. SQLite por defecto (data/scrap.sqlite); MySQL si DB_DRIVER=mysql.
 * Si la base de datos no responde, el portal sigue sirviendo páginas:
 * las funciones devuelven null / [] / 0 y el error queda en el log.
 */
require_once __DIR__ . '/env.php';

const SCHEMA_VERSION = 3;

function db(): ?PDO {
    static $pdo = null, $tried = false;
    if ($pdo !== null || $tried) return $pdo;
    $tried = true;

    $opts = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        if (db_driver() === 'mysql') {
            $name = env('DB_NAME');
            $user = env('DB_USER');
            if (!$name || !$user) throw new RuntimeException('Faltan DB_NAME / DB_USER para MySQL.');
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                env('DB_HOST', '127.0.0.1'), env('DB_PORT', '3306'), $name);
            $pdo = new PDO($dsn, $user, env('DB_PASS', ''), $opts + [PDO::ATTR_TIMEOUT => 3]);
        } else {
            $path = env('DB_PATH', __DIR__ . '/../data/scrap.sqlite');
            if (!is_dir(dirname($path))) @mkdir(dirname($path), 0775, true);
            $pdo = new PDO('sqlite:' . $path, null, null, $opts);
        }
    } catch (Throwable $e) {
        error_log('[BBDD] Conexión fallida: ' . $e->getMessage());
        return $pdo = null;
    }

    db_install($pdo);
    return $pdo;
}

function db_driver(): string {
    return strtolower(env('DB_DRIVER', 'sqlite'));
}

/** Crea las tablas la primera vez. Un fichero centinela evita repetirlo en cada visita. */
function db_install(PDO $pdo): void {
    $sentinel = __DIR__ . '/../data/.schema-v' . SCHEMA_VERSION;
    if (is_file($sentinel)) return;

    $sqlite = db_driver() !== 'mysql';
    $pk  = $sqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';
    $now = $sqlite ? 'DATETIME DEFAULT CURRENT_TIMESTAMP' : 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP';
    $eng = $sqlite ? '' : ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
            id $pk,
            nombre VARCHAR(120) NOT NULL,
            correo VARCHAR(190) NOT NULL UNIQUE,
            contrasena VARCHAR(255) NOT NULL,
            rol INT NOT NULL DEFAULT 1,
            creado_en $now
        )$eng");

        $pdo->exec("CREATE TABLE IF NOT EXISTS consultas (
            id $pk,
            nombre VARCHAR(160) NOT NULL,
            empresa VARCHAR(190) NOT NULL,
            email VARCHAR(190) NOT NULL,
            telefono VARCHAR(40) NULL,
            perfil VARCHAR(20) NOT NULL DEFAULT 'otro',
            mensaje TEXT NOT NULL,
            rgpd INT NOT NULL DEFAULT 0,
            rgpd_en VARCHAR(40) NULL,
            ip VARCHAR(60) NULL,
            estado VARCHAR(20) NOT NULL DEFAULT 'nueva',
            creado_en $now
        )$eng");

        // Primer usuario del panel: solo desde .env, nunca con una contraseña conocida.
        $email = env('ADMIN_EMAIL');
        $pass  = env('ADMIN_PASSWORD');
        $n = (int)$pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
        if ($n === 0 && $email && $pass) {
            $pdo->prepare('INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?,?,?,1)')
                ->execute(['Oficina Técnica', strtolower($email), password_hash($pass, PASSWORD_DEFAULT)]);
        }
    } catch (Throwable $e) {
        error_log('[BBDD] Instalación: ' . $e->getMessage());
        return;
    }

    @file_put_contents($sentinel, 'esquema v' . SCHEMA_VERSION . ' — ' . date('c') . "\n");
    foreach (glob(dirname($sentinel) . '/.schema-v*') ?: [] as $old) {
        if ($old !== $sentinel) @unlink($old);
    }
}

function db_query(string $sql, array $params = []): ?PDOStatement {
    $pdo = db();
    if (!$pdo) return null;
    try {
        $st = $pdo->prepare($sql);
        $st->execute($params);
        return $st;
    } catch (PDOException $e) {
        error_log('[BBDD] ' . $e->getMessage());
        return null;
    }
}

function db_fetch(string $sql, array $params = []): ?array {
    $row = db_query($sql, $params)?->fetch();
    return $row ?: null;
}

function db_fetch_all(string $sql, array $params = []): array {
    return db_query($sql, $params)?->fetchAll() ?? [];
}

function db_exec(string $sql, array $params = []): int {
    return db_query($sql, $params)?->rowCount() ?? 0;
}
