<?php
// File: admin-panel/pages/tools/phpmailer.php
?>

<div class="tool-card active">
    <div class="content-header">
        <h1><i class="fas fa-paper-plane"></i> PHPMailer Sender</h1>
        <p class="text-muted">Genera uno script PHP completo per inviare email tramite SMTP con PHPMailer e protezione reCAPTCHA.</p>
    </div>

    <div class="info-section">
        <h4><i class="fas fa-download"></i> 1. Scarica PHPMailer</h4>
        <p>
            Il metodo raccomandato per installare PHPMailer è usare <strong>Composer</strong>. Se non lo usi, puoi scaricare i file sorgente direttamente da GitHub.
        </p>
        <div class="d-flex gap-3 flex-wrap">
             <a href="https://github.com/PHPMailer/PHPMailer" target="_blank" class="btn btn-primary"><i class="fab fa-github"></i> Vai a PHPMailer su GitHub</a>
             <a href="https://getcomposer.org/download/" target="_blank" class="btn btn-info"><i class="fas fa-box-open"></i> Ottieni Composer</a>
        </div>
        <p class="mt-3">
            Se usi Composer, esegui questo comando nella cartella del tuo progetto:
            <br>
            <code>composer require phpmailer/phpmailer</code>
        </p>
    </div>

    <form id="phpmailerForm" class="mt-4">
        <h4><i class="fas fa-cogs"></i> 2. Configura lo script</h4>
        
        <div class="generator-section">
            <h5>Configurazione SMTP</h5>
            <div class="row">
                <div class="col-md-6 form-section"><label for="smtpHost">Host SMTP</label><input type="text" class="form-control" id="smtpHost" placeholder="es. smtp.example.com" required></div>
                <div class="col-md-6 form-section"><label for="smtpUser">Username SMTP</label><input type="text" class="form-control" id="smtpUser" placeholder="La tua email" required></div>
                <div class="col-md-6 form-section"><label for="smtpPass">Password SMTP</label><input type="password" class="form-control" id="smtpPass" placeholder="La tua password" required></div>
                <div class="col-md-3 form-section"><label for="smtpPort">Porta</label><input type="number" class="form-control" id="smtpPort" value="587" required></div>
                <div class="col-md-3 form-section"><label for="smtpSecure">Sicurezza</label><select class="form-select" id="smtpSecure"><option value="STARTTLS">TLS</option><option value="SMTPS">SSL</option></select></div>
            </div>
        </div>

        <div class="generator-section">
            <h5>Dettagli Email</h5>
            <div class="row">
                <div class="col-md-6 form-section"><label for="fromEmail">Email Mittente</label><input type="email" class="form-control" id="fromEmail" placeholder="no-reply@example.com" required></div>
                <div class="col-md-6 form-section"><label for="fromName">Nome Mittente</label><input type="text" class="form-control" id="fromName" placeholder="Nome del tuo sito" required></div>
                <div class="col-md-6 form-section"><label for="toEmail">Email Destinatario</label><input type="email" class="form-control" id="toEmail" placeholder="contact@example.com" required></div>
                 <div class="col-md-6 form-section"><label for="emailSubject">Oggetto</label><input type="text" class="form-control" id="emailSubject" value="Nuovo messaggio dal form di contatto" required></div>
            </div>
        </div>

        <div class="generator-section">
            <h5><i class="fas fa-shield-alt"></i> Protezione Antispam (reCAPTCHA v3)</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="useRecaptcha">
                <label class="form-check-label" for="useRecaptcha">Abilita Google reCAPTCHA v3</label>
            </div>
            <div id="recaptchaFields" style="display: none;" class="mt-3">
                 <p class="text-muted small">
                    Ottieni le tue chiavi dal <a href="https://www.google.com/recaptcha/admin/create" target="_blank">pannello di amministrazione di Google reCAPTCHA</a>.
                </p>
                <div class="row">
                    <div class="col-md-6 form-section"><label for="recaptchaSiteKey">Site Key (lato client)</label><input type="text" class="form-control" id="recaptchaSiteKey"></div>
                    <div class="col-md-6 form-section"><label for="recaptchaSecretKey">Secret Key (lato server)</label><input type="text" class="form-control" id="recaptchaSecretKey"></div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-generate mt-3"><i class="fas fa-magic"></i> Genera Codice PHP</button>
    </form>

    <div id="phpmailerOutput" class="code-output" style="display: none;">
        <div class="copy-btn-container">
            <button class="btn btn-copy" onclick="copyCode('phpmailerCode', this)"><i class="fas fa-copy"></i> <span>Copia</span></button>
        </div>
        <pre><code class="language-php" id="phpmailerCode"></code></pre>
    </div>
</div>

<textarea id="phpCodeTemplate" style="display:none;">
&lt;?php
// Importa le classi di PHPMailer nel namespace globale
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Carica l'autoloader di Composer
// Modifica il percorso se necessario, es. 'path/to/vendor/autoload.php'
require 'vendor/autoload.php';

// Controlla che la richiesta sia di tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- Inizio Validazione reCAPTCHA ---
    {{recaptcha_validation}}
    // --- Fine Validazione reCAPTCHA ---

    // Recupera i dati dal form e sanificali
    // Esempio: $nome = htmlspecialchars(strip_tags(trim($_POST['nome'])));
    // Aggiungi qui i campi del tuo form
    // $email_mittente = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    // $messaggio = htmlspecialchars(strip_tags(trim($_POST['messaggio'])));

    // Crea una nuova istanza di PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Impostazioni del server
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Abilita l'output di debug dettagliato
        $mail->isSMTP();
        $mail->Host       = '{{smtp_host}}';
        $mail->SMTPAuth   = true;
        $mail->Username   = '{{smtp_user}}';
        $mail->Password   = '{{smtp_pass}}';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_{{smtp_secure}};
        $mail->Port       = {{smtp_port}};

        // Mittente e Destinatari
        $mail->setFrom('{{from_email}}', '{{from_name}}');
        $mail->addAddress('{{to_email}}'); // Aggiungi un destinatario
        // $mail->addReplyTo($email_mittente, $nome); // Opzionale: imposta il Reply-To

        // Contenuto dell'email
        $mail->isHTML(true);
        $mail->Subject = '{{email_subject}}';
        
        // Costruisci il corpo dell'email con i dati del form
        // Esempio di corpo del messaggio:
        // $mail->Body    = "<b>Nuovo messaggio da:</b> {$nome}<br>" .
        //                "<b>Email:</b> {$email_mittente}<br><br>" .
        //                "<b>Messaggio:</b><br>{$messaggio}";
        // $mail->AltBody = "Nuovo messaggio da: {$nome} ({$email_mittente})\n\nMessaggio:\n{$messaggio}";
        
        $mail->Body = 'Questo è un messaggio di prova generato dal pannello.';
        $mail->AltBody = 'Questo è un messaggio di prova generato dal pannello.';

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'Messaggio inviato con successo!']);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => "Impossibile inviare il messaggio. Errore: {$mail->ErrorInfo}"]);
    }
} else {
    // Se non è una richiesta POST, mostra un errore
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Metodo non consentito']);
}
?&gt;
</textarea>


<script>
document.getElementById('useRecaptcha').addEventListener('change', function() {
    document.getElementById('recaptchaFields').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('phpmailerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Recupera il template del codice dalla textarea nascosta
    let codeTemplate = document.getElementById('phpCodeTemplate').value;

    const useRecaptcha = document.getElementById('useRecaptcha').checked;
    let recaptchaValidationCode = '';
    if (useRecaptcha) {
        recaptchaValidationCode = `
    if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
        http_response_code(400);
        die(json_encode(['status' => 'error', 'message' => 'Token reCAPTCHA non presente.']));
    }

    $recaptcha_token = $_POST['g-recaptcha-response'];
    $recaptcha_secret = '${document.getElementById('recaptchaSecretKey').value}';
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    
    $response = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_token);
    $response_keys = json_decode($response, true);
    
    if (!$response_keys["success"] || $response_keys["score"] < 0.5) {
        http_response_code(403);
        die(json_encode(['status' => 'error', 'message' => 'Verifica reCAPTCHA fallita. Sei un bot?']));
    }`;
    }

    // Sostituisci i segnaposto nel template
    codeTemplate = codeTemplate.replace('{{recaptcha_validation}}', recaptchaValidationCode);
    codeTemplate = codeTemplate.replace('{{smtp_host}}', document.getElementById('smtpHost').value);
    codeTemplate = codeTemplate.replace('{{smtp_user}}', document.getElementById('smtpUser').value);
    codeTemplate = codeTemplate.replace('{{smtp_pass}}', document.getElementById('smtpPass').value);
    codeTemplate = codeTemplate.replace('{{smtp_port}}', document.getElementById('smtpPort').value);
    codeTemplate = codeTemplate.replace('{{smtp_secure}}', document.getElementById('smtpSecure').value);
    codeTemplate = codeTemplate.replace('{{from_email}}', document.getElementById('fromEmail').value);
    codeTemplate = codeTemplate.replace('{{from_name}}', document.getElementById('fromName').value);
    codeTemplate = codeTemplate.replace('{{to_email}}', document.getElementById('toEmail').value);
    codeTemplate = codeTemplate.replace('{{email_subject}}', document.getElementById('emailSubject').value);


    const outputContainer = document.getElementById('phpmailerOutput');
    const codeContainer = document.getElementById('phpmailerCode');
    
    codeContainer.textContent = codeTemplate;
    outputContainer.style.display = 'block';
    
    if (typeof Prism !== 'undefined') {
        Prism.highlightElement(codeContainer);
    }

    outputContainer.scrollIntoView({ behavior: 'smooth' });
});

// Funzione di copia (probabilmente già in main.js)
function copyCode(elementId, buttonElement) {
    const code = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(code).then(() => {
        const span = buttonElement.querySelector('span');
        const originalText = span.textContent;
        span.textContent = 'Copiato!';
        buttonElement.style.backgroundColor = 'var(--success-color)';
        setTimeout(() => {
            span.textContent = 'Copia';
            buttonElement.style.backgroundColor = '';
        }, 2000);
    });
}
</script>