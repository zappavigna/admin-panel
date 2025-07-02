<?php
// Get current section
$currentSection = isset($_GET['section']) ? $_GET['section'] : 'htaccess';

// Include the appropriate section
switch($currentSection) {
    case 'htaccess':
        include __DIR__ . '/tools/htaccess.php';
        break;
    case 'regex':
        include __DIR__ . '/tools/regex.php';
        break;
    default:
        include __DIR__ . '/tools/htaccess.php';
}
?>