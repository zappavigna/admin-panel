<?php
// Performance Snippets
$snippets = [
    [
        'id' => 'disable-emojis',
        'title' => 'Disabilita Emoji',
        'description' => 'Rimuove il supporto emoji di WordPress per velocizzare il caricamento',
        'category' => 'optimization',
        'icon' => 'fas fa-smile-slash',
        'code' => '// Disable WordPress emojis
function disable_emojis() {
    remove_action(\'wp_head\', \'print_emoji_detection_script\', 7);
    remove_action(\'admin_print_scripts\', \'print_emoji_detection_script\');
    remove_action(\'wp_print_styles\', \'print_emoji_styles\');
    remove_action(\'admin_print_styles\', \'print_emoji_styles\');
    remove_filter(\'the_content_feed\', \'wp_staticize_emoji\');
    remove_filter(\'comment_text_rss\', \'wp_staticize_emoji\');
    remove_filter(\'wp_mail\', \'wp_staticize_emoji_for_email\');
    
    add_filter(\'tiny_mce_plugins\', \'disable_emojis_tinymce\');
    add_filter(\'wp_resource_hints\', \'disable_emojis_remove_dns_prefetch\', 10, 2);
}
add_action(\'init\', \'disable_emojis\');

function disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array(\'wpemoji\'));
    }
    return array();
}

function disable_emojis_remove_dns_prefetch($urls, $relation_type) {
    if (\'dns-prefetch\' == $relation_type) {
        $emoji_svg_url = apply_filters(\'emoji_svg_url\', \'https://s.w.org/images/core/emoji/2/svg/\');
        $urls = array_diff($urls, array($emoji_svg_url));
    }
    return $urls;
}'
    ],
    [
        'id' => 'lazy-load-images',
        'title' => 'Lazy Load Immagini',
        'description' => 'Carica le immagini solo quando entrano nel viewport',
        'category' => 'images',
        'icon' => 'fas fa-images',
        'code' => '// Native lazy loading for images
function add_lazy_loading($content) {
    // Add loading="lazy" to images
    $content = preg_replace(\'/<img(.*?)>/i\', \'<img$1 loading="lazy">\', $content);
    
    // Add loading="lazy" to iframes
    $content = preg_replace(\'/<iframe(.*?)>/i\', \'<iframe$1 loading="lazy">\', $content);
    
    return $content;
}
add_filter(\'the_content\', \'add_lazy_loading\');

// Add lazy loading to post thumbnails
function lazy_load_post_thumbnails($attr, $attachment, $size) {
    $attr[\'loading\'] = \'lazy\';
    return $attr;
}
add_filter(\'wp_get_attachment_image_attributes\', \'lazy_load_post_thumbnails\', 10, 3);

// Advanced lazy loading with JavaScript
/*
<script>
document.addEventListener("DOMContentLoaded", function() {
    var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));
    
    if ("IntersectionObserver" in window) {
        let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    let lazyImage = entry.target;
                    lazyImage.src = lazyImage.dataset.src;
                    lazyImage.classList.remove("lazy");
                    lazyImageObserver.unobserve(lazyImage);
                }
            });
        });
        
        lazyImages.forEach(function(lazyImage) {
            lazyImageObserver.observe(lazyImage);
        });
    }
});
</script>
*/'
    ],
    [
        'id' => 'limit-post-revisions',
        'title' => 'Limita Revisioni Post',
        'description' => 'Riduce il numero di revisioni salvate per ogni post',
        'category' => 'database',
        'icon' => 'fas fa-history',
        'code' => '// Limit post revisions
define(\'WP_POST_REVISIONS\', 3);

// Or disable completely
// define(\'WP_POST_REVISIONS\', false);

// Clean old revisions
function delete_old_revisions() {
    global $wpdb;
    
    $days = 30; // Delete revisions older than 30 days
    
    $sql = "DELETE FROM $wpdb->posts 
            WHERE post_type = \'revision\' 
            AND post_date < DATE_SUB(NOW(), INTERVAL %d DAY)";
    
    $wpdb->query($wpdb->prepare($sql, $days));
}
// Run monthly
if (!wp_next_scheduled(\'delete_old_revisions\')) {
    wp_schedule_event(time(), \'monthly\', \'delete_old_revisions\');
}
add_action(\'delete_old_revisions\', \'delete_old_revisions\');'
    ],
    [
        'id' => 'optimize-database',
        'title' => 'Ottimizza Database',
        'description' => 'Pulisce e ottimizza le tabelle del database',
        'category' => 'database',
        'icon' => 'fas fa-database',
        'code' => '// Database optimization
function optimize_database() {
    global $wpdb;
    
    // Get all database tables
    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
    
    foreach ($tables as $table) {
        $table_name = $table[0];
        
        // Optimize table
        $wpdb->query("OPTIMIZE TABLE $table_name");
    }
    
    // Clean transients
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE \'_transient_%\'");
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE \'_site_transient_%\'");
    
    // Clean spam and trash comments
    $wpdb->query("DELETE FROM $wpdb->comments WHERE comment_approved = \'spam\'");
    $wpdb->query("DELETE FROM $wpdb->comments WHERE comment_approved = \'trash\'");
    
    // Clean orphaned post meta
    $wpdb->query("DELETE pm FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL");
    
    // Clean orphaned comment meta
    $wpdb->query("DELETE FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)");
    
    // Clean orphaned term relationships
    $wpdb->query("DELETE tr FROM $wpdb->term_relationships tr LEFT JOIN $wpdb->posts p ON tr.object_id = p.ID WHERE p.ID IS NULL");
}

// Schedule monthly optimization
if (!wp_next_scheduled(\'optimize_database_monthly\')) {
    wp_schedule_event(time(), \'monthly\', \'optimize_database_monthly\');
}
add_action(\'optimize_database_monthly\', \'optimize_database\');'
    ],
    [
        'id' => 'disable-heartbeat',
        'title' => 'Ottimizza Heartbeat API',
        'description' => 'Riduce la frequenza dell\'Heartbeat API per risparmiare risorse',
        'category' => 'optimization',
        'icon' => 'fas fa-heartbeat',
        'code' => '// Disable Heartbeat API on certain pages
function disable_heartbeat() {
    wp_deregister_script(\'heartbeat\');
}

// Disable everywhere except post editor
add_action(\'init\', \'control_heartbeat\', 1);
function control_heartbeat() {
    global $pagenow;
    
    // Keep heartbeat only on post editing pages
    if ($pagenow != \'post.php\' && $pagenow != \'post-new.php\') {
        wp_deregister_script(\'heartbeat\');
    }
}

// Modify Heartbeat frequency
function slow_heartbeat($settings) {
    $settings[\'interval\'] = 60; // Heartbeat every 60 seconds instead of 15
    return $settings;
}
add_filter(\'heartbeat_settings\', \'slow_heartbeat\');'
    ],
    [
        'id' => 'defer-javascript',
        'title' => 'Defer JavaScript',
        'description' => 'Posticipa il caricamento di JavaScript non critico',
        'category' => 'optimization',
        'icon' => 'fas fa-bolt',
        'code' => '// Defer JavaScript loading
function defer_parsing_of_js($tag, $handle) {
    // Scripts to exclude from defer
    $scripts_to_exclude = array(
        \'jquery\',
        \'jquery-core\',
        \'jquery-migrate\'
    );
    
    foreach ($scripts_to_exclude as $exclude_script) {
        if (true == strpos($handle, $exclude_script)) {
            return $tag;
        }
    }
    
    // Don\'t defer for logged in users (admin bar compatibility)
    if (is_user_logged_in()) {
        return $tag;
    }
    
    return str_replace(\' src\', \' defer src\', $tag);
}
add_filter(\'script_loader_tag\', \'defer_parsing_of_js\', 10, 2);

// Async loading for specific scripts
function async_scripts($tag, $handle) {
    $scripts_to_async = array(
        \'google-analytics\',
        \'facebook-pixel\',
        \'twitter-widgets\'
    );
    
    foreach ($scripts_to_async as $async_script) {
        if ($async_script === $handle) {
            return str_replace(\' src\', \' async src\', $tag);
        }
    }
    
    return $tag;
}
add_filter(\'script_loader_tag\', \'async_scripts\', 10, 2);'
    ],
    [
        'id' => 'preload-critical-assets',
        'title' => 'Preload Asset Critici',
        'description' => 'Precarica font e CSS critici per velocizzare il rendering',
        'category' => 'optimization',
        'icon' => 'fas fa-rocket',
        'code' => '// Preload critical assets
function preload_critical_assets() {
    // Preload fonts
    echo \'<link rel="preload" href="\' . get_template_directory_uri() . \'/fonts/your-font.woff2" as="font" type="font/woff2" crossorigin>\';
    
    // Preload critical CSS
    echo \'<link rel="preload" href="\' . get_template_directory_uri() . \'/css/critical.css" as="style">\';
    
    // Preconnect to external domains
    echo \'<link rel="preconnect" href="https://fonts.googleapis.com">\';
    echo \'<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>\';
    
    // DNS prefetch for external resources
    echo \'<link rel="dns-prefetch" href="//www.google-analytics.com">\';
}
add_action(\'wp_head\', \'preload_critical_assets\', 1);

// Resource hints for WordPress
function custom_resource_hints($hints, $relation_type) {
    if (\'dns-prefetch\' === $relation_type) {
        $hints[] = \'//cdnjs.cloudflare.com\';
        $hints[] = \'//fonts.googleapis.com\';
    } elseif (\'preconnect\' === $relation_type) {
        $hints[] = array(
            \'href\' => \'https://fonts.gstatic.com\',
            \'crossorigin\' => \'anonymous\',
        );
    }
    
    return $hints;
}
add_filter(\'wp_resource_hints\', \'custom_resource_hints\', 10, 2);'
    ],
    [
        'id' => 'disable-gutenberg-css',
        'title' => 'Rimuovi CSS Gutenberg',
        'description' => 'Rimuove CSS di Gutenberg se non utilizzato',
        'category' => 'optimization',
        'icon' => 'fas fa-paint-brush',
        'code' => '// Remove Gutenberg block CSS
function remove_gutenberg_css() {
    // Remove block library CSS
    wp_dequeue_style(\'wp-block-library\');
    wp_dequeue_style(\'wp-block-library-theme\');
    
    // Remove inline global styles
    wp_dequeue_style(\'global-styles\');
    
    // Remove classic theme styles
    wp_dequeue_style(\'classic-theme-styles\');
}
add_action(\'wp_enqueue_scripts\', \'remove_gutenberg_css\', 100);

// Remove Gutenberg inline styles
function remove_global_styles() {
    remove_action(\'wp_enqueue_scripts\', \'wp_enqueue_global_styles\');
    remove_action(\'wp_footer\', \'wp_enqueue_global_styles\', 1);
}
add_action(\'init\', \'remove_global_styles\');'
    ],
    [
        'id' => 'cache-gravatars',
        'title' => 'Cache Gravatar Locali',
        'description' => 'Salva i Gravatar localmente per ridurre richieste esterne',
        'category' => 'images',
        'icon' => 'fas fa-user-circle',
        'code' => '// Cache Gravatars locally
function cache_gravatar($avatar, $id_or_email, $size, $default, $alt) {
    // Get email
    if (is_numeric($id_or_email)) {
        $user = get_user_by(\'id\', $id_or_email);
        $email = $user->user_email;
    } elseif (is_object($id_or_email)) {
        $email = $id_or_email->comment_author_email;
    } else {
        $email = $id_or_email;
    }
    
    $email_hash = md5(strtolower(trim($email)));
    $cache_dir = WP_CONTENT_DIR . \'/cache/gravatars/\';
    $cache_file = $cache_dir . $email_hash . \'-\' . $size . \'.jpg\';
    $cache_url = content_url(\'/cache/gravatars/\' . $email_hash . \'-\' . $size . \'.jpg\');
    
    // Create cache directory if not exists
    if (!file_exists($cache_dir)) {
        wp_mkdir_p($cache_dir);
    }
    
    // Check if cached file exists and is less than 7 days old
    if (file_exists($cache_file) && (time() - filemtime($cache_file) < 604800)) {
        return \'<img src="\' . $cache_url . \'" alt="\' . $alt . \'" class="avatar" width="\' . $size . \'" height="\' . $size . \'">\';
    }
    
    // Get gravatar
    $gravatar_url = \'https://www.gravatar.com/avatar/\' . $email_hash . \'?s=\' . $size . \'&d=\' . $default;
    $gravatar_data = wp_remote_get($gravatar_url);
    
    if (!is_wp_error($gravatar_data)) {
        file_put_contents($cache_file, wp_remote_retrieve_body($gravatar_data));
        return \'<img src="\' . $cache_url . \'" alt="\' . $alt . \'" class="avatar" width="\' . $size . \'" height="\' . $size . \'">\';
    }
    
    return $avatar;
}
add_filter(\'get_avatar\', \'cache_gravatar\', 10, 5);'
    ],
    [
        'id' => 'minify-html',
        'title' => 'Minifica HTML',
        'description' => 'Comprime l\'output HTML rimuovendo spazi inutili',
        'category' => 'optimization',
        'icon' => 'fas fa-compress',
        'code' => '// Minify HTML output
function minify_html_output($buffer) {
    if (is_admin() || (defined(\'DOING_AJAX\') && DOING_AJAX)) {
        return $buffer;
    }
    
    // Remove comments (keep IE conditional comments)
    $buffer = preg_replace(\'/<!--(?!\\[if).*?-->/s\', \'\', $buffer);
    
    // Remove whitespace
    $buffer = preg_replace(\'/\\s+/\', \' \', $buffer);
    
    // Remove whitespace around tags
    $buffer = preg_replace(\'/\\s*(<\\/?[^>]+>)\\s*/\', \'$1\', $buffer);
    
    // Remove whitespace between tags
    $buffer = preg_replace(\'/>(\\s+)</\', \'><\', $buffer);
    
    return trim($buffer);
}

function start_html_compression() {
    ob_start(\'minify_html_output\');
}

function end_html_compression() {
    if (ob_get_level() > 0) {
        ob_end_flush();
    }
}

// Don\'t minify for logged in users (better debugging)
if (!is_user_logged_in() && !is_admin()) {
    add_action(\'init\', \'start_html_compression\', 1);
    add_action(\'wp_footer\', \'end_html_compression\', 999);
}'
    ]
];

// Categories
$categories = [
    'all' => 'Tutti',
    'optimization' => 'Ottimizzazione',
    'images' => 'Immagini',
    'database' => 'Database'
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-tachometer-alt"></i> Performance Snippets</h1>
        <p class="text-muted">Codici per ottimizzare le prestazioni di WordPress</p>
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
                    <div class="snippet-icon performance-icon">
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

<style>
.performance-icon {
    background: var(--success-color) !important;
    color: white !important;
}
</style>

<!-- Reuse modal and scripts -->
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
// Reuse scripts
document.querySelectorAll('.snippet-filters button').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.snippet-filters button').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
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

function viewSnippet(id) {
    const snippet = document.getElementById('snippet-' + id);
    const code = snippet.querySelector('code').textContent;
    const card = snippet.closest('.snippet-card');
    const title = card.querySelector('h5').textContent;
    
    document.getElementById('snippetModalTitle').textContent = title;
    document.getElementById('modalSnippetCode').textContent = code;
    
    Prism.highlightElement(document.getElementById('modalSnippetCode'));
    
    const modal = new bootstrap.Modal(document.getElementById('snippetModal'));
    modal.show();
}

function copySnippet(id) {
    const snippet = document.getElementById('snippet-' + id);
    const code = snippet.querySelector('code').textContent;
    
    navigator.clipboard.writeText(code).then(() => {
        showNotification('Snippet copiato negli appunti!', 'success');
    });
}

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