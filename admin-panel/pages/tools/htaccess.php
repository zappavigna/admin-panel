<?php
// Htaccess templates
$htaccessTemplates = [
    'security' => [
        'name' => 'Sicurezza Base',
        'icon' => 'fas fa-shield-alt',
        'description' => 'Protezioni essenziali per WordPress'
    ],
    'performance' => [
        'name' => 'Performance',
        'icon' => 'fas fa-rocket',
        'description' => 'Compressione, cache e ottimizzazioni'
    ],
    'redirects' => [
        'name' => 'Redirect',
        'icon' => 'fas fa-directions',
        'description' => 'Gestione redirect e URL'
    ],
    'wordpress' => [
        'name' => 'WordPress',
        'icon' => 'fab fa-wordpress',
        'description' => 'Configurazioni specifiche per WordPress'
    ],
    'custom' => [
        'name' => 'Personalizzato',
        'icon' => 'fas fa-cog',
        'description' => 'Crea configurazione personalizzata'
    ]
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-file-code"></i> Generatore .htaccess</h1>
        <p class="text-muted">Crea file .htaccess ottimizzati per Apache</p>
    </div>

    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> <strong>Attenzione:</strong> Fai sempre un backup del tuo .htaccess esistente prima di applicare modifiche.
    </div>

    <!-- Template Selection -->
    <div class="htaccess-templates">
        <h4 class="mb-3">Seleziona un template o crea il tuo</h4>
        <div class="template-grid">
            <?php foreach ($htaccessTemplates as $key => $template): ?>
                <div class="template-card" data-template="<?php echo $key; ?>" onclick="selectHtaccessTemplate('<?php echo $key; ?>')">
                    <div class="template-icon">
                        <i class="<?php echo $template['icon']; ?>"></i>
                    </div>
                    <h5><?php echo $template['name']; ?></h5>
                    <p><?php echo $template['description']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Configuration Form -->
    <form id="htaccessForm" style="display: none;">
        <div class="htaccess-config">
            <h4 id="configTitle"></h4>
            
            <!-- Dynamic configuration options -->
            <div id="configOptions"></div>
            
            <!-- Common Options -->
            <div class="common-options mt-4">
                <h5>Opzioni Comuni</h5>
                <div class="checkbox-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="addComments" name="addComments" value="1" checked>
                        <label class="form-check-label" for="addComments">
                            Aggiungi commenti esplicativi
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="addTimestamp" name="addTimestamp" value="1" checked>
                        <label class="form-check-label" for="addTimestamp">
                            Aggiungi timestamp generazione
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-generate mt-3">
            <i class="fas fa-magic"></i> Genera .htaccess
        </button>
    </form>

    <!-- Output -->
    <div class="code-output" id="htaccessOutput" style="display: none;">
        <div class="output-header">
            <h5>.htaccess Generato</h5>
            <div class="output-actions">
                <button class="btn btn-sm btn-secondary" onclick="downloadHtaccess()">
                    <i class="fas fa-download"></i> Download
                </button>
                <button class="btn btn-copy" onclick="copyCode('htaccessCode')">
                    <i class="fas fa-copy"></i> Copia
                </button>
            </div>
        </div>
        <pre><code class="language-apache" id="htaccessCode"></code></pre>
        
        <div class="htaccess-info mt-3">
            <h5>Come utilizzare:</h5>
            <ol>
                <li>Copia il codice generato o scarica il file</li>
                <li>Fai un backup del tuo .htaccess attuale</li>
                <li>Incolla il nuovo codice nel file .htaccess nella root del tuo sito</li>
                <li>Testa il sito per verificare che tutto funzioni correttamente</li>
            </ol>
        </div>
    </div>
</div>

<style>
.template-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.template-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.template-card:hover {
    border-color: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.template-card.active {
    border-color: var(--success-color);
    background: #e8f5e9;
}

.template-icon {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 15px;
}

.template-card h5 {
    margin-bottom: 10px;
    font-weight: 600;
}

.template-card p {
    margin: 0;
    font-size: 0.9rem;
    color: #6c757d;
}

.htaccess-config {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.config-option {
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.config-option label {
    font-weight: 500;
    margin-bottom: 5px;
    display: block;
}

.config-option .form-text {
    font-size: 0.85rem;
    color: #6c757d;
}

.output-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.output-actions {
    display: flex;
    gap: 10px;
}
</style>

<script>
let currentTemplate = null;

// Template configurations
const templateConfigs = {
    security: {
        title: 'Configurazione Sicurezza',
        options: [
            {
                type: 'checkbox',
                name: 'blockFileAccess',
                label: 'Blocca accesso a file sensibili',
                checked: true,
                help: 'Blocca accesso a wp-config.php, .htaccess, etc.'
            },
            {
                type: 'checkbox',
                name: 'blockDirectoryListing',
                label: 'Disabilita listing directory',
                checked: true,
                help: 'Impedisce la visualizzazione del contenuto delle directory'
            },
            {
                type: 'checkbox',
                name: 'blockXMLRPC',
                label: 'Blocca XML-RPC',
                checked: true,
                help: 'Disabilita XML-RPC per prevenire attacchi'
            },
            {
                type: 'checkbox',
                name: 'protectWPIncludes',
                label: 'Proteggi wp-includes',
                checked: true,
                help: 'Blocca accesso diretto a wp-includes'
            },
            {
                type: 'checkbox',
                name: 'blockAuthorScanning',
                label: 'Blocca scansione autori',
                checked: true,
                help: 'Previene enumerazione utenti WordPress'
            },
            {
                type: 'checkbox',
                name: 'disableHotlinking',
                label: 'Disabilita hotlinking immagini',
                checked: false,
                help: 'Impedisce ad altri siti di linkare le tue immagini'
            }
        ]
    },
    performance: {
        title: 'Configurazione Performance',
        options: [
            {
                type: 'checkbox',
                name: 'enableGzip',
                label: 'Abilita compressione GZIP',
                checked: true,
                help: 'Comprime file HTML, CSS, JS per velocizzare il caricamento'
            },
            {
                type: 'checkbox',
                name: 'enableBrowserCache',
                label: 'Abilita cache browser',
                checked: true,
                help: 'Imposta headers di cache per risorse statiche'
            },
            {
                type: 'checkbox',
                name: 'enableDeflate',
                label: 'Abilita mod_deflate',
                checked: true,
                help: 'Metodo alternativo di compressione'
            },
            {
                type: 'checkbox',
                name: 'enableETags',
                label: 'Disabilita ETags',
                checked: true,
                help: 'Migliora performance disabilitando ETags'
            },
            {
                type: 'select',
                name: 'cacheExpiration',
                label: 'Durata cache browser',
                options: [
                    { value: '1week', text: '1 settimana' },
                    { value: '1month', text: '1 mese' },
                    { value: '3months', text: '3 mesi' },
                    { value: '1year', text: '1 anno' }
                ],
                defaultValue: '1month',
                help: 'Quanto tempo i browser devono mantenere i file in cache'
            }
        ]
    },
    redirects: {
        title: 'Configurazione Redirect',
        options: [
            {
                type: 'checkbox',
                name: 'forceHTTPS',
                label: 'Forza HTTPS',
                checked: true,
                help: 'Reindirizza tutto il traffico HTTP verso HTTPS'
            },
            {
                type: 'checkbox',
                name: 'forceWWW',
                label: 'Forza WWW',
                checked: false,
                help: 'Reindirizza non-www a www'
            },
            {
                type: 'checkbox',
                name: 'forceNonWWW',
                label: 'Forza non-WWW',
                checked: false,
                help: 'Reindirizza www a non-www'
            },
            {
                type: 'checkbox',
                name: 'removeTrailingSlash',
                label: 'Rimuovi slash finale',
                checked: true,
                help: 'Rimuove lo slash finale dagli URL'
            },
            {
                type: 'textarea',
                name: 'customRedirects',
                label: 'Redirect personalizzati',
                placeholder: 'Redirect 301 /vecchia-pagina /nuova-pagina',
                help: 'Inserisci redirect personalizzati (uno per riga)'
            }
        ]
    },
    wordpress: {
        title: 'Configurazione WordPress',
        options: [
            {
                type: 'checkbox',
                name: 'wpBasicSecurity',
                label: 'Sicurezza base WordPress',
                checked: true,
                help: 'Protezioni standard per WordPress'
            },
            {
                type: 'checkbox',
                name: 'wpProtectAdmin',
                label: 'Proteggi area admin',
                checked: true,
                help: 'Protezione aggiuntiva per wp-admin'
            },
            {
                type: 'checkbox',
                name: 'wpDisableFileEdit',
                label: 'Disabilita editor file',
                checked: true,
                help: 'Blocca modifica file da admin WordPress'
            },
            {
                type: 'checkbox',
                name: 'wpLimitUploadSize',
                label: 'Limita dimensione upload',
                checked: false,
                help: 'Imposta limite massimo upload file'
            },
            {
                type: 'select',
                name: 'wpUploadLimit',
                label: 'Limite upload (MB)',
                options: [
                    { value: '2', text: '2 MB' },
                    { value: '5', text: '5 MB' },
                    { value: '10', text: '10 MB' },
                    { value: '25', text: '25 MB' },
                    { value: '50', text: '50 MB' }
                ],
                defaultValue: '10',
                showIf: 'wpLimitUploadSize'
            }
        ]
    },
    custom: {
        title: 'Configurazione Personalizzata',
        options: [
            {
                type: 'textarea',
                name: 'customRules',
                label: 'Regole personalizzate',
                placeholder: '# Le tue regole htaccess personalizzate',
                rows: 10,
                help: 'Inserisci le tue regole htaccess personalizzate'
            }
        ]
    }
};

function selectHtaccessTemplate(template) {
    currentTemplate = template;
    
    // Update UI
    document.querySelectorAll('.template-card').forEach(card => {
        card.classList.remove('active');
    });
    document.querySelector(`[data-template="${template}"]`).classList.add('active');
    
    // Show form
    document.getElementById('htaccessForm').style.display = 'block';
    
    // Load configuration
    const config = templateConfigs[template];
    document.getElementById('configTitle').textContent = config.title;
    
    // Generate options HTML
    let optionsHTML = '';
    config.options.forEach(option => {
        optionsHTML += generateOptionHTML(option);
    });
    
    document.getElementById('configOptions').innerHTML = optionsHTML;
    
    // Add event listeners for conditional fields
    config.options.forEach(option => {
        if (option.showIf) {
            const trigger = document.getElementById(option.showIf);
            const field = document.getElementById(option.name + '-container');
            
            if (trigger && field) {
                trigger.addEventListener('change', function() {
                    field.style.display = this.checked ? 'block' : 'none';
                });
                
                // Initial state
                field.style.display = trigger.checked ? 'block' : 'none';
            }
        }
    });
    
    // Scroll to form
    document.getElementById('htaccessForm').scrollIntoView({ behavior: 'smooth' });
}

function generateOptionHTML(option) {
    let html = '<div class="config-option"';
    if (option.showIf) {
        html += ` id="${option.name}-container"`;
    }
    html += '>';
    
    switch(option.type) {
        case 'checkbox':
            html += `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" 
                           id="${option.name}" name="${option.name}" 
                           value="1" ${option.checked ? 'checked' : ''}>
                    <label class="form-check-label" for="${option.name}">
                        ${option.label}
                    </label>
                    ${option.help ? `<small class="form-text text-muted d-block">${option.help}</small>` : ''}
                </div>
            `;
            break;
            
        case 'select':
            html += `
                <label for="${option.name}">${option.label}</label>
                <select class="form-select" id="${option.name}" name="${option.name}">
                    ${option.options.map(opt => `
                        <option value="${opt.value}" ${opt.value === option.defaultValue ? 'selected' : ''}>
                            ${opt.text}
                        </option>
                    `).join('')}
                </select>
                ${option.help ? `<small class="form-text text-muted">${option.help}</small>` : ''}
            `;
            break;
            
        case 'textarea':
            html += `
                <label for="${option.name}">${option.label}</label>
                <textarea class="form-control" id="${option.name}" name="${option.name}" 
                          rows="${option.rows || 3}" placeholder="${option.placeholder || ''}">${option.value || ''}</textarea>
                ${option.help ? `<small class="form-text text-muted">${option.help}</small>` : ''}
            `;
            break;
    }
    
    html += '</div>';
    return html;
}

// Form submission
document.getElementById('htaccessForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('template', currentTemplate);
    
    // Generate htaccess content
    const htaccessContent = generateHtaccessContent(formData);
    
    // Display result
    document.getElementById('htaccessCode').textContent = htaccessContent;
    document.getElementById('htaccessOutput').style.display = 'block';
    
    // Highlight code
    Prism.highlightElement(document.getElementById('htaccessCode'));
    
    // Scroll to output
    document.getElementById('htaccessOutput').scrollIntoView({ behavior: 'smooth' });
});

function generateHtaccessContent(formData) {
    let content = '';
    const addComments = formData.get('addComments');
    const addTimestamp = formData.get('addTimestamp');
    const template = formData.get('template');
    
    // Header
    if (addComments) {
        content += '# ===================================\n';
        content += '# .htaccess Configuration\n';
        content += `# Template: ${templateConfigs[template].title}\n`;
        if (addTimestamp) {
            content += `# Generated: ${new Date().toLocaleString('it-IT')}\n`;
        }
        content += '# ===================================\n\n';
    }
    
    // Generate based on template
    switch(template) {
        case 'security':
            content += generateSecurityRules(formData, addComments);
            break;
        case 'performance':
            content += generatePerformanceRules(formData, addComments);
            break;
        case 'redirects':
            content += generateRedirectRules(formData, addComments);
            break;
        case 'wordpress':
            content += generateWordPressRules(formData, addComments);
            break;
        case 'custom':
            content += formData.get('customRules') || '';
            break;
    }
    
    return content;
}

function generateSecurityRules(formData, addComments) {
    let rules = '';
    
    // Block file access
    if (formData.get('blockFileAccess')) {
        if (addComments) {
            rules += '# Protect sensitive files\n';
        }
        rules += '<FilesMatch "^.*(error_log|wp-config\\.php|php\\.ini|\\.htaccess|\\.htpasswd)$">\n';
        rules += '    Order deny,allow\n';
        rules += '    Deny from all\n';
        rules += '</FilesMatch>\n\n';
    }
    
    // Block directory listing
    if (formData.get('blockDirectoryListing')) {
        if (addComments) {
            rules += '# Disable directory listing\n';
        }
        rules += 'Options -Indexes\n\n';
    }
    
    // Block XML-RPC
    if (formData.get('blockXMLRPC')) {
        if (addComments) {
            rules += '# Block XML-RPC\n';
        }
        rules += '<Files xmlrpc.php>\n';
        rules += '    Order deny,allow\n';
        rules += '    Deny from all\n';
        rules += '</Files>\n\n';
    }
    
    // Protect wp-includes
    if (formData.get('protectWPIncludes')) {
        if (addComments) {
            rules += '# Protect wp-includes\n';
        }
        rules += '<IfModule mod_rewrite.c>\n';
        rules += '    RewriteEngine On\n';
        rules += '    RewriteBase /\n';
        rules += '    RewriteRule ^wp-admin/includes/ - [F,L]\n';
        rules += '    RewriteRule !^wp-includes/ - [S=3]\n';
        rules += '    RewriteRule ^wp-includes/[^/]+\\.php$ - [F,L]\n';
        rules += '    RewriteRule ^wp-includes/js/tinymce/langs/.+\\.php - [F,L]\n';
        rules += '    RewriteRule ^wp-includes/theme-compat/ - [F,L]\n';
        rules += '</IfModule>\n\n';
    }
    
    // Block author scanning
    if (formData.get('blockAuthorScanning')) {
        if (addComments) {
            rules += '# Block author scanning\n';
        }
        rules += '<IfModule mod_rewrite.c>\n';
        rules += '    RewriteCond %{QUERY_STRING} ^author=([0-9]*)\n';
        rules += '    RewriteRule .* - [F]\n';
        rules += '</IfModule>\n\n';
    }
    
    // Disable hotlinking
    if (formData.get('disableHotlinking')) {
        if (addComments) {
            rules += '# Disable image hotlinking\n';
        }
        rules += '<IfModule mod_rewrite.c>\n';
        rules += '    RewriteCond %{HTTP_REFERER} !^$\n';
        rules += '    RewriteCond %{HTTP_REFERER} !^http(s)?://(www\\.)?' + window.location.hostname + ' [NC]\n';
        rules += '    RewriteRule \\.(jpg|jpeg|png|gif|webp)$ - [F]\n';
        rules += '</IfModule>\n\n';
    }
    
    return rules;
}

function generatePerformanceRules(formData, addComments) {
    let rules = '';
    
    // Enable GZIP
    if (formData.get('enableGzip')) {
        if (addComments) {
            rules += '# Enable GZIP compression\n';
        }
        rules += '<IfModule mod_gzip.c>\n';
        rules += '    mod_gzip_on Yes\n';
        rules += '    mod_gzip_dechunk Yes\n';
        rules += '    mod_gzip_item_include file \\.(html?|txt|css|js|php|pl)$\n';
        rules += '    mod_gzip_item_include handler ^cgi-script$\n';
        rules += '    mod_gzip_item_include mime ^text/.*\n';
        rules += '    mod_gzip_item_include mime ^application/x-javascript.*\n';
        rules += '    mod_gzip_item_exclude mime ^image/.*\n';
        rules += '    mod_gzip_item_exclude rspheader ^Content-Encoding:.*gzip.*\n';
        rules += '</IfModule>\n\n';
    }
    
    // Enable Deflate
    if (formData.get('enableDeflate')) {
        if (addComments) {
            rules += '# Enable Deflate compression\n';
        }
        rules += '<IfModule mod_deflate.c>\n';
        rules += '    AddOutputFilterByType DEFLATE text/plain\n';
        rules += '    AddOutputFilterByType DEFLATE text/html\n';
        rules += '    AddOutputFilterByType DEFLATE text/xml\n';
        rules += '    AddOutputFilterByType DEFLATE text/css\n';
        rules += '    AddOutputFilterByType DEFLATE application/xml\n';
        rules += '    AddOutputFilterByType DEFLATE application/xhtml+xml\n';
        rules += '    AddOutputFilterByType DEFLATE application/rss+xml\n';
        rules += '    AddOutputFilterByType DEFLATE application/javascript\n';
        rules += '    AddOutputFilterByType DEFLATE application/x-javascript\n';
        rules += '</IfModule>\n\n';
    }
    
    // Browser caching
    if (formData.get('enableBrowserCache')) {
        const expiration = formData.get('cacheExpiration') || '1month';
        let expirationTime = '';
        
        switch(expiration) {
            case '1week':
                expirationTime = '604800';
                break;
            case '1month':
                expirationTime = '2592000';
                break;
            case '3months':
                expirationTime = '7776000';
                break;
            case '1year':
                expirationTime = '31536000';
                break;
        }
        
        if (addComments) {
            rules += '# Enable browser caching\n';
        }
        rules += '<IfModule mod_expires.c>\n';
        rules += '    ExpiresActive On\n';
        rules += '    ExpiresByType image/jpg "access plus ' + expiration.replace(/(\d+)/, '$1 ') + '"\n';
        rules += '    ExpiresByType image/jpeg "access plus ' + expiration.replace(/(\d+)/, '$1 ') + '"\n';
        rules += '    ExpiresByType image/gif "access plus ' + expiration.replace(/(\d+)/, '$1 ') + '"\n';
        rules += '    ExpiresByType image/png "access plus ' + expiration.replace(/(\d+)/, '$1 ') + '"\n';
        rules += '    ExpiresByType image/webp "access plus ' + expiration.replace(/(\d+)/, '$1 ') + '"\n';
        rules += '    ExpiresByType text/css "access plus 1 month"\n';
        rules += '    ExpiresByType text/html "access plus 1 hour"\n';
        rules += '    ExpiresByType text/javascript "access plus 1 month"\n';
        rules += '    ExpiresByType application/javascript "access plus 1 month"\n';
        rules += '    ExpiresByType application/x-javascript "access plus 1 month"\n';
        rules += '    ExpiresByType application/pdf "access plus 1 month"\n';
        rules += '    ExpiresByType application/x-shockwave-flash "access plus 1 month"\n';
        rules += '    ExpiresByType image/x-icon "access plus 1 year"\n';
        rules += '    ExpiresDefault "access plus 2 days"\n';
        rules += '</IfModule>\n\n';
        
        // Add Cache-Control headers
        rules += '<IfModule mod_headers.c>\n';
        rules += '    <FilesMatch "\\.(jpg|jpeg|png|gif|webp|css|js)$">\n';
        rules += '        Header set Cache-Control "max-age=' + expirationTime + ', public"\n';
        rules += '    </FilesMatch>\n';
        rules += '</IfModule>\n\n';
    }
    
    // Disable ETags
    if (formData.get('enableETags')) {
        if (addComments) {
            rules += '# Disable ETags\n';
        }
        rules += 'FileETag None\n';
        rules += '<IfModule mod_headers.c>\n';
        rules += '    Header unset ETag\n';
        rules += '</IfModule>\n\n';
    }
    
    return rules;
}

function generateRedirectRules(formData, addComments) {
    let rules = '';
    
    // Force HTTPS
    if (formData.get('forceHTTPS')) {
        if (addComments) {
            rules += '# Force HTTPS\n';
        }
        rules += '<IfModule mod_rewrite.c>\n';
        rules += '    RewriteEngine On\n';
        rules += '    RewriteCond %{HTTPS} off\n';
        rules += '    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\n';
        rules += '</IfModule>\n\n';
    }
    
    // Force WWW
    if (formData.get('forceWWW') && !formData.get('forceNonWWW')) {
        if (addComments) {
            rules += '# Force WWW\n';
        }
        rules += '<IfModule mod_rewrite.c>\n';
        rules += '    RewriteEngine On\n';
        rules += '    RewriteCond %{HTTP_HOST} !^www\\. [NC]\n';
        rules += '    RewriteRule ^(.*)$ https://www.%{HTTP_HOST}/$1 [R=301,L]\n';
        rules += '</IfModule>\n\n';
    }
    
    // Force non-WWW
    if (formData.get('forceNonWWW') && !formData.get('forceWWW')) {
        if (addComments) {
            rules += '# Force non-WWW\n';
        }
        rules += '<IfModule mod_rewrite.c>\n';
        rules += '    RewriteEngine On\n';
        rules += '    RewriteCond %{HTTP_HOST} ^www\\.(.*)$ [NC]\n';
        rules += '    RewriteRule ^(.*)$ https://%1/$1 [R=301,L]\n';
        rules += '</IfModule>\n\n';
    }
    
    // Remove trailing slash
    if (formData.get('removeTrailingSlash')) {
        if (addComments) {
            rules += '# Remove trailing slash\n';
        }
        rules += '<IfModule mod_rewrite.c>\n';
        rules += '    RewriteCond %{REQUEST_FILENAME} !-d\n';
        rules += '    RewriteRule ^(.*)/$ /$1 [L,R=301]\n';
        rules += '</IfModule>\n\n';
    }
    
    // Custom redirects
    const customRedirects = formData.get('customRedirects');
    if (customRedirects) {
        if (addComments) {
            rules += '# Custom redirects\n';
        }
        rules += customRedirects + '\n\n';
    }
    
    return rules;
}

function generateWordPressRules(formData, addComments) {
    let rules = '';
    
    // Basic WordPress security
    if (formData.get('wpBasicSecurity')) {
        if (addComments) {
            rules += '# WordPress basic security\n';
        }
        
        // Protect wp-config
        rules += '<Files wp-config.php>\n';
        rules += '    Order allow,deny\n';
        rules += '    Deny from all\n';
        rules += '</Files>\n\n';
        
        // Protect .htaccess itself
        rules += '<Files .htaccess>\n';
        rules += '    Order allow,deny\n';
        rules += '    Deny from all\n';
        rules += '</Files>\n\n';
        
        // Disable PHP in uploads
        rules += '<Directory "/wp-content/uploads/">\n';
        rules += '    <Files "*.php">\n';
        rules += '        Order allow,deny\n';
        rules += '        Deny from all\n';
        rules += '    </Files>\n';
        rules += '</Directory>\n\n';
    }
    
    // Protect admin area
    if (formData.get('wpProtectAdmin')) {
        if (addComments) {
            rules += '# Protect admin area\n';
        }
        rules += '<IfModule mod_rewrite.c>\n';
        rules += '    RewriteEngine On\n';
        rules += '    RewriteCond %{REQUEST_METHOD} POST\n';
        rules += '    RewriteCond %{REQUEST_URI} .*/(wp-comments-post\\.php|wp-login\\.php|wp-admin) [NC]\n';
        rules += '    RewriteCond %{HTTP_REFERER} !.*(' + window.location.hostname + ') [NC]\n';
        rules += '    RewriteRule (.*) - [F]\n';
        rules += '</IfModule>\n\n';
    }
    
    // Disable file editing
    if (formData.get('wpDisableFileEdit')) {
        if (addComments) {
            rules += '# Note: Add this to wp-config.php instead:\n';
            rules += '# define(\'DISALLOW_FILE_EDIT\', true);\n\n';
        }
    }
    
    // Limit upload size
    if (formData.get('wpLimitUploadSize')) {
        const limit = formData.get('wpUploadLimit') || '10';
        if (addComments) {
            rules += '# Limit upload size\n';
        }
        rules += 'php_value upload_max_filesize ' + limit + 'M\n';
        rules += 'php_value post_max_size ' + limit + 'M\n';
        rules += 'php_value max_execution_time 300\n';
        rules += 'php_value max_input_time 300\n\n';
    }
    
    // Standard WordPress rules
    if (addComments) {
        rules += '# BEGIN WordPress\n';
    }
    rules += '<IfModule mod_rewrite.c>\n';
    rules += '    RewriteEngine On\n';
    rules += '    RewriteBase /\n';
    rules += '    RewriteRule ^index\\.php$ - [L]\n';
    rules += '    RewriteCond %{REQUEST_FILENAME} !-f\n';
    rules += '    RewriteCond %{REQUEST_FILENAME} !-d\n';
    rules += '    RewriteRule . /index.php [L]\n';
    rules += '</IfModule>\n';
    if (addComments) {
        rules += '# END WordPress\n';
    }
    
    return rules;
}

// Download function
function downloadHtaccess() {
    const content = document.getElementById('htaccessCode').textContent;
    const blob = new Blob([content], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = '.htaccess';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
    
    showNotification('File .htaccess scaricato!', 'success');
}
</script>