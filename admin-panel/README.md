# Admin Panel - Developer Tools

Un pannello di amministrazione completo per sviluppatori web con focus su WordPress e altri strumenti utili.

## 📁 Struttura delle cartelle

```
admin-panel/
│
├── index.php                    # File principale
│
├── assets/                      # Risorse statiche
│   ├── css/
│   │   └── style.css           # Stili CSS principali
│   └── js/
│       └── main.js             # JavaScript principale
│
├── components/                  # Componenti riutilizzabili
│   └── sidebar.php             # Menu di navigazione laterale
│
├── pages/                       # Pagine principali
│   ├── dashboard.php           # Dashboard homepage
│   ├── wordpress.php           # Router per sezioni WordPress
│   ├── seo.php                 # Router per sezioni SEO (da implementare)
│   ├── tools.php               # Router per utility tools (da implementare)
│   │
│   └── wordpress/              # Sottosezioni WordPress
│       ├── post-type.php       # Generatore Post Type
│       ├── metabox.php         # Generatore Metabox
│       └── custom-code.php     # Generatore Custom Code
│
└── generators/                  # Script PHP per generazione codice
    ├── post-type-generator.php  # Genera codice Post Type
    ├── metabox-generator.php    # Genera codice Metabox
    └── custom-code-generator.php # Genera codice CSS/JS

```

## 🚀 Installazione

1. **Copia la cartella** `admin-panel` nel tuo server web locale o remoto

2. **Configura il percorso base** in `index.php`:
   ```php
   define('BASE_URL', '/admin-panel/'); // Modifica secondo il tuo percorso
   ```

3. **Accedi al pannello** navigando su:
   ```
   http://tuo-dominio.com/admin-panel/
   ```

## 💻 Requisiti

- PHP 7.4 o superiore
- Web server (Apache/Nginx)
- Browser moderno con JavaScript abilitato

## 🛠️ Funzionalità attuali

### WordPress Tools

#### 1. **Post Type Generator**
- Crea custom post type con tutte le opzioni
- Supporto per tassonomie personalizzate
- Generazione automatica di categorie e tag
- Compatibilità Gutenberg

#### 2. **Metabox Generator** 
- Creazione di metabox con campi multipli
- Supporto per 12 tipi di campo diversi
- Upload media integrato
- Color picker e WYSIWYG editor

#### 3. **Custom Code Generator**
- Inserimento codice in 4 modalità diverse
- Condizioni di caricamento flessibili
- Opzioni di minificazione e cache busting
- Supporto per CSS e JavaScript

## 🔧 Come aggiungere nuove sezioni

### 1. Aggiungi voce al menu (`components/sidebar.php`):
```php
[
    'id' => 'nuova-sezione',
    'title' => 'Nuova Sezione',
    'icon' => 'fas fa-icon',
    'submenu' => [
        [
            'id' => 'tool-1',
            'title' => 'Tool 1',
            'icon' => 'fas fa-tool',
            'url' => '?page=nuova-sezione&section=tool-1'
        ]
    ]
]
```

### 2. Crea la pagina router (`pages/nuova-sezione.php`):
```php
<?php
$currentSection = isset($_GET['section']) ? $_GET['section'] : 'default';

switch($currentSection) {
    case 'tool-1':
        include __DIR__ . '/nuova-sezione/tool-1.php';
        break;
    default:
        include __DIR__ . '/nuova-sezione/default.php';
}
?>
```

### 3. Crea i file delle sottosezioni nella cartella appropriata

### 4. Se necessario, crea il generatore in `generators/`

## 📝 Note per lo sviluppo

- Il sistema usa AJAX per generare il codice senza ricaricare la pagina
- Tutti i form sono validati lato client e server
- Il codice generato è evidenziato con Prism.js
- La struttura è modulare per facilitare l'aggiunta di nuovi tool

## 🎨 Personalizzazione

### Colori principali (in `assets/css/style.css`):
```css
:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
}
```

### Larghezza sidebar:
```css
:root {
    --sidebar-width: 280px;
}
```

## 📱 Responsive

Il pannello è completamente responsive:
- Menu laterale nascosto su mobile con toggle
- Layout adattivo per tutti i dispositivi
- Form ottimizzati per touch

## 🔒 Sicurezza

- Tutti gli input sono sanitizzati
- Protezione CSRF con nonce (nei metabox)
- Escape dell'output per prevenire XSS
- Validazione lato server

## 🚧 Prossime implementazioni

- [ ] SEO Tools (Meta tags, Schema generator)
- [ ] Utility Tools (.htaccess, Regex tester)
- [ ] Database delle generazioni salvate
- [ ] Sistema di template predefiniti
- [ ] Export/Import configurazioni
- [ ] Generatore di shortcode
- [ ] Generatore di widget
- [ ] Integrazioni API esterne

## 📄 Licenza

Questo progetto è open source e disponibile sotto licenza MIT.