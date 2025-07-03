<?php
// Security Snippets
$snippets = [
    [
        'id' => 'hide-wp-version',
        'title' => 'Nascondi Versione WordPress',
        'description' => 'Rimuove la versione di WordPress dal codice sorgente',
        'category' => 'headers',
        'icon' => 'fas fa-eye-slash',
        'code' => '// Remove WordPress version from header
remove_action(\'wp_head\', \'wp_generator\');

// Remove version from RSS feeds
add_filter(\'the_generator\', \'__return_empty_string\');

// Remove version from scripts and styles
function remove_version_from_assets($src) {
    if (strpos($src, \'ver=\')) {
        $src = remove_query_arg(\'ver\', $src);
    }
    return $src;
}
add_filter(\'script_loader_src\', \'remove_version_from_assets\', 999);
add_filter(\'style_loader_src\', \'remove_version_from_assets\', 999);'
    ],
    [
        'id' => 'disable-xmlrpc',
        'title' => 'Disabilita XML-RPC',
        'description' => 'Blocca XML-RPC per prevenire attacchi brute force',
        'category' => 'access',
        'icon' => 'fas fa-ban',
        'code' => '// Disable XML-RPC
add_filter(\'xmlrpc_enabled\', \'__return_false\');

// Remove XML-RPC headers
remove_action(\'wp_head\', \'rsd_link\');

// Block XML-RPC requests
add_filter(\'xmlrpc_methods\', function($methods) {
    return array();
});

// .htaccess protection
/*
# Block WordPress xmlrpc.php requests
<Files xmlrpc.php>
    order deny,allow
    deny from all
</Files>
*/'
    ],
    [
        'id' => 'limit-login-attempts',
        'title' => 'Limita Tentativi di Login',
        'description' => 'Blocca IP dopo tentativi di login falliti',
        'category' => 'login',
        'icon' => 'fas fa-lock',
        'code' => '// Limit login attempts
class Login_Attempt_Limiter {
    private $failed_login_limit = 3;
    private $lockout_duration = 1800; // 30 minutes
    private $transient_name = \'failed_login_\';
    
    public function __construct() {
        add_filter(\'authenticate\', array($this, \'check_attempted_login\'), 30, 3);
        add_action(\'wp_login_failed\', array($this, \'login_failed\'), 10, 1);
    }
    
    public function check_attempted_login($user, $username, $password) {
        if (get_transient($this->transient_name . $this->get_user_ip())) {
            return new WP_Error(\'too_many_attempts\', 
                sprintf(
                    __(\'<strong>ERRORE</strong>: Troppi tentativi di login. Riprova tra %d minuti.\'),
                    round($this->lockout_duration / 60)
                )
            );
        }
        return $user;
    }
    
    public function login_failed($username) {
        $ip = $this->get_user_ip();
        $attempts = get_transient($this->transient_name . \'attempts_\' . $ip);
        
        if ($attempts) {
            $attempts++;
        } else {
            $attempts = 1;
        }
        
        set_transient($this->transient_name . \'attempts_\' . $ip, $attempts, $this->lockout_duration);
        
        if ($attempts >= $this->failed_login_limit) {
            set_transient($this->transient_name . $ip, true, $this->lockout_duration);
        }
    }
    
    private function get_user_ip() {
        $ip_keys = array(\'HTTP_CF_CONNECTING_IP\', \'HTTP_CLIENT_IP\', \'HTTP_X_FORWARDED_FOR\', \'HTTP_X_FORWARDED\', \'HTTP_X_CLUSTER_CLIENT_IP\', \'HTTP_FORWARDED_FOR\', \'HTTP_FORWARDED\', \'REMOTE_ADDR\');
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(\',\', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return isset($_SERVER[\'REMOTE_ADDR\']) ? $_SERVER[\'REMOTE_ADDR\'] : \'0.0.0.0\';
    }
}

new Login_Attempt_Limiter();'
    ],
    [
        'id' => 'disable-file-editing',
        'title' => 'Disabilita Editor File',
        'description' => 'Rimuove l\'editor di temi e plugin dall\'admin',
        'category' => 'admin',
        'icon' => 'fas fa-file-code',
        'code' => '// Disable file editing in admin
define(\'DISALLOW_FILE_EDIT\', true);

// Or via filter
add_filter(\'file_editors_enabled\', \'__return_false\');

// Remove editor menu items
function remove_editor_menu() {
    remove_submenu_page(\'themes.php\', \'theme-editor.php\');
    remove_submenu_page(\'plugins.php\', \'plugin-editor.php\');
}
add_action(\'admin_menu\', \'remove_editor_menu\', 999);'
    ],
    [
        'id' => 'secure-headers',
        'title' => 'Headers di Sicurezza',
        'description' => 'Aggiunge headers HTTP di sicurezza',
        'category' => 'headers',
        'icon' => 'fas fa-shield-alt',
        'code' => '// Add security headers
function add_security_headers() {
    // Prevent clickjacking
    header(\'X-Frame-Options: SAMEORIGIN\');
    
    // Prevent MIME type sniffing
    header(\'X-Content-Type-Options: nosniff\');
    
    // Enable XSS filter
    header(\'X-XSS-Protection: 1; mode=block\');
    
    // Referrer policy
    header(\'Referrer-Policy: no-referrer-when-downgrade\');
    
    // Content Security Policy
    header("Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https://cdnjs.cloudflare.com; style-src \'self\' \'unsafe-inline\' https://fonts.googleapis.com; font-src \'self\' https://fonts.gstatic.com; img-src \'self\' data: https:; frame-src \'self\' https://www.youtube.com https://player.vimeo.com");
    
    // Strict Transport Security (HTTPS only)
    if (is_ssl()) {
        header(\'Strict-Transport-Security: max-age=31536000; includeSubDomains; preload\');
    }
    
    // Permissions Policy
    header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
}
add_action(\'send_headers\', \'add_security_headers\');'
    ],
    [
        'id' => 'hide-login-errors',
        'title' => 'Nascondi Errori di Login',
        'description' => 'Mostra messaggi di errore generici al login',
        'category' => 'login',
        'icon' => 'fas fa-user-secret',
        'code' => '// Hide login error details
function hide_login_errors() {
    return \'<strong>ERRORE</strong>: Username o password non corretti.\';
}
add_filter(\'login_errors\', \'hide_login_errors\');

// Remove login shake
function remove_login_shake() {
    remove_action(\'login_head\', \'wp_shake_js\', 12);
}
add_action(\'login_head\', \'remove_login_shake\');'
    ],
    [
        'id' => 'disable-rest-api',
        'title' => 'Limita REST API',
        'description' => 'Richiede autenticazione per accedere alle REST API',
        'category' => 'api',
        'icon' => 'fas fa-plug',
        'code' => '// Restrict REST API to authenticated users
add_filter(\'rest_authentication_errors\', function($result) {
    if (!empty($result)) {
        return $result;
    }
    
    if (!is_user_logged_in()) {
        return new WP_Error(\'rest_forbidden\', __(\'Non sei autorizzato ad accedere alle REST API.\'), array(\'status\' => 401));
    }
    
    return $result;
});

// Disable REST API user enumeration
add_filter(\'rest_endpoints\', function($endpoints) {
    if (isset($endpoints[\'/wp/v2/users\'])) {
        unset($endpoints[\'/wp/v2/users\']);
    }
    if (isset($endpoints[\'/wp/v2/users/(?P<id>[\\d]+)\'])) {
        unset($endpoints[\'/wp/v2/users/(?P<id>[\\d]+)\']);
    }
    return $endpoints;
});'
    ],
    [
        'id' => 'change-login-url',
        'title' => 'Cambia URL di Login',
        'description' => 'Modifica l\'URL della pagina di login',
        'category' => 'login',
        'icon' => 'fas fa-link',
        'code' => '// Change login URL
class Custom_Login_URL {
    private $wp_login_php;
    
    public function __construct() {
        $this->wp_login_php = \'secret-login\'; // Your custom login slug
        
        add_action(\'plugins_loaded\', array($this, \'plugins_loaded\'), 1);
        add_action(\'wp_loaded\', array($this, \'wp_loaded\'));
        add_filter(\'site_url\', array($this, \'site_url\'), 10, 4);
        add_filter(\'wp_redirect\', array($this, \'wp_redirect\'), 10, 2);
    }
    
    public function plugins_loaded() {
        global $pagenow;
        
        if (!is_multisite() && (strpos($_SERVER[\'REQUEST_URI\'], \'wp-signup\') !== false || strpos($_SERVER[\'REQUEST_URI\'], \'wp-activate\') !== false)) {
            wp_die(__(\'This feature is not enabled.\'));
        }
        
        $request = parse_url($_SERVER[\'REQUEST_URI\']);
        
        if ((strpos($request[\'path\'], \'wp-login.php\') !== false || $request[\'path\'] === site_url(\'wp-login\', \'relative\')) && !is_admin()) {
            $this->wp_login_php = true;
            $_SERVER[\'REQUEST_URI\'] = $this->user_trailingslashit(\'/\' . str_repeat(\'-\', 10));
            $pagenow = \'index.php\';
        } elseif ($request[\'path\'] === home_url($this->wp_login_php, \'relative\') || (!get_option(\'permalink_structure\') && isset($_GET[$this->wp_login_php]))) {
            $pagenow = \'wp-login.php\';
        }
    }
    
    public function wp_loaded() {
        global $pagenow;
        
        if ($pagenow === \'wp-login.php\' && $request[\'path\'] !== $this->user_trailingslashit($request[\'path\']) && get_option(\'permalink_structure\')) {
            wp_safe_redirect($this->user_trailingslashit($this->wp_login_php) . (!empty($_SERVER[\'QUERY_STRING\']) ? \'?\' . $_SERVER[\'QUERY_STRING\'] : \'\'));
            die;
        } elseif ($this->wp_login_php) {
            wp_die(\'Page not found.\', 404);
        } elseif ($pagenow === \'wp-login.php\') {
            global $error, $interim_login, $action, $user_login;
            
            @require_once ABSPATH . \'wp-login.php\';
            die;
        }
    }
    
    public function site_url($url, $path, $scheme, $blog_id) {
        return $this->filter_wp_login_php($url, $scheme);
    }
    
    public function wp_redirect($location, $status) {
        return $this->filter_wp_login_php($location);
    }
    
    private function filter_wp_login_php($url, $scheme = null) {
        if (strpos($url, \'wp-login.php\') !== false) {
            if (is_ssl()) {
                $scheme = \'https\';
            }
            
            $args = explode(\'?\', $url);
            
            if (isset($args[1])) {
                parse_str($args[1], $args);
                $url = add_query_arg($args, $this->new_login_url($scheme));
            } else {
                $url = $this->new_login_url($scheme);
            }
        }
        
        return $url;
    }
    
    private function new_login_url($scheme = null) {
        if (get_option(\'permalink_structure\')) {
            return $this->user_trailingslashit(home_url(\'/\', $scheme) . $this->wp_login_php);
        } else {
            return home_url(\'/\', $scheme) . \'?\' . $this->wp_login_php;
        }
    }
    
    private function user_trailingslashit($string) {
        return $this->use_trailing_slashes() ? trailingslashit($string) : untrailingslashit($string);
    }
    
    private function use_trailing_slashes() {
        return (\'/%postname%/\' === substr(get_option(\'permalink_structure\'), -strlen(\'/%postname%/\')));
    }
}

new Custom_Login_URL();'
    ],
    [
        'id' => 'disable-user-enumeration',
        'title' => 'Blocca User Enumeration',
        'description' => 'Previene la scoperta degli username',
        'category' => 'users',
        'icon' => 'fas fa-users-slash',
        'code' => '// Prevent user enumeration
function disable_user_enumeration() {
    if (isset($_GET[\'author\']) && $_GET[\'author\'] != \'\' && !is_admin()) {
        wp_redirect(home_url(), 301);
        exit;
    }
}
add_action(\'init\', \'disable_user_enumeration\');

// Remove author links
add_filter(\'author_link\', function() {
    return home_url();
});

// Disable oEmbed author discovery
remove_action(\'wp_head\', \'wp_oembed_add_discovery_links\');'
    ],
    [
        'id' => 'secure-uploads',
        'title' => 'Upload Sicuri',
        'description' => 'Limita i tipi di file caricabili',
        'category' => 'uploads',
        'icon' => 'fas fa-upload',
        'code' => '// Restrict file upload types
function restrict_mime_types($mimes) {
    // Remove potentially dangerous file types
    unset($mimes[\'exe\']);
    unset($mimes[\'php\']);
    unset($mimes[\'phtml\']);
    unset($mimes[\'php3\']);
    unset($mimes[\'php4\']);
    unset($mimes[\'php5\']);
    unset($mimes[\'pl\']);
    unset($mimes[\'py\']);
    unset($mimes[\'jsp\']);
    unset($mimes[\'asp\']);
    unset($mimes[\'bat\']);
    unset($mimes[\'sh\']);
    unset($mimes[\'cgi\']);
    
    // Allow only specific types
    $allowed = array(
        \'jpg|jpeg|jpe\' => \'image/jpeg\',
        \'gif\' => \'image/gif\',
        \'png\' => \'image/png\',
        \'pdf\' => \'application/pdf\',
        \'doc\' => \'application/msword\',
        \'docx\' => \'application/vnd.openxmlformats-officedocument.wordprocessingml.document\',
        \'xls\' => \'application/vnd.ms-excel\',
        \'xlsx\' => \'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet\',
        \'mp4\' => \'video/mp4\',
        \'webm\' => \'video/webm\',
        \'mp3\' => \'audio/mpeg\',
    );
    
    return $allowed;
}
add_filter(\'upload_mimes\', \'restrict_mime_types\', 1, 1);

// Additional file type check
function check_file_type($data, $file, $filename, $mimes) {
    $filetype = wp_check_filetype($filename, $mimes);
    
    if (!$filetype[\'ext\']) {
        $data[\'error\'] = \'Tipo di file non consentito per motivi di sicurezza.\';
    }
    
    return $data;
}
add_filter(\'wp_check_filetype_and_ext\', \'check_file_type\', 10, 4);'
    ]
];

// Categories
$categories = [
    'all' => 'Tutti',
    'headers' => 'Headers',
    'access' => 'Accesso',
    'login' => 'Login',
    'admin' => 'Admin',
    'api' => 'API',
    'users' => 'Utenti',
    'uploads' => 'Upload'
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-shield-alt"></i> Security Snippets</h1>
        <p class="text-muted">Codici per migliorare la sicurezza di WordPress</p>
    </div>

    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle"></i> <strong>Attenzione:</strong> Testa sempre questi snippet in un ambiente di sviluppo prima di applicarli in produzione.
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
                    <div class="snippet-icon security-icon">
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
.security-icon {
    background: var(--danger-color) !important;
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