<?php
// Post Type Code Generator
header('Content-Type: text/plain; charset=utf-8');

// Get form data
$name = $_POST['postTypeName'] ?? '';
$namePlural = $_POST['postTypeNamePlural'] ?? '';
$slug = $_POST['postTypeSlug'] ?? '';
$icon = $_POST['postTypeIcon'] ?? 'dashicons-admin-post';
$supports = $_POST['supports'] ?? [];
$createCategory = isset($_POST['createCategory']);
$createTags = isset($_POST['createTags']);
$hierarchical = isset($_POST['hierarchical']);
$hasArchive = isset($_POST['hasArchive']);
$showInRest = isset($_POST['showInRest']);

// Build supports array
$supportsArray = [];
foreach ($supports as $support) {
    $supportsArray[] = "'$support'";
}
$supportsString = implode(', ', $supportsArray);

// Generate the code
$code = "<?php\n";
$code .= "/**\n";
$code .= " * Register Custom Post Type: $name\n";
$code .= " * \n";
$code .= " * @package WordPress\n";
$code .= " * @subpackage Your_Theme\n";
$code .= " * @since 1.0.0\n";
$code .= " */\n\n";

// Register Post Type Function
$functionName = str_replace('-', '_', $slug);
$code .= "// Register Custom Post Type\n";
$code .= "function register_{$functionName}_post_type() {\n";
$code .= "    \$labels = array(\n";
$code .= "        'name'                  => _x( '$namePlural', 'Post Type General Name', 'textdomain' ),\n";
$code .= "        'singular_name'         => _x( '$name', 'Post Type Singular Name', 'textdomain' ),\n";
$code .= "        'menu_name'             => __( '$namePlural', 'textdomain' ),\n";
$code .= "        'name_admin_bar'        => __( '$name', 'textdomain' ),\n";
$code .= "        'archives'              => __( 'Archivio $namePlural', 'textdomain' ),\n";
$code .= "        'attributes'            => __( 'Attributi $name', 'textdomain' ),\n";
$code .= "        'parent_item_colon'     => __( '$name Genitore:', 'textdomain' ),\n";
$code .= "        'all_items'             => __( 'Tutti i $namePlural', 'textdomain' ),\n";
$code .= "        'add_new_item'          => __( 'Aggiungi Nuovo $name', 'textdomain' ),\n";
$code .= "        'add_new'               => __( 'Aggiungi Nuovo', 'textdomain' ),\n";
$code .= "        'new_item'              => __( 'Nuovo $name', 'textdomain' ),\n";
$code .= "        'edit_item'             => __( 'Modifica $name', 'textdomain' ),\n";
$code .= "        'update_item'           => __( 'Aggiorna $name', 'textdomain' ),\n";
$code .= "        'view_item'             => __( 'Visualizza $name', 'textdomain' ),\n";
$code .= "        'view_items'            => __( 'Visualizza $namePlural', 'textdomain' ),\n";
$code .= "        'search_items'          => __( 'Cerca $name', 'textdomain' ),\n";
$code .= "        'not_found'             => __( 'Non trovato', 'textdomain' ),\n";
$code .= "        'not_found_in_trash'    => __( 'Non trovato nel cestino', 'textdomain' ),\n";
$code .= "        'featured_image'        => __( 'Immagine in evidenza', 'textdomain' ),\n";
$code .= "        'set_featured_image'    => __( 'Imposta immagine in evidenza', 'textdomain' ),\n";
$code .= "        'remove_featured_image' => __( 'Rimuovi immagine in evidenza', 'textdomain' ),\n";
$code .= "        'use_featured_image'    => __( 'Usa come immagine in evidenza', 'textdomain' ),\n";
$code .= "        'insert_into_item'      => __( 'Inserisci nel $name', 'textdomain' ),\n";
$code .= "        'uploaded_to_this_item' => __( 'Caricato in questo $name', 'textdomain' ),\n";
$code .= "        'items_list'            => __( 'Lista $namePlural', 'textdomain' ),\n";
$code .= "        'items_list_navigation' => __( 'Navigazione lista $namePlural', 'textdomain' ),\n";
$code .= "        'filter_items_list'     => __( 'Filtra lista $namePlural', 'textdomain' ),\n";
$code .= "    );\n";
$code .= "    \n";
$code .= "    \$args = array(\n";
$code .= "        'label'                 => __( '$name', 'textdomain' ),\n";
$code .= "        'description'           => __( 'Post Type $name', 'textdomain' ),\n";
$code .= "        'labels'                => \$labels,\n";
$code .= "        'supports'              => array( $supportsString ),\n";
$code .= "        'taxonomies'            => array(),\n";
$code .= "        'hierarchical'          => " . ($hierarchical ? 'true' : 'false') . ",\n";
$code .= "        'public'                => true,\n";
$code .= "        'show_ui'               => true,\n";
$code .= "        'show_in_menu'          => true,\n";
$code .= "        'menu_position'         => 5,\n";
$code .= "        'menu_icon'             => '$icon',\n";
$code .= "        'show_in_admin_bar'     => true,\n";
$code .= "        'show_in_nav_menus'     => true,\n";
$code .= "        'can_export'            => true,\n";
$code .= "        'has_archive'           => " . ($hasArchive ? 'true' : 'false') . ",\n";
$code .= "        'exclude_from_search'   => false,\n";
$code .= "        'publicly_queryable'    => true,\n";
$code .= "        'capability_type'       => 'post',\n";
$code .= "        'show_in_rest'          => " . ($showInRest ? 'true' : 'false') . ",\n";
$code .= "    );\n";
$code .= "    \n";
$code .= "    register_post_type( '$slug', \$args );\n";
$code .= "}\n";
$code .= "add_action( 'init', 'register_{$functionName}_post_type', 0 );\n";

// Add Category Taxonomy
if ($createCategory) {
    $code .= "\n// Register Custom Taxonomy - Categories\n";
    $code .= "function register_{$functionName}_categories() {\n";
    $code .= "    \$labels = array(\n";
    $code .= "        'name'                       => _x( 'Categorie $name', 'Taxonomy General Name', 'textdomain' ),\n";
    $code .= "        'singular_name'              => _x( 'Categoria $name', 'Taxonomy Singular Name', 'textdomain' ),\n";
    $code .= "        'menu_name'                  => __( 'Categorie', 'textdomain' ),\n";
    $code .= "        'all_items'                  => __( 'Tutte le Categorie', 'textdomain' ),\n";
    $code .= "        'parent_item'                => __( 'Categoria Genitore', 'textdomain' ),\n";
    $code .= "        'parent_item_colon'          => __( 'Categoria Genitore:', 'textdomain' ),\n";
    $code .= "        'new_item_name'              => __( 'Nome Nuova Categoria', 'textdomain' ),\n";
    $code .= "        'add_new_item'               => __( 'Aggiungi Nuova Categoria', 'textdomain' ),\n";
    $code .= "        'edit_item'                  => __( 'Modifica Categoria', 'textdomain' ),\n";
    $code .= "        'update_item'                => __( 'Aggiorna Categoria', 'textdomain' ),\n";
    $code .= "        'view_item'                  => __( 'Visualizza Categoria', 'textdomain' ),\n";
    $code .= "        'separate_items_with_commas' => __( 'Separa le categorie con virgole', 'textdomain' ),\n";
    $code .= "        'add_or_remove_items'        => __( 'Aggiungi o rimuovi categorie', 'textdomain' ),\n";
    $code .= "        'choose_from_most_used'      => __( 'Scegli tra le più usate', 'textdomain' ),\n";
    $code .= "        'popular_items'              => __( 'Categorie Popolari', 'textdomain' ),\n";
    $code .= "        'search_items'               => __( 'Cerca Categorie', 'textdomain' ),\n";
    $code .= "        'not_found'                  => __( 'Non Trovato', 'textdomain' ),\n";
    $code .= "        'no_terms'                   => __( 'Nessuna categoria', 'textdomain' ),\n";
    $code .= "        'items_list'                 => __( 'Lista categorie', 'textdomain' ),\n";
    $code .= "        'items_list_navigation'      => __( 'Navigazione lista categorie', 'textdomain' ),\n";
    $code .= "    );\n";
    $code .= "    \n";
    $code .= "    \$args = array(\n";
    $code .= "        'labels'                     => \$labels,\n";
    $code .= "        'hierarchical'               => true,\n";
    $code .= "        'public'                     => true,\n";
    $code .= "        'show_ui'                    => true,\n";
    $code .= "        'show_admin_column'          => true,\n";
    $code .= "        'show_in_nav_menus'          => true,\n";
    $code .= "        'show_tagcloud'              => true,\n";
    $code .= "        'show_in_rest'               => " . ($showInRest ? 'true' : 'false') . ",\n";
    $code .= "    );\n";
    $code .= "    \n";
    $code .= "    register_taxonomy( '{$slug}_category', array( '$slug' ), \$args );\n";
    $code .= "}\n";
    $code .= "add_action( 'init', 'register_{$functionName}_categories', 0 );\n";
}

// Add Tags Taxonomy
if ($createTags) {
    $code .= "\n// Register Custom Taxonomy - Tags\n";
    $code .= "function register_{$functionName}_tags() {\n";
    $code .= "    \$labels = array(\n";
    $code .= "        'name'                       => _x( 'Tag $name', 'Taxonomy General Name', 'textdomain' ),\n";
    $code .= "        'singular_name'              => _x( 'Tag $name', 'Taxonomy Singular Name', 'textdomain' ),\n";
    $code .= "        'menu_name'                  => __( 'Tag', 'textdomain' ),\n";
    $code .= "        'all_items'                  => __( 'Tutti i Tag', 'textdomain' ),\n";
    $code .= "        'parent_item'                => __( 'Tag Genitore', 'textdomain' ),\n";
    $code .= "        'parent_item_colon'          => __( 'Tag Genitore:', 'textdomain' ),\n";
    $code .= "        'new_item_name'              => __( 'Nome Nuovo Tag', 'textdomain' ),\n";
    $code .= "        'add_new_item'               => __( 'Aggiungi Nuovo Tag', 'textdomain' ),\n";
    $code .= "        'edit_item'                  => __( 'Modifica Tag', 'textdomain' ),\n";
    $code .= "        'update_item'                => __( 'Aggiorna Tag', 'textdomain' ),\n";
    $code .= "        'view_item'                  => __( 'Visualizza Tag', 'textdomain' ),\n";
    $code .= "        'separate_items_with_commas' => __( 'Separa i tag con virgole', 'textdomain' ),\n";
    $code .= "        'add_or_remove_items'        => __( 'Aggiungi o rimuovi tag', 'textdomain' ),\n";
    $code .= "        'choose_from_most_used'      => __( 'Scegli tra i più usati', 'textdomain' ),\n";
    $code .= "        'popular_items'              => __( 'Tag Popolari', 'textdomain' ),\n";
    $code .= "        'search_items'               => __( 'Cerca Tag', 'textdomain' ),\n";
    $code .= "        'not_found'                  => __( 'Non Trovato', 'textdomain' ),\n";
    $code .= "        'no_terms'                   => __( 'Nessun tag', 'textdomain' ),\n";
    $code .= "        'items_list'                 => __( 'Lista tag', 'textdomain' ),\n";
    $code .= "        'items_list_navigation'      => __( 'Navigazione lista tag', 'textdomain' ),\n";
    $code .= "    );\n";
    $code .= "    \n";
    $code .= "    \$args = array(\n";
    $code .= "        'labels'                     => \$labels,\n";
    $code .= "        'hierarchical'               => false,\n";
    $code .= "        'public'                     => true,\n";
    $code .= "        'show_ui'                    => true,\n";
    $code .= "        'show_admin_column'          => true,\n";
    $code .= "        'show_in_nav_menus'          => true,\n";
    $code .= "        'show_tagcloud'              => true,\n";
    $code .= "        'show_in_rest'               => " . ($showInRest ? 'true' : 'false') . ",\n";
    $code .= "    );\n";
    $code .= "    \n";
    $code .= "    register_taxonomy( '{$slug}_tag', array( '$slug' ), \$args );\n";
    $code .= "}\n";
    $code .= "add_action( 'init', 'register_{$functionName}_tags', 0 );\n";
}

// Add flush rewrite rules
$code .= "\n// Flush rewrite rules on activation\n";
$code .= "function {$functionName}_rewrite_flush() {\n";
$code .= "    register_{$functionName}_post_type();\n";
if ($createCategory) {
    $code .= "    register_{$functionName}_categories();\n";
}
if ($createTags) {
    $code .= "    register_{$functionName}_tags();\n";
}
$code .= "    flush_rewrite_rules();\n";
$code .= "}\n";
$code .= "register_activation_hook( __FILE__, '{$functionName}_rewrite_flush' );\n";

// Output the generated code
echo $code;