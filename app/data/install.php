<?php
/**
 * Instalador idempotente. Crea las tablas si no existen y siembra
 * un usuario admin + noticias de ejemplo. Compatible SQLite y MySQL.
 *
 * Solo toca la BBDD cuando el centinela de esquema no está al día, así
 * una visita normal no ejecuta ningún CREATE ni ALTER.
 */
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

/** Subir este número cuando cambie el esquema */
const SCHEMA_VERSION = 2;

function schema_sentinel_path(): string {
    return __DIR__ . '/../../data/.schema-v' . SCHEMA_VERSION;
}

function db_install(): void {
    if (is_file(schema_sentinel_path())) return;

    $pdo = db();
    if (!$pdo) return;

    $isSqlite = db_driver() !== 'mysql';
    $pk  = $isSqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';
    $now = $isSqlite ? "DATETIME DEFAULT CURRENT_TIMESTAMP" : "TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
    $eng = $isSqlite ? '' : ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
            id $pk,
            nombre VARCHAR(120) NOT NULL,
            correo VARCHAR(190) NOT NULL UNIQUE,
            contrasena VARCHAR(255) NOT NULL,
            rol INT NOT NULL DEFAULT 2,
            creado_en $now
        )$eng");

        $pdo->exec("CREATE TABLE IF NOT EXISTS noticias (
            id $pk,
            titulo VARCHAR(220) NOT NULL,
            slug VARCHAR(240) NOT NULL,
            extracto TEXT NULL,
            cuerpo " . ($isSqlite ? "TEXT" : "MEDIUMTEXT") . " NULL,
            imagen VARCHAR(255) NULL,
            categoria VARCHAR(80) NOT NULL DEFAULT 'Envases',
            estado VARCHAR(20) NOT NULL DEFAULT 'publicada',
            destacada INT NOT NULL DEFAULT 0,
            autor_id INT NULL,
            fecha_publicacion $now,
            actualizado_en $now
        )$eng");

        $pdo->exec("CREATE TABLE IF NOT EXISTS demos (
            id $pk,
            seccion VARCHAR(20) NOT NULL DEFAULT 'scrap',
            titulo VARCHAR(200) NOT NULL,
            descripcion TEXT NULL,
            media_tipo VARCHAR(12) NOT NULL DEFAULT 'imagen',
            archivo VARCHAR(255) NULL,
            url VARCHAR(500) NULL,
            orden INT NOT NULL DEFAULT 0,
            creado_en $now
        )$eng");

        // Consultas de la Oficina Técnica de SCRAPs (OTS)
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

        /* --- Migraciones e índices: solo al subir de versión de esquema --- */

        // 'seccion' en instalaciones previas a la separación de demos por sección
        try { $pdo->exec("ALTER TABLE demos ADD COLUMN seccion VARCHAR(20) NOT NULL DEFAULT 'scrap'"); } catch (Throwable $e) { /* ya existe */ }

        // El slug identifica la noticia en la URL: único en BBDD, no solo por
        // la comprobación previa de noticia_slug_unico().
        $ine = $isSqlite ? 'IF NOT EXISTS ' : '';
        try { $pdo->exec("CREATE UNIQUE INDEX {$ine}ux_noticias_slug ON noticias (slug)"); } catch (Throwable $e) { /* ya existe */ }
        try { $pdo->exec("CREATE INDEX {$ine}ix_noticias_listado ON noticias (estado, fecha_publicacion)"); } catch (Throwable $e) { /* ya existe */ }
        try { $pdo->exec("CREATE INDEX {$ine}ix_consultas_estado ON consultas (estado, creado_en)"); } catch (Throwable $e) { /* ya existe */ }
    } catch (Throwable $e) {
        error_log('[INSTALL] ' . $e->getMessage());
        return;
    }

    // Semilla admin
    $count = db_fetch("SELECT COUNT(*) AS c FROM usuarios");
    if ($count && (int)$count['c'] === 0) {
        $hash = password_hash('admin1234', PASSWORD_BCRYPT);
        db_exec("INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?,?,?,?)",
            ['Administrador', 'admin@scrapenvases.com', $hash, ROL_ADMIN]);
    }

    // Semilla noticias
    $ncount = db_fetch("SELECT COUNT(*) AS c FROM noticias");
    if ($ncount && (int)$ncount['c'] === 0) {
        db_seed_noticias();
    }

    // Centinela: a partir de aquí las visitas ya no tocan el esquema.
    // Si no se puede escribir, el instalador vuelve a correr (es idempotente).
    $sentinel = schema_sentinel_path();
    $dir = dirname($sentinel);
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    @file_put_contents($sentinel, 'esquema v' . SCHEMA_VERSION . ' — ' . date('c') . "\n");
    foreach (glob($dir . '/.schema-v*') ?: [] as $old) {
        if ($old !== $sentinel) @unlink($old);
    }
}

function db_seed_noticias(): void {
    $seed = [
        [
            'titulo' => 'La RAP de envases comerciales e industriales entra en vigor: qué cambia para las empresas',
            'categoria' => 'Normativa',
            'destacada' => 1,
            'imagen' => 'noticia-rap-empresas.jpg',
            'extracto' => 'El Real Decreto 1055/2022 extiende la Responsabilidad Ampliada del Productor a los envases comerciales e industriales. Repasamos las obligaciones clave y los plazos.',
            'cuerpo' => "<p>La entrada en vigor de la <strong>Responsabilidad Ampliada del Productor (RAP)</strong> para envases comerciales e industriales marca un antes y un después en la gestión de residuos en España.</p><p>Los productores de producto deben registrarse en el RPP del MITECO, adherirse a un SCRAP o constituir un SIRAP, y declarar anualmente los envases puestos en el mercado.</p><h2>Plazos que no puedes perder</h2><p>La adhesión a un SCRAP o la creación de un SIRAP debía completarse antes del 31/12/2024. A partir de ahí, la declaración anual y la contribución financiera a la gestión pasan a ser obligatorias.</p><p>Desde ScrapEnvases acompañamos a productores, gestores y poseedores en la adaptación técnica y operativa a este nuevo modelo.</p>",
        ],
        [
            'titulo' => 'Trazabilidad digital: por qué el papel ya no basta en la gestión de residuos de envases',
            'categoria' => 'Digitalización',
            'destacada' => 0,
            'imagen' => 'noticia-trazabilidad-digital.jpg',
            'extracto' => 'La nueva normativa exige un nivel de trazabilidad que solo es viable con herramientas digitales. Analizamos el papel de plataformas como SCRAPP.',
            'cuerpo' => "<p>El reporting normativo y la coordinación entre SCRAP, gestores y poseedores generan un volumen de información imposible de manejar en hojas de cálculo.</p><p>Las plataformas de <strong>trazabilidad inteligente</strong> permiten centralizar documentación, automatizar la coordinación entre agentes y garantizar el cumplimiento legal en tiempo real.</p>",
        ],
        [
            'titulo' => 'Homologación de proveedores: el nuevo estándar de confianza en la cadena de residuos',
            'categoria' => 'Gestión',
            'destacada' => 0,
            'imagen' => 'noticia-homologacion-almacen.jpg',
            'extracto' => 'Evaluar, homologar y auditar proveedores de forma homogénea deja de ser opcional. La plataforma PROBATUS estandariza el proceso.',
            'cuerpo' => "<p>En un modelo de responsabilidad colectiva, la fiabilidad de cada eslabón de la cadena es crítica.</p><p>La <strong>homologación de proveedores</strong> permite comprobar que los gestores cumplen con los estándares técnicos, legales y ambientales requeridos, de forma transparente y trazable.</p>",
        ],
        [
            'titulo' => 'Códigos LER de la familia 15: guía rápida para clasificar residuos de envases',
            'categoria' => 'Envases',
            'destacada' => 0,
            'imagen' => 'noticia-ler-contenedores.jpg',
            'extracto' => 'Papel, plástico, madera, metal, vidrio, textiles… Repasamos los códigos LER 15 01 y 15 02 y cuáles se consideran peligrosos.',
            'cuerpo' => "<p>La correcta clasificación de los residuos de envases según el <strong>Listado Europeo de Residuos (LER)</strong> es el punto de partida de toda gestión conforme.</p><p>La familia 15 agrupa los envases y los materiales absorbentes, trapos y filtros. Los códigos marcados con asterisco (por ejemplo, 15 01 10*) corresponden a residuos peligrosos por contener restos de sustancias peligrosas.</p>",
        ],
    ];

    foreach ($seed as $n) {
        $slug = slugify($n['titulo']);
        db_exec(
            "INSERT INTO noticias (titulo, slug, extracto, cuerpo, imagen, categoria, estado, destacada, autor_id)
             VALUES (?,?,?,?,?,?,?,?,?)",
            [$n['titulo'], $slug, $n['extracto'], $n['cuerpo'], $n['imagen'], $n['categoria'], 'publicada', $n['destacada'], 1]
        );
    }
}
