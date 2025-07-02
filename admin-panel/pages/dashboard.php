<?php
// Dashboard stats (puoi collegare a un database per dati reali)
$stats = [
    [
        'title' => 'Post Types Generati',
        'count' => '15',
        'icon' => 'fas fa-file-alt',
        'color' => 'primary'
    ],
    [
        'title' => 'Metabox Creati',
        'count' => '32',
        'icon' => 'fas fa-box',
        'color' => 'success'
    ],
    [
        'title' => 'Custom Code',
        'count' => '48',
        'icon' => 'fas fa-code',
        'color' => 'info'
    ],
    [
        'title' => 'Tools Utilizzati',
        'count' => '95',
        'icon' => 'fas fa-tools',
        'color' => 'warning'
    ]
];

// Recent activities
$recentActivities = [
    [
        'action' => 'Post Type generato',
        'name' => 'Prodotti',
        'time' => '2 ore fa',
        'icon' => 'fas fa-file-alt'
    ],
    [
        'action' => 'Metabox creato',
        'name' => 'Informazioni Extra',
        'time' => '5 ore fa',
        'icon' => 'fas fa-box'
    ],
    [
        'action' => 'Custom CSS aggiunto',
        'name' => 'Header Styles',
        'time' => '1 giorno fa',
        'icon' => 'fas fa-code'
    ],
    [
        'action' => 'Schema generato',
        'name' => 'Product Schema',
        'time' => '2 giorni fa',
        'icon' => 'fas fa-project-diagram'
    ]
];
?>

<div class="dashboard-container">
    <div class="content-header">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <p class="text-muted">Benvenuto nel tuo pannello di amministrazione per sviluppatori</p>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <?php foreach ($stats as $stat): ?>
            <div class="col-md-3 mb-3">
                <div class="stat-card bg-<?php echo $stat['color']; ?> bg-gradient">
                    <div class="stat-icon">
                        <i class="<?php echo $stat['icon']; ?>"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $stat['count']; ?></h3>
                        <p><?php echo $stat['title']; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-rocket"></i> Azioni Rapide</h5>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="?page=wordpress&section=post-type" class="quick-action-btn">
                            <i class="fas fa-plus-circle"></i>
                            <span>Nuovo Post Type</span>
                        </a>
                        <a href="?page=wordpress&section=metabox" class="quick-action-btn">
                            <i class="fas fa-plus-square"></i>
                            <span>Nuovo Metabox</span>
                        </a>
                        <a href="?page=wordpress&section=custom-code" class="quick-action-btn">
                            <i class="fas fa-code"></i>
                            <span>Custom Code</span>
                        </a>
                        <a href="?page=seo&section=schema" class="quick-action-btn">
                            <i class="fas fa-sitemap"></i>
                            <span>Schema Generator</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Attività Recenti</h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="<?php echo $activity['icon']; ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="mb-0">
                                        <strong><?php echo $activity['action']; ?>:</strong> 
                                        <?php echo $activity['name']; ?>
                                    </p>
                                    <small class="text-muted"><?php echo $activity['time']; ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tools Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-toolbox"></i> Panoramica Tools</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="tool-preview">
                                <h6><i class="fab fa-wordpress text-primary"></i> WordPress Tools</h6>
                                <ul class="list-unstyled ps-3">
                                    <li><i class="fas fa-check text-success"></i> Post Type Generator</li>
                                    <li><i class="fas fa-check text-success"></i> Metabox Creator</li>
                                    <li><i class="fas fa-check text-success"></i> Custom Code Manager</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="tool-preview">
                                <h6><i class="fas fa-search text-info"></i> SEO Tools</h6>
                                <ul class="list-unstyled ps-3">
                                    <li><i class="fas fa-check text-success"></i> Meta Tags Generator</li>
                                    <li><i class="fas fa-check text-success"></i> Schema Markup Creator</li>
                                    <li class="text-muted"><i class="fas fa-clock"></i> Sitemap Generator (Coming Soon)</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="tool-preview">
                                <h6><i class="fas fa-tools text-warning"></i> Utility Tools</h6>
                                <ul class="list-unstyled ps-3">
                                    <li><i class="fas fa-check text-success"></i> .htaccess Generator</li>
                                    <li><i class="fas fa-check text-success"></i> Regex Tester</li>
                                    <li class="text-muted"><i class="fas fa-clock"></i> CSS Minifier (Coming Soon)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>