// Toggle Sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Toggle Submenu
function toggleSubmenu(submenuId) {
    const submenu = document.getElementById(submenuId);
    const allSubmenus = document.querySelectorAll('.submenu');
    
    // Close all other submenus
    allSubmenus.forEach(menu => {
        if (menu.id !== submenuId) {
            menu.classList.remove('show');
        }
    });
    
    // Toggle current submenu
    submenu.classList.toggle('show');
}

// ===================================================================
// NUOVA FUNZIONE DI COPIA (SOLUZIONE DEFINITIVA)
// ===================================================================
function copyCode(codeId, buttonElement) {
    const codeElement = document.getElementById(codeId);
    if (!codeElement) {
        console.error('Elemento con ID "' + codeId + '" non trovato.');
        return;
    }
    const code = codeElement.innerText;
    
    // Crea un textarea temporaneo per eseguire la copia
    const textArea = document.createElement('textarea');
    textArea.value = code;
    
    // Rendi l'elemento invisibile e posizionalo fuori schermo
    textArea.style.position = 'fixed';
    textArea.style.top = '-9999px';
    textArea.style.left = '-9999px';
    
    document.body.appendChild(textArea);
    
    // Seleziona e copia il contenuto
    textArea.select();
    let success = false;
    try {
        success = document.execCommand('copy');
    } catch (err) {
        console.error('Copia fallita: ', err);
    }
    
    // Rimuovi l'elemento temporaneo
    document.body.removeChild(textArea);

    // Usa l'elemento bottone passato come argomento per il feedback
    const btn = buttonElement || event.target.closest('button');

    // Fornisci un feedback visivo
    if (success && btn) {
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copiato!';
        btn.classList.add('btn-success'); // Usa una classe specifica per lo stato di successo
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.disabled = false;
        }, 2000);
    } else if (!success) {
        alert('Copia fallita. Prova a copiare il testo manualmente.');
    }
}
// ===================================================================
// FINE NUOVA FUNZIONE DI COPIA
// ===================================================================


// Add Metabox Field
function addMetaboxField() {
    const fieldsContainer = document.getElementById('metaboxFields');
    const fieldRow = document.createElement('div');
    fieldRow.className = 'field-row mb-3 fade-in';
    
    fieldRow.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Nome campo" name="fieldName[]" required>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="ID campo" name="fieldId[]" required>
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
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeField(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    fieldsContainer.appendChild(fieldRow);
}

// Remove Field
function removeField(button) {
    const fieldRow = button.closest('.field-row');
    fieldRow.style.opacity = '0';
    fieldRow.style.transform = 'translateX(20px)';
    
    setTimeout(() => {
        fieldRow.remove();
    }, 300);
}

// Auto-generate slug from name
function generateSlug(text) {
    return text
        .toLowerCase()
        .replace(/[àáäâ]/g, 'a')
        .replace(/[èéëê]/g, 'e')
        .replace(/[ìíïî]/g, 'i')
        .replace(/[òóöô]/g, 'o')
        .replace(/[ùúüû]/g, 'u')
        .replace(/[ñ]/g, 'n')
        .replace(/[ç]/g, 'c')
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug for post type
    const postTypeName = document.getElementById('postTypeName');
    const postTypeSlug = document.getElementById('postTypeSlug');
    
    if (postTypeName && postTypeSlug) {
        postTypeName.addEventListener('input', function() {
            if (!postTypeSlug.dataset.manual) {
                postTypeSlug.value = generateSlug(this.value);
            }
        });
        
        postTypeSlug.addEventListener('input', function() {
            this.dataset.manual = 'true';
        });
    }
    
    // Auto-generate ID for metabox
    const metaboxTitle = document.getElementById('metaboxTitle');
    const metaboxId = document.getElementById('metaboxId');
    
    if (metaboxTitle && metaboxId) {
        metaboxTitle.addEventListener('input', function() {
            if (!metaboxId.dataset.manual) {
                metaboxId.value = generateSlug(this.value).replace(/-/g, '_');
            }
        });
        
        metaboxId.addEventListener('input', function() {
            this.dataset.manual = 'true';
        });
    }
    
    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const mobileToggle = document.querySelector('.mobile-toggle');
        
        if (window.innerWidth < 768 && sidebar.classList.contains('show')) {
            if (!sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
    
    // Handle form submissions with loading state
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';
                submitBtn.disabled = true;
                
                // Re-enable after a few seconds (in case of error)
                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="fas fa-magic"></i> Genera Codice';
                    submitBtn.disabled = false;
                }, 3000);
            }
        });
    });
    
    // Add tooltips
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show notification`;
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Position notification
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        const alert = new bootstrap.Alert(notification);
        alert.close();
    }, 5000);
}

// Export functions for use in other scripts
window.appFunctions = {
    toggleSidebar,
    toggleSubmenu,
    copyCode,
    addMetaboxField,
    removeField,
    generateSlug,
    showNotification
};