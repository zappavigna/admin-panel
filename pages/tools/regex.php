<?php
// Common regex patterns
$commonPatterns = [
    'email' => [
        'name' => 'Email',
        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'description' => 'Valida indirizzi email',
        'example' => 'user@example.com'
    ],
    'url' => [
        'name' => 'URL',
        'pattern' => '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        'description' => 'Valida URL web',
        'example' => 'https://www.example.com'
    ],
    'phone_it' => [
        'name' => 'Telefono IT',
        'pattern' => '/^(\+39)?[ ]?([0-9]{2,4})[ ]?([0-9]{5,10})$/',
        'description' => 'Numeri di telefono italiani',
        'example' => '+39 02 12345678'
    ],
    'fiscal_code' => [
        'name' => 'Codice Fiscale',
        'pattern' => '/^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$/i',
        'description' => 'Codice fiscale italiano',
        'example' => 'RSSMRA85T10A562S'
    ],
    'vat_it' => [
        'name' => 'Partita IVA',
        'pattern' => '/^[0-9]{11}$/',
        'description' => 'Partita IVA italiana',
        'example' => '12345678901'
    ],
    'postal_code_it' => [
        'name' => 'CAP Italiano',
        'pattern' => '/^[0-9]{5}$/',
        'description' => 'Codice postale italiano',
        'example' => '20121'
    ],
    'date_iso' => [
        'name' => 'Data ISO',
        'pattern' => '/^\d{4}-\d{2}-\d{2}$/',
        'description' => 'Data in formato ISO (YYYY-MM-DD)',
        'example' => '2024-03-15'
    ],
    'time_24h' => [
        'name' => 'Orario 24h',
        'pattern' => '/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/',
        'description' => 'Orario in formato 24 ore',
        'example' => '14:30'
    ],
    'hex_color' => [
        'name' => 'Colore HEX',
        'pattern' => '/^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/',
        'description' => 'Colore esadecimale',
        'example' => '#FF5733'
    ],
    'ipv4' => [
        'name' => 'IPv4',
        'pattern' => '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/',
        'description' => 'Indirizzo IPv4',
        'example' => '192.168.1.1'
    ],
    'username' => [
        'name' => 'Username',
        'pattern' => '/^[a-zA-Z0-9_-]{3,16}$/',
        'description' => 'Username (3-16 caratteri, alfanumerico, - e _)',
        'example' => 'user_name123'
    ],
    'password_strong' => [
        'name' => 'Password Forte',
        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        'description' => 'Minimo 8 caratteri, maiuscola, minuscola, numero e carattere speciale',
        'example' => 'Pass123!'
    ],
    'credit_card' => [
        'name' => 'Carta di Credito',
        'pattern' => '/^[0-9]{13,19}$/',
        'description' => 'Numero carta di credito (solo cifre)',
        'example' => '4532123456789012'
    ],
    'slug' => [
        'name' => 'URL Slug',
        'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        'description' => 'Slug URL (minuscolo, numeri e trattini)',
        'example' => 'my-awesome-post'
    ],
    'html_tag' => [
        'name' => 'Tag HTML',
        'pattern' => '/<\/?[\w\s]*>|<.+[\W]>/',
        'description' => 'Trova tag HTML',
        'example' => '<div class="example">'
    ]
];

// WordPress specific patterns
$wordpressPatterns = [
    'shortcode' => [
        'name' => 'Shortcode',
        'pattern' => '/\[([a-z0-9_-]+)([^\]]*)\](?:([^\[]*)\[\/\1\])?/i',
        'description' => 'WordPress shortcode',
        'example' => '[gallery id="123"]'
    ],
    'wp_image_class' => [
        'name' => 'Classe Immagine WP',
        'pattern' => '/wp-image-[0-9]+/',
        'description' => 'Classe immagine WordPress',
        'example' => 'wp-image-123'
    ],
    'wp_video_url' => [
        'name' => 'URL Video (YouTube/Vimeo)',
        'pattern' => '/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be|vimeo\.com)\/.+/',
        'description' => 'URL video YouTube o Vimeo',
        'example' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'
    ],
    'wp_action_hook' => [
        'name' => 'Action/Filter Hook',
        'pattern' => '/^[a-z0-9_]+$/',
        'description' => 'Nome hook WordPress valido',
        'example' => 'wp_enqueue_scripts'
    ]
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-terminal"></i> Regex Tester</h1>
        <p class="text-muted">Testa e debugging di espressioni regolari con pattern comuni</p>
    </div>

    <div class="row">
        <!-- Left Column: Tester -->
        <div class="col-lg-8">
            <div class="regex-tester-section">
                <form id="regexForm">
                    <!-- Pattern Input -->
                    <div class="form-section">
                        <label for="regexPattern">Pattern Regex</label>
                        <div class="input-group">
                            <input type="text" class="form-control font-monospace" id="regexPattern" 
                                   placeholder="/^[a-z]+$/i" value="/^[a-z]+$/i">
                            <button class="btn btn-outline-secondary" type="button" onclick="clearPattern()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted">Inserisci il pattern con o senza delimitatori</small>
                    </div>

                    <!-- Flags -->
                    <div class="form-section">
                        <label>Flags</label>
                        <div class="regex-flags">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="flagG" value="g" checked>
                                <label class="form-check-label" for="flagG">
                                    <code>g</code> Global
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="flagI" value="i">
                                <label class="form-check-label" for="flagI">
                                    <code>i</code> Case Insensitive
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="flagM" value="m">
                                <label class="form-check-label" for="flagM">
                                    <code>m</code> Multiline
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="flagS" value="s">
                                <label class="form-check-label" for="flagS">
                                    <code>s</code> Dotall
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Test String -->
                    <div class="form-section">
                        <label for="testString">Stringa di Test</label>
                        <textarea class="form-control font-monospace" id="testString" rows="6" 
                                  placeholder="Inserisci il testo da testare...">Hello World
test@example.com
https://www.example.com
+39 02 12345678</textarea>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-play"></i> Test Pattern
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="testReplace()">
                            <i class="fas fa-exchange-alt"></i> Test Replace
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="explainRegex()">
                            <i class="fas fa-info-circle"></i> Spiega
                        </button>
                    </div>
                </form>

                <!-- Results -->
                <div class="regex-results mt-4" id="regexResults" style="display: none;">
                    <h5>Risultati</h5>
                    <div id="resultsContent"></div>
                </div>

                <!-- Replace Section -->
                <div class="regex-replace mt-4" id="replaceSection" style="display: none;">
                    <h5>Replace</h5>
                    <div class="form-section">
                        <label for="replaceWith">Sostituisci con</label>
                        <input type="text" class="form-control font-monospace" id="replaceWith" 
                               placeholder="$1" value="[$1]">
                        <small class="form-text text-muted">Usa $1, $2, etc. per i gruppi catturati</small>
                    </div>
                    <div class="form-section">
                        <label>Risultato</label>
                        <div class="result-box" id="replaceResult"></div>
                    </div>
                </div>

                <!-- Explanation -->
                <div class="regex-explanation mt-4" id="explanationSection" style="display: none;">
                    <h5>Spiegazione Pattern</h5>
                    <div id="explanationContent"></div>
                </div>
            </div>
        </div>

        <!-- Right Column: Common Patterns -->
        <div class="col-lg-4">
            <div class="common-patterns-section">
                <h5><i class="fas fa-bookmark"></i> Pattern Comuni</h5>
                
                <!-- General Patterns -->
                <div class="pattern-group">
                    <h6>Generali</h6>
                    <?php foreach ($commonPatterns as $key => $pattern): ?>
                        <div class="pattern-item" onclick="usePattern('<?php echo htmlspecialchars($pattern['pattern']); ?>', '<?php echo htmlspecialchars($pattern['example']); ?>')">
                            <div class="pattern-name"><?php echo $pattern['name']; ?></div>
                            <div class="pattern-desc"><?php echo $pattern['description']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- WordPress Patterns -->
                <div class="pattern-group mt-4">
                    <h6>WordPress</h6>
                    <?php foreach ($wordpressPatterns as $key => $pattern): ?>
                        <div class="pattern-item" onclick="usePattern('<?php echo htmlspecialchars($pattern['pattern']); ?>', '<?php echo htmlspecialchars($pattern['example']); ?>')">
                            <div class="pattern-name"><?php echo $pattern['name']; ?></div>
                            <div class="pattern-desc"><?php echo $pattern['description']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Quick Reference -->
            <div class="quick-reference mt-4">
                <h5><i class="fas fa-book"></i> Riferimento Rapido</h5>
                <div class="reference-section">
                    <h6>Caratteri Speciali</h6>
                    <table class="table table-sm">
                        <tr><td><code>.</code></td><td>Qualsiasi carattere</td></tr>
                        <tr><td><code>\d</code></td><td>Cifra (0-9)</td></tr>
                        <tr><td><code>\w</code></td><td>Parola (a-z, A-Z, 0-9, _)</td></tr>
                        <tr><td><code>\s</code></td><td>Spazio</td></tr>
                        <tr><td><code>\b</code></td><td>Confine parola</td></tr>
                        <tr><td><code>^</code></td><td>Inizio stringa</td></tr>
                        <tr><td><code>$</code></td><td>Fine stringa</td></tr>
                    </table>
                </div>
                <div class="reference-section">
                    <h6>Quantificatori</h6>
                    <table class="table table-sm">
                        <tr><td><code>*</code></td><td>0 o più</td></tr>
                        <tr><td><code>+</code></td><td>1 o più</td></tr>
                        <tr><td><code>?</code></td><td>0 o 1</td></tr>
                        <tr><td><code>{n}</code></td><td>Esattamente n</td></tr>
                        <tr><td><code>{n,}</code></td><td>n o più</td></tr>
                        <tr><td><code>{n,m}</code></td><td>Da n a m</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.regex-tester-section {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.regex-flags {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.regex-flags .form-check-label {
    font-size: 0.9rem;
}

.regex-flags code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-weight: bold;
}

.regex-results, .regex-replace, .regex-explanation {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.result-box {
    background: white;
    padding: 15px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    font-family: monospace;
    white-space: pre-wrap;
}

.match-highlight {
    background: #ffc107;
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: bold;
}

.no-match {
    color: #dc3545;
}

.common-patterns-section {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    max-height: 600px;
    overflow-y: auto;
}

.pattern-group h6 {
    color: var(--primary-color);
    margin-bottom: 15px;
    font-weight: 600;
}

.pattern-item {
    background: #f8f9fa;
    padding: 12px;
    margin-bottom: 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.pattern-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.pattern-name {
    font-weight: 600;
    color: var(--primary-color);
}

.pattern-desc {
    font-size: 0.85rem;
    color: #6c757d;
}

.quick-reference {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.reference-section {
    margin-bottom: 20px;
}

.reference-section h6 {
    color: var(--secondary-color);
    margin-bottom: 10px;
}

.reference-section table {
    font-size: 0.9rem;
}

.reference-section code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
}

.match-info {
    background: #e8f5e9;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 10px;
}

.group-info {
    background: #e3f2fd;
    padding: 8px;
    border-radius: 4px;
    margin-top: 5px;
    font-size: 0.9rem;
}

.error-message {
    background: #ffebee;
    color: #c62828;
    padding: 10px;
    border-radius: 6px;
    margin-top: 10px;
}
</style>

<script>
// Test regex pattern
document.getElementById('regexForm').addEventListener('submit', function(e) {
    e.preventDefault();
    testRegex();
});

function testRegex() {
    const pattern = document.getElementById('regexPattern').value;
    const testString = document.getElementById('testString').value;
    const flags = getSelectedFlags();
    
    try {
        // Clean pattern (remove delimiters if present)
        let cleanPattern = pattern;
        if (pattern.startsWith('/') && pattern.lastIndexOf('/') > 0) {
            cleanPattern = pattern.substring(1, pattern.lastIndexOf('/'));
        }
        
        const regex = new RegExp(cleanPattern, flags);
        const matches = [...testString.matchAll(regex)];
        
        displayResults(matches, testString, regex);
        
    } catch (error) {
        displayError(error.message);
    }
}

function getSelectedFlags() {
    let flags = '';
    if (document.getElementById('flagG').checked) flags += 'g';
    if (document.getElementById('flagI').checked) flags += 'i';
    if (document.getElementById('flagM').checked) flags += 'm';
    if (document.getElementById('flagS').checked) flags += 's';
    return flags;
}

function displayResults(matches, testString, regex) {
    const resultsSection = document.getElementById('regexResults');
    const resultsContent = document.getElementById('resultsContent');
    
    resultsSection.style.display = 'block';
    
    if (matches.length === 0) {
        resultsContent.innerHTML = '<div class="no-match">Nessuna corrispondenza trovata</div>';
        return;
    }
    
    let html = `<div class="match-info">Trovate ${matches.length} corrispondenze</div>`;
    
    // Highlight matches in text
    let highlightedText = testString;
    let offset = 0;
    
    matches.forEach((match, index) => {
        html += `<div class="match-info">`;
        html += `<strong>Match ${index + 1}:</strong> "${match[0]}"`;
        html += ` (posizione: ${match.index})`;
        
        // Show groups if any
        if (match.length > 1) {
            html += '<div class="group-info">';
            for (let i = 1; i < match.length; i++) {
                if (match[i] !== undefined) {
                    html += `Gruppo $${i}: "${match[i]}"<br>`;
                }
            }
            html += '</div>';
        }
        html += '</div>';
    });
    
    // Create highlighted version
    let lastIndex = 0;
    let highlighted = '';
    
    // Reset regex lastIndex
    regex.lastIndex = 0;
    
    let match;
    while ((match = regex.exec(testString)) !== null) {
        highlighted += escapeHtml(testString.substring(lastIndex, match.index));
        highlighted += `<span class="match-highlight">${escapeHtml(match[0])}</span>`;
        lastIndex = match.index + match[0].length;
        
        // Prevent infinite loop
        if (!regex.global) break;
    }
    highlighted += escapeHtml(testString.substring(lastIndex));
    
    html += '<div class="mt-3"><strong>Testo con evidenziazioni:</strong></div>';
    html += `<div class="result-box">${highlighted}</div>`;
    
    resultsContent.innerHTML = html;
}

function displayError(message) {
    const resultsSection = document.getElementById('regexResults');
    const resultsContent = document.getElementById('resultsContent');
    
    resultsSection.style.display = 'block';
    resultsContent.innerHTML = `<div class="error-message"><i class="fas fa-exclamation-triangle"></i> Errore: ${message}</div>`;
}

function testReplace() {
    const replaceSection = document.getElementById('replaceSection');
    replaceSection.style.display = 'block';
    
    const pattern = document.getElementById('regexPattern').value;
    const testString = document.getElementById('testString').value;
    const replaceWith = document.getElementById('replaceWith').value;
    const flags = getSelectedFlags();
    
    try {
        let cleanPattern = pattern;
        if (pattern.startsWith('/') && pattern.lastIndexOf('/') > 0) {
            cleanPattern = pattern.substring(1, pattern.lastIndexOf('/'));
        }
        
        const regex = new RegExp(cleanPattern, flags);
        const result = testString.replace(regex, replaceWith);
        
        document.getElementById('replaceResult').textContent = result;
    } catch (error) {
        document.getElementById('replaceResult').innerHTML = `<div class="error-message">${error.message}</div>`;
    }
}

function explainRegex() {
    const explanationSection = document.getElementById('explanationSection');
    const explanationContent = document.getElementById('explanationContent');
    const pattern = document.getElementById('regexPattern').value;
    
    explanationSection.style.display = 'block';
    
    // Basic explanation - you can expand this
    const explanations = {
        '^': 'Inizio della stringa',
        '$': 'Fine della stringa',
        '.': 'Qualsiasi carattere singolo',
        '*': 'Zero o più occorrenze',
        '+': 'Una o più occorrenze',
        '?': 'Zero o una occorrenza',
        '\\d': 'Qualsiasi cifra (0-9)',
        '\\w': 'Qualsiasi carattere di parola (a-z, A-Z, 0-9, _)',
        '\\s': 'Qualsiasi spazio bianco',
        '\\b': 'Confine di parola',
        '[': 'Inizio classe di caratteri',
        ']': 'Fine classe di caratteri',
        '(': 'Inizio gruppo di cattura',
        ')': 'Fine gruppo di cattura',
        '|': 'Alternativa (OR)',
    };
    
    let html = '<div class="explanation-list">';
    let cleanPattern = pattern;
    if (pattern.startsWith('/')) {
        cleanPattern = pattern.substring(1, pattern.lastIndexOf('/'));
    }
    
    // Simple explanation
    html += `<p><strong>Pattern:</strong> <code>${escapeHtml(cleanPattern)}</code></p>`;
    html += '<p><strong>Componenti trovati:</strong></p><ul>';
    
    for (let [key, value] of Object.entries(explanations)) {
        if (cleanPattern.includes(key)) {
            html += `<li><code>${escapeHtml(key)}</code> - ${value}</li>`;
        }
    }
    
    html += '</ul>';
    
    // Check for common patterns
    if (cleanPattern.includes('[a-z]')) {
        html += '<li><code>[a-z]</code> - Qualsiasi lettera minuscola</li>';
    }
    if (cleanPattern.includes('[A-Z]')) {
        html += '<li><code>[A-Z]</code> - Qualsiasi lettera maiuscola</li>';
    }
    if (cleanPattern.includes('[0-9]')) {
        html += '<li><code>[0-9]</code> - Qualsiasi cifra</li>';
    }
    
    html += '</div>';
    explanationContent.innerHTML = html;
}

function usePattern(pattern, example) {
    document.getElementById('regexPattern').value = pattern;
    document.getElementById('testString').value = example;
    
    // Auto-detect flags from pattern
    if (pattern.endsWith('/i')) {
        document.getElementById('flagI').checked = true;
    }
    
    // Auto-test
    testRegex();
}

function clearPattern() {
    document.getElementById('regexPattern').value = '';
    document.getElementById('testString').value = '';
    document.getElementById('regexResults').style.display = 'none';
    document.getElementById('replaceSection').style.display = 'none';
    document.getElementById('explanationSection').style.display = 'none';
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Initialize with a simple example
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('regexPattern').value = '/[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}/i';
    document.getElementById('testString').value = 'Contact us at: info@example.com or support@test.org';
    document.getElementById('flagI').checked = true;
});
</script>