<?php
// Get current section
$currentSection = isset($_GET['section']) ? $_GET['section'] : 'meta-tags';

// Include the appropriate section
switch($currentSection) {
    case 'meta-tags':
        include __DIR__ . '/seo/meta-tags.php';
        break;
    case 'schema':
        include __DIR__ . '/seo/schema.php';
        break;
    default:
        include __DIR__ . '/seo/meta-tags.php';
}
?>