<?php
// Preventivo HTML/PDF Generator
// Genera un HTML stampabile che può essere salvato come PDF

// Ricevi i dati JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Genera numero preventivo
$preventivo_num = date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
$today = date('d/m/Y');
$validityDays = $data['validityDays'] ?? 30;
$validUntil = date('d/m/Y', strtotime('+' . $validityDays . ' days'));

// Inizia output HTML
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preventivo <?php echo htmlspecialchars($data['clientName'] ?? 'Cliente'); ?></title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        @page :first {
            margin: 0;
        }
        
        @page :left {
            margin: 20mm 15mm;
        }
        
        @page :right {
            margin: 20mm 15mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm;
            margin: 0 auto;
            background: white;
            position: relative;
        }
        
        /* Header */
        .header {
            background: #2c3e50;
            margin: -15mm -15mm 0 -15mm;
            padding: 15mm;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo-section {
            flex: 1;
        }
        
        .logo {
            height: 50px;
            filter: brightness(0) invert(1);
        }
        
        .company-info {
            text-align: right;
            font-size: 11px;
            line-height: 1.4;
        }
        
        /* Title Section */
        .title-section {
            text-align: center;
            margin: 30px 0;
        }
        
        .title {
            font-size: 32px;
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        /* Info Boxes */
        .info-boxes {
            display: flex;
            gap: 20px;
            margin: 30px 0;
        }
        
        .info-box {
            flex: 1;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
        }
        
        .info-box h3 {
            color: #2c3e50;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .info-box p {
            font-size: 13px;
            margin: 3px 0;
        }
        
        /* Services Table */
        .services-section {
            margin: 40px 0;
        }
        
        .section-title {
            font-size: 18px;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #3498db;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: #3498db;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
        }
        
        th:last-child {
            text-align: right;
        }
        
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
            font-size: 13px;
        }
        
        td:last-child {
            text-align: right;
            font-weight: 600;
        }
        
        .service-name {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .service-description {
            font-size: 11px;
            color: #7f8c8d;
            font-style: italic;
        }
        
        .price-original {
            text-decoration: line-through;
            color: #e74c3c;
            opacity: 0.7;
        }
        
        .price-discount {
            color: #e74c3c;
        }
        
        .price-final {
            color: #27ae60;
            font-weight: 700;
        }
        
        /* Totals */
        .totals {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }
        
        .totals-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            min-width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 14px;
        }
        
        .total-row.final {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #3498db;
            font-size: 18px;
            font-weight: 700;
            color: #3498db;
        }
        
        /* Notes & Terms */
        .notes-section {
            margin: 40px 0;
        }
        
        .notes-box {
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .notes-box h4 {
            color: #2c3e50;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .notes-box p {
            font-size: 13px;
            line-height: 1.6;
        }
        
        /* Conditions */
        .conditions {
            font-size: 11px;
            color: #7f8c8d;
            line-height: 1.8;
        }
        
        .conditions li {
            margin-left: 20px;
            margin-bottom: 5px;
        }
        
        /* Acceptance Box */
        .acceptance-box {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 25px;
            margin-top: 40px;
            background: #f8f9fa;
        }
        
        .acceptance-box h4 {
            color: #2c3e50;
            font-size: 14px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        
        .signature-fields {
            display: flex;
            gap: 40px;
        }
        
        .signature-field {
            flex: 1;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 40px;
            margin-bottom: 5px;
        }
        
        .signature-label {
            font-size: 12px;
            color: #7f8c8d;
        }
        
        /* Footer */
        .footer {
            position: absolute;
            bottom: 15mm;
            left: 15mm;
            right: 15mm;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            padding-top: 10px;
            border-top: 1px solid #e9ecef;
        }
        
        /* Print Styles */
        @media print {
            body {
                margin: 0;
                background: white;
            }
            
            .page {
                margin: 0;
                border: none;
                box-shadow: none;
                page-break-after: always;
            }
            
            .no-print {
                display: none !important;
            }
            
            /* Controllo interruzioni di pagina */
            .info-boxes,
            .services-section,
            .notes-box,
            .acceptance-box,
            table {
                page-break-inside: avoid;
            }
            
            .section-title {
                page-break-after: avoid;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            .totals {
                page-break-inside: avoid;
            }
            
            /* Assicura che header tabella non si separi dal contenuto */
            thead {
                display: table-header-group;
            }
            
            /* Evita che le sezioni si separino dai loro titoli */
            h2, h3, h4 {
                page-break-after: avoid;
            }
            
            /* Margine di sicurezza per evitare tagli */
            .services-section,
            .notes-section {
                padding-bottom: 20px;
            }
        }
        
        /* Utility Classes */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mt-2 { margin-top: 20px; }
        .mb-2 { margin-bottom: 20px; }
        
        /* Keep Together - Evita interruzioni di pagina */
        .keep-together {
            page-break-inside: avoid;
            break-inside: avoid;
            display: block;
        }
        
        /* Spacing per evitare tagli */
        .services-section {
            page-break-inside: auto;
        }
        
        .services-table {
            page-break-inside: auto;
        }
        
        /* Assicura che ogni riga della tabella rimanga unita */
        tbody tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        /* Mantieni header tabella con contenuto */
        thead {
            display: table-header-group;
        }
        
        /* Evita separazione titoli dal contenuto */
        h1, h2, h3, h4, h5, h6 {
            page-break-after: avoid;
            break-after: avoid;
        }
        
        /* Box acceptance sempre in nuova pagina se non c'è spazio */
        .acceptance-box {
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top: auto;
        }
        
        /* Info boxes sempre insieme */
        .info-boxes {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        /* Notes boxes */
        .notes-box {
            page-break-inside: avoid;
            break-inside: avoid;
            margin-bottom: 20px;
        }
        
        /* Condizioni sempre insieme */
        .conditions-section {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        /* Totali sempre con la tabella */
        .totals {
            page-break-inside: avoid;
            break-inside: avoid;
            page-break-before: avoid;
            break-before: avoid;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <!-- Logo placeholder - sostituisci con il tuo logo -->
                <div style="font-size: 24px; font-weight: bold;">FZ</div>
            </div>
            <div class="company-info">
                Francesco Zappavigna<br>
                Web Developer<br>
                P.IVA: 12763370967<br>
                Tel: +39 349 658 9178<br>
                info@francescozappavigna.it
            </div>
        </div>
        
        <!-- Title -->
        <div class="title-section">
            <h1 class="title">PREVENTIVO</h1>
            <p class="subtitle">N. <?php echo $preventivo_num; ?> del <?php echo $today; ?></p>
        </div>
        
        <!-- Info Boxes -->
        <div class="info-boxes keep-together">
            <div class="info-box">
                <h3>Cliente</h3>
                <p><strong><?php echo htmlspecialchars($data['clientName'] ?? ''); ?></strong></p>
                <?php if (!empty($data['clientEmail'])): ?>
                    <p><?php echo htmlspecialchars($data['clientEmail']); ?></p>
                <?php endif; ?>
                <?php if (!empty($data['clientPhone'])): ?>
                    <p><?php echo htmlspecialchars($data['clientPhone']); ?></p>
                <?php endif; ?>
                <?php if (!empty($data['clientPiva'])): ?>
                    <p>P.IVA/CF: <?php echo htmlspecialchars($data['clientPiva']); ?></p>
                <?php endif; ?>
                <?php if (!empty($data['clientAddress'])): ?>
                    <p><?php echo nl2br(htmlspecialchars($data['clientAddress'])); ?></p>
                <?php endif; ?>
            </div>
            <div class="info-box">
                <h3>Validità Preventivo</h3>
                <p>Data emissione: <strong><?php echo $today; ?></strong></p>
                <p>Valido fino al: <strong><?php echo $validUntil; ?></strong></p>
                <p>(<?php echo $validityDays; ?> giorni)</p>
            </div>
        </div>
        
        <!-- Services -->
        <div class="services-section keep-together">
            <h2 class="section-title">DETTAGLIO SERVIZI</h2>
            <table class="services-table">
                <thead>
                    <tr>
                        <th style="width: 55%">Servizio</th>
                        <th style="width: 15%">Prezzo</th>
                        <th style="width: 15%">Sconto</th>
                        <th style="width: 15%">Totale</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['services'])): ?>
                        <?php foreach ($data['services'] as $service): ?>
                            <?php if (!empty($service['name'])): ?>
                                <tr>
                                    <td>
                                        <div class="service-name"><?php echo htmlspecialchars($service['name']); ?></div>
                                        <?php if (!empty($service['description'])): ?>
                                            <div class="service-description"><?php echo htmlspecialchars($service['description']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <?php if (isset($service['discount']) && $service['discount'] > 0): ?>
                                            <span class="price-original">€ <?php echo number_format($service['price'] ?? 0, 2, ',', '.'); ?></span>
                                        <?php else: ?>
                                            € <?php echo number_format($service['price'] ?? 0, 2, ',', '.'); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <?php if (isset($service['discount']) && $service['discount'] > 0): ?>
                                            <span class="price-discount">- € <?php echo number_format($service['discount'], 2, ',', '.'); ?></span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="price-final">
                                        € <?php echo number_format($service['finalPrice'] ?? 0, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($data['thirdPartyServices'])): ?>
                        <tr>
                            <td colspan="4" style="padding-top: 20px; font-weight: 600; color: #2c3e50;">
                                SERVIZI TERZE PARTI
                            </td>
                        </tr>
                        <?php foreach ($data['thirdPartyServices'] as $service): ?>
                            <?php if (!empty($service['name'])): ?>
                                <tr>
                                    <td>
                                        <div class="service-name">
                                            <?php echo htmlspecialchars($service['name']); ?>
                                            <?php if (!empty($service['annual'])): ?>
                                                <span style="background: #17a2b8; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; margin-left: 10px;">ANNUALE</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($service['description'])): ?>
                                            <div class="service-description"><?php echo htmlspecialchars($service['description']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">€ <?php echo number_format($service['price'] ?? 0, 2, ',', '.'); ?></td>
                                    <td class="text-right">-</td>
                                    <td>€ <?php echo number_format($service['price'] ?? 0, 2, ',', '.'); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Totals -->
            <div class="totals keep-together">
                <div class="totals-box">
                    <div class="total-row">
                        <span>Subtotale:</span>
                        <span>€ <?php echo number_format($data['totalAmount'] ?? 0, 2, ',', '.'); ?></span>
                    </div>
                    <div class="total-row">
                        <span>IVA 22%:</span>
                        <span>€ <?php echo number_format($data['vatAmount'] ?? 0, 2, ',', '.'); ?></span>
                    </div>
                    <div class="total-row final">
                        <span>TOTALE:</span>
                        <span>€ <?php echo number_format($data['totalWithVat'] ?? 0, 2, ',', '.'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notes & Terms -->
        <div class="notes-section">
            <?php if (!empty($data['projectNotes'])): ?>
                <div class="notes-box keep-together">
                    <h4>NOTE SUL PROGETTO</h4>
                    <p><?php echo nl2br(htmlspecialchars($data['projectNotes'])); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="notes-box keep-together">
                <h4>TERMINI DI PAGAMENTO</h4>
                <p><?php echo htmlspecialchars($data['paymentTermsText'] ?? '50% all\'accettazione del preventivo, 50% alla consegna'); ?></p>
            </div>
            
            <div class="conditions-section keep-together">
                <h4 class="section-title" style="font-size: 14px; margin-top: 30px;">CONDIZIONI GENERALI</h4>
                <ul class="conditions">
                    <li>Il presente preventivo ha validità di <?php echo $validityDays; ?> giorni dalla data di emissione.</li>
                    <li>I prezzi indicati sono IVA esclusa, salvo diversa indicazione.</li>
                    <li>I tempi di realizzazione verranno concordati all'accettazione del preventivo.</li>
                    <li>Eventuali modifiche al progetto potrebbero comportare variazioni di prezzo.</li>
                    <li>Il cliente si impegna a fornire tutto il materiale necessario in formato digitale.</li>
                    <li>I servizi di terze parti sono soggetti ai termini dei rispettivi fornitori.</li>
                </ul>
            </div>
        </div>
        
        <!-- Acceptance Box -->
        <div class="acceptance-box keep-together">
            <h4>Accettazione del Preventivo</h4>
            <div class="signature-fields">
                <div class="signature-field">
                    <div class="signature-line"></div>
                    <div class="signature-label">Data</div>
                </div>
                <div class="signature-field">
                    <div class="signature-line"></div>
                    <div class="signature-label">Firma del Cliente</div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            Francesco Zappavigna - P.IVA: 12763370967 - info@francescozappavigna.it
        </div>
    </div>
    
    <!-- Auto-print script -->
    <script>
        // Apri automaticamente la finestra di stampa
        window.onload = function() {
            window.print();
            
            // Opzionale: chiudi la finestra dopo la stampa
            window.onafterprint = function() {
                // window.close();
            };
        }
    </script>
</body>
</html>