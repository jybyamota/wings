<?php

declare(strict_types=1);

session_start();

$pageTitle = 'Upload Payment Proof - Wing Master';
$currentPage = 'upload-payment';
$message = '';
$messageType = '';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $refNumber = trim($_POST['reference_number'] ?? '');
    
    if ($refNumber === '') {
        $message = 'Please enter your reference number.';
        $messageType = 'error';
    } else {
        // Load reservations and find matching reference
        $resFile = __DIR__ . '/data/reservations.json';
        $found = false;
        $reservation = null;
        
        if (file_exists($resFile)) {
            $list = json_decode(file_get_contents($resFile), true) ?: [];
            foreach ($list as &$res) {
                if (($res['reference_number'] ?? '') === $refNumber) {
                    $found = true;
                    $reservation = $res;
                    break;
                }
            }
        }
        
        if (!$found) {
            $message = 'Reference number not found. Please check and try again.';
            $messageType = 'error';
        } elseif (!isset($_FILES['payment_screenshot']) || $_FILES['payment_screenshot']['error'] !== UPLOAD_ERR_OK) {
            $message = 'Please select a file to upload.';
            $messageType = 'error';
        } else {
            $file = $_FILES['payment_screenshot'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            
            if (!in_array($file['type'], $allowed)) {
                $message = 'Please upload a valid image file (PNG, JPG, or GIF).';
                $messageType = 'error';
            } elseif ($file['size'] > 5 * 1024 * 1024) {
                $message = 'File is too large. Maximum size is 5MB.';
                $messageType = 'error';
            } else {
                // Save file
                $uploadDir = __DIR__ . '/data/uploads';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = $refNumber . '.' . $ext;
                $filepath = $uploadDir . '/' . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    // Update reservation with screenshot
                    if (file_exists($resFile)) {
                        $list = json_decode(file_get_contents($resFile), true) ?: [];
                        foreach ($list as &$res) {
                            if (($res['reference_number'] ?? '') === $refNumber) {
                                $res['payment_screenshot'] = $filename;
                                $res['screenshot_uploaded_at'] = date('Y-m-d H:i:s');
                                break;
                            }
                        }
                        file_put_contents($resFile, json_encode($list, JSON_PRETTY_PRINT));
                    }
                    
                    $message = 'Payment proof uploaded successfully! We will verify it shortly.';
                    $messageType = 'success';
                } else {
                    $message = 'Error uploading file. Please try again.';
                    $messageType = 'error';
                }
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';

?>

<!-- HERO -->
<header class="page-hero page-hero--light transition-fade-in" style="padding:3rem 2rem;">
    <div class="container page-hero-inner slide-transition-up" style="text-align:center;">
        <p class="hero-eyebrow" style="animation-delay:0.1s;">Payment Verification</p>
        <h1 style="animation-delay:0.2s;">Upload Payment Proof</h1>
        <p class="hero-lead" style="animation-delay:0.3s; max-width:560px; margin:0 auto;">
            Upload your GCash payment screenshot to complete your reservation verification.
        </p>
    </div>
</header>

<!-- UPLOAD FORM -->
<section style="background:var(--bg-cream); padding:4rem 2rem; min-height:60vh;">
    <div class="container" style="max-width:620px; margin:0 auto;">
        <div style="
            background:var(--bg-card); border:1px solid rgba(232,184,60,0.1);
            border-radius:8px; padding:2.5rem;
        ">
            <?php if ($message): ?>
            <div style="
                background:<?= $messageType === 'success' ? 'rgba(100,200,100,0.12)' : 'rgba(200,50,50,0.12)' ?>;
                border:1px solid <?= $messageType === 'success' ? 'rgba(100,200,100,0.3)' : 'rgba(200,50,50,0.3)' ?>;
                color:<?= $messageType === 'success' ? '#70ff70' : '#ff7070' ?>;
                border-radius:4px; padding:1rem;
                margin-bottom:2rem; font-size:0.95rem; line-height:1.5;
            ">
                <?php if ($messageType === 'success'): ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle; margin-right:0.5rem;">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <?php else: ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle; margin-right:0.5rem;">
                    <circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <?php endif; ?>
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:1.5rem;">
                <!-- REFERENCE NUMBER -->
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    <label style="font-size:0.75rem; font-weight:700; color:rgba(255,255,255,0.6); text-transform:uppercase; letter-spacing:0.05em;">
                        Reference Number <span style="color:#ff7070;">*</span>
                    </label>
                    <input type="text" name="reference_number" placeholder="e.g., WM-20260617-001" required style="
                        padding:0.85rem 1rem; background:rgba(255,255,255,0.05);
                        border:1px solid rgba(255,255,255,0.1); border-radius:4px;
                        color:#fff; font-family:monospace; font-size:0.95rem;
                        outline:none; transition:border-color 0.2s;
                    " onfocus="this.style.borderColor='var(--accent-gold)'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                    <p style="font-size:0.8rem; color:rgba(255,255,255,0.4); margin:0;">You received this when you submitted your reservation</p>
                </div>

                <!-- FILE UPLOAD -->
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    <label style="font-size:0.75rem; font-weight:700; color:rgba(255,255,255,0.6); text-transform:uppercase; letter-spacing:0.05em;">
                        GCash Payment Screenshot <span style="color:#ff7070;">*</span>
                    </label>
                    <div class="file-upload-wrapper" style="position:relative;">
                        <input type="file" id="payment-screenshot" name="payment_screenshot" accept="image/*" required style="display:none;">
                        <label for="payment-screenshot" class="payment-file-label" style="
                            display:flex; align-items:center; justify-content:center;
                            min-height:150px; background:rgba(255,255,255,0.03);
                            border:2px dashed rgba(232,184,60,0.3); border-radius:4px;
                            cursor:pointer; transition:all 0.2s; padding:2rem; text-align:center;
                        ">
                            <div style="display:flex; flex-direction:column; align-items:center; gap:0.75rem; pointer-events:none;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:0.6;">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                <div>
                                    <p style="color:rgba(255,255,255,0.7); font-size:0.95rem; margin:0; font-weight:500;">Click to upload or drag & drop</p>
                                    <p style="color:rgba(255,255,255,0.4); font-size:0.85rem; margin:0.25rem 0 0 0;">PNG, JPG, GIF (max 5MB)</p>
                                </div>
                            </div>
                        </label>
                        <div id="file-name-display" style="margin-top:0.75rem; font-size:0.85rem; color:var(--accent-gold); display:none; text-align:center;"></div>
                    </div>
                </div>

                <!-- INFO BOX -->
                <div style="
                    background:rgba(232,184,60,0.08); border:1px solid rgba(232,184,60,0.2);
                    border-radius:4px; padding:1rem; font-size:0.85rem; color:rgba(255,255,255,0.7);
                    line-height:1.6;
                ">
                    <p style="margin:0 0 0.5rem 0; font-weight:600; color:#fff;">What to upload:</p>
                    <ul style="margin:0; padding-left:1.2rem;">
                        <li>Screenshot of GCash payment confirmation</li>
                        <li>Must show transaction amount (₱500) and timestamp</li>
                        <li>Your reference number in the GCash memo/message</li>
                    </ul>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" style="
                    padding:1rem; background:var(--accent-gold); color:#000;
                    border:none; border-radius:4px; font-weight:700; font-size:0.9rem;
                    cursor:pointer; transition:all 0.2s; text-transform:uppercase;
                    letter-spacing:0.05em;
                " onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    Upload Proof of Payment
                </button>

                <!-- HELP TEXT -->
                <p style="font-size:0.8rem; color:rgba(255,255,255,0.5); text-align:center; margin:0;">
                    Questions? Contact us at <strong style="color:rgba(255,255,255,0.7);">0909 163 9984</strong>
                </p>
            </form>
        </div>

        <!-- FAQ SECTION -->
        <div style="margin-top:3rem; padding-top:3rem; border-top:1px solid rgba(232,184,60,0.1);">
            <h3 style="font-family:var(--font-display); font-size:1.3rem; color:#fff; margin-bottom:1.5rem;">Frequently Asked Questions</h3>
            
            <div style="display:flex; flex-direction:column; gap:1.5rem;">
                <div>
                    <h4 style="color:var(--accent-gold); font-size:0.95rem; margin:0 0 0.5rem 0;">Where do I send the ₱500 payment?</h4>
                    <p style="color:rgba(255,255,255,0.6); font-size:0.9rem; margin:0;">
                        Send via GCash to <strong style="color:#fff;">0909 163 9984</strong>. Include your reference number (e.g., WM-20260617-001) in the GCash memo field.
                    </p>
                </div>
                
                <div>
                    <h4 style="color:var(--accent-gold); font-size:0.95rem; margin:0 0 0.5rem 0;">How long does verification take?</h4>
                    <p style="color:rgba(255,255,255,0.6); font-size:0.9rem; margin:0;">
                        Our admin team verifies payments within 1-2 hours during business hours. You'll see the status update in your reservation history.
                    </p>
                </div>
                
                <div>
                    <h4 style="color:var(--accent-gold); font-size:0.95rem; margin:0 0 0.5rem 0;">What if my payment was rejected?</h4>
                    <p style="color:rgba(255,255,255,0.6); font-size:0.9rem; margin:0;">
                        If your payment is rejected, you'll receive notification. Please contact us at <strong style="color:#fff;">0909 163 9984</strong> or <strong style="color:#fff;">wingmastersilogsamal@gmail.com</strong> to resubmit.
                    </p>
                </div>
                
                <div>
                    <h4 style="color:var(--accent-gold); font-size:0.95rem; margin:0 0 0.5rem 0;">Can I upload multiple screenshots?</h4>
                    <p style="color:rgba(255,255,255,0.6); font-size:0.9rem; margin:0;">
                        Upload the clearest screenshot showing your payment confirmation. If needed, you can upload a new one to replace it.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('payment-screenshot');
    var fileLabel = document.querySelector('.payment-file-label');
    var fileNameDisplay = document.getElementById('file-name-display');

    if (!fileInput) return;

    // Handle file selection
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            var file = this.files[0];
            fileLabel.style.background = 'rgba(232,184,60,0.12)';
            fileLabel.style.borderColor = 'var(--accent-gold)';
            fileNameDisplay.textContent = '✓ ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            fileNameDisplay.style.display = 'block';
        } else {
            fileLabel.style.background = 'rgba(255,255,255,0.03)';
            fileLabel.style.borderColor = 'rgba(232,184,60,0.3)';
            fileNameDisplay.style.display = 'none';
        }
    });

    // Drag & drop
    fileLabel.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = 'var(--accent-gold)';
        this.style.background = 'rgba(232,184,60,0.15)';
    });

    fileLabel.addEventListener('dragleave', function() {
        if (fileInput.files.length === 0) {
            this.style.borderColor = 'rgba(232,184,60,0.3)';
            this.style.background = 'rgba(255,255,255,0.03)';
        }
    });

    fileLabel.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = 'rgba(232,184,60,0.3)';
        this.style.background = 'rgba(255,255,255,0.03)';
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            var event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
