<?php
// Popular libraries configuration
$libraries = [
    'css' => [
        [
            'id' => 'bootstrap5',
            'name' => 'Bootstrap 5',
            'version' => '5.3.0',
            'description' => 'Framework CSS più popolare per UI responsive',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css',
            'js' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js',
            'dependencies' => [],
            'icon' => 'fab fa-bootstrap'
        ],
        [
            'id' => 'fontawesome',
            'name' => 'Font Awesome 6',
            'version' => '6.4.0',
            'description' => 'Libreria di icone più completa',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
            'js' => null,
            'dependencies' => [],
            'icon' => 'fab fa-font-awesome'
        ],
        [
            'id' => 'animate',
            'name' => 'Animate.css',
            'version' => '4.1.1',
            'description' => 'Animazioni CSS pronte all\'uso',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css',
            'js' => null,
            'dependencies' => [],
            'icon' => 'fas fa-magic'
        ],
        [
            'id' => 'bulma',
            'name' => 'Bulma',
            'version' => '0.9.4',
            'description' => 'Framework CSS moderno basato su Flexbox',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css',
            'js' => null,
            'dependencies' => [],
            'icon' => 'fas fa-cube'
        ],
        [
            'id' => 'tailwind',
            'name' => 'Tailwind CSS',
            'version' => '3.3.0',
            'description' => 'Framework CSS utility-first',
            'url' => 'https://cdn.tailwindcss.com',
            'js' => null,
            'dependencies' => [],
            'icon' => 'fas fa-wind'
        ]
    ],
    'js' => [
        [
            'id' => 'jquery',
            'name' => 'jQuery',
            'version' => '3.7.0',
            'description' => 'Libreria JavaScript per manipolazione DOM',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js',
            'dependencies' => [],
            'icon' => 'fab fa-js'
        ],
        [
            'id' => 'owlcarousel',
            'name' => 'Owl Carousel 2',
            'version' => '2.3.4',
            'description' => 'Slider/Carousel responsive touch-enabled',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js',
            'css' => 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css',
            'dependencies' => ['jquery'],
            'icon' => 'fas fa-images'
        ],
        [
            'id' => 'swiper',
            'name' => 'Swiper',
            'version' => '10.0.0',
            'description' => 'Slider moderno senza dipendenze',
            'url' => 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js',
            'css' => 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css',
            'dependencies' => [],
            'icon' => 'fas fa-sliders-h'
        ],
        [
            'id' => 'lightbox2',
            'name' => 'Lightbox2',
            'version' => '2.11.3',
            'description' => 'Lightbox per immagini semplice ed elegante',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js',
            'css' => 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css',
            'dependencies' => ['jquery'],
            'icon' => 'fas fa-expand'
        ],
        [
            'id' => 'glightbox',
            'name' => 'GLightbox',
            'version' => '3.2.0',
            'description' => 'Lightbox moderno senza jQuery',
            'url' => 'https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js',
            'css' => 'https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/css/glightbox.min.css',
            'dependencies' => [],
            'icon' => 'fas fa-photo-video'
        ],
        [
            'id' => 'aos',
            'name' => 'AOS (Animate On Scroll)',
            'version' => '2.3.4',
            'description' => 'Animazioni on scroll',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js',
            'css' => 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css',
            'dependencies' => [],
            'icon' => 'fas fa-scroll'
        ],
        [
            'id' => 'gsap',
            'name' => 'GSAP',
            'version' => '3.12.2',
            'description' => 'Libreria professionale per animazioni',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js',
            'dependencies' => [],
            'icon' => 'fas fa-rocket'
        ],
        [
            'id' => 'typed',
            'name' => 'Typed.js',
            'version' => '2.0.16',
            'description' => 'Effetto macchina da scrivere',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.16/typed.umd.js',
            'dependencies' => [],
            'icon' => 'fas fa-keyboard'
        ],
        [
            'id' => 'particles',
            'name' => 'Particles.js',
            'version' => '2.0.0',
            'description' => 'Particelle animate per background',
            'url' => 'https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js',
            'dependencies' => [],
            'icon' => 'fas fa-circle'
        ],
        [
            'id' => 'isotope',
            'name' => 'Isotope',
            'version' => '3.0.6',
            'description' => 'Layout filtrabile e ordinabile',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js',
            'dependencies' => ['jquery'],
            'icon' => 'fas fa-th'
        ],
        [
            'id' => 'mixitup',
            'name' => 'MixItUp',
            'version' => '3.3.1',
            'description' => 'Filtri e ordinamento animati',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/mixitup/3.3.1/mixitup.min.js',
            'dependencies' => [],
            'icon' => 'fas fa-filter'
        ],
        [
            'id' => 'sweetalert2',
            'name' => 'SweetAlert2',
            'version' => '11.7.12',
            'description' => 'Alert e popup bellissimi',
            'url' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js',
            'css' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css',
            'dependencies' => [],
            'icon' => 'fas fa-exclamation-circle'
        ],
        [
            'id' => 'select2',
            'name' => 'Select2',
            'version' => '4.1.0',
            'description' => 'Select box avanzati',
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js',
            'css' => 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css',
            'dependencies' => ['jquery'],
            'icon' => 'fas fa-list'
        ]
    ]
];

// Load locations
$locations = [
    'theme' => 'Tema (functions.php)',
    'child-theme' => 'Tema Child',
    'plugin' => 'Plugin Personalizzato'
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-download"></i> Importa Librerie</h1>
        <p class="text-muted">Genera il codice per importare le librerie più popolari in WordPress</p>
    </div>
    
    <form id="librariesForm" method="post">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-section">
                    <label for="loadLocation">Dove caricare le librerie</label>
                    <select class="form-select" id="loadLocation" name="loadLocation">
                        <?php foreach ($locations as $value => $label): ?>
                            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-section">
                    <label for="loadWhere">Area di caricamento</label>
                    <select class="form-select" id="loadWhere" name="loadWhere">
                        <option value="frontend">Solo Frontend</option>
                        <option value="backend">Solo Backend</option>
                        <option value="both">Frontend + Backend</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <label>Seleziona le librerie da importare</label>
            <small class="form-text text-muted mb-3 d-block">Puoi selezionare più librerie. Le dipendenze verranno gestite automaticamente.</small>
            
            <!-- CSS Libraries -->
            <h5 class="mb-3"><i class="fas fa-paint-brush"></i> Librerie CSS</h5>
            <div class="libraries-grid mb-4">
                <?php foreach ($libraries['css'] as $lib): ?>
                    <div class="library-card" data-library='<?php echo json_encode($lib); ?>'>
                        <div class="library-checkbox">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="lib_<?php echo $lib['id']; ?>" 
                                   name="libraries[]" 
                                   value="<?php echo $lib['id']; ?>">
                        </div>
                        <div class="library-icon">
                            <i class="<?php echo $lib['icon']; ?>"></i>
                        </div>
                        <div class="library-info">
                            <h6><?php echo $lib['name']; ?> <small class="text-muted">v<?php echo $lib['version']; ?></small></h6>
                            <p class="mb-0 small text-muted"><?php echo $lib['description']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- JS Libraries -->
            <h5 class="mb-3"><i class="fas fa-code"></i> Librerie JavaScript</h5>
            <div class="libraries-grid">
                <?php foreach ($libraries['js'] as $lib): ?>
                    <div class="library-card" data-library='<?php echo json_encode($lib); ?>'>
                        <div class="library-checkbox">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="lib_<?php echo $lib['id']; ?>" 
                                   name="libraries[]" 
                                   value="<?php echo $lib['id']; ?>"
                                   data-dependencies='<?php echo json_encode($lib['dependencies'] ?? []); ?>'>
                        </div>
                        <div class="library-icon">
                            <i class="<?php echo $lib['icon']; ?>"></i>
                        </div>
                        <div class="library-info">
                            <h6><?php echo $lib['name']; ?> <small class="text-muted">v<?php echo $lib['version']; ?></small></h6>
                            <p class="mb-0 small text-muted"><?php echo $lib['description']; ?></p>
                            <?php if (!empty($lib['dependencies'])): ?>
                                <p class="mb-0 small text-warning">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    Richiede: <?php echo implode(', ', $lib['dependencies']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-section">
            <label>Opzioni aggiuntive</label>
            <div class="checkbox-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="asyncLoad" id="asyncLoad" value="1">
                    <label class="form-check-label" for="asyncLoad">
                        Caricamento asincrono (async/defer)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="localFallback" id="localFallback" value="1">
                    <label class="form-check-label" for="localFallback">
                        Aggiungi fallback locale
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="integrityCheck" id="integrityCheck" value="1" checked>
                    <label class="form-check-label" for="integrityCheck">
                        Aggiungi controllo integrità (SRI)
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-generate">
            <i class="fas fa-magic"></i> Genera Codice
        </button>
    </form>

    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
        <div class="spinner-container">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Generando...</span>
            </div>
            <p class="mt-3">Generando il codice...</p>
        </div>
    </div>

    <div class="code-output" id="librariesOutput" style="display: none;">
        <div class="copy-btn-container">
            <button class="btn btn-copy" onclick="copyCode('librariesCode')">
                <i class="fas fa-copy"></i> Copia
            </button>
        </div>
        <pre><code class="language-php" id="librariesCode"></code></pre>
    </div>
</div>

<style>
.libraries-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 15px;
}

.library-card {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s;
    cursor: pointer;
}

.library-card:hover {
    border-color: var(--secondary-color);
    background: #e3f2fd;
}

.library-card.selected {
    border-color: var(--success-color);
    background: #e8f5e9;
}

.library-checkbox {
    flex-shrink: 0;
}

.library-icon {
    font-size: 2rem;
    color: var(--secondary-color);
    width: 50px;
    text-align: center;
    flex-shrink: 0;
}

.library-info {
    flex: 1;
}

.library-info h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.spinner-container {
    text-align: center;
    color: white;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>

<script>
// Handle library card clicks
document.querySelectorAll('.library-card').forEach(card => {
    card.addEventListener('click', function(e) {
        if (e.target.type !== 'checkbox') {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
        }
    });
});

// Handle checkbox changes
document.querySelectorAll('.library-card input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const card = this.closest('.library-card');
        if (this.checked) {
            card.classList.add('selected');
            
            // Check dependencies
            const dependencies = JSON.parse(this.dataset.dependencies || '[]');
            dependencies.forEach(dep => {
                const depCheckbox = document.getElementById('lib_' + dep);
                if (depCheckbox && !depCheckbox.checked) {
                    depCheckbox.checked = true;
                    depCheckbox.dispatchEvent(new Event('change'));
                }
            });
        } else {
            card.classList.remove('selected');
        }
    });
});

// Form submission
document.getElementById('librariesForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading
    document.getElementById('loadingOverlay').style.display = 'flex';
    
    // Get form data
    const formData = new FormData(this);
    
    // Add library data
    const selectedLibs = [];
    document.querySelectorAll('.library-card input:checked').forEach(checkbox => {
        const card = checkbox.closest('.library-card');
        const libData = JSON.parse(card.dataset.library);
        selectedLibs.push(libData);
    });
    
    formData.append('libraryData', JSON.stringify(selectedLibs));
    
    // Generate code via AJAX
    fetch('generators/libraries-generator.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(code => {
        // Hide loading
        document.getElementById('loadingOverlay').style.display = 'none';
        
        // Show code
        document.getElementById('librariesCode').textContent = code;
        document.getElementById('librariesOutput').style.display = 'block';
        Prism.highlightElement(document.getElementById('librariesCode'));
        
        // Scroll to code
        document.getElementById('librariesOutput').scrollIntoView({ behavior: 'smooth' });
    })
    .catch(error => {
        console.error('Errore:', error);
        document.getElementById('loadingOverlay').style.display = 'none';
        showNotification('Errore nella generazione del codice', 'danger');
    });
});
</script>