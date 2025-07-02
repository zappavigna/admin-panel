<?php
// Popular CDN providers
$cdnProviders = [
    [
        'id' => 'cloudflare',
        'name' => 'Cloudflare CDN',
        'url' => 'https://cdnjs.cloudflare.com',
        'description' => 'CDN gratuito e veloce con migliaia di librerie',
        'icon' => 'fas fa-cloud',
        'color' => '#f48120'
    ],
    [
        'id' => 'jsdelivr',
        'name' => 'jsDelivr',
        'url' => 'https://cdn.jsdelivr.net',
        'description' => 'CDN open source con supporto NPM, GitHub',
        'icon' => 'fas fa-rocket',
        'color' => '#e84d3d'
    ],
    [
        'id' => 'unpkg',
        'name' => 'UNPKG',
        'url' => 'https://unpkg.com',
        'description' => 'CDN veloce per tutto ciò che è su NPM',
        'icon' => 'fas fa-box',
        'color' => '#333333'
    ],
    [
        'id' => 'googleapis',
        'name' => 'Google Hosted Libraries',
        'url' => 'https://ajax.googleapis.com',
        'description' => 'Librerie popolari hostate da Google',
        'icon' => 'fab fa-google',
        'color' => '#4285f4'
    ],
    [
        'id' => 'microsoft',
        'name' => 'Microsoft Ajax CDN',
        'url' => 'https://ajax.aspnetcdn.com',
        'description' => 'CDN Microsoft per librerie popolari',
        'icon' => 'fab fa-microsoft',
        'color' => '#00a4ef'
    ]
];

// Popular libraries for testing
$testLibraries = [
    'jquery' => [
        'name' => 'jQuery',
        'version' => '3.7.0',
        'paths' => [
            'cloudflare' => '/ajax/libs/jquery/3.7.0/jquery.min.js',
            'googleapis' => '/ajax/libs/jquery/3.7.0/jquery.min.js',
            'microsoft' => '/ajax/jQuery/jquery-3.7.0.min.js',
            'jsdelivr' => '/npm/jquery@3.7.0/dist/jquery.min.js',
            'unpkg' => '/jquery@3.7.0/dist/jquery.min.js'
        ]
    ],
    'bootstrap' => [
        'name' => 'Bootstrap CSS',
        'version' => '5.3.0',
        'paths' => [
            'cloudflare' => '/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css',
            'jsdelivr' => '/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
            'unpkg' => '/bootstrap@5.3.0/dist/css/bootstrap.min.css'
        ]
    ],
    'fontawesome' => [
        'name' => 'Font Awesome',
        'version' => '6.4.0',
        'paths' => [
            'cloudflare' => '/ajax/libs/font-awesome/6.4.0/css/all.min.css',
            'jsdelivr' => '/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css',
            'unpkg' => '/@fortawesome/fontawesome-free@6.4.0/css/all.min.css'
        ]
    ]
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-cloud"></i> CDN Manager</h1>
        <p class="text-muted">Gestisci e testa le CDN per le tue librerie JavaScript e CSS</p>
    </div>

    <!-- CDN Providers -->
    <div class="cdn-section">
        <h4 class="mb-3"><i class="fas fa-server"></i> Provider CDN Popolari</h4>
        <div class="cdn-providers-grid">
            <?php foreach ($cdnProviders as $provider): ?>
                <div class="cdn-provider-card" data-provider="<?php echo $provider['id']; ?>">
                    <div class="cdn-icon" style="color: <?php echo $provider['color']; ?>">
                        <i class="<?php echo $provider['icon']; ?>"></i>
                    </div>
                    <div class="cdn-info">
                        <h5><?php echo $provider['name']; ?></h5>
                        <p class="text-muted small mb-1"><?php echo $provider['description']; ?></p>
                        <code class="small"><?php echo $provider['url']; ?></code>
                    </div>
                    <div class="cdn-status" id="status-<?php echo $provider['id']; ?>">
                        <span class="badge bg-secondary">Non testato</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <button class="btn btn-primary mt-3" onclick="testAllCDNs()">
            <i class="fas fa-network-wired"></i> Testa Tutti i CDN
        </button>
    </div>

    <!-- CDN URL Builder -->
    <div class="cdn-section mt-5">
        <h4 class="mb-3"><i class="fas fa-link"></i> Generatore URL CDN</h4>
        <form id="cdnUrlBuilder">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-section">
                        <label for="cdnProvider">Provider CDN</label>
                        <select class="form-select" id="cdnProvider" name="cdnProvider">
                            <?php foreach ($cdnProviders as $provider): ?>
                                <option value="<?php echo $provider['id']; ?>" data-url="<?php echo $provider['url']; ?>">
                                    <?php echo $provider['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-section">
                        <label for="packageName">Nome Pacchetto</label>
                        <input type="text" class="form-control" id="packageName" name="packageName" 
                               placeholder="es. jquery, bootstrap, lodash" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-section">
                        <label for="packageVersion">Versione</label>
                        <input type="text" class="form-control" id="packageVersion" name="packageVersion" 
                               placeholder="es. 3.7.0, latest" value="latest">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-section">
                        <label for="filePath">Percorso File (opzionale)</label>
                        <input type="text" class="form-control" id="filePath" name="filePath" 
                               placeholder="es. dist/jquery.min.js">
                        <small class="form-text text-muted">Lascia vuoto per il file principale</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-section">
                        <label for="fileType">Tipo File</label>
                        <select class="form-select" id="fileType" name="fileType">
                            <option value="auto">Auto-detect</option>
                            <option value="js">JavaScript (.js)</option>
                            <option value="css">CSS (.css)</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-generate">
                <i class="fas fa-magic"></i> Genera URL
            </button>
        </form>
        
        <div class="cdn-url-output" id="cdnUrlOutput" style="display: none;">
            <h5>URL Generati:</h5>
            <div id="generatedUrls"></div>
        </div>
    </div>

    <!-- CDN Comparison Tool -->
    <div class="cdn-section mt-5">
        <h4 class="mb-3"><i class="fas fa-chart-bar"></i> Confronto Performance CDN</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="form-section">
                    <label for="testLibrary">Libreria da Testare</label>
                    <select class="form-select" id="testLibrary">
                        <?php foreach ($testLibraries as $key => $lib): ?>
                            <option value="<?php echo $key; ?>">
                                <?php echo $lib['name']; ?> v<?php echo $lib['version']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <button class="btn btn-info mt-4" onclick="compareLibrarySpeed()">
                    <i class="fas fa-tachometer-alt"></i> Confronta Velocità
                </button>
            </div>
        </div>
        
        <div id="performanceResults" class="performance-results mt-4" style="display: none;">
            <h5>Risultati Performance:</h5>
            <div class="results-grid" id="resultsGrid"></div>
        </div>
    </div>

    <!-- Custom CDN -->
    <div class="cdn-section mt-5">
        <h4 class="mb-3"><i class="fas fa-cog"></i> CDN Personalizzato</h4>
        <form id="customCdnForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-section">
                        <label for="customCdnUrl">URL Base CDN</label>
                        <input type="url" class="form-control" id="customCdnUrl" name="customCdnUrl" 
                               placeholder="https://cdn.tuosito.com" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-section">
                        <label for="customCdnName">Nome CDN</label>
                        <input type="text" class="form-control" id="customCdnName" name="customCdnName" 
                               placeholder="My Custom CDN" required>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <label>Configurazione WordPress</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="replaceWpUrls" name="replaceWpUrls" value="1">
                    <label class="form-check-label" for="replaceWpUrls">
                        Sostituisci automaticamente URL di wp-content
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="cdnImages" name="cdnImages" value="1">
                    <label class="form-check-label" for="cdnImages">
                        Includi immagini
                    </label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-generate">
                <i class="fas fa-plus"></i> Genera Codice CDN
            </button>
        </form>
        
        <div class="code-output" id="customCdnOutput" style="display: none;">
            <div class="copy-btn-container">
                <button class="btn btn-copy" onclick="copyCode('customCdnCode')">
                    <i class="fas fa-copy"></i> Copia
                </button>
            </div>
            <pre><code class="language-php" id="customCdnCode"></code></pre>
        </div>
    </div>
</div>

<script>
// Test all CDNs
async function testAllCDNs() {
    const providers = <?php echo json_encode($cdnProviders); ?>;
    
    for (let provider of providers) {
        const statusEl = document.getElementById('status-' + provider.id);
        statusEl.innerHTML = '<span class="badge bg-warning">Testing...</span>';
        
        try {
            const start = performance.now();
            const response = await fetch(provider.url + '/ajax/libs/jquery/3.7.0/jquery.min.js', {
                method: 'HEAD',
                mode: 'no-cors'
            });
            const end = performance.now();
            const time = Math.round(end - start);
            
            statusEl.innerHTML = `<span class="badge bg-success">Online (${time}ms)</span>`;
        } catch (error) {
            statusEl.innerHTML = '<span class="badge bg-danger">Offline</span>';
        }
    }
}

// CDN URL Builder
document.getElementById('cdnUrlBuilder').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const provider = document.getElementById('cdnProvider').value;
    const providerUrl = document.querySelector(`#cdnProvider option[value="${provider}"]`).dataset.url;
    const packageName = document.getElementById('packageName').value;
    const version = document.getElementById('packageVersion').value;
    const filePath = document.getElementById('filePath').value;
    
    let urls = [];
    
    // Generate URLs based on provider
    switch(provider) {
        case 'cloudflare':
            if (filePath) {
                urls.push(`${providerUrl}/ajax/libs/${packageName}/${version}/${filePath}`);
            } else {
                urls.push(`${providerUrl}/ajax/libs/${packageName}/${version}/${packageName}.min.js`);
                urls.push(`${providerUrl}/ajax/libs/${packageName}/${version}/${packageName}.min.css`);
            }
            break;
            
        case 'jsdelivr':
            if (filePath) {
                urls.push(`${providerUrl}/npm/${packageName}@${version}/${filePath}`);
            } else {
                urls.push(`${providerUrl}/npm/${packageName}@${version}`);
            }
            break;
            
        case 'unpkg':
            if (filePath) {
                urls.push(`${providerUrl}/${packageName}@${version}/${filePath}`);
            } else {
                urls.push(`${providerUrl}/${packageName}@${version}`);
            }
            break;
            
        case 'googleapis':
            // Google has specific paths
            const googleLibs = {
                'jquery': 'jquery',
                'angular': 'angularjs',
                'dojo': 'dojo',
                'mootools': 'mootools',
                'prototype': 'prototype',
                'scriptaculous': 'scriptaculous',
                'spf': 'spf',
                'swfobject': 'swfobject',
                'three': 'threejs',
                'webfont': 'webfont'
            };
            
            if (googleLibs[packageName]) {
                urls.push(`${providerUrl}/ajax/libs/${googleLibs[packageName]}/${version}/${packageName}.min.js`);
            }
            break;
    }
    
    // Display URLs
    const output = document.getElementById('cdnUrlOutput');
    const urlsContainer = document.getElementById('generatedUrls');
    
    urlsContainer.innerHTML = urls.map(url => `
        <div class="url-item">
            <code>${url}</code>
            <button class="btn btn-sm btn-primary" onclick="copyToClipboard('${url}')">
                <i class="fas fa-copy"></i>
            </button>
        </div>
    `).join('');
    
    output.style.display = 'block';
});

// Compare library speed
async function compareLibrarySpeed() {
    const library = document.getElementById('testLibrary').value;
    const libData = <?php echo json_encode($testLibraries); ?>[library];
    const resultsGrid = document.getElementById('resultsGrid');
    const performanceResults = document.getElementById('performanceResults');
    
    performanceResults.style.display = 'block';
    resultsGrid.innerHTML = '<div class="text-center w-100"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    
    const results = [];
    
    for (let [provider, path] of Object.entries(libData.paths)) {
        const providerData = <?php echo json_encode($cdnProviders); ?>.find(p => p.id === provider);
        if (!providerData) continue;
        
        const url = providerData.url + path;
        
        try {
            const start = performance.now();
            await fetch(url, { mode: 'no-cors' });
            const end = performance.now();
            
            results.push({
                provider: providerData.name,
                time: Math.round(end - start),
                url: url
            });
        } catch (error) {
            results.push({
                provider: providerData.name,
                time: -1,
                url: url
            });
        }
    }
    
    // Sort by speed
    results.sort((a, b) => {
        if (a.time === -1) return 1;
        if (b.time === -1) return -1;
        return a.time - b.time;
    });
    
    // Display results
    resultsGrid.innerHTML = results.map((result, index) => `
        <div class="result-card ${index === 0 && result.time !== -1 ? 'fastest' : ''}">
            <div class="cdn-name">${result.provider}</div>
            <div class="load-time">
                ${result.time === -1 ? 'Errore' : result.time + 'ms'}
                ${index === 0 && result.time !== -1 ? '<small>Più veloce!</small>' : ''}
            </div>
        </div>
    `).join('');
}

// Custom CDN form
document.getElementById('customCdnForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const cdnUrl = document.getElementById('customCdnUrl').value;
    const cdnName = document.getElementById('customCdnName').value;
    const replaceUrls = document.getElementById('replaceWpUrls').checked;
    const includeImages = document.getElementById('cdnImages').checked;
    
    let code = '<?php\n';
    code += '/**\n';
    code += ' * Custom CDN Configuration: ' + cdnName + '\n';
    code += ' */\n\n';
    code += '// Define CDN URL\n';
    code += "define('CDN_URL', '" + cdnUrl + "');\n\n";
    code += '// Replace WordPress URLs with CDN\n';
    code += 'function custom_cdn_url($url) {\n';
    code += '    // Only modify URLs in production\n';
    code += "    if (defined('WP_ENV') && WP_ENV !== 'production') {\n";
    code += '        return $url;\n';
    code += '    }\n';
    code += '    \n';
    code += '    // Check if URL is local\n';
    code += '    if (strpos($url, home_url()) === false) {\n';
    code += '        return $url;\n';
    code += '    }\n';
    
    if (replaceUrls) {
        code += '    \n';
        code += '    // File types to serve from CDN\n';
        code += "    $cdn_extensions = array('js', 'css', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'woff', 'woff2', 'ttf', 'eot');\n";
        
        if (includeImages) {
            code += '    \n';
            code += '    // Include images in CDN\n';
            code += "    $cdn_extensions = array_merge($cdn_extensions, array('png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'ico'));\n";
        }
        
        code += '    \n';
        code += '    // Get file extension\n';
        code += '    $file_extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);\n';
        code += '    \n';
        code += '    // Check if file should be served from CDN\n';
        code += '    if (in_array($file_extension, $cdn_extensions)) {\n';
        code += '        $url = str_replace(home_url(), CDN_URL, $url);\n';
        code += '    }\n';
    }
    
    code += '    \n';
    code += '    return $url;\n';
    code += '}\n\n';
    code += '// Apply CDN to various WordPress URLs\n';
    code += "add_filter('wp_get_attachment_url', 'custom_cdn_url');\n";
    code += "add_filter('style_loader_src', 'custom_cdn_url');\n";
    code += "add_filter('script_loader_src', 'custom_cdn_url');\n";

    if (includeImages) {
        code += '\n// Apply CDN to content images\n';
        code += 'function cdn_content_images($content) {\n';
        code += '    $site_url = get_site_url();\n';
        code += '    $cdn_url = CDN_URL;\n';
        code += '    \n';
        code += '    // Replace image URLs in content\n';
        code += "    $content = str_replace($site_url . '/wp-content/uploads', $cdn_url . '/wp-content/uploads', $content);\n";
        code += '    \n';
        code += '    return $content;\n';
        code += '}\n';
        code += "add_filter('the_content', 'cdn_content_images');\n\n";
        code += '// Apply CDN to srcset\n';
        code += 'function cdn_srcset($sources) {\n';
        code += '    foreach ($sources as &$source) {\n';
        code += "        $source['url'] = custom_cdn_url($source['url']);\n";
        code += '    }\n';
        code += '    return $sources;\n';
        code += '}\n';
        code += "add_filter('wp_calculate_image_srcset', 'cdn_srcset');\n";
    }
    
    code += '\n// Preconnect to CDN for better performance\n';
    code += 'function add_cdn_preconnect($hints, $relation_type) {\n';
    code += "    if ('dns-prefetch' === $relation_type || 'preconnect' === $relation_type) {\n";
    code += '        $hints[] = CDN_URL;\n';
    code += '    }\n';
    code += '    return $hints;\n';
    code += '}\n';
    code += "add_filter('wp_resource_hints', 'add_cdn_preconnect', 10, 2);\n\n";
    code += '// Optional: Add CORS headers for fonts\n';
    code += 'function add_cors_headers() {\n';
    code += '    header("Access-Control-Allow-Origin: *");\n';
    code += '}\n';
    code += "add_action('send_headers', 'add_cors_headers');\n\n";
    code += '?>';
    
    document.getElementById('customCdnCode').textContent = code;
    document.getElementById('customCdnOutput').style.display = 'block';
    Prism.highlightElement(document.getElementById('customCdnCode'));
});

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('URL copiato negli appunti!', 'success');
    });
}
</script>