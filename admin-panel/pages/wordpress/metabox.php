<?php
// Metabox positions
$positions = [
    'normal' => 'Normale (sotto l\'editor)',
    'side' => 'Sidebar laterale',
    'advanced' => 'Sezione avanzata'
];

// Metabox priorities
$priorities = [
    'high' => 'Alta',
    'default' => 'Default',
    'low' => 'Bassa'
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-box"></i> Generatore Metabox</h1>
        <p class="text-muted">Crea metabox personalizzati con campi multipli per i tuoi post type</p>
    </div>
    
    <form id="metaboxForm" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-section">
                    <label for="metaboxTitle">Titolo Metabox</label>
                    <input type="text" class="form-control" id="metaboxTitle" name="metaboxTitle" 
                           placeholder="es. Informazioni Aggiuntive" required>
                    <small class="form-text text-muted">Il titolo che apparirà nella barra del metabox</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-section">
                    <label for="metaboxId">ID Metabox</label>
                    <input type="text" class="form-control" id="metaboxId" name="metaboxId" 
                           placeholder="es. info_aggiuntive" required>
                    <small class="form-text text-muted">ID univoco (solo lettere minuscole e underscore)</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-section">
                    <label for="metaboxPostType">Post Type</label>
                    <input type="text" class="form-control" id="metaboxPostType" name="metaboxPostType" 
                           placeholder="es. post, page, prodotto" required>
                    <small class="form-text text-muted">Post type dove apparirà il metabox</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-section">
                    <label for="metaboxPosition">Posizione</label>
                    <select class="form-select" id="metaboxPosition" name="metaboxPosition">
                        <?php foreach ($positions as $value => $label): ?>
                            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-section">
                    <label for="metaboxPriority">Priorità</label>
                    <select class="form-select" id="metaboxPriority" name="metaboxPriority">
                        <?php foreach ($priorities as $value => $label): ?>
                            <option value="<?php echo $value; ?>" <?php echo $value === 'high' ? 'selected' : ''; ?>>
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <label>Campi del Metabox</label>
            <div id="metaboxFields">
                <div class="field-row mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Nome campo" 
                                   name="fieldName[]" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="ID campo" 
                                   name="fieldId[]" required>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="fieldType[]">
                                <option value="text">Testo</option>
                                <option value="textarea">Textarea</option>
                                <option value="select">Dropdown</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="radio">Radio</option>
                                <option value="media">Upload Media</option>
                                <option value="color">Color Picker</option>
                                <option value="date">Data</option>
                                <option value="number">Numero</option>
                                <option value="email">Email</option>
                                <option value="url">URL</option>
                                <option value="wysiwyg">Editor WYSIWYG</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeField(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addMetaboxField()">
                <i class="fas fa-plus"></i> Aggiungi Campo
            </button>
        </div>

        <div class="form-section">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="addNonce" id="addNonce" value="1" checked>
                <label class="form-check-label" for="addNonce">
                    Aggiungi protezione nonce (raccomandato)
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-generate">
            <i class="fas fa-magic"></i> Genera Codice
        </button>
    </form>

    <div class="code-output" id="metaboxOutput" style="display: none;">
        <div class="copy-btn-container">
            <button class="btn btn-copy" onclick="copyCode('metaboxCode')">
                <i class="fas fa-copy"></i> Copia
            </button>
        </div>
        <pre><code class="language-php" id="metaboxCode"></code></pre>
    </div>
</div>

<script>
document.getElementById('metaboxForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Ottieni i valori del form
    const formData = new FormData(this);
    
    // Genera il codice via AJAX
    fetch('generators/metabox-generator.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(code => {
        document.getElementById('metaboxCode').textContent = code;
        document.getElementById('metaboxOutput').style.display = 'block';
        Prism.highlightElement(document.getElementById('metaboxCode'));
        
        // Scroll to code output
        document.getElementById('metaboxOutput').scrollIntoView({ behavior: 'smooth' });
    })
    .catch(error => {
        console.error('Errore:', error);
        showNotification('Errore nella generazione del codice', 'danger');
    });
});
</script>