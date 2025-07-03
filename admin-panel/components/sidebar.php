<?php
// Menu configuration
$menuItems = [
    [
        'id' => 'dashboard',
        'title' => 'Dashboard',
        'icon' => 'fas fa-home',
        'url' => '?page=dashboard'
    ],
    [
        'id' => 'preventivi',
        'title' => 'Preventivi',
        'icon' => 'fas fa-file-invoice-dollar',
        'url' => '?page=preventivi' // Link diretto, senza sottomenu
    ],
    [
        'id' => 'wordpress',
        'title' => 'WordPress',
        'icon' => 'fab fa-wordpress',
        'submenu' => [
            [
                'id' => 'post-type',
                'title' => 'Post Type',
                'icon' => 'fas fa-file-alt',
                'url' => '?page=wordpress&section=post-type'
            ],
            [
                'id' => 'metabox',
                'title' => 'Metabox',
                'icon' => 'fas fa-box',
                'url' => '?page=wordpress&section=metabox'
            ],
            [
                'id' => 'custom-code',
                'title' => 'Custom Code',
                'icon' => 'fas fa-code',
                'url' => '?page=wordpress&section=custom-code'
            ],
             [
                'id' => 'manutenzione',
                'title' => 'Modalità Manutenzione',
                'icon' => 'fas fa-person-digging',
                'url' => '?page=wordpress&section=manutenzione'
            ]
        ]
    ],
    [
        'id' => 'seo',
        'title' => 'SEO Tools',
        'icon' => 'fas fa-search',
        'submenu' => [
            [
                'id' => 'meta-tags',
                'title' => 'Meta Tags',
                'icon' => 'fas fa-tags',
                'url' => '?page=seo&section=meta-tags'
            ],
            [
                'id' => 'schema',
                'title' => 'Schema Generator',
                'icon' => 'fas fa-project-diagram',
                'url' => '?page=seo&section=schema'
            ]
        ]
    ],
    [
        'id' => 'libraries',
        'title' => 'Librerie',
        'icon' => 'fas fa-book',
        'submenu' => [
            [
                'id' => 'import',
                'title' => 'Importa Librerie',
                'icon' => 'fas fa-download',
                'url' => '?page=libraries&section=import'
            ],
            [
                'id' => 'cdn-manager',
                'title' => 'CDN Manager',
                'icon' => 'fas fa-cloud',
                'url' => '?page=libraries&section=cdn-manager'
            ]
        ]
    ],
    [
        'id' => 'snippets',
        'title' => 'Snippets',
        'icon' => 'fas fa-puzzle-piece',
        'submenu' => [
            [
                'id' => 'wordpress',
                'title' => 'WordPress Ready',
                'icon' => 'fab fa-wordpress',
                'url' => '?page=snippets&section=wordpress'
            ],
            [
                'id' => 'woocommerce',
                'title' => 'WooCommerce',
                'icon' => 'fas fa-shopping-cart',
                'url' => '?page=snippets&section=woocommerce'
            ],
            [
                'id' => 'security',
                'title' => 'Security',
                'icon' => 'fas fa-shield-alt',
                'url' => '?page=snippets&section=security'
            ],
            [
                'id' => 'performance',
                'title' => 'Performance',
                'icon' => 'fas fa-tachometer-alt',
                'url' => '?page=snippets&section=performance'
            ]
        ]
    ],
    [
        'id' => 'tools',
        'title' => 'Utility',
        'icon' => 'fas fa-tools',
        'submenu' => [
            [
                'id' => 'htaccess',
                'title' => '.htaccess',
                'icon' => 'fas fa-file-code',
                'url' => '?page=tools&section=htaccess'
            ],
            [
                'id' => 'regex',
                'title' => 'Regex Tester',
                'icon' => 'fas fa-terminal',
                'url' => '?page=tools&section=regex'
            ],
            [
                'id' => 'phpmailer',
                'title' => 'PHPMailer Sender',
                'icon' => 'fas fa-paper-plane',
                'url' => '?page=tools&section=phpmailer'
            ]
        ]
    ]
];

// Function to check if menu item is active
function isMenuActive($menuId, $submenuId = null) {
    global $page, $section;
    
    if ($submenuId) {
        return $page == $menuId && $section == $submenuId;
    }
    
    return $page == $menuId;
}
?>

<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-tools"></i> Dev Tools</h3>
    </div>
    <div class="sidebar-menu">
        <?php foreach ($menuItems as $item): ?>
            <?php if (isset($item['submenu'])): ?>
                <!-- Menu with submenu -->
                <button class="menu-item <?php echo isMenuActive($item['id']) ? 'active' : ''; ?>" 
                        onclick="toggleSubmenu('<?php echo $item['id']; ?>-submenu')">
                    <i class="<?php echo $item['icon']; ?>"></i>
                    <?php echo $item['title']; ?>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </button>
                <div id="<?php echo $item['id']; ?>-submenu" 
                     class="submenu <?php echo isMenuActive($item['id']) ? 'show' : ''; ?>">
                    <?php foreach ($item['submenu'] as $subitem): ?>
                        <a href="<?php echo $subitem['url']; ?>" 
                           class="menu-item <?php echo isMenuActive($item['id'], $subitem['id']) ? 'active' : ''; ?>">
                            <i class="<?php echo $subitem['icon']; ?>"></i>
                            <?php echo $subitem['title']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Simple menu item -->
                <a href="<?php echo $item['url']; ?>" 
                   class="menu-item <?php echo isMenuActive($item['id']) ? 'active' : ''; ?>">
                    <i class="<?php echo $item['icon']; ?>"></i>
                    <?php echo $item['title']; ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</nav>