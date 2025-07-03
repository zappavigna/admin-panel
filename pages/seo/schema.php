<?php
// Schema types
$schemaTypes = [
    'Article' => [
        'name' => 'Articolo',
        'icon' => 'fas fa-newspaper',
        'fields' => ['headline', 'author', 'datePublished', 'dateModified', 'image', 'publisher']
    ],
    'Product' => [
        'name' => 'Prodotto',
        'icon' => 'fas fa-shopping-bag',
        'fields' => ['name', 'description', 'image', 'brand', 'price', 'priceCurrency', 'availability', 'rating']
    ],
    'LocalBusiness' => [
        'name' => 'Attività Locale',
        'icon' => 'fas fa-store',
        'fields' => ['name', 'description', 'address', 'telephone', 'email', 'openingHours', 'priceRange', 'image']
    ],
    'Person' => [
        'name' => 'Persona',
        'icon' => 'fas fa-user',
        'fields' => ['name', 'jobTitle', 'worksFor', 'email', 'telephone', 'image', 'sameAs']
    ],
    'Organization' => [
        'name' => 'Organizzazione',
        'icon' => 'fas fa-building',
        'fields' => ['name', 'description', 'url', 'logo', 'contactPoint', 'sameAs', 'foundingDate']
    ],
    'Event' => [
        'name' => 'Evento',
        'icon' => 'fas fa-calendar-alt',
        'fields' => ['name', 'description', 'startDate', 'endDate', 'location', 'organizer', 'image', 'price']
    ],
    'Recipe' => [
        'name' => 'Ricetta',
        'icon' => 'fas fa-utensils',
        'fields' => ['name', 'description', 'image', 'author', 'prepTime', 'cookTime', 'totalTime', 'recipeYield', 'recipeIngredient', 'recipeInstructions', 'nutrition']
    ],
    'FAQ' => [
        'name' => 'FAQ',
        'icon' => 'fas fa-question-circle',
        'fields' => ['questions']
    ],
    'BreadcrumbList' => [
        'name' => 'Breadcrumb',
        'icon' => 'fas fa-angle-right',
        'fields' => ['items']
    ],
    'VideoObject' => [
        'name' => 'Video',
        'icon' => 'fas fa-video',
        'fields' => ['name', 'description', 'thumbnailUrl', 'uploadDate', 'duration', 'embedUrl']
    ]
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-project-diagram"></i> Schema Markup Generator</h1>
        <p class="text-muted">Genera structured data JSON-LD per migliorare la visibilità nei motori di ricerca</p>
    </div>

    <!-- Schema Type Selection -->
    <div class="schema-type-selection">
        <h4 class="mb-3">Seleziona il tipo di Schema</h4>
        <div class="schema-types-grid">
            <?php foreach ($schemaTypes as $type => $info): ?>
                <div class="schema-type-card" data-type="<?php echo $type; ?>" onclick="selectSchemaType('<?php echo $type; ?>')">
                    <div class="schema-icon">
                        <i class="<?php echo $info['icon']; ?>"></i>
                    </div>
                    <h5><?php echo $info['name']; ?></h5>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Dynamic Form Container -->
    <div id="schemaFormContainer" style="display: none;">
        <div class="selected-schema-header">
            <h4 id="selectedSchemaTitle"></h4>
            <button class="btn btn-sm btn-secondary" onclick="resetSchemaSelection()">
                <i class="fas fa-arrow-left"></i> Cambia tipo
            </button>
        </div>

        <form id="schemaForm">
            <input type="hidden" id="schemaType" name="schemaType">
            <div id="dynamicFields"></div>
            
            <button type="submit" class="btn btn-generate">
                <i class="fas fa-magic"></i> Genera Schema Markup
            </button>
        </form>
    </div>

    <!-- Output -->
    <div class="code-output" id="schemaOutput" style="display: none;">
        <div class="output-tabs">
            <button class="output-tab active" onclick="switchSchemaOutput('json')">JSON-LD</button>
            <button class="output-tab" onclick="switchSchemaOutput('microdata')">Microdata</button>
            <button class="output-tab" onclick="switchSchemaOutput('test')">Test Tool</button>
        </div>
        
        <div id="jsonOutput" class="output-content active">
            <div class="copy-btn-container">
                <button class="btn btn-copy" onclick="copyCode('schemaJsonCode')">
                    <i class="fas fa-copy"></i> Copia JSON-LD
                </button>
            </div>
            <pre><code class="language-json" id="schemaJsonCode"></code></pre>
        </div>
        
        <div id="microdataOutput" class="output-content" style="display: none;">
            <div class="copy-btn-container">
                <button class="btn btn-copy" onclick="copyCode('schemaMicrodataCode')">
                    <i class="fas fa-copy"></i> Copia Microdata
                </button>
            </div>
            <pre><code class="language-html" id="schemaMicrodataCode"></code></pre>
        </div>
        
        <div id="testOutput" class="output-content" style="display: none;">
            <div class="test-tools">
                <h5>Strumenti di Test</h5>
                <div class="test-buttons">
                    <button class="btn btn-primary" onclick="testInGoogleTool()">
                        <i class="fab fa-google"></i> Test con Google Rich Results
                    </button>
                    <button class="btn btn-info" onclick="testInSchemaValidator()">
                        <i class="fas fa-check-circle"></i> Schema.org Validator
                    </button>
                </div>
                <div class="test-info mt-3">
                    <p><strong>Come testare il tuo Schema:</strong></p>
                    <ol>
                        <li>Copia il codice JSON-LD generato</li>
                        <li>Clicca su uno dei pulsanti di test sopra</li>
                        <li>Incolla il codice nello strumento di test</li>
                        <li>Verifica che non ci siano errori</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Schema field configurations
const schemaFields = {
    // Common fields
    name: { label: 'Nome', type: 'text', required: true },
    description: { label: 'Descrizione', type: 'textarea', required: true },
    url: { label: 'URL', type: 'url', required: true },
    image: { label: 'URL Immagine', type: 'url', required: false },
    
    // Article fields
    headline: { label: 'Titolo', type: 'text', required: true },
    author: { label: 'Autore', type: 'text', required: true },
    datePublished: { label: 'Data Pubblicazione', type: 'datetime-local', required: true },
    dateModified: { label: 'Data Modifica', type: 'datetime-local', required: false },
    publisher: { label: 'Editore', type: 'text', required: true },
    
    // Product fields
    brand: { label: 'Marca', type: 'text', required: true },
    price: { label: 'Prezzo', type: 'number', step: '0.01', required: true },
    priceCurrency: { label: 'Valuta', type: 'select', options: ['EUR', 'USD', 'GBP'], required: true },
    availability: { 
        label: 'Disponibilità', 
        type: 'select', 
        options: {
            'https://schema.org/InStock': 'Disponibile',
            'https://schema.org/OutOfStock': 'Non disponibile',
            'https://schema.org/PreOrder': 'Preordine',
            'https://schema.org/BackOrder': 'In arrivo'
        },
        required: true 
    },
    rating: { label: 'Valutazione (1-5)', type: 'number', min: 1, max: 5, step: '0.1', required: false },
    
    // LocalBusiness fields
    telephone: { label: 'Telefono', type: 'tel', required: true },
    email: { label: 'Email', type: 'email', required: true },
    priceRange: { label: 'Fascia Prezzo', type: 'text', placeholder: '€€', required: false },
    openingHours: { label: 'Orari Apertura', type: 'textarea', placeholder: 'Mo-Fr 09:00-18:00', required: false },
    
    // Person fields
    jobTitle: { label: 'Titolo Lavorativo', type: 'text', required: false },
    worksFor: { label: 'Azienda', type: 'text', required: false },
    
    // Event fields
    startDate: { label: 'Data Inizio', type: 'datetime-local', required: true },
    endDate: { label: 'Data Fine', type: 'datetime-local', required: false },
    location: { label: 'Luogo', type: 'text', required: true },
    organizer: { label: 'Organizzatore', type: 'text', required: true },
    
    // Recipe fields
    prepTime: { label: 'Tempo Preparazione (minuti)', type: 'number', required: true },
    cookTime: { label: 'Tempo Cottura (minuti)', type: 'number', required: true },
    totalTime: { label: 'Tempo Totale (minuti)', type: 'number', required: true },
    recipeYield: { label: 'Porzioni', type: 'text', placeholder: '4 porzioni', required: true },
    
    // Video fields
    thumbnailUrl: { label: 'URL Thumbnail', type: 'url', required: true },
    uploadDate: { label: 'Data Upload', type: 'date', required: true },
    duration: { label: 'Durata', type: 'text', placeholder: 'PT15M33S', required: true },
    embedUrl: { label: 'URL Embed', type: 'url', required: false },
    
    // Organization fields
    logo: { label: 'URL Logo', type: 'url', required: true },
    foundingDate: { label: 'Data Fondazione', type: 'date', required: false }
};

let currentSchemaType = null;

function selectSchemaType(type) {
    currentSchemaType = type;
    document.getElementById('schemaType').value = type;
    
    // Update UI
    document.querySelectorAll('.schema-type-card').forEach(card => {
        card.classList.remove('active');
    });
    document.querySelector(`[data-type="${type}"]`).classList.add('active');
    
    // Show form
    document.getElementById('schemaFormContainer').style.display = 'block';
    document.getElementById('selectedSchemaTitle').textContent = schemaTypes[type].name;
    
    // Generate fields
    generateSchemaFields(type);
    
    // Scroll to form
    document.getElementById('schemaFormContainer').scrollIntoView({ behavior: 'smooth' });
}

function generateSchemaFields(type) {
    const container = document.getElementById('dynamicFields');
    container.innerHTML = '';
    
    const typeConfig = schemaTypes[type];
    
    // Special handling for different types
    if (type === 'FAQ') {
        container.innerHTML = `
            <div class="form-section">
                <label>Domande e Risposte</label>
                <div id="faqItems">
                    <div class="faq-item">
                        <input type="text" class="form-control mb-2" placeholder="Domanda" name="question[]" required>
                        <textarea class="form-control mb-2" placeholder="Risposta" name="answer[]" required></textarea>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeFAQItem(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addFAQItem()">
                    <i class="fas fa-plus"></i> Aggiungi FAQ
                </button>
            </div>
        `;
    } else if (type === 'BreadcrumbList') {
        container.innerHTML = `
            <div class="form-section">
                <label>Elementi Breadcrumb</label>
                <div id="breadcrumbItems">
                    <div class="breadcrumb-item">
                        <input type="text" class="form-control mb-2" placeholder="Nome" name="itemName[]" required>
                        <input type="url" class="form-control mb-2" placeholder="URL" name="itemUrl[]" required>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeBreadcrumbItem(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addBreadcrumbItem()">
                    <i class="fas fa-plus"></i> Aggiungi Elemento
                </button>
            </div>
        `;
    } else if (type === 'Recipe') {
        // Recipe has special fields
        typeConfig.fields.forEach(fieldName => {
            if (fieldName === 'recipeIngredient') {
                container.innerHTML += `
                    <div class="form-section">
                        <label>Ingredienti</label>
                        <div id="ingredientItems">
                            <input type="text" class="form-control mb-2" placeholder="es. 2 tazze di farina" name="ingredient[]" required>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addIngredient()">
                            <i class="fas fa-plus"></i> Aggiungi Ingrediente
                        </button>
                    </div>
                `;
            } else if (fieldName === 'recipeInstructions') {
                container.innerHTML += `
                    <div class="form-section">
                        <label>Istruzioni</label>
                        <div id="instructionItems">
                            <textarea class="form-control mb-2" placeholder="Passo 1..." name="instruction[]" required></textarea>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addInstruction()">
                            <i class="fas fa-plus"></i> Aggiungi Passo
                        </button>
                    </div>
                `;
            } else if (fieldName === 'nutrition') {
                container.innerHTML += `
                    <div class="form-section">
                        <label>Informazioni Nutrizionali (opzionale)</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="number" class="form-control mb-2" placeholder="Calorie" name="calories">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control mb-2" placeholder="Porzione (es. 1 porzione)" name="servingSize">
                            </div>
                        </div>
                    </div>
                `;
            } else if (schemaFields[fieldName]) {
                container.innerHTML += createFieldHTML(fieldName, schemaFields[fieldName]);
            }
        });
    } else {
        // Standard fields
        typeConfig.fields.forEach(fieldName => {
            if (fieldName === 'address') {
                container.innerHTML += `
                    <div class="form-section">
                        <label>Indirizzo</label>
                        <div class="address-fields">
                            <input type="text" class="form-control mb-2" placeholder="Via e numero" name="streetAddress" required>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control mb-2" placeholder="Città" name="addressLocality" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control mb-2" placeholder="CAP" name="postalCode" required>
                                </div>
                            </div>
                            <input type="text" class="form-control mb-2" placeholder="Paese" name="addressCountry" value="IT">
                        </div>
                    </div>
                `;
            } else if (fieldName === 'sameAs') {
                container.innerHTML += `
                    <div class="form-section">
                        <label>Profili Social (uno per riga)</label>
                        <textarea class="form-control" name="sameAs" rows="3" 
                                  placeholder="https://facebook.com/tuapagina&#10;https://twitter.com/tuoprofilo"></textarea>
                    </div>
                `;
            } else if (fieldName === 'contactPoint') {
                container.innerHTML += `
                    <div class="form-section">
                        <label>Punto di Contatto</label>
                        <div class="contact-fields">
                            <input type="tel" class="form-control mb-2" placeholder="Telefono" name="contactTelephone">
                            <input type="email" class="form-control mb-2" placeholder="Email" name="contactEmail">
                            <select class="form-select mb-2" name="contactType">
                                <option value="customer service">Servizio Clienti</option>
                                <option value="technical support">Supporto Tecnico</option>
                                <option value="sales">Vendite</option>
                            </select>
                        </div>
                    </div>
                `;
            } else if (schemaFields[fieldName]) {
                container.innerHTML += createFieldHTML(fieldName, schemaFields[fieldName]);
            }
        });
    }
}

function createFieldHTML(name, config) {
    let html = '<div class="form-section">';
    html += `<label for="${name}">${config.label}</label>`;
    
    if (config.type === 'textarea') {
        html += `<textarea class="form-control" id="${name}" name="${name}" rows="3" ${config.required ? 'required' : ''}`;
        if (config.placeholder) html += ` placeholder="${config.placeholder}"`;
        html += '></textarea>';
    } else if (config.type === 'select') {
        html += `<select class="form-select" id="${name}" name="${name}" ${config.required ? 'required' : ''}>`;
        
        if (typeof config.options === 'object' && !Array.isArray(config.options)) {
            Object.entries(config.options).forEach(([value, label]) => {
                html += `<option value="${value}">${label}</option>`;
            });
        } else {
            config.options.forEach(option => {
                html += `<option value="${option}">${option}</option>`;
            });
        }
        
        html += '</select>';
    } else {
        html += `<input type="${config.type}" class="form-control" id="${name}" name="${name}" ${config.required ? 'required' : ''}`;
        if (config.placeholder) html += ` placeholder="${config.placeholder}"`;
        if (config.min) html += ` min="${config.min}"`;
        if (config.max) html += ` max="${config.max}"`;
        if (config.step) html += ` step="${config.step}"`;
        html += '>';
    }
    
    html += '</div>';
    return html;
}

// Dynamic field functions
function addFAQItem() {
    const container = document.getElementById('faqItems');
    const item = document.createElement('div');
    item.className = 'faq-item mt-3';
    item.innerHTML = `
        <input type="text" class="form-control mb-2" placeholder="Domanda" name="question[]" required>
        <textarea class="form-control mb-2" placeholder="Risposta" name="answer[]" required></textarea>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeFAQItem(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(item);
}

function removeFAQItem(button) {
    button.parentElement.remove();
}

function addBreadcrumbItem() {
    const container = document.getElementById('breadcrumbItems');
    const item = document.createElement('div');
    item.className = 'breadcrumb-item mt-3';
    item.innerHTML = `
        <input type="text" class="form-control mb-2" placeholder="Nome" name="itemName[]" required>
        <input type="url" class="form-control mb-2" placeholder="URL" name="itemUrl[]" required>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeBreadcrumbItem(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(item);
}

function removeBreadcrumbItem(button) {
    button.parentElement.remove();
}

function addIngredient() {
    const container = document.getElementById('ingredientItems');
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control mb-2';
    input.placeholder = 'es. 1 tazza di zucchero';
    input.name = 'ingredient[]';
    input.required = true;
    container.appendChild(input);
}

function addInstruction() {
    const container = document.getElementById('instructionItems');
    const textarea = document.createElement('textarea');
    textarea.className = 'form-control mb-2';
    textarea.placeholder = 'Passo successivo...';
    textarea.name = 'instruction[]';
    textarea.required = true;
    container.appendChild(textarea);
}

function resetSchemaSelection() {
    document.getElementById('schemaFormContainer').style.display = 'none';
    document.querySelectorAll('.schema-type-card').forEach(card => {
        card.classList.remove('active');
    });
    currentSchemaType = null;
}

// Form submission
document.getElementById('schemaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const schema = generateSchema(formData);
    
    // Display JSON-LD
    const jsonLd = JSON.stringify(schema, null, 2);
    document.getElementById('schemaJsonCode').textContent = `<script type="application/ld+json">\n${jsonLd}\n</script>`;
    
    // Generate Microdata
    const microdata = generateMicrodata(schema);
    document.getElementById('schemaMicrodataCode').textContent = microdata;
    
    // Show output
    document.getElementById('schemaOutput').style.display = 'block';
    
    // Highlight code
    Prism.highlightElement(document.getElementById('schemaJsonCode'));
    Prism.highlightElement(document.getElementById('schemaMicrodataCode'));
    
    // Scroll to output
    document.getElementById('schemaOutput').scrollIntoView({ behavior: 'smooth' });
});

function generateSchema(formData) {
    const type = formData.get('schemaType');
    const schema = {
        "@context": "https://schema.org",
        "@type": type
    };
    
    // Handle different schema types
    switch(type) {
        case 'FAQ':
            schema.mainEntity = [];
            const questions = formData.getAll('question[]');
            const answers = formData.getAll('answer[]');
            
            questions.forEach((question, index) => {
                if (question && answers[index]) {
                    schema.mainEntity.push({
                        "@type": "Question",
                        "name": question,
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": answers[index]
                        }
                    });
                }
            });
            break;
            
        case 'BreadcrumbList':
            schema.itemListElement = [];
            const names = formData.getAll('itemName[]');
            const urls = formData.getAll('itemUrl[]');
            
            names.forEach((name, index) => {
                if (name && urls[index]) {
                    schema.itemListElement.push({
                        "@type": "ListItem",
                        "position": index + 1,
                        "name": name,
                        "item": urls[index]
                    });
                }
            });
            break;
            
        case 'Recipe':
            // Standard fields
            for (let [key, value] of formData.entries()) {
                if (value && !key.includes('[]')) {
                    schema[key] = value;
                }
            }
            
            // Special recipe fields
            const ingredients = formData.getAll('ingredient[]').filter(i => i);
            if (ingredients.length) schema.recipeIngredient = ingredients;
            
            const instructions = formData.getAll('instruction[]').filter(i => i);
            if (instructions.length) {
                schema.recipeInstructions = instructions.map((instruction, index) => ({
                    "@type": "HowToStep",
                    "text": instruction,
                    "name": `Passo ${index + 1}`
                }));
            }
            
            // Nutrition
            if (formData.get('calories') || formData.get('servingSize')) {
                schema.nutrition = {
                    "@type": "NutritionInformation"
                };
                if (formData.get('calories')) schema.nutrition.calories = formData.get('calories');
                if (formData.get('servingSize')) schema.nutrition.servingSize = formData.get('servingSize');
            }
            
            // Format times
            if (schema.prepTime) schema.prepTime = `PT${schema.prepTime}M`;
            if (schema.cookTime) schema.cookTime = `PT${schema.cookTime}M`;
            if (schema.totalTime) schema.totalTime = `PT${schema.totalTime}M`;
            break;
            
        case 'LocalBusiness':
            // Standard fields
            for (let [key, value] of formData.entries()) {
                if (value && !key.includes('Address') && !key.includes('contact')) {
                    schema[key] = value;
                }
            }
            
            // Address
            if (formData.get('streetAddress')) {
                schema.address = {
                    "@type": "PostalAddress",
                    "streetAddress": formData.get('streetAddress'),
                    "addressLocality": formData.get('addressLocality'),
                    "postalCode": formData.get('postalCode'),
                    "addressCountry": formData.get('addressCountry')
                };
            }
            break;
            
        case 'Organization':
            // Standard fields
            for (let [key, value] of formData.entries()) {
                if (value && !key.includes('contact')) {
                    schema[key] = value;
                }
            }
            
            // Contact Point
            if (formData.get('contactTelephone') || formData.get('contactEmail')) {
                schema.contactPoint = {
                    "@type": "ContactPoint",
                    "contactType": formData.get('contactType')
                };
                if (formData.get('contactTelephone')) schema.contactPoint.telephone = formData.get('contactTelephone');
                if (formData.get('contactEmail')) schema.contactPoint.email = formData.get('contactEmail');
            }
            
            // Same As (social profiles)
            if (formData.get('sameAs')) {
                schema.sameAs = formData.get('sameAs').split('\n').filter(url => url.trim());
            }
            break;
            
        case 'Product':
            // Standard fields
            for (let [key, value] of formData.entries()) {
                if (value) {
                    schema[key] = value;
                }
            }
            
            // Offers
            if (schema.price) {
                schema.offers = {
                    "@type": "Offer",
                    "price": schema.price,
                    "priceCurrency": schema.priceCurrency || "EUR"
                };
                if (schema.availability) {
                    schema.offers.availability = schema.availability;
                }
                delete schema.price;
                delete schema.priceCurrency;
                delete schema.availability;
            }
            
            // Rating
            if (schema.rating) {
                schema.aggregateRating = {
                    "@type": "AggregateRating",
                    "ratingValue": schema.rating,
                    "bestRating": "5"
                };
                delete schema.rating;
            }
            break;
            
        default:
            // Standard handling for other types
            for (let [key, value] of formData.entries()) {
                if (value) {
                    schema[key] = value;
                }
            }
    }
    
    return schema;
}

function generateMicrodata(schema) {
    // This is a simplified microdata generator
    let html = `<div itemscope itemtype="https://schema.org/${schema['@type']}">\n`;
    
    Object.entries(schema).forEach(([key, value]) => {
        if (key !== '@context' && key !== '@type') {
            if (typeof value === 'object' && !Array.isArray(value)) {
                // Nested object
                html += `  <div itemprop="${key}" itemscope itemtype="https://schema.org/${value['@type'] || 'Thing'}">\n`;
                Object.entries(value).forEach(([subKey, subValue]) => {
                    if (subKey !== '@type') {
                        html += `    <span itemprop="${subKey}">${subValue}</span>\n`;
                    }
                });
                html += `  </div>\n`;
            } else if (Array.isArray(value)) {
                // Array
                value.forEach(item => {
                    if (typeof item === 'object') {
                        html += `  <div itemprop="${key}" itemscope itemtype="https://schema.org/${item['@type'] || 'Thing'}">\n`;
                        Object.entries(item).forEach(([subKey, subValue]) => {
                            if (subKey !== '@type') {
                                html += `    <span itemprop="${subKey}">${subValue}</span>\n`;
                            }
                        });
                        html += `  </div>\n`;
                    } else {
                        html += `  <span itemprop="${key}">${item}</span>\n`;
                    }
                });
            } else {
                // Simple value
                html += `  <span itemprop="${key}">${value}</span>\n`;
            }
        }
    });
    
    html += '</div>';
    return html;
}

function switchSchemaOutput(tab) {
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

function testInGoogleTool() {
    const code = document.getElementById('schemaJsonCode').textContent;
    const encoded = encodeURIComponent(code);
    window.open('https://search.google.com/test/rich-results', '_blank');
}

function testInSchemaValidator() {
    window.open('https://validator.schema.org/', '_blank');
}
</script>