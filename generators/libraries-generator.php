<?php
// Libraries Code Generator
header('Content-Type: text/plain; charset=utf-8');

// Get form data
$location = $_POST['loadLocation'] ?? 'theme';
$loadWhere = $_POST['loadWhere'] ?? 'frontend';
$asyncLoad = isset($_POST['asyncLoad']);
$localFallback = isset($_POST['localFallback']);
$integrityCheck = isset($_POST['integrityCheck']);
$libraryData = json_decode($_POST['libraryData'] ?? '[]', true);

// Sort libraries by dependencies
function sortLibrariesByDependencies($libraries) {
    $sorted = [];
    $processed = [];
    
    while (count($sorted) < count($libraries)) {
        foreach ($libraries as $lib) {
            if (in_array($lib['id'], $processed)) {
                continue;
            }
            
            $canAdd = true;
            if (isset($lib['dependencies'])) {
                foreach ($lib['dependencies'] as $dep) {
                    if (!in_array($dep, $processed)) {
                        $canAdd = false;
                        break;
                    }
                }
            }
            
            if ($canAdd) {
                $sorted[] = $lib;
                $processed[] = $lib['id'];
            }
        }
    }
    
    return $sorted;
}

$libraries = sortLibrariesByDependencies($libraryData);

// Generate code
$code = "<?php\n";
$code .= "/**\n";
$code .= " * Import External Libraries\n";
$code .= " * Generated on " . date('Y-m-d H:i:s') . "\n";
$code .= " * \n";
$code .= " * Libraries included:\n";
foreach ($libraries as $lib) {
    $code .= " * - {$lib['name']} v{$lib['version']}\n";
}
$code .= " */\n\n";

// Generate function name
$functionPrefix = $location === 'plugin' ? 'plugin' : 'theme';
$uniqueId = substr(md5(time()), 0, 8);

// Add enqueue function
$code .= "// Enqueue external libraries\n";
$code .= "function {$functionPrefix}_enqueue_libraries_{$uniqueId}() {\n";

// Add condition check
if ($loadWhere === 'frontend') {
    $code .= "    // Load only on frontend\n";
    $code .= "    if ( is_admin() ) {\n";
    $code .= "        return;\n";
    $code .= "    }\n\n";
} elseif ($loadWhere === 'backend') {
    $code .= "    // Load only on backend\n";
    $code .= "    if ( ! is_admin() ) {\n";
    $code .= "        return;\n";
    $code .= "    }\n\n";
}

// Process each library
foreach ($libraries as $lib) {
    $code .= "    // {$lib['name']}\n";
    
    // CSS file
    if (isset($lib['css']) || (isset($lib['url']) && !isset($lib['js']))) {
        $cssUrl = $lib['css'] ?? $lib['url'];
        $cssHandle = "lib-{$lib['id']}-css";
        
        $code .= "    wp_enqueue_style(\n";
        $code .= "        '{$cssHandle}',\n";
        $code .= "        '{$cssUrl}',\n";
        $code .= "        array(),\n";
        $code .= "        '{$lib['version']}'\n";
        $code .= "    );\n";
        
        if ($localFallback) {
            $code .= "    \n";
            $code .= "    // Local fallback for {$lib['name']} CSS\n";
            $code .= "    wp_add_inline_script( 'jquery', '\n";
            $code .= "        if (!document.getElementById(\"{$cssHandle}-css\")) {\n";
            $code .= "            var link = document.createElement(\"link\");\n";
            $code .= "            link.id = \"{$cssHandle}-css\";\n";
            $code .= "            link.rel = \"stylesheet\";\n";
            $code .= "            link.href = \"' . get_template_directory_uri() . '/assets/libs/{$lib['id']}/{$lib['id']}.min.css\";\n";
            $code .= "            document.head.appendChild(link);\n";
            $code .= "        }\n";
            $code .= "    ' );\n";
        }
    }
    
    // JS file
    if (isset($lib['js']) || (isset($lib['url']) && !isset($lib['css']) && $lib['id'] !== 'fontawesome')) {
        $jsUrl = $lib['js'] ?? $lib['url'];
        $jsHandle = "lib-{$lib['id']}-js";
        $deps = isset($lib['dependencies']) ? array_map(function($dep) { return "lib-{$dep}-js"; }, $lib['dependencies']) : array();
        
        // Add jQuery if needed
        if (in_array('jquery', $lib['dependencies'] ?? [])) {
            $deps = array('jquery');
        }
        
        $code .= "    wp_enqueue_script(\n";
        $code .= "        '{$jsHandle}',\n";
        $code .= "        '{$jsUrl}',\n";
        $code .= "        array(" . (empty($deps) ? "" : "'" . implode("', '", $deps) . "'") . "),\n";
        $code .= "        '{$lib['version']}',\n";
        $code .= "        true\n";
        $code .= "    );\n";
        
        if ($asyncLoad) {
            $code .= "    \n";
            $code .= "    // Add async/defer attributes\n";
            $code .= "    wp_script_add_data( '{$jsHandle}', 'async', true );\n";
        }
        
        if ($localFallback) {
            $code .= "    \n";
            $code .= "    // Local fallback for {$lib['name']} JS\n";
            $fallbackTest = "";
            
            // Specific fallback tests for known libraries
            switch($lib['id']) {
                case 'jquery':
                    $fallbackTest = "typeof jQuery === 'undefined'";
                    break;
                case 'bootstrap5':
                    $fallbackTest = "typeof bootstrap === 'undefined'";
                    break;
                case 'owlcarousel':
                    $fallbackTest = "!jQuery.fn.owlCarousel";
                    break;
                case 'swiper':
                    $fallbackTest = "typeof Swiper === 'undefined'";
                    break;
                case 'gsap':
                    $fallbackTest = "typeof gsap === 'undefined'";
                    break;
                default:
                    $fallbackTest = "false"; // Skip fallback for unknown libraries
            }
            
            if ($fallbackTest !== "false") {
                $code .= "    wp_add_inline_script( '{$jsHandle}', '\n";
                $code .= "        if ({$fallbackTest}) {\n";
                $code .= "            var script = document.createElement(\"script\");\n";
                $code .= "            script.src = \"' . get_template_directory_uri() . '/assets/libs/{$lib['id']}/{$lib['id']}.min.js\";\n";
                $code .= "            document.body.appendChild(script);\n";
                $code .= "        }\n";
                $code .= "    ', 'after' );\n";
            }
        }
    }
    
    $code .= "    \n";
}

// Add integrity check if requested
if ($integrityCheck) {
    $code .= "    // Add SRI (Subresource Integrity) attributes\n";
    $code .= "    add_filter( 'script_loader_tag', function( \$tag, \$handle, \$src ) {\n";
    $code .= "        \$sri_hashes = array(\n";
    $code .= "            // Add your SRI hashes here\n";
    $code .= "            // 'lib-jquery-js' => 'sha384-...',\n";
    $code .= "        );\n";
    $code .= "        \n";
    $code .= "        if ( isset( \$sri_hashes[\$handle] ) ) {\n";
    $code .= "            \$tag = str_replace( ' src=', ' integrity=\"' . \$sri_hashes[\$handle] . '\" crossorigin=\"anonymous\" src=', \$tag );\n";
    $code .= "        }\n";
    $code .= "        \n";
    $code .= "        return \$tag;\n";
    $code .= "    }, 10, 3 );\n";
    $code .= "    \n";
    $code .= "    add_filter( 'style_loader_tag', function( \$tag, \$handle, \$href, \$media ) {\n";
    $code .= "        \$sri_hashes = array(\n";
    $code .= "            // Add your SRI hashes here\n";
    $code .= "            // 'lib-bootstrap5-css' => 'sha384-...',\n";
    $code .= "        );\n";
    $code .= "        \n";
    $code .= "        if ( isset( \$sri_hashes[\$handle] ) ) {\n";
    $code .= "            \$tag = str_replace( ' href=', ' integrity=\"' . \$sri_hashes[\$handle] . '\" crossorigin=\"anonymous\" href=', \$tag );\n";
    $code .= "        }\n";
    $code .= "        \n";
    $code .= "        return \$tag;\n";
    $code .= "    }, 10, 4 );\n";
}

$code .= "}\n";

// Add action hook
if ($loadWhere === 'backend') {
    $code .= "add_action( 'admin_enqueue_scripts', '{$functionPrefix}_enqueue_libraries_{$uniqueId}' );\n";
} else {
    $code .= "add_action( 'wp_enqueue_scripts', '{$functionPrefix}_enqueue_libraries_{$uniqueId}' );\n";
}

// Add initialization code for specific libraries
$hasInitCode = false;
foreach ($libraries as $lib) {
    if (in_array($lib['id'], ['owlcarousel', 'swiper', 'aos', 'typed', 'particles', 'isotope', 'mixitup', 'select2'])) {
        $hasInitCode = true;
        break;
    }
}

if ($hasInitCode) {
    $code .= "\n// Add initialization code\n";
    $code .= "function {$functionPrefix}_init_libraries_{$uniqueId}() {\n";
    $code .= "    ?>\n";
    $code .= "    <script>\n";
    $code .= "    jQuery(document).ready(function($) {\n";
    
    foreach ($libraries as $lib) {
        switch($lib['id']) {
            case 'owlcarousel':
                $code .= "        // Initialize Owl Carousel\n";
                $code .= "        if ($.fn.owlCarousel) {\n";
                $code .= "            $('.owl-carousel').owlCarousel({\n";
                $code .= "                loop: true,\n";
                $code .= "                margin: 10,\n";
                $code .= "                nav: true,\n";
                $code .= "                responsive: {\n";
                $code .= "                    0: { items: 1 },\n";
                $code .= "                    600: { items: 3 },\n";
                $code .= "                    1000: { items: 5 }\n";
                $code .= "                }\n";
                $code .= "            });\n";
                $code .= "        }\n\n";
                break;
                
            case 'swiper':
                $code .= "        // Initialize Swiper\n";
                $code .= "        if (typeof Swiper !== 'undefined') {\n";
                $code .= "            const swiper = new Swiper('.swiper', {\n";
                $code .= "                direction: 'horizontal',\n";
                $code .= "                loop: true,\n";
                $code .= "                pagination: {\n";
                $code .= "                    el: '.swiper-pagination',\n";
                $code .= "                },\n";
                $code .= "                navigation: {\n";
                $code .= "                    nextEl: '.swiper-button-next',\n";
                $code .= "                    prevEl: '.swiper-button-prev',\n";
                $code .= "                },\n";
                $code .= "            });\n";
                $code .= "        }\n\n";
                break;
                
            case 'aos':
                $code .= "        // Initialize AOS\n";
                $code .= "        if (typeof AOS !== 'undefined') {\n";
                $code .= "            AOS.init({\n";
                $code .= "                duration: 1000,\n";
                $code .= "                once: true,\n";
                $code .= "                offset: 100\n";
                $code .= "            });\n";
                $code .= "        }\n\n";
                break;
                
            case 'typed':
                $code .= "        // Initialize Typed.js\n";
                $code .= "        if (typeof Typed !== 'undefined' && document.querySelector('.typed-text')) {\n";
                $code .= "            var typed = new Typed('.typed-text', {\n";
                $code .= "                strings: ['Your text here', 'Another text'],\n";
                $code .= "                typeSpeed: 50,\n";
                $code .= "                backSpeed: 50,\n";
                $code .= "                loop: true\n";
                $code .= "            });\n";
                $code .= "        }\n\n";
                break;
                
            case 'particles':
                $code .= "        // Initialize Particles.js\n";
                $code .= "        if (typeof particlesJS !== 'undefined' && document.getElementById('particles-js')) {\n";
                $code .= "            particlesJS('particles-js', {\n";
                $code .= "                particles: {\n";
                $code .= "                    number: { value: 80, density: { enable: true, value_area: 800 } },\n";
                $code .= "                    color: { value: '#ffffff' },\n";
                $code .= "                    shape: { type: 'circle' },\n";
                $code .= "                    opacity: { value: 0.5, random: false },\n";
                $code .= "                    size: { value: 3, random: true },\n";
                $code .= "                    line_linked: { enable: true, distance: 150, color: '#ffffff', opacity: 0.4, width: 1 },\n";
                $code .= "                    move: { enable: true, speed: 6, direction: 'none', random: false, straight: false, out_mode: 'out', bounce: false }\n";
                $code .= "                },\n";
                $code .= "                interactivity: {\n";
                $code .= "                    detect_on: 'canvas',\n";
                $code .= "                    events: { onhover: { enable: true, mode: 'repulse' }, onclick: { enable: true, mode: 'push' }, resize: true }\n";
                $code .= "                },\n";
                $code .= "                retina_detect: true\n";
                $code .= "            });\n";
                $code .= "        }\n\n";
                break;
                
            case 'isotope':
                $code .= "        // Initialize Isotope\n";
                $code .= "        if ($.fn.isotope) {\n";
                $code .= "            var \$grid = $('.grid').isotope({\n";
                $code .= "                itemSelector: '.grid-item',\n";
                $code .= "                layoutMode: 'fitRows'\n";
                $code .= "            });\n";
                $code .= "            \n";
                $code .= "            // Filter items on button click\n";
                $code .= "            $('.filter-button-group').on('click', 'button', function() {\n";
                $code .= "                var filterValue = $(this).attr('data-filter');\n";
                $code .= "                \$grid.isotope({ filter: filterValue });\n";
                $code .= "            });\n";
                $code .= "        }\n\n";
                break;
                
            case 'mixitup':
                $code .= "        // Initialize MixItUp\n";
                $code .= "        if (typeof mixitup !== 'undefined') {\n";
                $code .= "            var mixer = mixitup('.mix-container', {\n";
                $code .= "                selectors: {\n";
                $code .= "                    target: '.mix'\n";
                $code .= "                },\n";
                $code .= "                animation: {\n";
                $code .= "                    duration: 300\n";
                $code .= "                }\n";
                $code .= "            });\n";
                $code .= "        }\n\n";
                break;
                
            case 'select2':
                $code .= "        // Initialize Select2\n";
                $code .= "        if ($.fn.select2) {\n";
                $code .= "            $('.select2').select2({\n";
                $code .= "                placeholder: 'Seleziona un\'opzione',\n";
                $code .= "                allowClear: true\n";
                $code .= "            });\n";
                $code .= "        }\n\n";
                break;
        }
    }
    
    $code .= "    });\n";
    $code .= "    </script>\n";
    $code .= "    <?php\n";
    $code .= "}\n";
    $code .= "add_action( 'wp_footer', '{$functionPrefix}_init_libraries_{$uniqueId}', 100 );\n";
}

// Add usage instructions
$code .= "\n/*\n";
$code .= " * ISTRUZIONI D'USO:\n";
$code .= " * \n";
foreach ($libraries as $lib) {
    $code .= " * {$lib['name']}:\n";
    
    switch($lib['id']) {
        case 'bootstrap5':
            $code .= " *   - Usa le classi Bootstrap nei tuoi template\n";
            $code .= " *   - Esempio: <div class=\"container\"><div class=\"row\"><div class=\"col-md-6\">...</div></div></div>\n";
            break;
        case 'fontawesome':
            $code .= " *   - Usa le icone Font Awesome\n";
            $code .= " *   - Esempio: <i class=\"fas fa-home\"></i>\n";
            break;
        case 'owlcarousel':
            $code .= " *   - Aggiungi la classe 'owl-carousel' al tuo container\n";
            $code .= " *   - Esempio: <div class=\"owl-carousel\"><div>Slide 1</div><div>Slide 2</div></div>\n";
            break;
        case 'swiper':
            $code .= " *   - Struttura HTML richiesta:\n";
            $code .= " *   <div class=\"swiper\">\n";
            $code .= " *     <div class=\"swiper-wrapper\">\n";
            $code .= " *       <div class=\"swiper-slide\">Slide 1</div>\n";
            $code .= " *       <div class=\"swiper-slide\">Slide 2</div>\n";
            $code .= " *     </div>\n";
            $code .= " *     <div class=\"swiper-pagination\"></div>\n";
            $code .= " *     <div class=\"swiper-button-prev\"></div>\n";
            $code .= " *     <div class=\"swiper-button-next\"></div>\n";
            $code .= " *   </div>\n";
            break;
        case 'lightbox2':
            $code .= " *   - Aggiungi data-lightbox alle tue immagini\n";
            $code .= " *   - Esempio: <a href=\"image.jpg\" data-lightbox=\"gallery\"><img src=\"thumb.jpg\"></a>\n";
            break;
        case 'glightbox':
            $code .= " *   - Aggiungi la classe 'glightbox' ai tuoi link\n";
            $code .= " *   - Esempio: <a href=\"image.jpg\" class=\"glightbox\"><img src=\"thumb.jpg\"></a>\n";
            break;
        case 'aos':
            $code .= " *   - Aggiungi data-aos agli elementi da animare\n";
            $code .= " *   - Esempio: <div data-aos=\"fade-up\" data-aos-duration=\"1000\">...</div>\n";
            break;
        case 'typed':
            $code .= " *   - Aggiungi la classe 'typed-text' all'elemento\n";
            $code .= " *   - Esempio: <span class=\"typed-text\"></span>\n";
            break;
        case 'particles':
            $code .= " *   - Crea un div con id 'particles-js'\n";
            $code .= " *   - Esempio: <div id=\"particles-js\"></div>\n";
            break;
        case 'isotope':
            $code .= " *   - Struttura HTML:\n";
            $code .= " *   <div class=\"filter-button-group\">\n";
            $code .= " *     <button data-filter=\"*\">All</button>\n";
            $code .= " *     <button data-filter=\".category1\">Category 1</button>\n";
            $code .= " *   </div>\n";
            $code .= " *   <div class=\"grid\">\n";
            $code .= " *     <div class=\"grid-item category1\">...</div>\n";
            $code .= " *   </div>\n";
            break;
        case 'select2':
            $code .= " *   - Aggiungi la classe 'select2' ai tuoi select\n";
            $code .= " *   - Esempio: <select class=\"select2\"><option>...</option></select>\n";
            break;
    }
    $code .= " * \n";
}

if ($localFallback) {
    $code .= " * NOTA: Ricordati di scaricare le librerie localmente in:\n";
    $code .= " * /assets/libs/{nome-libreria}/{nome-libreria}.min.css\n";
    $code .= " * /assets/libs/{nome-libreria}/{nome-libreria}.min.js\n";
}

$code .= " */\n";

$code .= "\n?>";

// Output the generated code
echo $code;