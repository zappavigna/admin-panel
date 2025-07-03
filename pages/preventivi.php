<?php
// Preventivi page
?>
<div class="content-header">
    <h1><i class="fas fa-file-invoice-dollar"></i> Generatore Preventivi</h1>
    <p class="text-muted">Crea preventivi professionali per i tuoi servizi di sviluppo web</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <form id="preventivoForm" method="post">
            <!-- Informazioni Cliente -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informazioni Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-section">
                                <label for="clientName">Nome Cliente/Azienda *</label>
                                <input type="text" class="form-control" id="clientName" name="clientName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-section">
                                <label for="clientEmail">Email</label>
                                <input type="email" class="form-control" id="clientEmail" name="clientEmail">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-section">
                                <label for="clientPhone">Telefono</label>
                                <input type="tel" class="form-control" id="clientPhone" name="clientPhone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-section">
                                <label for="clientPiva">P.IVA / CF</label>
                                <input type="text" class="form-control" id="clientPiva" name="clientPiva">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-section">
                                <label for="clientAddress">Indirizzo</label>
                                <textarea class="form-control" id="clientAddress" name="clientAddress" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Servizi Principali -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-code"></i> Servizi di Sviluppo</h5>
                </div>
                <div class="card-body">
                    <div id="mainServices">
                        <!-- I servizi verranno popolati dinamicamente -->
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCustomService()">
                            <i class="fas fa-plus"></i> Aggiungi Servizio Personalizzato
                        </button>
                    </div>
                </div>
            </div>

            <!-- Servizi Terze Parti -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-server"></i> Servizi Terze Parti</h5>
                </div>
                <div class="card-body">
                    <div id="thirdPartyServices">
                        <!-- I servizi verranno popolati dinamicamente -->
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCustomThirdPartyService()">
                            <i class="fas fa-plus"></i> Aggiungi Servizio Terze Parti
                        </button>
                    </div>
                </div>
            </div>

            <!-- Note e Condizioni -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Note e Condizioni</h5>
                </div>
                <div class="card-body">
                    <div class="form-section">
                        <label for="projectNotes">Note sul Progetto</label>
                        <textarea class="form-control" id="projectNotes" name="projectNotes" rows="3"></textarea>
                    </div>
                    <div class="form-section">
                        <label for="paymentTerms">Termini di Pagamento</label>
                        <select class="form-select" id="paymentTerms" name="paymentTerms">
                            <option value="50-50">50% all'accettazione, 50% alla consegna</option>
                            <option value="30-40-30">30% all'accettazione, 40% a metà lavoro, 30% alla consegna</option>
                            <option value="100-delivery">100% alla consegna</option>
                            <option value="custom">Personalizzato</option>
                        </select>
                    </div>
                    <div class="form-section" id="customPaymentTerms" style="display: none;">
                        <label for="customPaymentText">Termini Personalizzati</label>
                        <textarea class="form-control" id="customPaymentText" name="customPaymentText" rows="2"></textarea>
                    </div>
                    <div class="form-section">
                        <label for="validityDays">Validità Preventivo (giorni)</label>
                        <input type="number" class="form-control" id="validityDays" name="validityDays" value="30" min="1">
                    </div>
                </div>
            </div>

            <!-- Azioni -->
            <div class="card mt-4">
                <div class="card-body text-center">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-file-pdf"></i> Genera Preventivo PDF
                    </button>
                    <button type="button" class="btn btn-primary btn-lg ms-2" onclick="generatePDFClient(event)">
                        <i class="fas fa-download"></i> Download Diretto PDF
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg ms-2" onclick="previewPreventivo()">
                        <i class="fas fa-eye"></i> Anteprima
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Sidebar Riepilogo -->
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-calculator"></i> Riepilogo</h5>
            </div>
            <div class="card-body">
                <div id="preventivoSummary">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotale Servizi:</span>
                        <strong>€ <span id="subtotalServices">0</span></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Servizi Terze Parti:</span>
                        <strong>€ <span id="subtotalThirdParty">0</span></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-danger" id="discountRow" style="display: none;">
                        <span>Sconto Applicato:</span>
                        <strong>- € <span id="totalDiscount">0</span></strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Totale (IVA esclusa):</h5>
                        <h5 class="text-primary">€ <span id="totalAmount">0</span></h5>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <small>IVA (22%):</small>
                        <small>€ <span id="vatAmount">0</span></small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>Totale con IVA:</strong>
                        <strong>€ <span id="totalWithVat">0</span></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Includi jsPDF per generazione PDF lato client -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- Modal Anteprima -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Anteprima Preventivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="generatePDFFromPreview()">
                    <i class="fas fa-download"></i> Scarica PDF
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<script>
// Configurazione servizi predefiniti
const defaultServices = [
    {
        id: 'website-basic',
        name: 'Sito Web Basic',
        description: 'Sito web professionale fino a 5 pagine, responsive, ottimizzato SEO base',
        price: 1500,
        discountedPrice: 1500,
        selected: false
    },
    {
        id: 'website-advanced',
        name: 'Sito Web Avanzato',
        description: 'Sito web completo fino a 15 pagine, design personalizzato, SEO avanzato, blog integrato',
        price: 3500,
        discountedPrice: 3500,
        selected: false
    },
    {
        id: 'ecommerce-basic',
        name: 'E-commerce Base',
        description: 'Negozio online WooCommerce, fino a 50 prodotti, pagamenti online, gestione ordini',
        price: 4000,
        discountedPrice: 4000,
        selected: false
    },
    {
        id: 'ecommerce-pro',
        name: 'E-commerce Professional',
        description: 'E-commerce avanzato, prodotti illimitati, multi-lingua, integrazioni avanzate',
        price: 7000,
        discountedPrice: 7000,
        selected: false
    },
    {
        id: 'landing-page',
        name: 'Landing Page',
        description: 'Pagina di atterraggio ottimizzata per conversioni, A/B testing ready',
        price: 800,
        discountedPrice: 800,
        selected: false
    },
    {
        id: 'restyling',
        name: 'Restyling Sito Esistente',
        description: 'Redesign completo del sito esistente, mantenendo i contenuti',
        price: 2000,
        discountedPrice: 2000,
        selected: false
    },
    {
        id: 'maintenance',
        name: 'Manutenzione Annuale',
        description: 'Aggiornamenti, backup, monitoraggio sicurezza, assistenza prioritaria',
        price: 800,
        discountedPrice: 800,
        selected: false
    },
    {
        id: 'seo-optimization',
        name: 'Ottimizzazione SEO',
        description: 'Analisi e ottimizzazione SEO completa, report mensili',
        price: 1200,
        discountedPrice: 1200,
        selected: false
    }
];

const defaultThirdPartyServices = [
    {
        id: 'hosting-basic',
        name: 'Hosting Basic',
        description: 'Hosting condiviso, 10GB spazio, SSL incluso',
        price: 100,
        annual: true,
        selected: false
    },
    {
        id: 'hosting-pro',
        name: 'Hosting Professional',
        description: 'Hosting VPS, 50GB SSD, backup giornalieri, SSL',
        price: 300,
        annual: true,
        selected: false
    },
    {
        id: 'domain',
        name: 'Dominio .it/.com',
        description: 'Registrazione o trasferimento dominio',
        price: 25,
        annual: true,
        selected: false
    },
    {
        id: 'email-pro',
        name: 'Email Professionali',
        description: '5 caselle email professionali da 5GB',
        price: 60,
        annual: true,
        selected: false
    },
    {
        id: 'ssl-certificate',
        name: 'Certificato SSL Premium',
        description: 'Certificato SSL con validazione estesa',
        price: 150,
        annual: true,
        selected: false
    }
];

let services = [...defaultServices];
let thirdPartyServices = [...defaultThirdPartyServices];
let customServiceCounter = 0;

// Inizializzazione
document.addEventListener('DOMContentLoaded', function() {
    renderServices();
    renderThirdPartyServices();
    updateSummary();
    
    // Event listeners
    document.getElementById('paymentTerms').addEventListener('change', function() {
        document.getElementById('customPaymentTerms').style.display = 
            this.value === 'custom' ? 'block' : 'none';
    });
    
    document.getElementById('preventivoForm').addEventListener('submit', generatePDF);
});

// Rendering servizi principali
function renderServices() {
    const container = document.getElementById('mainServices');
    container.innerHTML = services.map(service => `
        <div class="service-item ${service.selected ? 'selected' : ''}" data-service-id="${service.id}">
            <div class="service-checkbox-wrapper">
                <input class="form-check-input service-checkbox" type="checkbox" 
                       id="service-${service.id}" 
                       ${service.selected ? 'checked' : ''}
                       onchange="toggleService('${service.id}')">
            </div>
            <div class="service-content">
                <div class="service-info">
                    ${service.custom ? `
                        <input type="text" class="form-control form-control-sm mb-1 custom-title" 
                               value="${service.name}" placeholder="Nome servizio"
                               onchange="updateServiceInfo('${service.id}', 'name', this.value)">
                        <textarea class="form-control form-control-sm custom-description" rows="2"
                                  placeholder="Descrizione servizio"
                                  onchange="updateServiceInfo('${service.id}', 'description', this.value)">${service.description}</textarea>
                    ` : `
                        <label for="service-${service.id}">
                            <h6 class="service-title mb-1">${service.name}</h6>
                            <p class="service-description text-muted mb-0">${service.description}</p>
                        </label>
                    `}
                </div>
                <div class="service-price">
                    ${service.custom ? `
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove" 
                                onclick="removeCustomService('${service.id}')">
                            <i class="fas fa-times"></i>
                        </button>
                    ` : ''}
                    <div class="price-display">
                        ${service.discountedPrice < service.price ? `
                            <span class="original-price">€ ${service.price}</span>
                            <span class="discounted-price">€ ${service.discountedPrice}</span>
                        ` : `
                            <span class="regular-price">€ ${service.price}</span>
                        `}
                    </div>
                    <div class="price-edit" style="display: none;">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">€</span>
                            <input type="number" class="form-control price-input" value="${service.price}" 
                                   placeholder="Prezzo pieno"
                                   onchange="updateServicePrice('${service.id}', 'price', this.value)">
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">€</span>
                            <input type="number" class="form-control price-input" value="${service.discountedPrice}" 
                                   placeholder="Prezzo scontato"
                                   onchange="updateServicePrice('${service.id}', 'discountedPrice', this.value)">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-light btn-edit-price" 
                            onclick="togglePriceEdit(this)">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Rendering servizi terze parti
function renderThirdPartyServices() {
    const container = document.getElementById('thirdPartyServices');
    container.innerHTML = thirdPartyServices.map(service => `
        <div class="service-item third-party ${service.selected ? 'selected' : ''}" 
             data-service-id="${service.id}">
            <div class="service-checkbox-wrapper">
                <input class="form-check-input third-party-checkbox" type="checkbox" 
                       id="third-${service.id}" 
                       ${service.selected ? 'checked' : ''}
                       onchange="toggleThirdPartyService('${service.id}')">
            </div>
            <div class="service-content">
                <div class="service-info">
                    ${service.custom ? `
                        <input type="text" class="form-control form-control-sm mb-1 custom-title" 
                               value="${service.name}" placeholder="Nome servizio"
                               onchange="updateThirdPartyInfo('${service.id}', 'name', this.value)">
                        <textarea class="form-control form-control-sm custom-description" rows="2"
                                  placeholder="Descrizione servizio"
                                  onchange="updateThirdPartyInfo('${service.id}', 'description', this.value)">${service.description}</textarea>
                    ` : `
                        <label for="third-${service.id}">
                            <h6 class="service-title mb-1">
                                ${service.name}
                                ${service.annual ? '<span class="badge bg-info ms-2">Annuale</span>' : ''}
                            </h6>
                            <p class="service-description text-muted mb-0">${service.description}</p>
                        </label>
                    `}
                </div>
                <div class="service-price">
                    ${service.custom ? `
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove" 
                                onclick="removeCustomThirdPartyService('${service.id}')">
                            <i class="fas fa-times"></i>
                        </button>
                    ` : ''}
                    <div class="price-display">
                        <span class="regular-price">€ ${service.price}${service.annual ? '/anno' : ''}</span>
                    </div>
                    <div class="price-edit" style="display: none;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">€</span>
                            <input type="number" class="form-control price-input" value="${service.price}" 
                                   onchange="updateThirdPartyPrice('${service.id}', this.value)">
                            ${service.annual ? '<span class="input-group-text">/anno</span>' : ''}
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-light btn-edit-price" 
                            onclick="togglePriceEdit(this)">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Toggle servizi
function toggleService(serviceId) {
    const service = services.find(s => s.id === serviceId);
    if (service) {
        service.selected = !service.selected;
        renderServices();
        updateSummary();
    }
}

function toggleThirdPartyService(serviceId) {
    const service = thirdPartyServices.find(s => s.id === serviceId);
    if (service) {
        service.selected = !service.selected;
        renderThirdPartyServices();
        updateSummary();
    }
}

// Aggiorna prezzi
function updateServicePrice(serviceId, priceType, value) {
    const service = services.find(s => s.id === serviceId);
    if (service) {
        service[priceType] = parseFloat(value) || 0;
        // Se si aggiorna il prezzo pieno e il prezzo scontato è uguale al vecchio prezzo pieno, aggiorna anche quello
        if (priceType === 'price' && service.discountedPrice === service.price - (parseFloat(value) - service.price)) {
            service.discountedPrice = parseFloat(value) || 0;
        }
        updateSummary();
    }
}

function updateThirdPartyPrice(serviceId, value) {
    const service = thirdPartyServices.find(s => s.id === serviceId);
    if (service) {
        service.price = parseFloat(value) || 0;
        updateSummary();
    }
}

// Aggiungi servizi custom
function addCustomService() {
    const customId = 'custom-service-' + customServiceCounter++;
    const newService = {
        id: customId,
        name: '',
        description: '',
        price: 0,
        discountedPrice: 0,
        selected: true,
        custom: true
    };
    
    services.push(newService);
    renderServices();
    updateSummary();
    
    // Focus sul nuovo servizio
    setTimeout(() => {
        const element = document.querySelector(`[data-service-id="${customId}"]`);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const titleInput = element.querySelector('.custom-title');
            if (titleInput) titleInput.focus();
        }
    }, 100);
}

function addCustomThirdPartyService() {
    const customId = 'custom-third-' + customServiceCounter++;
    const newService = {
        id: customId,
        name: '',
        description: '',
        price: 0,
        annual: false,
        selected: true,
        custom: true
    };
    
    thirdPartyServices.push(newService);
    renderThirdPartyServices();
    updateSummary();
    
    // Focus sul nuovo servizio
    setTimeout(() => {
        const element = document.querySelector(`[data-service-id="${customId}"]`);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const titleInput = element.querySelector('.custom-title');
            if (titleInput) titleInput.focus();
        }
    }, 100);
}

// Aggiorna info servizi
function updateServiceInfo(serviceId, field, value) {
    const service = services.find(s => s.id === serviceId);
    if (service) {
        service[field] = value;
    }
}

function updateThirdPartyInfo(serviceId, field, value) {
    const service = thirdPartyServices.find(s => s.id === serviceId);
    if (service) {
        service[field] = value;
    }
}

// Toggle modifica prezzi
function togglePriceEdit(button) {
    const serviceItem = button.closest('.service-item');
    const priceDisplay = serviceItem.querySelector('.price-display');
    const priceEdit = serviceItem.querySelector('.price-edit');
    
    if (priceDisplay.style.display === 'none') {
        priceDisplay.style.display = 'block';
        priceEdit.style.display = 'none';
        button.innerHTML = '<i class="fas fa-edit"></i>';
    } else {
        priceDisplay.style.display = 'none';
        priceEdit.style.display = 'block';
        button.innerHTML = '<i class="fas fa-check"></i>';
    }
}

// Rimuovi servizi custom
function removeCustomService(serviceId) {
    services = services.filter(s => s.id !== serviceId);
    renderServices();
    updateSummary();
}

function removeCustomThirdPartyService(serviceId) {
    thirdPartyServices = thirdPartyServices.filter(s => s.id !== serviceId);
    renderThirdPartyServices();
    updateSummary();
}

// Calcola e aggiorna riepilogo
function updateSummary() {
    const selectedServices = services.filter(s => s.selected);
    const selectedThirdParty = thirdPartyServices.filter(s => s.selected);
    
    let subtotalServices = 0;
    let discountServices = 0;
    
    selectedServices.forEach(service => {
        subtotalServices += service.price;
        discountServices += (service.price - service.discountedPrice);
    });
    
    const subtotalThirdParty = selectedThirdParty.reduce((sum, s) => sum + s.price, 0);
    const totalBeforeDiscount = subtotalServices + subtotalThirdParty;
    const totalAfterDiscount = (subtotalServices - discountServices) + subtotalThirdParty;
    
    const vat = totalAfterDiscount * 0.22;
    const totalWithVat = totalAfterDiscount + vat;
    
    // Aggiorna UI
    document.getElementById('subtotalServices').textContent = subtotalServices.toFixed(2);
    document.getElementById('subtotalThirdParty').textContent = subtotalThirdParty.toFixed(2);
    document.getElementById('totalDiscount').textContent = discountServices.toFixed(2);
    document.getElementById('totalAmount').textContent = totalAfterDiscount.toFixed(2);
    document.getElementById('vatAmount').textContent = vat.toFixed(2);
    document.getElementById('totalWithVat').textContent = totalWithVat.toFixed(2);
    
    // Mostra/nascondi riga sconto
    document.getElementById('discountRow').style.display = discountServices > 0 ? 'flex' : 'none';
}

// Anteprima preventivo
function previewPreventivo() {
    const formData = collectFormData();
    const previewHtml = generatePreviewHTML(formData);
    
    document.getElementById('previewContent').innerHTML = previewHtml;
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}

// Genera HTML anteprima
function generatePreviewHTML(data) {
    const today = new Date().toLocaleDateString('it-IT');
    const validUntil = new Date(Date.now() + data.validityDays * 24 * 60 * 60 * 1000).toLocaleDateString('it-IT');
    
    return `
        <div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <img src="assets/images/logo_zappavigna.svg" alt="Logo" style="height: 60px;">
                <h1 style="margin: 20px 0;">PREVENTIVO</h1>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
                <div>
                    <h3>Cliente:</h3>
                    <p>${data.clientName}<br>
                    ${data.clientEmail ? data.clientEmail + '<br>' : ''}
                    ${data.clientPhone ? data.clientPhone + '<br>' : ''}
                    ${data.clientPiva ? 'P.IVA/CF: ' + data.clientPiva + '<br>' : ''}
                    ${data.clientAddress ? data.clientAddress.replace(/\n/g, '<br>') : ''}</p>
                </div>
                <div style="text-align: right;">
                    <p><strong>Data:</strong> ${today}<br>
                    <strong>Validità:</strong> ${validUntil}<br>
                    <strong>Preventivo N°:</strong> ${new Date().getFullYear()}-${String(Math.floor(Math.random() * 1000)).padStart(4, '0')}</p>
                </div>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="padding: 10px; text-align: left; border-bottom: 2px solid #dee2e6;">Servizio</th>
                        <th style="padding: 10px; text-align: right; border-bottom: 2px solid #dee2e6;">Prezzo</th>
                        <th style="padding: 10px; text-align: right; border-bottom: 2px solid #dee2e6;">Sconto</th>
                        <th style="padding: 10px; text-align: right; border-bottom: 2px solid #dee2e6;">Totale</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.services.map(service => `
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #dee2e6;">
                                <strong>${service.name}</strong><br>
                                <small style="color: #6c757d;">${service.description}</small>
                            </td>
                            <td style="padding: 10px; text-align: right; border-bottom: 1px solid #dee2e6;">€ ${service.price.toFixed(2)}</td>
                            <td style="padding: 10px; text-align: right; border-bottom: 1px solid #dee2e6; color: #dc3545;">
                                ${service.discount > 0 ? '- € ' + service.discount.toFixed(2) : '-'}
                            </td>
                            <td style="padding: 10px; text-align: right; border-bottom: 1px solid #dee2e6;">€ ${service.finalPrice.toFixed(2)}</td>
                        </tr>
                    `).join('')}
                    ${data.thirdPartyServices.length > 0 ? `
                        <tr>
                            <td colspan="4" style="padding: 20px 10px 10px; font-weight: bold;">Servizi Terze Parti</td>
                        </tr>
                        ${data.thirdPartyServices.map(service => `
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #dee2e6;">
                                    <strong>${service.name}</strong><br>
                                    <small style="color: #6c757d;">${service.description}</small>
                                    ${service.annual ? '<span style="background: #17a2b8; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-left: 10px;">ANNUALE</span>' : ''}
                                </td>
                                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #dee2e6;">€ ${service.price.toFixed(2)}</td>
                                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #dee2e6;">-</td>
                                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #dee2e6;">€ ${service.price.toFixed(2)}</td>
                            </tr>
                        `).join('')}
                    ` : ''}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="padding: 10px; text-align: right;"><strong>Totale (IVA esclusa):</strong></td>
                        <td style="padding: 10px; text-align: right;"><strong>€ ${data.totalAmount.toFixed(2)}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding: 10px; text-align: right;">IVA 22%:</td>
                        <td style="padding: 10px; text-align: right;">€ ${data.vatAmount.toFixed(2)}</td>
                    </tr>
                    <tr style="background-color: #f8f9fa;">
                        <td colspan="3" style="padding: 10px; text-align: right;"><strong>Totale con IVA:</strong></td>
                        <td style="padding: 10px; text-align: right;"><strong style="color: #007bff; font-size: 1.2em;">€ ${data.totalWithVat.toFixed(2)}</strong></td>
                    </tr>
                </tfoot>
            </table>
            
            ${data.projectNotes ? `
                <div style="margin-bottom: 20px;">
                    <h4>Note sul Progetto:</h4>
                    <p>${data.projectNotes.replace(/\n/g, '<br>')}</p>
                </div>
            ` : ''}
            
            <div style="margin-bottom: 20px;">
                <h4>Termini di Pagamento:</h4>
                <p>${data.paymentTermsText}</p>
            </div>
            
            <div style="margin-top: 40px; padding: 20px; background-color: #f8f9fa; border-radius: 5px;">
                <p style="margin: 0;"><strong>Francesco Zappavigna</strong><br>
                Web Developer<br>
                Tel: +39 349 658 9178<br>
                Email: info@francescozappavigna.it<br>
                P.IVA: 12763370967</p>
            </div>
        </div>
    `;
}

// Raccogli dati form
function collectFormData() {
    const selectedServices = services.filter(s => s.selected).map(s => ({
        name: s.name,
        description: s.description,
        price: s.price,
        discount: s.price - s.discountedPrice,
        finalPrice: s.discountedPrice
    }));
    
    const selectedThirdParty = thirdPartyServices.filter(s => s.selected).map(s => ({
        name: s.name,
        description: s.description,
        price: s.price,
        annual: s.annual
    }));
    
    const totalAmount = selectedServices.reduce((sum, s) => sum + s.finalPrice, 0) + 
                       selectedThirdParty.reduce((sum, s) => sum + s.price, 0);
    const vatAmount = totalAmount * 0.22;
    
    let paymentTermsText = '';
    const paymentTerms = document.getElementById('paymentTerms').value;
    switch(paymentTerms) {
        case '50-50':
            paymentTermsText = '50% all\'accettazione del preventivo, 50% alla consegna del progetto';
            break;
        case '30-40-30':
            paymentTermsText = '30% all\'accettazione, 40% a metà lavoro, 30% alla consegna';
            break;
        case '100-delivery':
            paymentTermsText = '100% alla consegna del progetto';
            break;
        case 'custom':
            paymentTermsText = document.getElementById('customPaymentText').value;
            break;
    }
    
    return {
        clientName: document.getElementById('clientName').value,
        clientEmail: document.getElementById('clientEmail').value,
        clientPhone: document.getElementById('clientPhone').value,
        clientPiva: document.getElementById('clientPiva').value,
        clientAddress: document.getElementById('clientAddress').value,
        services: selectedServices,
        thirdPartyServices: selectedThirdParty,
        projectNotes: document.getElementById('projectNotes').value,
        paymentTermsText: paymentTermsText,
        validityDays: parseInt(document.getElementById('validityDays').value),
        totalAmount: totalAmount,
        vatAmount: vatAmount,
        totalWithVat: totalAmount + vatAmount
    };
}

// Genera PDF
async function generatePDF(e) {
    e.preventDefault();
    
    const formData = collectFormData();
    
    // Valida che ci sia almeno il nome del cliente
    if (!formData.clientName) {
        showNotification('Inserisci almeno il nome del cliente', 'warning');
        return;
    }
    
    // Mostra loading
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generazione in corso...';
    submitBtn.disabled = true;
    
    try {
        // Invia richiesta al server per generare HTML
        const response = await fetch('pages/preventivo-pdf-generator.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        
        if (response.ok) {
            const html = await response.text();
            
            // Apri una nuova finestra con l'HTML del preventivo
            const printWindow = window.open('', '_blank');
            printWindow.document.write(html);
            printWindow.document.close();
            
            // La finestra di stampa si aprirà automaticamente grazie allo script nell'HTML
            
            // Mostra notifica successo
            showNotification('Preventivo generato! Usa "Salva come PDF" nella finestra di stampa.', 'success');
        } else {
            throw new Error('Errore nella generazione del preventivo');
        }
    } catch (error) {
        console.error('Errore:', error);
        showNotification('Errore nella generazione del preventivo. Riprova.', 'danger');
    } finally {
        // Ripristina bottone
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    }
}

// Genera PDF lato client con jsPDF
function generatePDFClient(event) {
    const formData = collectFormData();
    
    if (!formData.clientName) {
        showNotification('Inserisci almeno il nome del cliente', 'warning');
        return;
    }
    
    // Mostra loading
    const btn = event.currentTarget || event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generazione...';
    btn.disabled = true;
    
    try {
        // Inizializza jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Configurazione
        const pageWidth = doc.internal.pageSize.getWidth();
        const pageHeight = doc.internal.pageSize.getHeight();
        const margin = 20;
        const lineHeight = 7;
        let yPosition = margin;
        
        // Colori
        const primaryColor = [44, 62, 80];
        const blueColor = [52, 152, 219];
        const greenColor = [39, 174, 96];
        const grayColor = [127, 140, 141];
        
        // Header background
        doc.setFillColor(...primaryColor);
        doc.rect(0, 0, pageWidth, 40, 'F');
        
        // Logo placeholder
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(24);
        doc.setFont(undefined, 'bold');
        doc.text('FZ', margin, 25);
        
        // Info azienda
        doc.setFontSize(9);
        doc.setFont(undefined, 'normal');
        const companyInfo = [
            'Francesco Zappavigna',
            'Web Developer',
            'P.IVA: 12763370967',
            'Tel: +39 349 658 9178',
            'info@francescozappavigna.it'
        ];
        
        let infoY = 15;
        companyInfo.forEach(line => {
            doc.text(line, pageWidth - margin, infoY, { align: 'right' });
            infoY += 4;
        });
        
        // Reset text color
        doc.setTextColor(0, 0, 0);
        yPosition = 50;
        
        // Titolo
        doc.setFontSize(28);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(...primaryColor);
        doc.text('PREVENTIVO', pageWidth / 2, yPosition, { align: 'center' });
        
        // Sottotitolo
        yPosition += 10;
        doc.setFontSize(11);
        doc.setFont(undefined, 'normal');
        doc.setTextColor(...grayColor);
        const preventivo_num = new Date().getFullYear() + '-' + String(Math.floor(Math.random() * 10000)).padStart(4, '0');
        doc.text(`N. ${preventivo_num} del ${new Date().toLocaleDateString('it-IT')}`, pageWidth / 2, yPosition, { align: 'center' });
        
        // Box Cliente
        yPosition += 20;
        doc.setTextColor(0, 0, 0);
        doc.setFillColor(248, 249, 250);
        doc.roundedRect(margin, yPosition, 80, 45, 3, 3, 'F');
        doc.setFontSize(12);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(...primaryColor);
        doc.text('CLIENTE', margin + 5, yPosition + 10);
        
        doc.setFontSize(10);
        doc.setFont(undefined, 'normal');
        doc.setTextColor(0, 0, 0);
        let clientY = yPosition + 18;
        doc.text(formData.clientName, margin + 5, clientY);
        if (formData.clientEmail) {
            clientY += 5;
            doc.text(formData.clientEmail, margin + 5, clientY);
        }
        if (formData.clientPhone) {
            clientY += 5;
            doc.text(formData.clientPhone, margin + 5, clientY);
        }
        if (formData.clientPiva) {
            clientY += 5;
            doc.text(`P.IVA/CF: ${formData.clientPiva}`, margin + 5, clientY);
        }
        
        // Box Validità
        doc.roundedRect(pageWidth - margin - 80, yPosition, 80, 45, 3, 3, 'F');
        doc.setFontSize(12);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(...primaryColor);
        doc.text('VALIDITÀ PREVENTIVO', pageWidth - margin - 75, yPosition + 10);
        
        doc.setFontSize(10);
        doc.setFont(undefined, 'normal');
        doc.setTextColor(0, 0, 0);
        const validUntil = new Date(Date.now() + formData.validityDays * 24 * 60 * 60 * 1000);
        doc.text(`Valido fino al: ${validUntil.toLocaleDateString('it-IT')}`, pageWidth - margin - 75, yPosition + 18);
        doc.text(`(${formData.validityDays} giorni)`, pageWidth - margin - 75, yPosition + 23);
        
        // Servizi
        yPosition += 55;
        doc.setFontSize(14);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(...primaryColor);
        doc.text('DETTAGLIO SERVIZI', margin, yPosition);
        
        yPosition += 10;
        
        // Tabella servizi
        const tableData = [];
        
        // Aggiungi servizi principali
        formData.services.forEach(service => {
            tableData.push([
                service.name,
                `€ ${service.price.toFixed(2)}`,
                service.discount > 0 ? `- € ${service.discount.toFixed(2)}` : '-',
                `€ ${service.finalPrice.toFixed(2)}`
            ]);
            if (service.description) {
                tableData.push([
                    {content: '  ' + service.description, colSpan: 4, styles: {fontSize: 8, textColor: [108, 117, 125], fontStyle: 'italic'}}
                ]);
            }
        });
        
        // Aggiungi servizi terze parti
        if (formData.thirdPartyServices.length > 0) {
            tableData.push([{content: '', colSpan: 4}]); // Riga vuota
            tableData.push([{content: 'SERVIZI TERZE PARTI', colSpan: 4, styles: {fontStyle: 'bold'}}]);
            formData.thirdPartyServices.forEach(service => {
                tableData.push([
                    service.name + (service.annual ? ' (Annuale)' : ''),
                    `€ ${service.price.toFixed(2)}`,
                    '-',
                    `€ ${service.price.toFixed(2)}`
                ]);
                if (service.description) {
                    tableData.push([
                        {content: '  ' + service.description, colSpan: 4, styles: {fontSize: 8, textColor: [108, 117, 125], fontStyle: 'italic'}}
                    ]);
                }
            });
        }
        
        // Disegna tabella
        doc.autoTable({
            startY: yPosition,
            head: [['Servizio', 'Prezzo', 'Sconto', 'Totale']],
            body: tableData,
            theme: 'grid',
            headStyles: {
                fillColor: blueColor,
                textColor: [255, 255, 255],
                fontStyle: 'bold'
            },
            columnStyles: {
                0: { cellWidth: 90 },
                1: { cellWidth: 30, halign: 'right' },
                2: { cellWidth: 30, halign: 'right' },
                3: { cellWidth: 30, halign: 'right', fontStyle: 'bold' }
            },
            margin: { left: margin, right: margin },
            didDrawPage: function (data) {
                // Aggiungi margini alle pagine successive
                if (doc.internal.getNumberOfPages() > 1) {
                    doc.setFontSize(10);
                    doc.setTextColor(128);
                    doc.text('Preventivo - Pagina ' + doc.internal.getNumberOfPages(), pageWidth / 2, pageHeight - 10, { align: 'center' });
                }
            }
        });
        
        yPosition = doc.lastAutoTable.finalY + 15;
        
        // Totali
        const vatAmount = formData.vatAmount;
        const totalWithVat = formData.totalWithVat;
        
        // Box totali
        const totalsX = pageWidth - margin - 70;
        doc.setFillColor(248, 249, 250);
        doc.roundedRect(totalsX - 10, yPosition - 5, 80, 35, 3, 3, 'F');
        
        doc.setFontSize(11);
        doc.setFont(undefined, 'normal');
        doc.text('Subtotale:', totalsX, yPosition);
        doc.text(`€ ${formData.totalAmount.toFixed(2)}`, totalsX + 60, yPosition, { align: 'right' });
        
        yPosition += 7;
        doc.text('IVA 22%:', totalsX, yPosition);
        doc.text(`€ ${vatAmount.toFixed(2)}`, totalsX + 60, yPosition, { align: 'right' });
        
        yPosition += 7;
        doc.setDrawColor(...blueColor);
        doc.line(totalsX, yPosition, totalsX + 60, yPosition);
        
        yPosition += 7;
        doc.setFontSize(13);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(...blueColor);
        doc.text('TOTALE:', totalsX, yPosition);
        doc.text(`€ ${totalWithVat.toFixed(2)}`, totalsX + 60, yPosition, { align: 'right' });
        
        // Note e termini di pagamento
        if (formData.projectNotes || formData.paymentTermsText) {
            yPosition += 20;
            
            // Controlla se serve nuova pagina
            if (yPosition > pageHeight - 60) {
                doc.addPage();
                yPosition = margin;
            }
            
            doc.setTextColor(0, 0, 0);
            
            if (formData.projectNotes) {
                doc.setFontSize(12);
                doc.setFont(undefined, 'bold');
                doc.setTextColor(...primaryColor);
                doc.text('NOTE SUL PROGETTO', margin, yPosition);
                yPosition += 7;
                
                doc.setFontSize(10);
                doc.setFont(undefined, 'normal');
                doc.setTextColor(0, 0, 0);
                const noteLines = doc.splitTextToSize(formData.projectNotes, pageWidth - 2 * margin);
                noteLines.forEach(line => {
                    if (yPosition > pageHeight - 30) {
                        doc.addPage();
                        yPosition = margin;
                    }
                    doc.text(line, margin, yPosition);
                    yPosition += 5;
                });
            }
            
            if (formData.paymentTermsText) {
                yPosition += 10;
                if (yPosition > pageHeight - 40) {
                    doc.addPage();
                    yPosition = margin;
                }
                doc.setFontSize(12);
                doc.setFont(undefined, 'bold');
                doc.setTextColor(...primaryColor);
                doc.text('TERMINI DI PAGAMENTO', margin, yPosition);
                yPosition += 7;
                
                doc.setFontSize(10);
                doc.setFont(undefined, 'normal');
                doc.setTextColor(0, 0, 0);
                const paymentLines = doc.splitTextToSize(formData.paymentTermsText, pageWidth - 2 * margin);
                paymentLines.forEach(line => {
                    doc.text(line, margin, yPosition);
                    yPosition += 5;
                });
            }
        }
        
        // Condizioni generali
        yPosition += 15;
        if (yPosition > pageHeight - 80) {
            doc.addPage();
            yPosition = margin;
        }
        
        doc.setFontSize(12);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(...primaryColor);
        doc.text('CONDIZIONI GENERALI', margin, yPosition);
        yPosition += 7;
        
        doc.setFontSize(9);
        doc.setFont(undefined, 'normal');
        doc.setTextColor(0, 0, 0);
        const conditions = [
            `• Il presente preventivo ha validità di ${formData.validityDays} giorni dalla data di emissione.`,
            '• I prezzi indicati sono IVA esclusa, salvo diversa indicazione.',
            '• I tempi di realizzazione verranno concordati all\'accettazione del preventivo.',
            '• Eventuali modifiche al progetto potrebbero comportare variazioni di prezzo.',
            '• Il cliente si impegna a fornire tutto il materiale necessario in formato digitale.',
            '• I servizi di terze parti sono soggetti ai termini dei rispettivi fornitori.'
        ];
        
        conditions.forEach(condition => {
            if (yPosition > pageHeight - 30) {
                doc.addPage();
                yPosition = margin;
            }
            const lines = doc.splitTextToSize(condition, pageWidth - 2 * margin);
            lines.forEach(line => {
                doc.text(line, margin, yPosition);
                yPosition += 5;
            });
        });
        
        // Box accettazione
        yPosition += 15;
        if (yPosition > pageHeight - 50) {
            doc.addPage();
            yPosition = margin;
        }
        
        doc.setFillColor(248, 249, 250);
        doc.setDrawColor(200, 200, 200);
        doc.roundedRect(margin, yPosition, pageWidth - 2 * margin, 40, 3, 3, 'DF');
        
        doc.setFontSize(11);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(...primaryColor);
        doc.text('ACCETTAZIONE DEL PREVENTIVO', margin + 5, yPosition + 10);
        
        doc.setFont(undefined, 'normal');
        doc.setTextColor(0, 0, 0);
        doc.text('Data: _______________________', margin + 5, yPosition + 25);
        doc.text('Firma: _______________________', pageWidth / 2, yPosition + 25);
        
        // Salva PDF
        const filename = `Preventivo_${formData.clientName.replace(/[^a-z0-9]/gi, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(filename);
        
        showNotification('PDF generato con successo!', 'success');
    } catch (error) {
        console.error('Errore generazione PDF:', error);
        showNotification('Errore nella generazione del PDF', 'danger');
    } finally {
        // Ripristina bottone
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

// Genera PDF dall'anteprima
function generatePDFFromPreview() {
    const element = document.getElementById('previewContent');
    const opt = {
        margin: 0,
        filename: `Preventivo_${new Date().toISOString().split('T')[0]}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    
    // Usa html2canvas + jsPDF
    html2canvas(element, {
        scale: 2,
        useCORS: true,
        logging: false
    }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF('p', 'mm', 'a4');
        const imgWidth = 210;
        const pageHeight = 295;
        const imgHeight = canvas.height * imgWidth / canvas.width;
        let heightLeft = imgHeight;
        let position = 0;
        
        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;
        
        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }
        
        pdf.save(opt.filename);
        showNotification('PDF generato con successo!', 'success');
    });
}
</script>

<style>
/* Service Items */
.service-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    margin-bottom: 15px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.service-item:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.service-item.selected {
    border-color: #3498db;
    background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%);
}

.service-item.third-party.selected {
    border-color: #27ae60;
    background: linear-gradient(135deg, #ffffff 0%, #f0fff4 100%);
}

.service-checkbox-wrapper {
    padding-top: 5px;
}

.service-checkbox {
    width: 22px;
    height: 22px;
    cursor: pointer;
}

.service-content {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.service-info {
    flex: 1;
}

.service-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 5px;
}

.service-description {
    font-size: 0.9rem;
    line-height: 1.4;
    margin: 0;
}

.custom-title, .custom-description {
    width: 100%;
}

/* Price Display */
.service-price {
    display: flex;
    align-items: center;
    gap: 10px;
}

.price-display {
    text-align: right;
    min-width: 120px;
}

.original-price {
    display: block;
    text-decoration: line-through;
    color: #dc3545;
    font-size: 0.9rem;
    opacity: 0.7;
}

.discounted-price {
    display: block;
    color: #27ae60;
    font-size: 1.2rem;
    font-weight: 700;
}

.regular-price {
    display: block;
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: 600;
}

.price-edit {
    width: 120px;
}

.price-input {
    text-align: right;
}

.btn-edit-price {
    padding: 5px 10px;
    border: 1px solid #dee2e6;
}

.btn-edit-price:hover {
    background: #f8f9fa;
    border-color: #3498db;
    color: #3498db;
}

.btn-remove {
    padding: 5px 10px;
}

/* Summary Card */
#preventivoSummary {
    font-size: 0.95rem;
}

#preventivoSummary .text-primary {
    font-weight: 700;
}

.sticky-top {
    top: 20px;
}

/* Buttons */
.btn-generate {
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
    border: none;
    padding: 15px 40px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.btn-generate:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(39, 174, 96, 0.4);
}

/* Preview Modal */
#previewContent {
    background: white;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

#previewContent table {
    margin: 20px 0;
}

#previewContent img {
    max-width: 200px;
    height: auto;
}

.modal-xl {
    max-width: 90%;
}

/* Responsive */
@media (max-width: 768px) {
    .service-item {
        flex-direction: column;
    }
    
    .service-content {
        flex-direction: column;
        width: 100%;
    }
    
    .service-price {
        width: 100%;
        justify-content: space-between;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }
    
    .price-display, .price-edit {
        min-width: auto;
    }
    
    .sticky-top {
        position: static !important;
    }
    
    .btn-generate {
        width: 100%;
    }
}

@media print {
    .modal-header, .modal-footer {
        display: none;
    }
}
</style>