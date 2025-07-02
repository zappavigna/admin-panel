<?php
// Get current section
$currentSection = isset($_GET['section']) ? $_GET['section'] : 'import';

// Include the appropriate section
switch($currentSection) {
    case 'import':
        include __DIR__ . '/libraries/import.php';
        break;
    case 'cdn-manager':
        include __DIR__ . '/libraries/cdn-manager.php';
        break;
    default:
        include __DIR__ . '/libraries/import.php';
}
?>