<?php
/**
 * Conexión PDO fail-soft.
 *
 * Por defecto usa SQLite (fichero en /data/scrap.sqlite) — cero configuración.
 * Si en .env defines DB_DRIVER=mysql + credenciales, usa MySQL.
 *
 * Si la BBDD no responde, la web sigue funcionando:
 *  - db() devuelve null y cachea el estado
 *  - db_fetch / db_fetch_all / db_exec devuelven null / [] / 0 silenciosamente
 */
require_once __DIR__ . '/env.php';

function db(): ?PDO {
    static $pdo = null;
    static $tried = false;

    if ($pdo !== null) return $pdo;
    if ($tried) return null;
    $tried = true;

    env_load();
    $driver = strtolower(env('DB_DRIVER', 'sqlite'));

    try {
        if ($driver === 'mysql') {
            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', '3306');
            $name = env('DB_NAME');
            $user = env('DB_USER');
            $pass = env('DB_PASS', '');
            if (!$name || !$user) {
                throw new RuntimeException('Faltan DB_NAME / DB_USER para MySQL.');
            }
            $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_TIMEOUT            => 3,
            ]);
        } else {
            $path = env('DB_PATH', __DIR__ . '/../data/scrap.sqlite');
            $dir = dirname($path);
            if (!is_dir($dir)) @mkdir($dir, 0775, true);
            $pdo = new PDO('sqlite:' . $path, null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $pdo->exec('PRAGMA foreign_keys = ON');
        }
    } catch (Throwable $e) {
        $GLOBALS['__db_error'] = $e->getMessage();
        error_log('[BBDD] Conexión fallida: ' . $e->getMessage());
        return null;
    }

    return $pdo;
}

function db_driver(): string {
    return strtolower(env('DB_DRIVER', 'sqlite'));
}

function db_is_down(): bool {
    db();
    return !empty($GLOBALS['__db_error']) || db() === null;
}

function db_error(): ?string {
    return $GLOBALS['__db_error'] ?? null;
}

function db_fetch(string $sql, array $params = []): ?array {
    $pdo = db();
    if (!$pdo) return null;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    } catch (PDOException $e) {
        error_log('[BBDD] db_fetch: ' . $e->getMessage());
        return null;
    }
}

function db_fetch_all(string $sql, array $params = []): array {
    $pdo = db();
    if (!$pdo) return [];
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('[BBDD] db_fetch_all: ' . $e->getMessage());
        return [];
    }
}

function db_exec(string $sql, array $params = []): int {
    $pdo = db();
    if (!$pdo) return 0;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log('[BBDD] db_exec: ' . $e->getMessage());
        return 0;
    }
}

function db_last_id(): ?int {
    $pdo = db();
    if (!$pdo) return null;
    $id = $pdo->lastInsertId();
    return ($id !== false && $id !== '') ? (int)$id : null;
}
