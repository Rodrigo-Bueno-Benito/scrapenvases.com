<?php
/**
 * Capa de datos de noticias.
 */
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

function noticias_publicadas(int $limit = 100, ?string $categoria = null): array {
    $sql = "SELECT * FROM noticias WHERE estado = 'publicada'";
    $params = [];
    if ($categoria) {
        $sql .= " AND categoria = ?";
        $params[] = $categoria;
    }
    $sql .= " ORDER BY fecha_publicacion DESC, id DESC LIMIT " . (int)$limit;
    return db_fetch_all($sql, $params);
}

function noticia_destacada(): ?array {
    $row = db_fetch("SELECT * FROM noticias WHERE estado='publicada' AND destacada=1 ORDER BY fecha_publicacion DESC LIMIT 1");
    if ($row) return $row;
    // Fallback: la más reciente
    return db_fetch("SELECT * FROM noticias WHERE estado='publicada' ORDER BY fecha_publicacion DESC LIMIT 1");
}

function noticia_by_slug(string $slug): ?array {
    return db_fetch("SELECT * FROM noticias WHERE slug = ? LIMIT 1", [$slug]);
}

function noticia_by_id(int $id): ?array {
    return db_fetch("SELECT * FROM noticias WHERE id = ? LIMIT 1", [$id]);
}

function noticias_all_admin(): array {
    return db_fetch_all("SELECT * FROM noticias ORDER BY fecha_publicacion DESC, id DESC");
}

function noticias_relacionadas(int $exceptId, string $categoria, int $limit = 3): array {
    return db_fetch_all(
        "SELECT * FROM noticias WHERE estado='publicada' AND id <> ? ORDER BY (categoria = ?) DESC, fecha_publicacion DESC LIMIT " . (int)$limit,
        [$exceptId, $categoria]
    );
}

function noticias_categorias(): array {
    $rows = db_fetch_all("SELECT DISTINCT categoria FROM noticias WHERE estado='publicada' ORDER BY categoria");
    return array_column($rows, 'categoria');
}

/** Slug único (añade sufijo -2, -3… si colisiona) */
function noticia_slug_unico(string $base, ?int $exceptId = null): string {
    $slug = slugify($base);
    $candidate = $slug;
    $i = 2;
    while (true) {
        $row = db_fetch(
            "SELECT id FROM noticias WHERE slug = ?" . ($exceptId ? " AND id <> ?" : "") . " LIMIT 1",
            $exceptId ? [$candidate, $exceptId] : [$candidate]
        );
        if (!$row) return $candidate;
        $candidate = $slug . '-' . $i++;
    }
}

function noticia_crear(array $d): ?int {
    $slug = noticia_slug_unico($d['titulo']);
    db_exec(
        "INSERT INTO noticias (titulo, slug, extracto, cuerpo, imagen, categoria, estado, destacada, autor_id)
         VALUES (?,?,?,?,?,?,?,?,?)",
        [
            $d['titulo'], $slug, $d['extracto'], $d['cuerpo'], $d['imagen'],
            $d['categoria'], $d['estado'], $d['destacada'], $d['autor_id'],
        ]
    );
    $id = db_last_id();
    if ($id && (int)$d['destacada'] === 1) noticia_destacar_exclusiva($id);
    return $id;
}

function noticia_actualizar(int $id, array $d): void {
    $slug = noticia_slug_unico($d['titulo'], $id);
    db_exec(
        "UPDATE noticias SET titulo=?, slug=?, extracto=?, cuerpo=?, imagen=?, categoria=?, estado=?, destacada=?, actualizado_en=CURRENT_TIMESTAMP
         WHERE id=?",
        [
            $d['titulo'], $slug, $d['extracto'], $d['cuerpo'], $d['imagen'],
            $d['categoria'], $d['estado'], $d['destacada'], $id,
        ]
    );
    if ((int)$d['destacada'] === 1) noticia_destacar_exclusiva($id);
}

/** Solo una noticia destacada a la vez */
function noticia_destacar_exclusiva(int $id): void {
    db_exec("UPDATE noticias SET destacada = 0 WHERE id <> ?", [$id]);
}

function noticia_borrar(int $id): void {
    db_exec("DELETE FROM noticias WHERE id = ?", [$id]);
}

function noticias_stats(): array {
    $total = db_fetch("SELECT COUNT(*) c FROM noticias");
    $pub   = db_fetch("SELECT COUNT(*) c FROM noticias WHERE estado='publicada'");
    $draft = db_fetch("SELECT COUNT(*) c FROM noticias WHERE estado='borrador'");
    return [
        'total'     => (int)($total['c'] ?? 0),
        'publicada' => (int)($pub['c'] ?? 0),
        'borrador'  => (int)($draft['c'] ?? 0),
    ];
}
