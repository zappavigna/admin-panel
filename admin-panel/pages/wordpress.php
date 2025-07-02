<?php
// Get current section
$currentSection = isset($_GET['section']) ? $_GET['section'] : 'post-type';

// Include the appropriate section
switch($currentSection) {
    case 'post-type':
        include __DIR__ . '/wordpress/post-type.php';
        break;
    case 'metabox':
        include __DIR__ . '/wordpress/metabox.php';
        break;
    case 'custom-code':
        include __DIR__ . '/wordpress/custom-code.php';
        break;
    default:
        include __DIR__ . '/wordpress/post-type.php';
}
?>