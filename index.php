<?php
session_start();

// Configurazione base
define('SITE_TITLE', 'Admin Panel - Developer Tools');
define('BASE_URL', '/admin-panel/'); // Modifica secondo il tuo percorso

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Funzione per includere componenti
function includeComponent($component) {
    $file = __DIR__ . '/components/' . $component . '.php';
    if (file_exists($file)) {
        include $file;
    } else {
        echo "Component not found: " . $component;
    }
}

// Funzione per includere pagine
function includePage($page) {
    $file = __DIR__ . '/pages/' . $page . '.php';
    if (file_exists($file)) {
        include $file;
    } else {
        include __DIR__ . '/pages/dashboard.php';
    }
}

// Ottieni la pagina corrente
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$section = isset($_GET['section']) ? $_GET['section'] : '';

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_TITLE; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Dashicons per WordPress -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dashicons/0.9.0/css/dashicons.min.css" rel="stylesheet">
    <!-- Prism CSS for code highlighting -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Mobile Toggle -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <?php includeComponent('sidebar'); ?>

    <!-- Main Content -->
    <main class="main-content">
        <?php includePage($page); ?>
    </main>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-clike.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>