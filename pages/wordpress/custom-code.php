<?php
// Code locations
$locations = [
    'theme' => 'Tema Normale (functions.php)',
    'child-theme' => 'Tema Child',
    'plugin' => 'Plugin Personalizzato',
    'mu-plugin' => 'Must-Use Plugin'
];

// Code types
$codeTypes = [
    'css' => 'Solo CSS',
    'js' => 'Solo JavaScript',
    'both' => 'CSS + JavaScript'
];

// Load conditions
$loadConditions = [
    'always' => 'Sempre',
    'frontend' => 'Solo Frontend',
    'backend' => 'Solo Backend',
    'homepage' => 'Solo Homepage',
    'single' => 'Solo Singoli Post',
    'page' => 'Solo Pagine',
    'archive' => 'Solo Archivi'
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-code"></i> Custom Code Generator</h1>
        <p class="text-muted">Genera codice per aggiungere CSS e JavaScript personalizzato a WordPress</p>
    </div>
    
    <form id="customCodeForm" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-section">
                    <label for="codeLocation">Percorso di installazione</label>
                    <select class="form-select" id="codeLocation" name="codeLocation">
                        <?php foreach ($locations as $value => $label): ?>
                            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted">Dove vuoi inserire il codice</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-section">
                    <label for="codeType">Tipo di Codice</label>
                    <select class="form-select" id="codeType" name="codeType">
                        <?php foreach ($codeTypes as $value => $label): ?>
                            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-section">
                    <label for="codeDescription">Descrizione/Commento</label>
                    <input type="text" class="form-control" id="codeDescription" name="codeDescription"
                           placeholder="es. Stili personalizzati per il footer">
                    <small class="form-text text-muted">Descrizione del codice (opzionale)</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-section">
                    <label for="loadCondition">Condizione di caricamento</label>
                    <select class="form-select" id="loadCondition" name="loadCondition">
                        <?php foreach ($loadConditions as $value => $label): ?>
                            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted">Quando caricare il codice</small>
                </div>
            </div>
        </div>

        <div class="form-section" id="cssSection">
            <label for="customCSS">Codice CSS</label>
            <textarea class="form-control code-editor" id="customCSS" name="customCSS" rows="10" 
                      placeholder="/* Il tuo CSS qui */">/* Custom CSS */
.custom-class {
    /* I tuoi stili qui */
}</textarea>
        </div>

        <div class="form-section" id="jsSection" style="display: none;">
            <label for="customJS">Codice JavaScript</label>
            <textarea class="form-control code-editor" id="customJS" name="customJS" rows="10" 
                      placeholder="// Il tuo JavaScript qui">// Custom JavaScript
jQuery(document).ready(function($) {
    // Il tuo codice qui
});</textarea>
        </div>

        <div class="form-section">
            <label>Opzioni aggiuntive</label>
            <div class="checkbox-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="minify" id="minify" value="1">
                    <label class="form-check-label" for="minify">
                        Minifica il codice
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="cacheBust" id="cacheBust" value="1" checked>
                    <label class="form-check-label" for="cacheBust">
                        Aggiungi versioning per cache busting
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="priority" id="priority" value="1">
                    <label class="form-check-label" for="priority">
                        Carica con priorità alta
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-generate">
            <i class="fas fa-magic"></i> Genera Codice
        </button>
    </form>

    <div class="code-output" id="customCodeOutput" style="display: none;">
        <div class="copy-btn-container">
            <button class="btn btn-copy" onclick="copyCode('customCodeGenerated')">
                <i class="fas fa-copy"></i> Copia
            </button>
        </div>
        <pre><code class="language-php" id="customCodeGenerated"></code></pre>
    </div>
</div>

<script>
// Toggle Code Type Sections
document.getElementById('codeType').addEventListener('change', function() {
    const cssSection = document.getElementById('cssSection');
    const jsSection = document.getElementById('jsSection');
    
    if (this.value === 'css') {
        cssSection.style.display = 'block';
        jsSection.style.display = 'none';
    } else if (this.value === 'js') {
        cssSection.style.display = 'none';
        jsSection.style.display = 'block';
    } else {
        cssSection.style.display = 'block';
        jsSection.style.display = 'block';
    }
});

// Form submission
document.getElementById('customCodeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Ottieni i valori del form
    const formData = new FormData(this);
    
    // Genera il codice via AJAX
    fetch('generators/custom-code-generator.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(code => {
        document.getElementById('customCodeGenerated').textContent = code;
        document.getElementById('customCodeOutput').style.display = 'block';
        Prism.highlightElement(document.getElementById('customCodeGenerated'));
        
        // Scroll to code output
        document.getElementById('customCodeOutput').scrollIntoView({ behavior: 'smooth' });
    })
    .catch(error => {
        console.error('Errore:', error);
        showNotification('Errore nella generazione del codice', 'danger');
    });
});
</script>