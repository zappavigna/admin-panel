<?php
// File: admin-panel/pages/wordpress/manutenzione.php

// Codice PHP completo per la modalità manutenzione
$maintenance_code = <<<'PHP'
<?php
/**
 * Plugin: Modalità Manutenzione Personalizzata
 * Description: Aggiunge una pagina di opzioni per attivare una modalità manutenzione personalizzata.
 * Version: 1.2
 */

// Evita l'accesso diretto al file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Custom_Maintenance_Mode {

    private static $instance;
    private $option_name = 'cmm_options';

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'settings_init' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
        add_action( 'template_redirect', [ $this, 'maintenance_mode_check' ], 0 );
    }

    public function add_admin_menu() {
        add_menu_page( 'Modalità Manutenzione', 'Manutenzione', 'manage_options', 'maintenance_mode_page', [ $this, 'options_page_html' ], 'dashicons-hammer', 81 );
    }

    public function admin_enqueue_scripts( $hook ) {
        if ( 'toplevel_page_maintenance_mode_page' !== $hook ) {
            return;
        }
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        
        $script_handle = 'cmm-admin-script';
        wp_register_script($script_handle, false, ['jquery', 'wp-color-picker'], false, true);
        wp_enqueue_script($script_handle);

        $current_user_ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? esc_attr($_SERVER['HTTP_X_FORWARDED_FOR']) : esc_attr($_SERVER['REMOTE_ADDR']);
        wp_localize_script($script_handle, 'cmm_vars', ['current_ip' => $current_user_ip]);
        
        wp_add_inline_script($script_handle, "
            jQuery(document).ready(function($){
                $('.cmm-color-picker').wpColorPicker();

                $('#cmm_upload_logo_button').click(function(e) {
                    e.preventDefault();
                    var image = wp.media({ title: 'Carica Logo', multiple: false }).open().on('select', function(e){
                        var uploaded_image = image.state().get('selection').first();
                        var image_url = uploaded_image.toJSON().url;
                        $('#cmm_logo_url').val(image_url);
                        $('#cmm_logo_preview').html('<img src=\"' + image_url + '\" style=\"max-width:200px;\"/>');
                    });
                });

                // --- CORREZIONE: Handler per il pulsante 'Aggiungi il mio IP' ---
                $('#cmm_add_my_ip').click(function(e) {
                    e.preventDefault();
                    var ip_textarea = $('#cmm_ip_exclude');
                    var current_ips = ip_textarea.val();
                    var my_ip = cmm_vars.current_ip;
                    if (current_ips.indexOf(my_ip) === -1) {
                        ip_textarea.val(current_ips + (current_ips ? '\\n' : '') + my_ip);
                    }
                });
            });
        ");
    }

    public function settings_init() {
        register_setting( 'maintenance_mode_page', $this->option_name );
        add_settings_section( 'cmm_general_section', 'Impostazioni Generali', null, 'maintenance_mode_page' );
        add_settings_field( 'cmm_enabled', 'Stato', [ $this, 'render_field' ], 'maintenance_mode_page', 'cmm_general_section', ['type' => 'toggle', 'id' => 'enabled'] );
        add_settings_field( 'cmm_ip_exclude', 'IP da Escludere', [ $this, 'render_field' ], 'maintenance_mode_page', 'cmm_general_section', ['type' => 'textarea', 'id' => 'ip_exclude', 'placeholder' => 'Un IP per riga.'] );
        add_settings_section( 'cmm_content_section', 'Contenuto Pagina', null, 'maintenance_mode_page' );
        add_settings_field( 'cmm_logo', 'Logo', [ $this, 'render_field' ], 'maintenance_mode_page', 'cmm_content_section', ['type' => 'logo', 'id' => 'logo_url'] );
        add_settings_field( 'cmm_bg_color', 'Colore Sfondo', [ $this, 'render_field' ], 'maintenance_mode_page', 'cmm_content_section', ['type' => 'color', 'id' => 'bg_color', 'default' => '#f9f9f9'] );
        add_settings_field( 'cmm_title', 'Titolo', [ $this, 'render_field' ], 'maintenance_mode_page', 'cmm_content_section', ['type' => 'text', 'id' => 'title', 'default' => 'Sito in Manutenzione'] );
        add_settings_field( 'cmm_text', 'Testo', [ $this, 'render_field' ], 'maintenance_mode_page', 'cmm_content_section', ['type' => 'editor', 'id' => 'text', 'default' => 'Stiamo effettuando degli aggiornamenti. Torneremo presto online!'] );
        add_settings_field( 'cmm_contacts', 'Contatti (Social)', [ $this, 'render_field' ], 'maintenance_mode_page', 'cmm_content_section', ['type' => 'textarea', 'id' => 'contacts', 'placeholder' => 'Un link per riga. Es: https://facebook.com/tuapagina'] );
    }
    
    public function render_field($args) {
        $options = get_option($this->option_name, []);
        $id = $args['id'];
        $value = $options[$id] ?? ($args['default'] ?? '');
        
        switch ($args['type']) {
            case 'toggle':
                echo '<label class="switch"><input type="checkbox" name="' . $this->option_name . '[' . $id . ']" value="1" ' . checked(1, (int)$value, false) . '/><span class="slider round"></span></label>';
                break;
            case 'textarea':
                 echo '<textarea name="' . $this->option_name . '[' . $id . ']" id="cmm_' . $id . '" rows="5" cols="50" class="large-text" placeholder="' . ($args['placeholder'] ?? '') . '">' . esc_textarea($value) . '</textarea>';
                if ($id === 'ip_exclude') {
                    echo '<p><button id="cmm_add_my_ip" class="button">Aggiungi il mio IP</button></p>';
                }
                break;
            case 'logo':
                echo '<input type="text" name="' . $this->option_name . '[' . $id . ']" id="cmm_' . $id . '" value="' . esc_url($value) . '" class="regular-text"/>';
                echo '<button id="cmm_upload_logo_button" class="button">Carica Logo</button>';
                echo '<div id="cmm_logo_preview" style="margin-top:10px;">' . ($value ? '<img src="' . esc_url($value) . '" style="max-width:200px;"/>' : '') . '</div>';
                break;
            case 'color':
                echo '<input type="text" name="' . $this->option_name . '[' . $id . ']" value="' . esc_attr($value) . '" class="cmm-color-picker" />';
                break;
            case 'editor':
                wp_editor($value, 'cmm_text_editor', ['textarea_name' => $this->option_name . '[' . $id . ']', 'media_buttons' => false, 'textarea_rows' => 5]);
                break;
            default: // text
                 echo '<input type="text" name="' . $this->option_name . '[' . $id . ']" value="' . esc_attr($value) . '" class="regular-text" />';
        }
    }

    public function options_page_html() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <style>
                .switch{position:relative;display:inline-block;width:60px;height:34px;}.switch input{opacity:0;width:0;height:0;}
                .slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;transition:.4s;}
                .slider:before{position:absolute;content:"";height:26px;width:26px;left:4px;bottom:4px;background-color:white;transition:.4s;}
                input:checked + .slider{background-color:#2196F3;}
                input:checked + .slider:before{transform:translateX(26px);}
                .slider.round{border-radius:34px;}.slider.round:before{border-radius:50%;}
            </style>
            <form action="options.php" method="post">
                <?php settings_fields( 'maintenance_mode_page' ); do_settings_sections( 'maintenance_mode_page' ); submit_button( 'Salva Impostazioni' ); ?>
            </form>
        </div>
        <?php
    }

    public function maintenance_mode_check() {
        $options = get_option($this->option_name, []);
        if (empty($options['enabled'])) return;
        if (current_user_can('manage_options')) return;
        $excluded_ips = isset($options['ip_exclude']) ? array_map('trim', explode("\n", $options['ip_exclude'])) : [];
        $user_ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        if (in_array($user_ip, $excluded_ips)) return;
        $this->render_maintenance_page();
    }
    
    public function render_maintenance_page() {
        $options = get_option($this->option_name, []);
        $logo_url = esc_url($options['logo_url'] ?? '');
        $bg_color = esc_attr($options['bg_color'] ?? '#f9f9f9');
        $title = esc_html($options['title'] ?? 'Sito in Manutenzione');
        $text = wp_kses_post($options['text'] ?? 'Stiamo lavorando per migliorare il sito. Torneremo presto!');
        $contacts = array_filter(array_map('trim', explode("\n", $options['contacts'] ?? '')));

        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
        header('Retry-After: 3600');
        ?>
        <!DOCTYPE html>
        <html lang="it">
        <head>
            <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $title; ?></title>
            <style>
                body{margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;background-color:<?php echo $bg_color; ?>;color:#333;display:flex;align-items:center;justify-content:center;min-height:100vh;text-align:center;}
                .container{background:rgba(255,255,255,0.85);padding:40px;border-radius:10px;box-shadow:0 10px 30px rgba(0,0,0,0.1);max-width:600px;margin:20px;z-index:1;}
                .logo img{max-width:200px;max-height:80px;margin-bottom:20px;}
                h1{font-size:2.5em;margin:0 0 20px 0;}
                .text-content{font-size:1.2em;line-height:1.6;}
                .contacts a{display:inline-block;margin:10px;font-size:2em;color:#333;text-decoration:none;}
                .login-button-area{position:absolute;top:20px;right:20px;z-index:10;}
                .login-button-area button{text-decoration:none;color:#555;background:rgba(255,255,255,0.7);padding:8px 15px;border-radius:5px;border:1px solid #ccc;cursor:pointer;font-size:14px;}
                .login-modal-overlay{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:1000;align-items:center;justify-content:center;}
                .login-modal-content{position:relative;width:90%;max-width:360px;background:#fff;box-shadow:0 5px 15px rgba(0,0,0,0.3);padding:40px;border-radius:8px;text-align:left;}
                .login-modal-close{position:absolute;top:10px;right:15px;font-size:28px;font-weight:bold;color:#aaa;cursor:pointer;}.login-modal-close:hover{color:#333;}
                .login-modal-content h2{text-align:center;margin-top:0;margin-bottom:25px;}
                .login-modal-content label{display:block;margin-bottom:5px;font-weight:bold;}
                .login-modal-content input[type=text],.login-modal-content input[type=password]{width:100%;padding:10px;margin-bottom:15px;border:1px solid #ddd;border-radius:4px;box-sizing:border-box;}
                .login-modal-content input[type=submit]{width:100%;background-color:#2196F3;color:white;padding:14px 20px;margin:8px 0;border:none;border-radius:4px;cursor:pointer;font-size:16px;}.login-modal-content input[type=submit]:hover{background-color:#0b7dda;}
            </style>
        </head>
        <body>
            <div class="login-button-area"><button id="openLoginModal">Admin Login</button></div>

            <div id="loginModal" class="login-modal-overlay">
                <div class="login-modal-content">
                    <span id="closeLoginModal" class="login-modal-close">&times;</span>
                    <h2>Login Amministratore</h2>
                    <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
                        <p><label for="user_login">Nome utente o email</label><input type="text" name="log" id="user_login" class="input" value="" size="20" /></p>
                        <p><label for="user_pass">Password</label><input type="password" name="pwd" id="user_pass" class="input" value="" size="20" /></p>
                        <p class="login-submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Accedi" /></p>
                        <input type="hidden" name="redirect_to" value="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" />
                    </form>
                </div>
            </div>

            <div class="container">
                <?php if ($logo_url) : ?><div class="logo"><img src="<?php echo $logo_url; ?>" alt="Logo"></div><?php endif; ?>
                <h1><?php echo $title; ?></h1>
                <div class="text-content"><?php echo do_shortcode($text); ?></div>
                <?php if (!empty($contacts)) : ?>
                    <div class="contacts">
                        <?php foreach($contacts as $link) { echo '<a href="' . esc_url($link) . '" target="_blank"></a>'; } ?>
                    </div>
                <?php endif; ?>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('loginModal');
                    const openBtn = document.getElementById('openLoginModal');
                    const closeBtn = document.getElementById('closeLoginModal');
                    if(modal){
                        openBtn.onclick = function() { modal.style.display = 'flex'; }
                        closeBtn.onclick = function() { modal.style.display = 'none'; }
                        window.onclick = function(event) { if (event.target == modal) { modal.style.display = 'none'; } }
                    }
                    const contactLinks = document.querySelectorAll('.contacts a');
                    if (contactLinks.length > 0) {
                        const script = document.createElement('script');
                        script.src = 'https://kit.fontawesome.com/a076d05399.js'; script.crossOrigin = 'anonymous';
                        document.head.appendChild(script);
                        contactLinks.forEach(link => {
                            let iC = 'fas fa-link';
                            if (link.href.includes('facebook.com')) iC = 'fab fa-facebook-f';
                            if (link.href.includes('twitter.com') || link.href.includes('x.com')) iC = 'fab fa-twitter';
                            if (link.href.includes('instagram.com')) iC = 'fab fa-instagram';
                            if (link.href.includes('linkedin.com')) iC = 'fab fa-linkedin-in';
                            if (link.href.includes('mailto:')) iC = 'fas fa-envelope';
                            link.innerHTML = '<i class="' + iC + '"></i>';
                        });
                    }
                });
            </script>
        </body>
        </html>
        <?php
        exit();
    }
}
Custom_Maintenance_Mode::get_instance();
?>
PHP;
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-person-digging"></i> Generatore Codice Manutenzione</h1>
        <p class="text-muted">Copia questo codice e incollalo nel file <code>functions.php</code> del tuo tema o usalo per creare un plugin personalizzato.</p>
    </div>

    <div class="code-output">
        <div class="copy-btn-container">
            <button class="btn btn-copy" onclick="copyCode('maintenanceCode', this)"><i class="fas fa-copy"></i> <span>Copia Codice</span></button>
        </div>
        <pre><code class="language-php" id="maintenanceCode"><?php echo htmlspecialchars($maintenance_code); ?></code></pre>
    </div>
</div>