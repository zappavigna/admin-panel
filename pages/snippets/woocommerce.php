<?php
// WooCommerce Snippets
$snippets = [
    [
        'id' => 'custom-add-to-cart',
        'title' => 'Testo Add to Cart Personalizzato',
        'description' => 'Cambia il testo del pulsante aggiungi al carrello',
        'category' => 'cart',
        'icon' => 'fas fa-shopping-cart',
        'code' => '// Change add to cart text on single product page
function custom_single_add_to_cart_text() {
    return __(\'Aggiungi al Carrello\', \'woocommerce\');
}
add_filter(\'woocommerce_product_single_add_to_cart_text\', \'custom_single_add_to_cart_text\');

// Change add to cart text on product archives
function custom_product_add_to_cart_text() {
    global $product;
    
    $product_type = $product->get_type();
    
    switch ($product_type) {
        case \'external\':
            return __(\'Acquista ora\', \'woocommerce\');
        case \'grouped\':
            return __(\'Vedi opzioni\', \'woocommerce\');
        case \'simple\':
            return __(\'Aggiungi\', \'woocommerce\');
        case \'variable\':
            return __(\'Scegli\', \'woocommerce\');
        default:
            return __(\'Leggi di più\', \'woocommerce\');
    }
}
add_filter(\'woocommerce_product_add_to_cart_text\', \'custom_product_add_to_cart_text\');'
    ],
    [
        'id' => 'free-shipping-notice',
        'title' => 'Avviso Spedizione Gratuita',
        'description' => 'Mostra quanto manca per la spedizione gratuita',
        'category' => 'shipping',
        'icon' => 'fas fa-truck',
        'code' => '// Free shipping progress bar
function free_shipping_progress_bar() {
    $min_amount = 50; // Minimum amount for free shipping
    $current = WC()->cart->get_subtotal();
    
    if ($current < $min_amount) {
        $remaining = $min_amount - $current;
        $percentage = ($current / $min_amount) * 100;
        
        echo \'<div class="free-shipping-notice">\';
        echo \'<p>Ti mancano <strong>\' . wc_price($remaining) . \'</strong> per la spedizione gratuita!</p>\';
        echo \'<div class="progress">\';
        echo \'<div class="progress-bar" style="width: \' . $percentage . \'%"></div>\';
        echo \'</div>\';
        echo \'</div>\';
    } else {
        echo \'<div class="free-shipping-notice success">\';
        echo \'<p><i class="fas fa-check-circle"></i> Hai diritto alla spedizione gratuita!</p>\';
        echo \'</div>\';
    }
}
add_action(\'woocommerce_before_cart\', \'free_shipping_progress_bar\');
add_action(\'woocommerce_before_checkout_form\', \'free_shipping_progress_bar\', 5);

// CSS
/*
.free-shipping-notice {
    background: #f7f7f7;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}
.free-shipping-notice.success {
    background: #d4edda;
    color: #155724;
}
.progress {
    height: 20px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 10px;
}
.progress-bar {
    height: 100%;
    background: #28a745;
    transition: width 0.3s;
}
*/'
    ],
    [
        'id' => 'products-per-page',
        'title' => 'Prodotti per Pagina',
        'description' => 'Permetti agli utenti di scegliere quanti prodotti visualizzare',
        'category' => 'shop',
        'icon' => 'fas fa-th',
        'code' => '// Products per page selector
function products_per_page_selector() {
    $per_page = filter_input(INPUT_GET, \'per_page\', FILTER_SANITIZE_NUMBER_INT);
    ?>
    <form class="woocommerce-ordering" method="get">
        <select name="per_page" class="per_page" onchange="this.form.submit()">
            <option value="12" <?php selected($per_page, 12); ?>>12 per pagina</option>
            <option value="24" <?php selected($per_page, 24); ?>>24 per pagina</option>
            <option value="36" <?php selected($per_page, 36); ?>>36 per pagina</option>
            <option value="-1" <?php selected($per_page, -1); ?>>Mostra tutti</option>
        </select>
        <?php
        // Keep query string vars intact
        foreach ($_GET as $key => $val) {
            if (\'per_page\' === $key || \'submit\' === $key) {
                continue;
            }
            if (is_array($val)) {
                foreach($val as $inner_val) {
                    echo \'<input type="hidden" name="\' . esc_attr($key) . \'[]" value="\' . esc_attr($inner_val) . \'" />\';
                }
            } else {
                echo \'<input type="hidden" name="\' . esc_attr($key) . \'" value="\' . esc_attr($val) . \'" />\';
            }
        }
        ?>
    </form>
    <?php
}
add_action(\'woocommerce_before_shop_loop\', \'products_per_page_selector\', 20);

// Apply products per page
function apply_products_per_page($query) {
    $per_page = filter_input(INPUT_GET, \'per_page\', FILTER_SANITIZE_NUMBER_INT);
    if ($per_page && $query->is_main_query() && !is_admin()) {
        $query->set(\'posts_per_page\', $per_page);
    }
}
add_action(\'pre_get_posts\', \'apply_products_per_page\');'
    ],
    [
        'id' => 'custom-checkout-fields',
        'title' => 'Campi Checkout Personalizzati',
        'description' => 'Aggiungi campi personalizzati al checkout',
        'category' => 'checkout',
        'icon' => 'fas fa-edit',
        'code' => '// Add custom checkout field
function custom_checkout_field($checkout) {
    echo \'<div id="custom_checkout_field">\';
    echo \'<h3>\' . __(\'Informazioni Aggiuntive\') . \'</h3>\';
    
    woocommerce_form_field(\'gift_message\', array(
        \'type\' => \'textarea\',
        \'class\' => array(\'my-field-class form-row-wide\'),
        \'label\' => __(\'Messaggio regalo\'),
        \'placeholder\' => __(\'Inserisci un messaggio per il destinatario\'),
        \'required\' => false,
    ), $checkout->get_value(\'gift_message\'));
    
    woocommerce_form_field(\'delivery_date\', array(
        \'type\' => \'date\',
        \'class\' => array(\'my-field-class form-row-wide\'),
        \'label\' => __(\'Data di consegna preferita\'),
        \'required\' => false,
    ), $checkout->get_value(\'delivery_date\'));
    
    echo \'</div>\';
}
add_action(\'woocommerce_after_order_notes\', \'custom_checkout_field\');

// Validate custom fields
function custom_checkout_field_validation() {
    // Add validation if needed
    if (isset($_POST[\'delivery_date\']) && !empty($_POST[\'delivery_date\'])) {
        $delivery_date = strtotime($_POST[\'delivery_date\']);
        $minimum_date = strtotime(\'+2 days\');
        
        if ($delivery_date < $minimum_date) {
            wc_add_notice(__(\'La data di consegna deve essere almeno 2 giorni da oggi.\'), \'error\');
        }
    }
}
add_action(\'woocommerce_checkout_process\', \'custom_checkout_field_validation\');

// Save custom fields
function custom_checkout_field_save($order_id) {
    if (!empty($_POST[\'gift_message\'])) {
        update_post_meta($order_id, \'gift_message\', sanitize_textarea_field($_POST[\'gift_message\']));
    }
    
    if (!empty($_POST[\'delivery_date\'])) {
        update_post_meta($order_id, \'delivery_date\', sanitize_text_field($_POST[\'delivery_date\']));
    }
}
add_action(\'woocommerce_checkout_update_order_meta\', \'custom_checkout_field_save\');

// Display in admin order
function custom_checkout_field_display_admin($order) {
    $gift_message = get_post_meta($order->get_id(), \'gift_message\', true);
    $delivery_date = get_post_meta($order->get_id(), \'delivery_date\', true);
    
    if ($gift_message) {
        echo \'<p><strong>\' . __(\'Messaggio regalo:\') . \'</strong> \' . $gift_message . \'</p>\';
    }
    
    if ($delivery_date) {
        echo \'<p><strong>\' . __(\'Data consegna preferita:\') . \'</strong> \' . $delivery_date . \'</p>\';
    }
}
add_action(\'woocommerce_admin_order_data_after_billing_address\', \'custom_checkout_field_display_admin\');'
    ],
    [
        'id' => 'custom-product-tabs',
        'title' => 'Tab Prodotto Personalizzati',
        'description' => 'Aggiungi tab personalizzati alla pagina prodotto',
        'category' => 'product',
        'icon' => 'fas fa-folder',
        'code' => '// Add custom product tab
function custom_product_tabs($tabs) {
    // Add new tab
    $tabs[\'custom_tab\'] = array(
        \'title\' => __(\'Informazioni Speciali\', \'woocommerce\'),
        \'priority\' => 50,
        \'callback\' => \'custom_product_tab_content\'
    );
    
    // Rename existing tabs
    $tabs[\'description\'][\'title\'] = __(\'Dettagli Prodotto\');
    $tabs[\'additional_information\'][\'title\'] = __(\'Specifiche\');
    
    // Remove a tab
    // unset($tabs[\'reviews\']);
    
    return $tabs;
}
add_filter(\'woocommerce_product_tabs\', \'custom_product_tabs\', 98);

function custom_product_tab_content() {
    global $product;
    
    // Tab content
    echo \'<h2>Informazioni Speciali</h2>\';
    echo \'<p>Contenuto personalizzato per il prodotto.</p>\';
    
    // You can add custom fields content
    $custom_info = get_post_meta($product->get_id(), \'_custom_product_info\', true);
    if ($custom_info) {
        echo wpautop($custom_info);
    }
}'
    ],
    [
        'id' => 'sale-percentage',
        'title' => 'Mostra Percentuale Sconto',
        'description' => 'Visualizza la percentuale di sconto sui prodotti in offerta',
        'category' => 'product',
        'icon' => 'fas fa-percentage',
        'code' => '// Show sale percentage
function show_sale_percentage() {
    global $product;
    
    if (!$product->is_on_sale()) return;
    
    if ($product->is_type(\'simple\')) {
        $regular_price = (float) $product->get_regular_price();
        $sale_price = (float) $product->get_sale_price();
        
        if ($regular_price > 0) {
            $percentage = round(100 - ($sale_price / $regular_price * 100));
            echo \'<span class="onsale">-\' . $percentage . \'%</span>\';
        }
    } elseif ($product->is_type(\'variable\')) {
        $percentages = array();
        
        foreach ($product->get_children() as $child_id) {
            $variation = wc_get_product($child_id);
            $regular_price = (float) $variation->get_regular_price();
            $sale_price = (float) $variation->get_sale_price();
            
            if ($sale_price != 0 && $regular_price > 0) {
                $percentages[] = round(100 - ($sale_price / $regular_price * 100));
            }
        }
        
        if (!empty($percentages)) {
            $percentage = max($percentages);
            echo \'<span class="onsale">Fino al -\' . $percentage . \'%</span>\';
        }
    }
}
// Remove default sale badge
remove_action(\'woocommerce_before_shop_loop_item_title\', \'woocommerce_show_product_loop_sale_flash\', 10);
remove_action(\'woocommerce_before_single_product_summary\', \'woocommerce_show_product_sale_flash\', 10);

// Add custom sale badge
add_action(\'woocommerce_before_shop_loop_item_title\', \'show_sale_percentage\', 10);
add_action(\'woocommerce_before_single_product_summary\', \'show_sale_percentage\', 10);'
    ],
    [
        'id' => 'minimum-order-amount',
        'title' => 'Importo Minimo Ordine',
        'description' => 'Imposta un importo minimo per completare l\'ordine',
        'category' => 'checkout',
        'icon' => 'fas fa-euro-sign',
        'code' => '// Set minimum order amount
function minimum_order_amount() {
    $minimum = 30; // Set minimum order amount
    
    if (WC()->cart->total < $minimum) {
        if (is_cart()) {
            wc_print_notice(
                sprintf(
                    \'Il tuo ordine attuale è di %s — devi avere un ordine minimo di %s per procedere al checkout.\',
                    wc_price(WC()->cart->total),
                    wc_price($minimum)
                ),
                \'error\'
            );
        } else {
            wc_add_notice(
                sprintf(
                    \'Il tuo ordine attuale è di %s — devi avere un ordine minimo di %s per procedere al checkout.\',
                    wc_price(WC()->cart->total),
                    wc_price($minimum)
                ),
                \'error\'
            );
        }
    }
}
add_action(\'woocommerce_checkout_process\', \'minimum_order_amount\');
add_action(\'woocommerce_before_cart\', \'minimum_order_amount\');'
    ],
    [
        'id' => 'custom-stock-status',
        'title' => 'Stati Stock Personalizzati',
        'description' => 'Aggiungi stati di disponibilità personalizzati',
        'category' => 'inventory',
        'icon' => 'fas fa-boxes',
        'code' => '// Add custom stock status
function add_custom_stock_status() {
    ?>
    <script type="text/javascript">
    jQuery(function($) {
        // Add new stock status
        $(\'.stock_status_field\').not(\'.custom-stock-status\').addClass(\'custom-stock-status\').append(\'<option value="onbackorder">In arrivo</option><option value="preorder">Preordine</option>\');
    });
    </script>
    <?php
}
add_action(\'admin_footer\', \'add_custom_stock_status\');

// Display custom stock status
function display_custom_stock_status($availability, $product) {
    switch($product->get_stock_status()) {
        case \'onbackorder\':
            $availability[\'availability\'] = __(\'In arrivo\', \'woocommerce\');
            $availability[\'class\'] = \'onbackorder\';
            break;
        case \'preorder\':
            $availability[\'availability\'] = __(\'Disponibile in preordine\', \'woocommerce\');
            $availability[\'class\'] = \'preorder\';
            break;
    }
    
    return $availability;
}
add_filter(\'woocommerce_get_availability\', \'display_custom_stock_status\', 10, 2);'
    ],
    [
        'id' => 'quick-view',
        'title' => 'Quick View Prodotti',
        'description' => 'Aggiungi funzionalità quick view ai prodotti',
        'category' => 'shop',
        'icon' => 'fas fa-eye',
        'code' => '// Add Quick View button
function add_quick_view_button() {
    global $product;
    echo \'<a href="#" class="button quick-view-button" data-product-id="\' . $product->get_id() . \'">Quick View</a>\';
}
add_action(\'woocommerce_after_shop_loop_item\', \'add_quick_view_button\', 15);

// Quick View AJAX handler
function load_product_quick_view() {
    $product_id = intval($_POST[\'product_id\']);
    
    // Get product data
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_die();
    }
    
    // Output product quick view content
    ?>
    <div class="quick-view-content">
        <div class="quick-view-image">
            <?php echo $product->get_image(\'large\'); ?>
        </div>
        <div class="quick-view-details">
            <h2><?php echo $product->get_name(); ?></h2>
            <div class="price"><?php echo $product->get_price_html(); ?></div>
            <div class="description">
                <?php echo wp_trim_words($product->get_description(), 20); ?>
            </div>
            <a href="<?php echo $product->get_permalink(); ?>" class="button">Vedi Dettagli</a>
            <?php woocommerce_template_single_add_to_cart(); ?>
        </div>
    </div>
    <?php
    
    wp_die();
}
add_action(\'wp_ajax_load_product_quick_view\', \'load_product_quick_view\');
add_action(\'wp_ajax_nopriv_load_product_quick_view\', \'load_product_quick_view\');

// Quick View JavaScript
/*
jQuery(document).ready(function($) {
    $(\'.quick-view-button\').on(\'click\', function(e) {
        e.preventDefault();
        var product_id = $(this).data(\'product-id\');
        
        // Open modal and load content
        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: \'POST\',
            data: {
                action: \'load_product_quick_view\',
                product_id: product_id
            },
            success: function(response) {
                // Display in modal
                $(\'#quick-view-modal .modal-content\').html(response);
                $(\'#quick-view-modal\').fadeIn();
            }
        });
    });
});
*/'
    ],
    [
        'id' => 'empty-cart-button',
        'title' => 'Pulsante Svuota Carrello',
        'description' => 'Aggiungi un pulsante per svuotare il carrello',
        'category' => 'cart',
        'icon' => 'fas fa-trash',
        'code' => '// Add empty cart button
function add_empty_cart_button() {
    echo \'<a href="\' . esc_url(add_query_arg(\'empty_cart\', \'yes\')) . \'" class="button empty-cart" onclick="return confirm(\\\'Sei sicuro di voler svuotare il carrello?\\\');">Svuota Carrello</a>\';
}
add_action(\'woocommerce_cart_actions\', \'add_empty_cart_button\');

// Handle empty cart action
function handle_empty_cart() {
    if (isset($_GET[\'empty_cart\']) && $_GET[\'empty_cart\'] == \'yes\') {
        WC()->cart->empty_cart();
        
        // Add notice
        wc_add_notice(\'Il carrello è stato svuotato.\', \'success\');
        
        // Redirect to cart
        $cart_url = wc_get_cart_url();
        wp_safe_redirect($cart_url);
        exit;
    }
}
add_action(\'init\', \'handle_empty_cart\');'
    ]
];

// Categories
$categories = [
    'all' => 'Tutti',
    'cart' => 'Carrello',
    'checkout' => 'Checkout',
    'product' => 'Prodotti',
    'shop' => 'Shop',
    'shipping' => 'Spedizione',
    'inventory' => 'Inventario'
];
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-shopping-cart"></i> WooCommerce Snippets</h1>
        <p class="text-muted">Codici pronti per estendere le funzionalità di WooCommerce</p>
    </div>

    <!-- Filter buttons -->
    <div class="snippet-filters mb-4">
        <button class="btn btn-sm btn-outline-primary active" data-filter="all">
            <i class="fas fa-th"></i> Tutti
        </button>
        <?php foreach ($categories as $key => $label): ?>
            <?php if ($key !== 'all'): ?>
                <button class="btn btn-sm btn-outline-primary" data-filter="<?php echo $key; ?>">
                    <?php echo $label; ?>
                </button>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Snippets grid -->
    <div class="snippets-grid">
        <?php foreach ($snippets as $snippet): ?>
            <div class="snippet-card" data-category="<?php echo $snippet['category']; ?>">
                <div class="snippet-header">
                    <div class="snippet-icon woo-icon">
                        <i class="<?php echo $snippet['icon']; ?>"></i>
                    </div>
                    <div class="snippet-info">
                        <h5><?php echo $snippet['title']; ?></h5>
                        <p class="text-muted mb-0"><?php echo $snippet['description']; ?></p>
                    </div>
                </div>
                <div class="snippet-actions">
                    <button class="btn btn-sm btn-primary" onclick="viewSnippet('<?php echo $snippet['id']; ?>')">
                        <i class="fas fa-eye"></i> Visualizza
                    </button>
                    <button class="btn btn-sm btn-success" onclick="copySnippet('<?php echo $snippet['id']; ?>')">
                        <i class="fas fa-copy"></i> Copia
                    </button>
                </div>
                <div class="snippet-code" id="snippet-<?php echo $snippet['id']; ?>" style="display: none;">
                    <pre><code class="language-php"><?php echo htmlspecialchars($snippet['code']); ?></code></pre>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.woo-icon {
    background: #96588a !important;
    color: white !important;
}
</style>

<!-- Reuse the same modal and scripts from wordpress.php -->
<div class="modal fade" id="snippetModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="snippetModalTitle">Snippet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="code-output">
                    <div class="copy-btn-container">
                        <button class="btn btn-copy" onclick="copyModalCode()">
                            <i class="fas fa-copy"></i> Copia
                        </button>
                    </div>
                    <pre><code class="language-php" id="modalSnippetCode"></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Reuse the same scripts from wordpress.php
document.querySelectorAll('.snippet-filters button').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.snippet-filters button').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        document.querySelectorAll('.snippet-card').forEach(card => {
            if (filter === 'all' || card.dataset.category === filter) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    });
});

function viewSnippet(id) {
    const snippet = document.getElementById('snippet-' + id);
    const code = snippet.querySelector('code').textContent;
    const card = snippet.closest('.snippet-card');
    const title = card.querySelector('h5').textContent;
    
    document.getElementById('snippetModalTitle').textContent = title;
    document.getElementById('modalSnippetCode').textContent = code;
    
    Prism.highlightElement(document.getElementById('modalSnippetCode'));
    
    const modal = new bootstrap.Modal(document.getElementById('snippetModal'));
    modal.show();
}

function copySnippet(id) {
    const snippet = document.getElementById('snippet-' + id);
    const code = snippet.querySelector('code').textContent;
    
    navigator.clipboard.writeText(code).then(() => {
        showNotification('Snippet copiato negli appunti!', 'success');
    });
}

function copyModalCode() {
    const code = document.getElementById('modalSnippetCode').textContent;
    
    navigator.clipboard.writeText(code).then(() => {
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-check"></i> Copiato!';
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
        }, 2000);
    });
}
</script>