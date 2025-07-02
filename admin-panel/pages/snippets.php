<?php
// Get current section
$currentSection = isset($_GET['section']) ? $_GET['section'] : 'wordpress';

// Include the appropriate section
switch($currentSection) {
    case 'wordpress':
        include __DIR__ . '/snippets/wordpress.php';
        break;
    case 'woocommerce':
        include __DIR__ . '/snippets/woocommerce.php';
        break;
    case 'security':
        include __DIR__ . '/snippets/security.php';
        break;
    case 'performance':
        include __DIR__ . '/snippets/performance.php';
        break;
    default:
        include __DIR__ . '/snippets/wordpress.php';
}
?>