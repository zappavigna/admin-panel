<?php
// Meta tag templates
$metaTemplates = [
    'basic' => 'Meta Tag Base',
    'opengraph' => 'Open Graph (Facebook)',
    'twitter' => 'Twitter Card',
    'all' => 'Set Completo'
];

// Page types
$pageTypes = [
    'website' => 'Sito Web',
    'article' => 'Articolo/Blog',
    'product' => 'Prodotto',
    'video' => 'Video',
    'profile' => 'Profilo'
];

// Twitter card types
$twitterCardTypes = [
    'summary' => 'Summary',
    'summary_large_image' => 'Summary con immagine grande',
    'app' => 'App',
    'player' => 'Player'
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-tags"></i> Generatore Meta Tags</h1>
        <p class="text-muted">Crea meta tag ottimizzati per SEO e social media</p>
    </div>

    <form id="metaTagsForm" method="post">
        <!-- Basic Information -->
        <div class="seo-section">
            <h4><i class="fas fa-info-circle"></i> Informazioni Base</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-section">
                        <label for="siteTitle">Titolo del Sito/Pagina</label>
                        <input type="text" class="form-control" id="siteTitle" name="siteTitle" 
                               placeholder="Il tuo titolo (max 60 caratteri)" maxlength="60" required>
                        <small class="form-text text-muted">
                            <span id="titleCount">0</span>/60 caratteri
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-section">
                        <label for="siteUrl">URL della Pagina</label>
                        <input type="url" class="form-control" id="siteUrl" name="siteUrl" 
                               placeholder="https://tuosito.com/pagina" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <label for="siteDescription">Descrizione</label>
                <textarea class="form-control" id="siteDescription" name="siteDescription" 
                          rows="3" placeholder="Descrizione del sito/pagina (max 160 caratteri)" 
                          maxlength="160" required></textarea>
                <small class="form-text text-muted">
                    <span id="descCount">0</span>/160 caratteri - Questa è la descrizione che apparirà nei risultati di ricerca
                </small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-section">
                        <label for="siteKeywords">Keywords (Parole Chiave)</label>
                        <input type="text" class="form-control" id="siteKeywords" name="siteKeywords" 
                               placeholder="parola1, parola2, parola3">
                        <small class="form-text text-muted">Separate da virgole</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-section">
                        <label for="siteAuthor">Autore</label>
                        <input type="text" class="form-control" id="siteAuthor" name="siteAuthor" 
                               placeholder="Nome Autore">
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="seo-section">
            <h4><i class="fas fa-search"></i> Impostazioni SEO</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-section">
                        <label for="siteLanguage">Lingua</label>
                        <select class="form-select" id="siteLanguage" name="siteLanguage">
                            <option value="it_IT">Italiano</option>
                            <option value="en_US">English (US)</option>
                            <option value="en_GB">English (UK)</option>
                            <option value="es_ES">Español</option>
                            <option value="fr_FR">Français</option>
                            <option value="de_DE">Deutsch</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-section">
                        <label for="robots">Robots</label>
                        <select class="form-select" id="robots" name="robots">
                            <option value="index, follow">Index, Follow (Default)</option>
                            <option value="noindex, follow">NoIndex, Follow</option>
                            <option value="index, nofollow">Index, NoFollow</option>
                            <option value="noindex, nofollow">NoIndex, NoFollow</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-section">
                        <label for="revisitAfter">Revisit After</label>
                        <select class="form-select" id="revisitAfter" name="revisitAfter">
                            <option value="">Non specificare</option>
                            <option value="1 day">1 giorno</option>
                            <option value="3 days">3 giorni</option>
                            <option value="7 days">7 giorni</option>
                            <option value="14 days">14 giorni</option>
                            <option value="30 days">30 giorni</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <label>Impostazioni Aggiuntive</label>
                <div class="checkbox-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="canonical" name="canonical" value="1" checked>
                        <label class="form-check-label" for="canonical">
                            Aggiungi Canonical URL
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="viewport" name="viewport" value="1" checked>
                        <label class="form-check-label" for="viewport">
                            Aggiungi Viewport (Mobile)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="favicon" name="favicon" value="1">
                        <label class="form-check-label" for="favicon">
                            Includi Favicon
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="seo-section">
            <h4><i class="fas fa-share-alt"></i> Social Media</h4>
            
            <!-- Open Graph -->
            <div class="social-subsection">
                <h5><i class="fab fa-facebook"></i> Open Graph (Facebook, LinkedIn)</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-section">
                            <label for="ogType">Tipo di Pagina</label>
                            <select class="form-select" id="ogType" name="ogType">
                                <?php foreach ($pageTypes as $value => $label): ?>
                                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-section">
                            <label for="ogImage">URL Immagine</label>
                            <input type="url" class="form-control" id="ogImage" name="ogImage" 
                                   placeholder="https://tuosito.com/immagine.jpg">
                            <small class="form-text text-muted">Dimensioni consigliate: 1200x630px</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Twitter Card -->
            <div class="social-subsection">
                <h5><i class="fab fa-twitter"></i> Twitter Card</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-section">
                            <label for="twitterCard">Tipo di Card</label>
                            <select class="form-select" id="twitterCard" name="twitterCard">
                                <?php foreach ($twitterCardTypes as $value => $label): ?>
                                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-section">
                            <label for="twitterSite">Twitter Username</label>
                            <input type="text" class="form-control" id="twitterSite" name="twitterSite" 
                                   placeholder="@username">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Template Selection -->
        <div class="form-section">
            <label for="metaTemplate">Template da Generare</label>
            <select class="form-select" id="metaTemplate" name="metaTemplate">
                <?php foreach ($metaTemplates as $value => $label): ?>
                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-generate">
            <i class="fas fa-magic"></i> Genera Meta Tags
        </button>
    </form>

    <div class="code-output" id="metaTagsOutput" style="display: none;">
        <div class="output-tabs">
            <button class="output-tab active" onclick="switchOutput('html')">HTML</button>
            <button class="output-tab" onclick="switchOutput('wordpress')">WordPress</button>
            <button class="output-tab" onclick="switchOutput('preview')">Preview</button>
        </div>
        
        <div id="htmlOutput" class="output-content active">
            <div class="copy-btn-container">
                <button class="btn btn-copy" onclick="copyCode('metaTagsCode')">
                    <i class="fas fa-copy"></i> Copia HTML
                </button>
            </div>
            <pre><code class="language-html" id="metaTagsCode"></code></pre>
        </div>
        
        <div id="wordpressOutput" class="output-content" style="display: none;">
            <div class="copy-btn-container">
                <button class="btn btn-copy" onclick="copyCode('wpMetaTagsCode')">
                    <i class="fas fa-copy"></i> Copia PHP
                </button>
            </div>
            <pre><code class="language-php" id="wpMetaTagsCode"></code></pre>
        </div>
        
        <div id="previewOutput" class="output-content" style="display: none;">
            <div class="preview-container">
                <h5>Preview Google Search</h5>
                <div class="google-preview">
                    <div class="preview-title" id="previewTitle"></div>
                    <div class="preview-url" id="previewUrl"></div>
                    <div class="preview-description" id="previewDescription"></div>
                </div>
                
                <h5 class="mt-4">Preview Facebook</h5>
                <div class="facebook-preview">
                    <div class="fb-preview-image" id="fbPreviewImage"></div>
                    <div class="fb-preview-content">
                        <div class="fb-preview-url" id="fbPreviewUrl"></div>
                        <div class="fb-preview-title" id="fbPreviewTitle"></div>
                        <div class="fb-preview-description" id="fbPreviewDescription"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counters
document.getElementById('siteTitle').addEventListener('input', function() {
    document.getElementById('titleCount').textContent = this.value.length;
});

document.getElementById('siteDescription').addEventListener('input', function() {
    document.getElementById('descCount').textContent = this.value.length;
});

// Form submission
document.getElementById('metaTagsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Generate HTML meta tags
    let html = generateHTMLMetaTags(formData);
    let wordpress = generateWordPressMetaTags(formData);
    
    // Display code
    document.getElementById('metaTagsCode').textContent = html;
    document.getElementById('wpMetaTagsCode').textContent = wordpress;
    document.getElementById('metaTagsOutput').style.display = 'block';
    
    // Update preview
    updatePreview(formData);
    
    // Highlight code
    Prism.highlightElement(document.getElementById('metaTagsCode'));
    Prism.highlightElement(document.getElementById('wpMetaTagsCode'));
});

function generateHTMLMetaTags(formData) {
    const template = formData.get('metaTemplate');
    let html = '<!-- Meta Tags Generated by SEO Tool -->\n';
    
    // Basic meta tags
    if (template === 'basic' || template === 'all') {
        html += '<meta charset="UTF-8">\n';
        if (formData.get('viewport')) {
            html += '<meta name="viewport" content="width=device-width, initial-scale=1.0">\n';
        }
        html += `<title>${formData.get('siteTitle')}</title>\n`;
        html += `<meta name="description" content="${formData.get('siteDescription')}">\n`;
        
        if (formData.get('siteKeywords')) {
            html += `<meta name="keywords" content="${formData.get('siteKeywords')}">\n`;
        }
        
        if (formData.get('siteAuthor')) {
            html += `<meta name="author" content="${formData.get('siteAuthor')}">\n`;
        }
        
        html += `<meta name="robots" content="${formData.get('robots')}">\n`;
        
        if (formData.get('revisitAfter')) {
            html += `<meta name="revisit-after" content="${formData.get('revisitAfter')}">\n`;
        }
        
        if (formData.get('canonical')) {
            html += `<link rel="canonical" href="${formData.get('siteUrl')}">\n`;
        }
        
        html += '\n';
    }
    
    // Open Graph tags
    if (template === 'opengraph' || template === 'all') {
        html += '<!-- Open Graph / Facebook -->\n';
        html += `<meta property="og:type" content="${formData.get('ogType')}">\n`;
        html += `<meta property="og:url" content="${formData.get('siteUrl')}">\n`;
        html += `<meta property="og:title" content="${formData.get('siteTitle')}">\n`;
        html += `<meta property="og:description" content="${formData.get('siteDescription')}">\n`;
        
        if (formData.get('ogImage')) {
            html += `<meta property="og:image" content="${formData.get('ogImage')}">\n`;
        }
        
        html += `<meta property="og:locale" content="${formData.get('siteLanguage')}">\n`;
        html += '\n';
    }
    
    // Twitter Card tags
    if (template === 'twitter' || template === 'all') {
        html += '<!-- Twitter Card -->\n';
        html += `<meta property="twitter:card" content="${formData.get('twitterCard')}">\n`;
        html += `<meta property="twitter:url" content="${formData.get('siteUrl')}">\n`;
        html += `<meta property="twitter:title" content="${formData.get('siteTitle')}">\n`;
        html += `<meta property="twitter:description" content="${formData.get('siteDescription')}">\n`;
        
        if (formData.get('ogImage')) {
            html += `<meta property="twitter:image" content="${formData.get('ogImage')}">\n`;
        }
        
        if (formData.get('twitterSite')) {
            html += `<meta property="twitter:site" content="${formData.get('twitterSite')}">\n`;
        }
    }
    
    return html;
}

function generateWordPressMetaTags(formData) {
    let php = "<?php\n";
    php += "// Add meta tags to WordPress head\n";
    php += "function add_custom_meta_tags() {\n";
    php += "    ?>\n";
    php += generateHTMLMetaTags(formData).split('\n').map(line => '    ' + line).join('\n');
    php += "\n    <?php\n";
    php += "}\n";
    php += "add_action('wp_head', 'add_custom_meta_tags');\n\n";
    
    php += "// Modify document title\n";
    php += "function custom_document_title(\$title) {\n";
    php += `    if (is_home() || is_front_page()) {\n`;
    php += `        \$title = '${formData.get('siteTitle')}';\n`;
    php += "    }\n";
    php += "    return \$title;\n";
    php += "}\n";
    php += "add_filter('pre_get_document_title', 'custom_document_title');\n";
    php += "?>";
    
    return php;
}

function updatePreview(formData) {
    // Google preview
    document.getElementById('previewTitle').textContent = formData.get('siteTitle');
    document.getElementById('previewUrl').textContent = formData.get('siteUrl');
    document.getElementById('previewDescription').textContent = formData.get('siteDescription');
    
    // Facebook preview
    document.getElementById('fbPreviewUrl').textContent = new URL(formData.get('siteUrl')).hostname;
    document.getElementById('fbPreviewTitle').textContent = formData.get('siteTitle');
    document.getElementById('fbPreviewDescription').textContent = formData.get('siteDescription');
    
    if (formData.get('ogImage')) {
        document.getElementById('fbPreviewImage').style.backgroundImage = `url(${formData.get('ogImage')})`;
    }
}

function switchOutput(tab) {
    // Update tabs
    document.querySelectorAll('.output-tab').forEach(t => t.classList.remove('active'));
    event.target.classList.add('active');
    
    // Update content
    document.querySelectorAll('.output-content').forEach(c => {
        c.style.display = 'none';
        c.classList.remove('active');
    });
    
    document.getElementById(tab + 'Output').style.display = 'block';
    document.getElementById(tab + 'Output').classList.add('active');
}
</script>