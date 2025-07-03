<?php
// WordPress Ready Snippets
$snippets = [
    [
        'id' => 'custom-login-logo',
        'title' => 'Logo Personalizzato Login',
        'description' => 'Sostituisce il logo WordPress nella pagina di login',
        'category' => 'admin',
        'icon' => 'fas fa-sign-in-alt',
        'code' => '// Custom Login Logo
function custom_login_logo() {
    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png);
            height: 100px;
            width: 300px;
            background-size: contain;
            background-repeat: no-repeat;
            padding-bottom: 10px;
        }
    </style>
    <?php
}
add_action( \'login_enqueue_scripts\', \'custom_login_logo\' );

// Change login logo URL
function custom_login_logo_url() {
    return home_url();
}
add_filter( \'login_headerurl\', \'custom_login_logo_url\' );

// Change login logo title
function custom_login_logo_url_title() {
    return get_bloginfo( \'name\' );
}
add_filter( \'login_headertext\', \'custom_login_logo_url_title\' );'
    ],
    [
        'id' => 'disable-admin-bar',
        'title' => 'Disabilita Admin Bar',
        'description' => 'Nasconde la barra admin per utenti non amministratori',
        'category' => 'admin',
        'icon' => 'fas fa-eye-slash',
        'code' => '// Disable admin bar for non-admins
function disable_admin_bar_for_non_admins() {
    if ( ! current_user_can( \'manage_options\' ) ) {
        show_admin_bar( false );
    }
}
add_action( \'after_setup_theme\', \'disable_admin_bar_for_non_admins\' );'
    ],
    [
        'id' => 'custom-excerpt-length',
        'title' => 'Lunghezza Excerpt Personalizzata',
        'description' => 'Modifica la lunghezza dell\'excerpt dei post',
        'category' => 'content',
        'icon' => 'fas fa-text-height',
        'code' => '// Custom excerpt length
function custom_excerpt_length( $length ) {
    return 30; // Numero di parole
}
add_filter( \'excerpt_length\', \'custom_excerpt_length\', 999 );

// Custom excerpt more
function custom_excerpt_more( $more ) {
    return \'...\';
}
add_filter( \'excerpt_more\', \'custom_excerpt_more\' );'
    ],
    [
        'id' => 'breadcrumbs',
        'title' => 'Breadcrumbs',
        'description' => 'Funzione per generare breadcrumbs',
        'category' => 'navigation',
        'icon' => 'fas fa-angle-right',
        'code' => '// Breadcrumbs function
function custom_breadcrumbs() {
    $separator = \' &raquo; \';
    $home = \'Home\';
    $before = \'<span class="current">\';
    $after = \'</span>\';
    
    if ( ! is_home() && ! is_front_page() || is_paged() ) {
        echo \'<div class="breadcrumbs">\';
        
        global $post;
        $homeLink = home_url();
        echo \'<a href="\' . $homeLink . \'">\' . $home . \'</a> \' . $separator . \' \';
        
        if ( is_category() ) {
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category( $thisCat );
            $parentCat = get_category( $thisCat->parent );
            
            if ( $thisCat->parent != 0 ) {
                echo get_category_parents( $parentCat, TRUE, \' \' . $separator . \' \' );
            }
            
            echo $before . single_cat_title( \'\', false ) . $after;
            
        } elseif ( is_day() ) {
            echo \'<a href="\' . get_year_link( get_the_time( \'Y\' ) ) . \'">\' . get_the_time( \'Y\' ) . \'</a> \' . $separator . \' \';
            echo \'<a href="\' . get_month_link( get_the_time( \'Y\' ), get_the_time( \'m\' ) ) . \'">\' . get_the_time( \'F\' ) . \'</a> \' . $separator . \' \';
            echo $before . get_the_time( \'d\' ) . $after;
            
        } elseif ( is_month() ) {
            echo \'<a href="\' . get_year_link( get_the_time( \'Y\' ) ) . \'">\' . get_the_time( \'Y\' ) . \'</a> \' . $separator . \' \';
            echo $before . get_the_time( \'F\' ) . $after;
            
        } elseif ( is_year() ) {
            echo $before . get_the_time( \'Y\' ) . $after;
            
        } elseif ( is_single() && ! is_attachment() ) {
            if ( get_post_type() != \'post\' ) {
                $post_type = get_post_type_object( get_post_type() );
                $slug = $post_type->rewrite;
                echo \'<a href="\' . $homeLink . \'/\' . $slug[\'slug\'] . \'/\">\' . $post_type->labels->singular_name . \'</a> \' . $separator . \' \';
                echo $before . get_the_title() . $after;
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                echo get_category_parents( $cat, TRUE, \' \' . $separator . \' \' );
                echo $before . get_the_title() . $after;
            }
            
        } elseif ( ! is_single() && ! is_page() && get_post_type() != \'post\' && ! is_404() ) {
            $post_type = get_post_type_object( get_post_type() );
            echo $before . $post_type->labels->singular_name . $after;
            
        } elseif ( is_attachment() ) {
            $parent = get_post( $post->post_parent );
            $cat = get_the_category( $parent->ID );
            $cat = $cat[0];
            echo get_category_parents( $cat, TRUE, \' \' . $separator . \' \' );
            echo \'<a href="\' . get_permalink( $parent ) . \'">\' . $parent->post_title . \'</a> \' . $separator . \' \';
            echo $before . get_the_title() . $after;
            
        } elseif ( is_page() && ! $post->post_parent ) {
            echo $before . get_the_title() . $after;
            
        } elseif ( is_page() && $post->post_parent ) {
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            
            while ( $parent_id ) {
                $page = get_page( $parent_id );
                $breadcrumbs[] = \'<a href="\' . get_permalink( $page->ID ) . \'">\' . get_the_title( $page->ID ) . \'</a>\';
                $parent_id = $page->post_parent;
            }
            
            $breadcrumbs = array_reverse( $breadcrumbs );
            
            foreach ( $breadcrumbs as $crumb ) {
                echo $crumb . \' \' . $separator . \' \';
            }
            
            echo $before . get_the_title() . $after;
            
        } elseif ( is_search() ) {
            echo $before . \'Risultati di ricerca per "\' . get_search_query() . \'"\' . $after;
            
        } elseif ( is_tag() ) {
            echo $before . \'Tag: \' . single_tag_title( \'\', false ) . $after;
            
        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata( $author );
            echo $before . \'Articoli di \' . $userdata->display_name . $after;
            
        } elseif ( is_404() ) {
            echo $before . \'Errore 404\' . $after;
        }
        
        if ( get_query_var( \'paged\' ) ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
                echo \' (\';
            }
            echo \'Pagina \' . get_query_var( \'paged\' );
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
                echo \')\';
            }
        }
        
        echo \'</div>\';
    }
}

// Usage: <?php custom_breadcrumbs(); ?>'
    ],
    [
        'id' => 'pagination',
        'title' => 'Paginazione Numerica',
        'description' => 'Paginazione numerica personalizzata',
        'category' => 'navigation',
        'icon' => 'fas fa-ellipsis-h',
        'code' => '// Numeric pagination
function custom_pagination() {
    global $wp_query;
    $big = 999999999;
    
    $pages = paginate_links( array(
        \'base\' => str_replace( $big, \'%#%\', esc_url( get_pagenum_link( $big ) ) ),
        \'format\' => \'?paged=%#%\',
        \'current\' => max( 1, get_query_var( \'paged\' ) ),
        \'total\' => $wp_query->max_num_pages,
        \'type\' => \'array\',
        \'prev_next\' => true,
        \'prev_text\' => __(\'&laquo; Precedente\'),
        \'next_text\' => __(\'Successivo &raquo;\'),
    ) );
    
    if ( is_array( $pages ) ) {
        $pagination = \'<div class="pagination">\';
        foreach ( $pages as $page ) {
            $pagination .= $page;
        }
        $pagination .= \'</div>\';
        echo $pagination;
    }
}'
    ],
    [
        'id' => 'enable-post-thumbnails',
        'title' => 'Abilita Immagini in Evidenza',
        'description' => 'Aggiunge il supporto per le immagini in evidenza (featured images)',
        'category' => 'theme-setup',
        'icon' => 'fas fa-image',
        'code' => '// Enable post thumbnails
function theme_setup_features() {
    add_theme_support( \'post-thumbnails\' );
    
    // Add custom image sizes
    add_image_size( \'custom-thumb\', 300, 200, true );
    add_image_size( \'custom-medium\', 600, 400, true );
    add_image_size( \'custom-large\', 1200, 800, true );
}
add_action( \'after_setup_theme\', \'theme_setup_features\' );'
    ],
    [
        'id' => 'register-nav-menu',
        'title' => 'Registra Menu di Navigazione',
        'description' => 'Registra una nuova posizione per i menu',
        'category' => 'theme-setup',
        'icon' => 'fas fa-bars',
        'code' => '// Register navigation menus
function register_custom_menus() {
    register_nav_menus(
        array(
            \'main-menu\' => __( \'Menu Principale\' ),
            \'footer-menu\' => __( \'Menu Footer\' ),
            \'mobile-menu\' => __( \'Menu Mobile\' )
        )
    );
}
add_action( \'init\', \'register_custom_menus\' );

// Usage in theme: <?php wp_nav_menu( array( \'theme_location\' => \'main-menu\' ) ); ?>'
    ],
    [
        'id' => 'remove-wp-version',
        'title' => 'Rimuovi Versione WordPress',
        'description' => 'Rimuove il meta tag con la versione di WordPress per sicurezza',
        'category' => 'security',
        'icon' => 'fas fa-shield-alt',
        'code' => '// Remove WordPress version number
function remove_wp_version_number() {
    return \'\';
}
add_filter(\'the_generator\', \'remove_wp_version_number\');

// Remove version from scripts and styles
function remove_version_from_style_js( $src ) {
    if ( strpos( $src, \'ver=\' . get_bloginfo( \'version\' ) ) ) {
        $src = remove_query_arg( \'ver\', $src );
    }
    return $src;
}
add_filter( \'style_loader_src\', \'remove_version_from_style_js\' );
add_filter( \'script_loader_src\', \'remove_version_from_style_js\' );'
    ],
    [
        'id' => 'custom-dashboard-widget',
        'title' => 'Widget Dashboard Personalizzato',
        'description' => 'Aggiunge un widget personalizzato alla dashboard',
        'category' => 'admin',
        'icon' => 'fas fa-tachometer-alt',
        'code' => '// Add custom dashboard widget
function add_custom_dashboard_widget() {
    wp_add_dashboard_widget(
        \'custom_dashboard_widget\',
        \'Informazioni Personalizzate\',
        \'custom_dashboard_widget_content\'
    );
}
add_action( \'wp_dashboard_setup\', \'add_custom_dashboard_widget\' );

function custom_dashboard_widget_content() {
    echo \'<p>Benvenuto nella dashboard!</p>\';
    echo \'<ul>\';
    echo \'<li>Totale post: \' . wp_count_posts()->publish . \'</li>\';
    echo \'<li>Totale pagine: \' . wp_count_posts(\'page\')->publish . \'</li>\';
    echo \'<li>Totale commenti: \' . wp_count_comments()->approved . \'</li>\';
    echo \'</ul>\';
}'
    ],
    [
        'id' => 'custom-search-form',
        'title' => 'Form di Ricerca Personalizzato',
        'description' => 'Crea un form di ricerca custom',
        'category' => 'forms',
        'icon' => 'fas fa-search',
        'code' => '// Custom search form
function custom_search_form( $form ) {
    $form = \'<form role="search" method="get" class="search-form" action="\' . home_url( \'/\' ) . \'">\';
    $form .= \'<div class="search-wrapper">\';
    $form .= \'<label class="screen-reader-text" for="s">\' . __( \'Search for:\' ) . \'</label>\';
    $form .= \'<input type="search" class="search-field" placeholder="\' . esc_attr__( \'Cerca...\' ) . \'" value="\' . get_search_query() . \'" name="s" />\';
    $form .= \'<button type="submit" class="search-submit">\';
    $form .= \'<i class="fas fa-search"></i>\';
    $form .= \'<span class="screen-reader-text">\' . __( \'Search\' ) . \'</span>\';
    $form .= \'</button>\';
    $form .= \'</div>\';
    $form .= \'</form>\';
    
    return $form;
}
add_filter( \'get_search_form\', \'custom_search_form\' );'
    ],
    [
        'id' => 'disable-comments',
        'title' => 'Disabilita Commenti',
        'description' => 'Disabilita completamente i commenti su tutto il sito',
        'category' => 'admin',
        'icon' => 'fas fa-comment-slash',
        'code' => '// Disable comments completely
function disable_comments_post_types_support() {
    $post_types = get_post_types();
    foreach ( $post_types as $post_type ) {
        if ( post_type_supports( $post_type, \'comments\' ) ) {
            remove_post_type_support( $post_type, \'comments\' );
            remove_post_type_support( $post_type, \'trackbacks\' );
        }
    }
}
add_action( \'admin_init\', \'disable_comments_post_types_support\' );

// Close comments on the front-end
function disable_comments_status() {
    return false;
}
add_filter( \'comments_open\', \'disable_comments_status\', 20, 2 );
add_filter( \'pings_open\', \'disable_comments_status\', 20, 2 );

// Hide existing comments
function disable_comments_hide_existing_comments( $comments ) {
    $comments = array();
    return $comments;
}
add_filter( \'comments_array\', \'disable_comments_hide_existing_comments\', 10, 2 );

// Remove comments page in menu
function disable_comments_admin_menu() {
    remove_menu_page( \'edit-comments.php\' );
}
add_action( \'admin_menu\', \'disable_comments_admin_menu\' );

// Redirect any user trying to access comments page
function disable_comments_admin_menu_redirect() {
    global $pagenow;
    if ( $pagenow === \'edit-comments.php\' ) {
        wp_redirect( admin_url() );
        exit;
    }
}
add_action( \'admin_init\', \'disable_comments_admin_menu_redirect\' );'
    ],
    [
        'id' => 'custom-admin-footer',
        'title' => 'Footer Admin Personalizzato',
        'description' => 'Modifica il testo del footer nell\'area admin',
        'category' => 'admin',
        'icon' => 'fas fa-edit',
        'code' => '// Custom admin footer text
function custom_admin_footer_text() {
    echo \'Sviluppato da <a href="https://tuosito.com">Il Tuo Nome</a> | \';
    echo \'Powered by <a href="https://wordpress.org">WordPress</a>\';
}
add_filter( \'admin_footer_text\', \'custom_admin_footer_text\' );

// Custom admin footer version
function custom_admin_footer_version() {
    return \'Versione personalizzata 1.0\';
}
add_filter( \'update_footer\', \'custom_admin_footer_version\', 11 );'
    ],
    [
        'id' => 'redirect-after-login',
        'title' => 'Redirect Dopo Login',
        'description' => 'Reindirizza gli utenti dopo il login in base al ruolo',
        'category' => 'users',
        'icon' => 'fas fa-directions',
        'code' => '// Redirect after login based on user role
function custom_login_redirect( $redirect_to, $request, $user ) {
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        // Redirect administrators to the dashboard
        if ( in_array( \'administrator\', $user->roles ) ) {
            return admin_url();
        }
        // Redirect editors to posts page
        elseif ( in_array( \'editor\', $user->roles ) ) {
            return admin_url( \'edit.php\' );
        }
        // Redirect subscribers to home page
        else {
            return home_url();
        }
    }
    return $redirect_to;
}
add_filter( \'login_redirect\', \'custom_login_redirect\', 10, 3 );'
    ],
    [
        'id' => 'custom-post-states',
        'title' => 'Stati Post Personalizzati',
        'description' => 'Aggiunge stati personalizzati nella lista post',
        'category' => 'content',
        'icon' => 'fas fa-tag',
        'code' => '// Add custom post states
function add_custom_post_states( $states, $post ) {
    // Mark sticky posts
    if ( is_sticky( $post->ID ) ) {
        $states[\'sticky\'] = __(\'In Evidenza\');
    }
    
    // Mark posts with no content
    if ( empty( $post->post_content ) ) {
        $states[\'no-content\'] = __(\'Nessun contenuto\');
    }
    
    // Mark scheduled posts
    if ( $post->post_status == \'future\' ) {
        $states[\'scheduled\'] = __(\'Programmato\');
    }
    
    // Custom meta example
    if ( get_post_meta( $post->ID, \'featured\', true ) == \'yes\' ) {
        $states[\'featured\'] = __(\'Featured\');
    }
    
    return $states;
}
add_filter( \'display_post_states\', \'add_custom_post_states\', 10, 2 );'
    ],
    [
        'id' => 'ajax-load-more',
        'title' => 'Ajax Load More Posts',
        'description' => 'Carica più post con Ajax',
        'category' => 'ajax',
        'icon' => 'fas fa-sync',
        'code' => '// Ajax load more posts
function load_more_posts() {
    $paged = $_POST[\'page\'] + 1;
    $query_args = array(
        \'post_type\' => \'post\',
        \'posts_per_page\' => 6,
        \'paged\' => $paged
    );
    
    $query = new WP_Query( $query_args );
    
    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();
            // Your post template here
            get_template_part( \'template-parts/content\', get_post_type() );
        endwhile;
    endif;
    
    wp_die();
}
add_action( \'wp_ajax_load_more_posts\', \'load_more_posts\' );
add_action( \'wp_ajax_nopriv_load_more_posts\', \'load_more_posts\' );

// JavaScript for the button
/*
jQuery(function($) {
    var page = 1;
    $(\'#load-more\').on(\'click\', function() {
        var button = $(this);
        $.ajax({
            url: ajax_object.ajax_url,
            type: \'post\',
            data: {
                action: \'load_more_posts\',
                page: page
            },
            beforeSend: function() {
                button.text(\'Loading...\');
            },
            success: function(response) {
                $(\'#posts-container\').append(response);
                page++;
                button.text(\'Load More\');
            }
        });
    });
});
*/'
    ],
    [
        'id' => 'custom-user-fields',
        'title' => 'Campi Utente Personalizzati',
        'description' => 'Aggiunge campi personalizzati al profilo utente',
        'category' => 'users',
        'icon' => 'fas fa-user-plus',
        'code' => '// Add custom user fields
function add_custom_user_fields( $user ) {
    ?>
    <h3>Informazioni Extra</h3>
    <table class="form-table">
        <tr>
            <th><label for="phone">Telefono</label></th>
            <td>
                <input type="text" name="phone" id="phone" value="<?php echo esc_attr( get_the_author_meta( \'phone\', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="company">Azienda</label></th>
            <td>
                <input type="text" name="company" id="company" value="<?php echo esc_attr( get_the_author_meta( \'company\', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="facebook">Facebook URL</label></th>
            <td>
                <input type="url" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( \'facebook\', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <?php
}
add_action( \'show_user_profile\', \'add_custom_user_fields\' );
add_action( \'edit_user_profile\', \'add_custom_user_fields\' );

// Save custom user fields
function save_custom_user_fields( $user_id ) {
    if ( ! current_user_can( \'edit_user\', $user_id ) ) {
        return false;
    }
    
    update_user_meta( $user_id, \'phone\', $_POST[\'phone\'] );
    update_user_meta( $user_id, \'company\', $_POST[\'company\'] );
    update_user_meta( $user_id, \'facebook\', $_POST[\'facebook\'] );
}
add_action( \'personal_options_update\', \'save_custom_user_fields\' );
add_action( \'edit_user_profile_update\', \'save_custom_user_fields\' );'
    ],
    [
        'id' => 'custom-rss-footer',
        'title' => 'Footer RSS Personalizzato',
        'description' => 'Aggiunge contenuto personalizzato ai feed RSS',
        'category' => 'content',
        'icon' => 'fas fa-rss',
        'code' => '// Add custom content to RSS feeds
function add_custom_rss_footer( $content ) {
    if ( is_feed() ) {
        $content .= \'<hr />\';
        $content .= \'<p>Questo articolo è stato pubblicato su <a href="\' . get_bloginfo(\'url\') . \'">\' . get_bloginfo(\'name\') . \'</a></p>\';
        $content .= \'<p>Leggi l\\\'articolo completo: <a href="\' . get_permalink() . \'">\' . get_the_title() . \'</a></p>\';
    }
    return $content;
}
add_filter( \'the_content_feed\', \'add_custom_rss_footer\' );'
    ],
    [
        'id' => 'maintenance-mode',
        'title' => 'Modalità Manutenzione',
        'description' => 'Attiva la modalità manutenzione per utenti non loggati',
        'category' => 'utilities',
        'icon' => 'fas fa-tools',
        'code' => '// Enable maintenance mode
function enable_maintenance_mode() {
    if ( ! current_user_can( \'edit_themes\' ) || ! is_user_logged_in() ) {
        wp_die( 
            \'<h1>Sito in Manutenzione</h1>
            <p>Stiamo effettuando alcuni aggiornamenti. Torneremo online a breve!</p>\',
            \'Manutenzione in Corso\',
            array( \'response\' => 503 )
        );
    }
}
// Uncomment to activate
// add_action( \'get_header\', \'enable_maintenance_mode\' );'
    ],
    [
        'id' => 'auto-set-featured-image',
        'title' => 'Immagine in Evidenza Automatica',
        'description' => 'Imposta automaticamente la prima immagine come featured',
        'category' => 'media',
        'icon' => 'fas fa-image',
        'code' => '// Auto set featured image from first image in post
function auto_set_featured_image() {
    global $post;
    
    if ( ! has_post_thumbnail( $post->ID ) ) {
        $attached_image = get_children( array(
            \'post_parent\' => $post->ID,
            \'post_type\' => \'attachment\',
            \'post_mime_type\' => \'image\',
            \'numberposts\' => 1
        ) );
        
        if ( $attached_image ) {
            foreach ( $attached_image as $attachment_id => $attachment ) {
                set_post_thumbnail( $post->ID, $attachment_id );
            }
        }
    }
}
add_action( \'save_post\', \'auto_set_featured_image\' );
add_action( \'draft_to_publish\', \'auto_set_featured_image\' );
add_action( \'new_to_publish\', \'auto_set_featured_image\' );
add_action( \'pending_to_publish\', \'auto_set_featured_image\' );
add_action( \'future_to_publish\', \'auto_set_featured_image\' );'
    ],
    [
        'id' => 'duplicate-post',
        'title' => 'Duplica Post/Pagina',
        'description' => 'Aggiunge la funzione per duplicare post e pagine',
        'category' => 'admin',
        'icon' => 'fas fa-copy',
        'code' => '// Add duplicate post/page function
function duplicate_post_as_draft() {
    global $wpdb;
    
    if ( ! ( isset( $_GET[\'post\'] ) || isset( $_POST[\'post\'] ) || ( isset( $_REQUEST[\'action\'] ) && \'duplicate_post_as_draft\' == $_REQUEST[\'action\'] ) ) ) {
        wp_die( \'No post to duplicate has been supplied!\' );
    }
    
    $post_id = ( isset( $_GET[\'post\'] ) ? absint( $_GET[\'post\'] ) : absint( $_POST[\'post\'] ) );
    $post = get_post( $post_id );
    
    if ( isset( $post ) && $post != null ) {
        $args = array(
            \'comment_status\' => $post->comment_status,
            \'ping_status\' => $post->ping_status,
            \'post_author\' => get_current_user_id(),
            \'post_content\' => $post->post_content,
            \'post_excerpt\' => $post->post_excerpt,
            \'post_name\' => $post->post_name,
            \'post_parent\' => $post->post_parent,
            \'post_password\' => $post->post_password,
            \'post_status\' => \'draft\',
            \'post_title\' => $post->post_title . \' (Copia)\',
            \'post_type\' => $post->post_type,
            \'to_ping\' => $post->to_ping,
            \'menu_order\' => $post->menu_order
        );
        
        $new_post_id = wp_insert_post( $args );
        
        // Copy post metadata
        $post_meta = get_post_meta( $post_id );
        foreach ( $post_meta as $key => $values ) {
            foreach ( $values as $value ) {
                add_post_meta( $new_post_id, $key, maybe_unserialize( $value ) );
            }
        }
        
        // Copy post taxonomies
        $taxonomies = get_object_taxonomies( $post->post_type );
        foreach ( $taxonomies as $taxonomy ) {
            $post_terms = wp_get_object_terms( $post_id, $taxonomy, array( \'fields\' => \'slugs\' ) );
            wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
        }
        
        // Redirect to the edit screen for the new draft
        wp_redirect( admin_url( \'post.php?action=edit&post=\' . $new_post_id ) );
        exit;
    } else {
        wp_die( \'Post creation failed, could not find original post: \' . $post_id );
    }
}
add_action( \'admin_action_duplicate_post_as_draft\', \'duplicate_post_as_draft\' );

// Add duplicate link to post row actions
function duplicate_post_link( $actions, $post ) {
    if ( current_user_can( \'edit_posts\' ) ) {
        $actions[\'duplicate\'] = \'<a href="\' . wp_nonce_url( \'admin.php?action=duplicate_post_as_draft&post=\' . $post->ID, basename( __FILE__ ), \'duplicate_nonce\' ) . \'" title="Duplica questo elemento" rel="permalink">Duplica</a>\';
    }
    return $actions;
}
add_filter( \'post_row_actions\', \'duplicate_post_link\', 10, 2 );
add_filter( \'page_row_actions\', \'duplicate_post_link\', 10, 2 );'
    ]
];

// Categories
$categories = [
    'all' => 'Tutti',
    'admin' => 'Admin',
    'content' => 'Contenuti',
    'navigation' => 'Navigazione',
    'theme-setup' => 'Setup Tema',
    'security' => 'Sicurezza',
    'forms' => 'Form',
    'users' => 'Utenti',
    'ajax' => 'Ajax',
    'media' => 'Media',
    'utilities' => 'Utilità'
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fab fa-wordpress"></i> WordPress Snippets</h1>
        <p class="text-muted">Codici pronti all'uso per estendere WordPress</p>
    </div>

    <!-- Filter buttons -->
    <div class="snippet-filters mb-4">
        <button class="btn btn-sm btn-outline-primary active" data-filter="all">
            <i class="fas fa-th"></i> Tutti
        </button>
        <?php foreach ($categories as $key => $label): ?>
            <?php if ($key !== 'all'): ?>
                <button class="btn btn-sm btn-outline-primary" data-filter="<?php echo $key; ?>">
                    <?php echo $label; ?>
                </button>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Snippets grid -->
    <div class="snippets-grid">
        <?php foreach ($snippets as $snippet): ?>
            <div class="snippet-card" data-category="<?php echo $snippet['category']; ?>">
                <div class="snippet-header">
                    <div class="snippet-icon">
                        <i class="<?php echo $snippet['icon']; ?>"></i>
                    </div>
                    <div class="snippet-info">
                        <h5><?php echo $snippet['title']; ?></h5>
                        <p class="text-muted mb-0"><?php echo $snippet['description']; ?></p>
                    </div>
                </div>
                <div class="snippet-actions">
                    <button class="btn btn-sm btn-primary" onclick="viewSnippet('<?php echo $snippet['id']; ?>')">
                        <i class="fas fa-eye"></i> Visualizza
                    </button>
                    <button class="btn btn-sm btn-success" onclick="copySnippet('<?php echo $snippet['id']; ?>')">
                        <i class="fas fa-copy"></i> Copia
                    </button>
                </div>
                <div class="snippet-code" id="snippet-<?php echo $snippet['id']; ?>" style="display: none;">
                    <pre><code class="language-php"><?php echo htmlspecialchars($snippet['code']); ?></code></pre>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal for viewing snippets -->
<div class="modal fade" id="snippetModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="snippetModalTitle">Snippet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="code-output">
                    <div class="copy-btn-container">
                        <button class="btn btn-copy" onclick="copyModalCode()">
                            <i class="fas fa-copy"></i> Copia
                        </button>
                    </div>
                    <pre><code class="language-php" id="modalSnippetCode"></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Filter snippets
document.querySelectorAll('.snippet-filters button').forEach(button => {
    button.addEventListener('click', function() {
        // Update active button
        document.querySelectorAll('.snippet-filters button').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Filter snippets
        const filter = this.dataset.filter;
        document.querySelectorAll('.snippet-card').forEach(card => {
            if (filter === 'all' || card.dataset.category === filter) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    });
});

// View snippet in modal
function viewSnippet(id) {
    const snippet = document.getElementById('snippet-' + id);
    const code = snippet.querySelector('code').textContent;
    const card = snippet.closest('.snippet-card');
    const title = card.querySelector('h5').textContent;
    
    document.getElementById('snippetModalTitle').textContent = title;
    document.getElementById('modalSnippetCode').textContent = code;
    
    // Re-highlight code
    Prism.highlightElement(document.getElementById('modalSnippetCode'));
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('snippetModal'));
    modal.show();
}

// Copy snippet
function copySnippet(id) {
    const snippet = document.getElementById('snippet-' + id);
    const code = snippet.querySelector('code').textContent;
    
    navigator.clipboard.writeText(code).then(() => {
        showNotification('Snippet copiato negli appunti!', 'success');
    });
}

// Copy from modal
function copyModalCode() {
    const code = document.getElementById('modalSnippetCode').textContent;
    
    navigator.clipboard.writeText(code).then(() => {
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-check"></i> Copiato!';
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
        }, 2000);
    });
}
</script>