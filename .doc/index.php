<?php
/**
 * DocViewer - Sistema de lectura para documentación
 * Desarrollado para el proyecto php-start
 */

require_once __DIR__ . '/Parsedown.php';

$docDir = __DIR__;

/**
 * Escanea recursivamente el directorio en busca de archivos .md y .html
 */
function getDocFiles($dir, $baseDir = '') {
    $results = [];
    $files = scandir($dir);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === 'index.php' || $file === 'Parsedown.php') continue;

        $path = $dir . DIRECTORY_SEPARATOR . $file;
        $relativePath = $baseDir ? $baseDir . '/' . $file : $file;

        if (is_dir($path)) {
            $results = array_merge($results, getDocFiles($path, $relativePath));
        } else {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($ext, ['md', 'html'])) {
                $results[] = $relativePath;
            }
        }
    }
    return $results;
}

$allowedFiles = getDocFiles($docDir);

// Ordenar alfabéticamente (Natural y sin distinguir mayúsculas)
natcasesort($allowedFiles);
$allowedFiles = array_values($allowedFiles);

// Obtener archivo actual
$currentFile = $_GET['file'] ?? (count($allowedFiles) > 0 ? $allowedFiles[0] : null);

// Validar que el archivo exista y esté en la lista permitida para evitar Path Traversal
if ($currentFile && !in_array($currentFile, $allowedFiles)) {
    die("Archivo no permitido.");
}

$content = "";
$title = "Documentación";

if ($currentFile) {
    $filePath = $docDir . '/' . $currentFile;
    $rawContent = file_get_contents($filePath);
    $extension = pathinfo($currentFile, PATHINFO_EXTENSION);
    $title = pathinfo($currentFile, PATHINFO_FILENAME);

    if ($extension === 'md') {
        $parsedown = new Parsedown();
        $content = $parsedown->text($rawContent);
    } else {
        $content = $rawContent;
    }
} else {
    $content = "<div class='alert alert-info'>No hay archivos de documentación disponibles.</div>";
}
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | DocViewer</title>
    
    <!-- Bootstrap 5 CSS (Custom PiruUI) -->
    <link href="../static/assets/css/piruui.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="../static/assets/css/fontawesome.css">
    <!-- PrismJS (Usamos el local del proyecto) -->
    <link rel="stylesheet" href="../static/plugins/prismjs/prismjs.css">

    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #f05;
            --bg-color: #000;
            --card-bg: #000;
        }

        body {
            background-color: var(--bg-color);
            color: #e2e8f0;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--card-bg);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1.5rem;
        }

        .sidebar-header h5 {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--primary-color);
        }

        .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #f8fafc;
        }

        .nav-link.active {
            background: var(--primary-color);
            color: white !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        /* Main Content Styles */
        main {
            margin-left: var(--sidebar-width);
            padding: 3rem;
            min-height: 100vh;
        }

        .content-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Documentation Rendering Styles */
        .doc-content h1 { font-weight: 800; margin-bottom: 1.5rem; border-bottom: 2px solid rgba(255,255,255,0.1); padding-bottom: 0.5rem; }
        .doc-content h2 { font-weight: 700; margin-top: 2.5rem; margin-bottom: 1rem; }
        .doc-content h3 { font-weight: 600; margin-top: 2rem; }
        .doc-content p { line-height: 1.7; color: #cbd5e1; }
        .doc-content code { 
            font-family: 'Fira Code', monospace; 
            background: rgba(0,0,0,0.3); 
            padding: 0.2rem 0.4rem; 
            border-radius: 4px; 
            font-size: 0.9em;
            color: #fb7185;
        }
        .doc-content pre { margin: 1.5rem 0; }
        .doc-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding: 1rem 1.5rem;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 0 8px 8px 0;
            font-style: italic;
        }
        .doc-content table {
            width: 100%;
            margin-bottom: 1rem;
            color: #e2e8f0;
            border-collapse: collapse;
        }
        .doc-content th, .doc-content td {
            padding: 0.75rem;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .doc-content th { background: rgba(255,255,255,0.05); }

        .btn-back {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1001;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
        }

        /* Scrollbar track */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-color); }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        @media (max-width: 992px) {
            #sidebar { transform: translateX(-100%); transition: 0.3s; }
            #sidebar.show { transform: translateX(0); }
            main { margin-left: 0; padding: 1.5rem; }
            .mobile-toggle { display: block; position: fixed; top: 1rem; left: 1rem; z-index: 1100; }
        }
        @media (min-width: 993px) {
            .mobile-toggle { display: none; }
        }
    </style>
</head>
<body>

    <button class="btn btn-primary mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
    </button>

    <aside id="sidebar">
        <div class="sidebar-header">
            <h5 class="mb-0"><i class="fa-solid fa-file-code me-2"></i>DocViewer</h5>
            <small class="text-muted">v1.0 - php-start</small>
        </div>
        
        <nav class="nav flex-column">
            <p class="text-uppercase small fw-bold text-muted mt-2 mb-2 px-3" style="font-size: 0.7rem;">Archivos</p>
            <?php foreach ($allowedFiles as $file): 
                $active = ($file === $currentFile) ? 'active' : '';
                $icon = (pathinfo($file, PATHINFO_EXTENSION) === 'md') ? 'fa-brands fa-markdown' : 'fa-solid fa-code';
            ?>
                <a class="nav-link <?= $active ?>" href="?file=<?= urlencode($file) ?>">
                    <i class="<?= $icon ?>"></i>
                    <?= htmlspecialchars(pathinfo($file, PATHINFO_FILENAME)) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <main>
        <div class="container-fluid">
            <div class="content-card">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Docs</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($currentFile) ?></li>
                    </ol>
                </nav>

                <div class="doc-content">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </main>

    <a href="../panel/dashboard" class="btn btn-outline-light btn-back">
        <i class="fa-solid fa-arrow-left me-2"></i>Volver al Panel
    </a>

    <!-- PrismJS Script -->
    <script src="../static/plugins/prismjs/prismjs.js"></script>
    <!-- Bootstrap JS (Custom PiruUI) -->
    <script src="../static/assets/js/piruui.js"></script>

</body>
</html>
