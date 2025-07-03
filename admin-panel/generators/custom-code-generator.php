<?php
// Custom Code Generator
header('Content-Type: text/plain; charset=utf-8');

// Get form data
$location = $_POST['codeLocation'] ?? 'theme';
$codeType = $_POST['codeType'] ?? 'css';
$description = $_POST['codeDescription'] ?? '';
$loadCondition = $_POST['loadCondition'] ?? 'always';
$customCSS = $_POST['customCSS'] ?? '';
$customJS = $_POST['customJS'] ?? '';
$minify = isset($_POST['minify']);
$cacheBust = isset($_POST['cacheBust']);
$priority = isset($_POST['priority']);

// Generate unique function names
$uniqueId = substr(md5($description . time()), 0, 8);

// Start generating code
$code = "<?php\n";
$code .= "/**\n";
$code .= " * Custom Code" . ($description ? ": $description" : "") . "\n";
$code .= " * \n";
$code .= " * @package WordPress\n";
$code .= " * @subpackage " . ($location === 'plugin' ? 'Custom_Plugin' : 'Your_Theme') . "\n";
$code .= " */\n\n";

// Generate condition check function
$code .= "// Check if code should be loaded\n";
$code .= "function should_load_custom_code_{$uniqueId}() {\n";

switch ($loadCondition) {
    case 'frontend':
        $code .= "    return ! is_admin();\n";
        break;
    case 'backend':
        $code .= "    return is_admin();\n";
        break;
    case 'homepage':
        $code .= "    return is_front_page() || is_home();\n";
        break;
    case 'single':
        $code .= "    return is_single();\n";
        break;
    case 'page':
        $code .= "    return is_page();\n";
        break;
    case 'archive':
        $code .= "    return is_archive();\n";
        break;
    default:
        $code .= "    return true;\n";
}

$code .= "}\n\n";

// Generate based on location
if ($location === 'theme') {
    // Theme functions.php
    if ($codeType === 'css' || $codeType === 'both') {
        $code .= "// Add Custom CSS\n";
        $code .= "function custom_css_{$uniqueId}() {\n";
        $code .= "    if ( ! should_load_custom_code_{$uniqueId}() ) {\n";
        $code .= "        return;\n";
        $code .= "    }\n";
        $code .= "    ?>\n";
        $code .= "    <style type=\"text/css\">\n";
        if ($minify) {
            // Simple minification
            $minifiedCSS = preg_replace('/\s+/', ' ', $customCSS);
            $minifiedCSS = str_replace(array("\r\n", "\r", "\n", "\t"), '', $minifiedCSS);
            $code .= "        " . trim($minifiedCSS) . "\n";
        } else {
            $code .= "        " . str_replace("\n", "\n        ", trim($customCSS)) . "\n";
        }
        $code .= "    </style>\n";
        $code .= "    <?php\n";
        $code .= "}\n";
        $code .= "add_action( 'wp_head', 'custom_css_{$uniqueId}'" . ($priority ? ", 5" : "") . " );\n\n";
    }
    
    if ($codeType === 'js' || $codeType === 'both') {
        $code .= "// Add Custom JavaScript\n";
        $code .= "function custom_js_{$uniqueId}() {\n";
        $code .= "    if ( ! should_load_custom_code_{$uniqueId}() ) {\n";
        $code .= "        return;\n";
        $code .= "    }\n";
        $code .= "    ?>\n";
        $code .= "    <script type=\"text/javascript\">\n";
        if ($minify) {
            // Simple minification
            $minifiedJS = preg_replace('/\s+/', ' ', $customJS);
            $minifiedJS = preg_replace('/\/\*.*?\*\//', '', $minifiedJS);
            $code .= "        " . trim($minifiedJS) . "\n";
        } else {
            $code .= "        " . str_replace("\n", "\n        ", trim($customJS)) . "\n";
        }
        $code .= "    </script>\n";
        $code .= "    <?php\n";
        $code .= "}\n";
        $code .= "add_action( 'wp_footer', 'custom_js_{$uniqueId}'" . ($priority ? ", 5" : "") . " );\n";
    }
    
} elseif ($location === 'child-theme') {
    // Child theme
    if ($codeType === 'css' || $codeType === 'both') {
        $code .= "// Enqueue Custom CSS\n";
        $code .= "function child_theme_custom_styles_{$uniqueId}() {\n";
        $code .= "    if ( ! should_load_custom_code_{$uniqueId}() ) {\n";
        $code .= "        return;\n";
        $code .= "    }\n";
        $code .= "    \n";
        $code .= "    wp_enqueue_style( \n";
        $code .= "        'child-custom-style-{$uniqueId}', \n";
        $code .= "        get_stylesheet_directory_uri() . '/assets/css/custom-{$uniqueId}.css', \n";
        $code .= "        array(), \n";
        $code .= "        " . ($cacheBust ? "filemtime( get_stylesheet_directory() . '/assets/css/custom-{$uniqueId}.css' )" : "'1.0.0'") . "\n";
        $code .= "    );\n";
        $code .= "}\n";
        $code .= "add_action( 'wp_enqueue_scripts', 'child_theme_custom_styles_{$uniqueId}'" . ($priority ? ", 5" : "") . " );\n\n";
        
        $code .= "/*\n";
        $code .= " * Crea il file: /assets/css/custom-{$uniqueId}.css\n";
        $code .= " * con il seguente contenuto:\n";
        $code .= " */\n\n";
        $code .= "/*\n";
        $code .= $customCSS . "\n";
        $code .= "*/\n\n";
    }
    
    if ($codeType === 'js' || $codeType === 'both') {
        $code .= "// Enqueue Custom JavaScript\n";
        $code .= "function child_theme_custom_scripts_{$uniqueId}() {\n";
        $code .= "    if ( ! should_load_custom_code_{$uniqueId}() ) {\n";
        $code .= "        return;\n";
        $code .= "    }\n";
        $code .= "    \n";
        $code .= "    wp_enqueue_script( \n";
        $code .= "        'child-custom-script-{$uniqueId}', \n";
        $code .= "        get_stylesheet_directory_uri() . '/assets/js/custom-{$uniqueId}.js', \n";
        $code .= "        array('jquery'), \n";
        $code .= "        " . ($cacheBust ? "filemtime( get_stylesheet_directory() . '/assets/js/custom-{$uniqueId}.js' )" : "'1.0.0'") . ", \n";
        $code .= "        true \n";
        $code .= "    );\n";
        $code .= "}\n";
        $code .= "add_action( 'wp_enqueue_scripts', 'child_theme_custom_scripts_{$uniqueId}'" . ($priority ? ", 5" : "") . " );\n\n";
        
        $code .= "/*\n";
        $code .= " * Crea il file: /assets/js/custom-{$uniqueId}.js\n";
        $code .= " * con il seguente contenuto:\n";
        $code .= " */\n\n";
        $code .= "/*\n";
        $code .= $customJS . "\n";
        $code .= "*/\n";
    }
    
} elseif ($location === 'plugin') {
    // Plugin
    $pluginName = $description ?: 'Custom Code Plugin';
    $pluginSlug = sanitize_title($pluginName);
    
    $code = "<?php\n";
    $code .= "/**\n";
    $code .= " * Plugin Name: $pluginName\n";
    $code .= " * Plugin URI: https://your-website.com/\n";
    $code .= " * Description: Plugin personalizzato per aggiungere codice custom\n";
    $code .= " * Version: 1.0.0\n";
    $code .= " * Author: Il tuo nome\n";
    $code .= " * Author URI: https://your-website.com/\n";
    $code .= " * License: GPL v2 or later\n";
    $code .= " * Text Domain: {$pluginSlug}\n";
    $code .= " */\n\n";
    
    $code .= "// Prevent direct access\n";
    $code .= "if ( ! defined( 'ABSPATH' ) ) {\n";
    $code .= "    exit;\n";
    $code .= "}\n\n";
    
    $code .= "// Define plugin constants\n";
    $code .= "define( '" . strtoupper(str_replace('-', '_', $pluginSlug)) . "_VERSION', '1.0.0' );\n";
    $code .= "define( '" . strtoupper(str_replace('-', '_', $pluginSlug)) . "_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );\n";
    $code .= "define( '" . strtoupper(str_replace('-', '_', $pluginSlug)) . "_PLUGIN_URL', plugin_dir_url( __FILE__ ) );\n\n";
    
    // Add condition check
    $code .= "// Check if code should be loaded\n";
    $code .= "function {$pluginSlug}_should_load() {\n";
    
    switch ($loadCondition) {
        case 'frontend':
            $code .= "    return ! is_admin();\n";
            break;
        case 'backend':
            $code .= "    return is_admin();\n";
            break;
        case 'homepage':
            $code .= "    return is_front_page() || is_home();\n";
            break;
        case 'single':
            $code .= "    return is_single();\n";
            break;
        case 'page':
            $code .= "    return is_page();\n";
            break;
        case 'archive':
            $code .= "    return is_archive();\n";
            break;
        default:
            $code .= "    return true;\n";
    }
    
    $code .= "}\n\n";
    
    if ($codeType === 'css' || $codeType === 'both') {
        $code .= "// Enqueue Custom CSS\n";
        $code .= "function {$pluginSlug}_enqueue_styles() {\n";
        $code .= "    if ( ! {$pluginSlug}_should_load() ) {\n";
        $code .= "        return;\n";
        $code .= "    }\n";
        $code .= "    \n";
        $code .= "    wp_enqueue_style( \n";
        $code .= "        '{$pluginSlug}-style', \n";
        $code .= "        " . strtoupper(str_replace('-', '_', $pluginSlug)) . "_PLUGIN_URL . 'assets/css/style.css', \n";
        $code .= "        array(), \n";
        $code .= "        " . ($cacheBust ? strtoupper(str_replace('-', '_', $pluginSlug)) . "_VERSION" : "'1.0.0'") . "\n";
        $code .= "    );\n";
        $code .= "}\n";
        $code .= "add_action( 'wp_enqueue_scripts', '{$pluginSlug}_enqueue_styles'" . ($priority ? ", 5" : "") . " );\n\n";
        
        $code .= "/*\n";
        $code .= " * Crea il file: /assets/css/style.css\n";
        $code .= " * nella directory del plugin con il seguente contenuto:\n";
        $code .= " */\n\n";
        $code .= "/*\n";
        $code .= $customCSS . "\n";
        $code .= "*/\n\n";
    }
    
    if ($codeType === 'js' || $codeType === 'both') {
        $code .= "// Enqueue Custom JavaScript\n";
        $code .= "function {$pluginSlug}_enqueue_scripts() {\n";
        $code .= "    if ( ! {$pluginSlug}_should_load() ) {\n";
        $code .= "        return;\n";
        $code .= "    }\n";
        $code .= "    \n";
        $code .= "    wp_enqueue_script( \n";
        $code .= "        '{$pluginSlug}-script', \n";
        $code .= "        " . strtoupper(str_replace('-', '_', $pluginSlug)) . "_PLUGIN_URL . 'assets/js/script.js', \n";
        $code .= "        array('jquery'), \n";
        $code .= "        " . ($cacheBust ? strtoupper(str_replace('-', '_', $pluginSlug)) . "_VERSION" : "'1.0.0'") . ", \n";
        $code .= "        true \n";
        $code .= "    );\n";
        $code .= "}\n";
        $code .= "add_action( 'wp_enqueue_scripts', '{$pluginSlug}_enqueue_scripts'" . ($priority ? ", 5" : "") . " );\n\n";
        
        $code .= "/*\n";
        $code .= " * Crea il file: /assets/js/script.js\n";
        $code .= " * nella directory del plugin con il seguente contenuto:\n";
        $code .= " */\n\n";
        $code .= "/*\n";
        $code .= $customJS . "\n";
        $code .= "*/\n";
    }
    
} elseif ($location === 'mu-plugin') {
    // Must-Use Plugin
    $pluginName = $description ?: 'Custom Code MU Plugin';
    $pluginSlug = sanitize_title($pluginName);
    
    $code = "<?php\n";
    $code .= "/**\n";
    $code .= " * Plugin Name: $pluginName\n";
    $code .= " * Description: Must-Use plugin per codice custom\n";
    $code .= " * Version: 1.0.0\n";
    $code .= " * \n";
    $code .= " * Questo file va inserito in: /wp-content/mu-plugins/{$pluginSlug}.php\n";
    $code .= " */\n\n";
    
    $code .= "// Prevent direct access\n";
    $code .= "if ( ! defined( 'ABSPATH' ) ) {\n";
    $code .= "    exit;\n";
    $code .= "}\n\n";
    
    // Generate inline code for MU plugin
    if ($codeType === 'css' || $codeType === 'both') {
        $code .= "// Add Custom CSS\n";
        $code .= "add_action( 'wp_head', function() {\n";
        
        // Add condition check
        switch ($loadCondition) {
            case 'frontend':
                $code .= "    if ( is_admin() ) return;\n";
                break;
            case 'backend':
                $code .= "    if ( ! is_admin() ) return;\n";
                break;
            case 'homepage':
                $code .= "    if ( ! is_front_page() && ! is_home() ) return;\n";
                break;
            case 'single':
                $code .= "    if ( ! is_single() ) return;\n";
                break;
            case 'page':
                $code .= "    if ( ! is_page() ) return;\n";
                break;
            case 'archive':
                $code .= "    if ( ! is_archive() ) return;\n";
                break;
        }
        
        $code .= "    ?>\n";
        $code .= "    <style type=\"text/css\">\n";
        $code .= "        " . str_replace("\n", "\n        ", trim($customCSS)) . "\n";
        $code .= "    </style>\n";
        $code .= "    <?php\n";
        $code .= "}" . ($priority ? ", 5" : "") . " );\n\n";
    }
    
    if ($codeType === 'js' || $codeType === 'both') {
        $code .= "// Add Custom JavaScript\n";
        $code .= "add_action( 'wp_footer', function() {\n";
        
        // Add condition check
        switch ($loadCondition) {
            case 'frontend':
                $code .= "    if ( is_admin() ) return;\n";
                break;
            case 'backend':
                $code .= "    if ( ! is_admin() ) return;\n";
                break;
            case 'homepage':
                $code .= "    if ( ! is_front_page() && ! is_home() ) return;\n";
                break;
            case 'single':
                $code .= "    if ( ! is_single() ) return;\n";
                break;
            case 'page':
                $code .= "    if ( ! is_page() ) return;\n";
                break;
            case 'archive':
                $code .= "    if ( ! is_archive() ) return;\n";
                break;
        }
        
        $code .= "    ?>\n";
        $code .= "    <script type=\"text/javascript\">\n";
        $code .= "        " . str_replace("\n", "\n        ", trim($customJS)) . "\n";
        $code .= "    </script>\n";
        $code .= "    <?php\n";
        $code .= "}" . ($priority ? ", 5" : "") . " );\n";
    }
}

$code .= "\n?>";

// Helper function to sanitize title
function sanitize_title($title) {
    $title = strtolower($title);
    $title = preg_replace('/[^a-z0-9]+/', '-', $title);
    $title = trim($title, '-');
    return $title;
}

// Output the generated code
echo $code;