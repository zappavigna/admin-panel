<?php
// Dashicons disponibili per WordPress
$dashicons = [
    'dashicons-admin-post' => 'Post',
    'dashicons-portfolio' => 'Portfolio',
    'dashicons-products' => 'Prodotti',
    'dashicons-calendar' => 'Calendario',
    'dashicons-camera' => 'Camera',
    'dashicons-cart' => 'Carrello',
    'dashicons-groups' => 'Gruppi',
    'dashicons-hammer' => 'Strumenti',
    'dashicons-store' => 'Negozio',
    'dashicons-album' => 'Album',
    'dashicons-book' => 'Libro',
    'dashicons-building' => 'Edificio',
    'dashicons-carrot' => 'Cibo',
    'dashicons-clipboard' => 'Appunti',
    'dashicons-desktop' => 'Desktop',
    'dashicons-forms' => 'Forms',
    'dashicons-location' => 'Posizione',
    'dashicons-megaphone' => 'Megafono',
    'dashicons-networking' => 'Network',
    'dashicons-tickets' => 'Tickets'
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-file-alt"></i> Generatore Post Type</h1>
        <p class="text-muted">Crea custom post type per WordPress con tutte le opzioni necessarie</p>
    </div>
    
    <form id="postTypeForm" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-section">
                    <label for="postTypeName">Nome Post Type (singolare)</label>
                    <input type="text" class="form-control" id="postTypeName" name="postTypeName" 
                           placeholder="es. Prodotto" required>
                    <small class="form-text text-muted">Il nome singolare del tuo post type</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-section">
                    <label for="postTypeNamePlural">Nome Post Type (plurale)</label>
                    <input type="text" class="form-control" id="postTypeNamePlural" name="postTypeNamePlural" 
                           placeholder="es. Prodotti" required>
                    <small class="form-text text-muted">Il nome plurale del tuo post type</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-section">
                    <label for="postTypeSlug">Slug</label>
                    <input type="text" class="form-control" id="postTypeSlug" name="postTypeSlug" 
                           placeholder="es. prodotto" required>
                    <small class="form-text text-muted">Utilizzato negli URL (solo lettere minuscole e trattini)</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-section">
                    <label for="postTypeIcon">Icona</label>
                    <select class="form-select" id="postTypeIcon" name="postTypeIcon">
                        <?php foreach ($dashicons as $icon => $label): ?>
                            <option value="<?php echo $icon; ?>">
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted">Icona da mostrare nel menu di WordPress</small>
                </div>
            </div>
        </div>

        <div class="form-section">
            <label>Funzionalità supportate</label>
            <div class="checkbox-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="supports[]" 
                           id="supportTitle" value="title" checked>
                    <label class="form-check-label" for="supportTitle">Titolo</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="supports[]" 
                           id="supportEditor" value="editor" checked>
                    <label class="form-check-label" for="supportEditor">Editor</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="supports[]" 
                           id="supportThumbnail" value="thumbnail" checked>
                    <label class="form-check-label" for="supportThumbnail">Immagine in evidenza</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="supports[]" 
                           id="supportExcerpt" value="excerpt">
                    <label class="form-check-label" for="supportExcerpt">Riassunto</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="supports[]" 
                           id="supportComments" value="comments">
                    <label class="form-check-label" for="supportComments">Commenti</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="supports[]" 
                           id="supportRevisions" value="revisions">
                    <label class="form-check-label" for="supportRevisions">Revisioni</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="supports[]" 
                           id="supportAuthor" value="author">
                    <label class="form-check-label" for="supportAuthor">Autore</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="supports[]" 
                           id="supportPageAttributes" value="page-attributes">
                    <label class="form-check-label" for="supportPageAttributes">Attributi pagina</label>
                </div>
            </div>
        </div>

        <div class="form-section">
            <label>Tassonomie</label>
            <div class="checkbox-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="createCategory" 
                           id="createCategory" value="1">
                    <label class="form-check-label" for="createCategory">
                        Crea Categorie personalizzate
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="createTags" 
                           id="createTags" value="1">
                    <label class="form-check-label" for="createTags">
                        Crea Tag personalizzati
                    </label>
                </div>
            </div>
        </div>

        <div class="form-section">
            <label>Opzioni avanzate</label>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="hierarchical" 
                               id="hierarchical" value="1">
                        <label class="form-check-label" for="hierarchical">
                            Gerarchico (come le pagine)
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="hasArchive" 
                               id="hasArchive" value="1" checked>
                        <label class="form-check-label" for="hasArchive">
                            Ha archivio
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="showInRest" 
                               id="showInRest" value="1" checked>
                        <label class="form-check-label" for="showInRest">
                            Supporto Gutenberg (REST API)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-generate">
            <i class="fas fa-magic"></i> Genera Codice
        </button>
    </form>

    <div class="code-output" id="postTypeOutput" style="display: none;">
        <div class="copy-btn-container">
            <button class="btn btn-copy" onclick="copyCode('postTypeCode')">
                <i class="fas fa-copy"></i> Copia
            </button>
        </div>
        <pre><code class="language-php" id="postTypeCode"></code></pre>
    </div>
</div>

<script>
document.getElementById('postTypeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Ottieni i valori del form
    const formData = new FormData(this);
    
    // Genera il codice via AJAX
    fetch('generators/post-type-generator.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(code => {
        document.getElementById('postTypeCode').textContent = code;
        document.getElementById('postTypeOutput').style.display = 'block';
        Prism.highlightElement(document.getElementById('postTypeCode'));
    });
});
</script>