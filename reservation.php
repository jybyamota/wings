<?php

declare(strict_types=1);

session_start();

$pageTitle = 'Reservation - Wing Master';
$currentPage = 'reservation';
$navScrolled = false;

// ---------- flat-file auth ----------
define('USERS_FILE', __DIR__ . '/data/users.json');

function load_users(): array {
    if (!file_exists(USERS_FILE)) return [];
    $json = file_get_contents(USERS_FILE);
    return $json ? json_decode($json, true) : [];
}

function save_users(array $users): void {
    if (!is_dir(dirname(USERS_FILE))) mkdir(dirname(USERS_FILE), 0755, true);
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

// ---------- handle POST ----------
$loginError = '';
$registerError = '';
$activeTab = 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $activeTab = 'login';
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $users    = load_users();

        if ($email === '' || $password === '') {
            $loginError = 'Please fill in all fields.';
        } elseif (!isset($users[$email])) {
            $loginError = 'No account found with that email.';
        } elseif (!password_verify($password, $users[$email]['hash'])) {
            $loginError = 'Incorrect password. Please try again.';
        } else {
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name']  = $users[$email]['name'];
            header('Location: reservation.php');
            exit;
        }
    }

    if ($action === 'register') {
        $activeTab = 'register';
        $name      = trim($_POST['name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $password  = $_POST['password'] ?? '';
        $confirm   = $_POST['confirm'] ?? '';
        $users     = load_users();

        if ($name === '' || $email === '' || $password === '') {
            $registerError = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $registerError = 'Please enter a valid email address.';
        } elseif (strlen($password) < 6) {
            $registerError = 'Password must be at least 6 characters.';
        } elseif ($password !== $confirm) {
            $registerError = 'Passwords do not match.';
        } elseif (isset($users[$email])) {
            $registerError = 'An account with that email already exists.';
        } else {
            $users[$email] = [
                'name'  => $name,
                'phone' => $phone,
                'hash'  => password_hash($password, PASSWORD_DEFAULT),
            ];
            save_users($users);
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name']  = $name;
            header('Location: reservation.php');
            exit;
        }
    }

    if ($action === 'logout') {
        session_destroy();
        header('Location: reservation.php');
        exit;
    }

    // ---------- reservation submit ----------
    if ($action === 'reserve' && isset($_SESSION['user_email'])) {
        $party   = (int)($_POST['party'] ?? 0);
        $date    = trim($_POST['res_date'] ?? '');
        $time    = trim($_POST['res_time'] ?? '');
        $notes   = trim($_POST['notes'] ?? '');

        if ($party < 1 || $date === '' || $time === '') {
            $reserveError = 'Please complete all required fields.';
        } else {
            // Generate reference number: WM-YYYYMMDD-XXX
            $dateStr = date('Ymd');
            $refCount = 1;
            $resFile = __DIR__ . '/data/reservations.json';
            $list    = file_exists($resFile) ? (json_decode(file_get_contents($resFile), true) ?: []) : [];
            
            // Count reservations for today
            foreach ($list as $res) {
                if (strpos($res['reference_number'] ?? '', 'WM-' . $dateStr) === 0) {
                    $refCount++;
                }
            }
            $refNumber = 'WM-' . $dateStr . '-' . str_pad((string)$refCount, 3, '0', STR_PAD_LEFT);
            
            // Handle file upload
            $paymentScreenshot = null;
            $uploadDir = __DIR__ . '/data/uploads';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            if (isset($_FILES['gcash_screenshot']) && $_FILES['gcash_screenshot']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['gcash_screenshot'];
                $allowed = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($file['type'], $allowed) && $file['size'] <= 5 * 1024 * 1024) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = $refNumber . '.' . $ext;
                    $filepath = $uploadDir . '/' . $filename;
                    if (move_uploaded_file($file['tmp_name'], $filepath)) {
                        $paymentScreenshot = $filename;
                    }
                }
            }
            
            // Get selected foods
            $selectedFoods = isset($_POST['foods']) ? (array)$_POST['foods'] : [];
            
            $list[]  = [
                'id'    => uniqid('res_', true),
                'reference_number' => $refNumber,
                'email' => $_SESSION['user_email'],
                'name'  => $_SESSION['user_name'],
                'party' => $party,
                'date'  => $date,
                'time'  => $time,
                'notes' => $notes,
                'selected_foods' => $selectedFoods,
                'status'=> 'Pending Payment',
                'payment_screenshot' => $paymentScreenshot,
                'payment_status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            if (!is_dir(__DIR__ . '/data')) mkdir(__DIR__ . '/data', 0755, true);
            file_put_contents($resFile, json_encode($list, JSON_PRETTY_PRINT));
            $_SESSION['last_reference'] = $refNumber;
            header('Location: reservation.php?booked=1');
            exit;
        }
    }
}

$isLoggedIn   = isset($_SESSION['user_email']);
$reserveError = $reserveError ?? '';
$booked       = isset($_GET['booked']);

// Load this user's reservations
$myReservations = [];
if ($isLoggedIn) {
    $resFile = __DIR__ . '/data/reservations.json';
    if (file_exists($resFile)) {
        $all = json_decode(file_get_contents($resFile), true) ?: [];
        foreach ($all as $r) {
            if (($r['email'] ?? '') === $_SESSION['user_email']) {
                $myReservations[] = $r;
            }
        }
        $myReservations = array_reverse($myReservations);
    }
}

require_once __DIR__ . '/includes/header.php';

?>

<?php if (!$isLoggedIn): ?>
    <!-- ===== LOGIN / REGISTER PAGE ===== -->
    <section class="auth-page">
        <div class="auth-container">

            <!-- Left: Branding panel -->
            <div class="auth-brand">
                <div class="auth-brand-overlay"></div>
                <div class="auth-brand-content">
                    <img src="images/logo.png" alt="Wing Master" class="auth-brand-logo">
                    <h2>Reserve Your<br>Table Today</h2>
                    <p>Sign in or create an account to book your table for birthdays, barkada nights, and family celebrations.</p>
                    <div class="auth-brand-features">
                        <div class="auth-feature">
                            <span class="auth-feature-icon">âœ¦</span>
                            <span>Quick &amp; easy booking</span>
                        </div>
                        <div class="auth-feature">
                            <span class="auth-feature-icon">âœ¦</span>
                            <span>Manage your reservations</span>
                        </div>
                        <div class="auth-feature">
                            <span class="auth-feature-icon">âœ¦</span>
                            <span>Exclusive member promos</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Form panel -->
            <div class="auth-form-panel">
                <!-- Tab switcher -->
                <div class="auth-tabs" id="auth-tabs">
                    <button type="button" class="auth-tab <?= $activeTab === 'login' ? 'is-active' : '' ?>" data-tab="login" id="tab-login-btn">Sign In</button>
                    <button type="button" class="auth-tab <?= $activeTab === 'register' ? 'is-active' : '' ?>" data-tab="register" id="tab-register-btn">Create Account</button>
                </div>

                <!-- ===== LOGIN FORM ===== -->
                <form method="post" class="auth-form <?= $activeTab === 'login' ? 'is-active' : '' ?>" id="login-form" autocomplete="off">
                    <input type="hidden" name="action" value="login">
                    <div class="auth-form-header">
                        <h3>Welcome Back</h3>
                        <p>Enter your credentials to access your account</p>
                    </div>
                    <?php if ($loginError): ?>
                        <div class="auth-alert auth-alert--error" id="login-error">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                            <?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                    <div class="auth-field">
                        <label for="login-email">Email Address</label>
                        <input type="email" id="login-email" name="email" placeholder="you@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="auth-field">
                        <label for="login-password">Password</label>
                        <div class="auth-password-wrap">
                            <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
                            <button type="button" class="auth-eye" aria-label="Show password" data-target="login-password">
                                <svg class="eye-show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-hide" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="auth-submit" id="login-submit-btn">
                        Sign In
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                    <p class="auth-switch">Don't have an account? <button type="button" class="auth-switch-link" data-switch="register">Create one</button></p>
                </form>

                <!-- ===== REGISTER FORM ===== -->
                <form method="post" class="auth-form <?= $activeTab === 'register' ? 'is-active' : '' ?>" id="register-form" autocomplete="off">
                    <input type="hidden" name="action" value="register">
                    <div class="auth-form-header">
                        <h3>Create Account</h3>
                        <p>Sign up to start reserving tables at Wing Master</p>
                    </div>
                    <?php if ($registerError): ?>
                        <div class="auth-alert auth-alert--error" id="register-error">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                            <?= htmlspecialchars($registerError, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                    <div class="auth-field">
                        <label for="register-name">Full Name <span class="required">*</span></label>
                        <input type="text" id="register-name" name="name" placeholder="Juan Dela Cruz" required value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="auth-field">
                        <label for="register-email">Email Address <span class="required">*</span></label>
                        <input type="email" id="register-email" name="email" placeholder="you@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="auth-field">
                        <label for="register-phone">Phone Number</label>
                        <input type="tel" id="register-phone" name="phone" placeholder="0909 163 9984" value="<?= htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="auth-field-row">
                        <div class="auth-field">
                            <label for="register-password">Password <span class="required">*</span></label>
                            <div class="auth-password-wrap">
                                <input type="password" id="register-password" name="password" placeholder="Min 6 chars" required minlength="6">
                                <button type="button" class="auth-eye" aria-label="Show password" data-target="register-password">
                                    <svg class="eye-show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg class="eye-hide" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="register-confirm">Confirm <span class="required">*</span></label>
                            <input type="password" id="register-confirm" name="confirm" placeholder="Re-enter" required minlength="6">
                        </div>
                    </div>
                    <button type="submit" class="auth-submit" id="register-submit-btn">
                        Create Account
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                    <p class="auth-switch">Already have an account? <button type="button" class="auth-switch-link" data-switch="login">Sign in</button></p>
                </form>
            </div>
        </div>
    </section>

<?php else: ?>
<!-- ===== LOGGED-IN RESERVATION PAGE ===== -->

<!-- HERO -->
<header class="page-hero page-hero--light transition-fade-in" style="position:relative;">

    <div class="container page-hero-inner slide-transition-up" style="text-align:center;">
        <p class="hero-eyebrow" style="animation-delay:0.1s;">Welcome back, <?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?></p>
        <h1 style="animation-delay:0.2s;">Book a Table</h1>
        <p class="hero-lead" style="animation-delay:0.3s; max-width:560px; margin:0 auto 2.5rem;">
            Celebrate every moment at Wing Master &mdash; birthdays, barkada nights, family gatherings, and more.
        </p>
        <div class="hero-actions" style="animation-delay:0.4s; justify-content:center;">
            <button class="btn btn-primary" id="open-reservation-btn" onclick="document.getElementById('res-modal').classList.add('is-open')">
                Reserve a Table
            </button>
        </div>
    </div>
</header>

<!-- ===== RESERVATION MODAL ===== -->
<div id="res-modal" class="res-modal-overlay" onclick="if(event.target===this)this.classList.remove('is-open')">
    <div class="res-modal-box">
        <button class="res-modal-close" onclick="document.getElementById('res-modal').classList.remove('is-open')" aria-label="Close">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>

        <?php if ($booked): ?>
        <!-- SUCCESS STATE -->
        <div class="res-success">
            <div class="res-success-icon">&#10022;</div>
            <h2>Reservation Submitted!</h2>
            <p>Your table request has been received. We'll confirm your booking shortly via phone or email.</p>
            
            <?php if (isset($_SESSION['last_reference'])): ?>
            <div style="
                background:rgba(232,184,60,0.12); border:1px solid var(--accent-gold); border-radius:4px;
                padding:1rem; margin:1rem 0; text-align:center;
            ">
                <p style="font-size:0.75rem; color:rgba(255,255,255,0.6); margin-bottom:0.3rem;">Your Reference Number</p>
                <p style="font-size:1.5rem; font-weight:700; color:var(--accent-gold); font-family:monospace; letter-spacing:0.2em;"><?= htmlspecialchars($_SESSION['last_reference'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <?php endif; ?>
            
            <p style="color:var(--accent-gold); font-size:0.85rem; margin-top:1rem;"><strong>Next Steps:</strong></p>
            <ol style="color:rgba(255,255,255,0.7); font-size:0.85rem; margin:0.5rem 0 1.5rem 1.2rem; line-height:1.6;">
                <li>Send &#8369;500 downpayment via GCash to <strong><?= SITE_PHONE ?></strong></li>
                <li>Include your reference number <strong><?= htmlspecialchars($_SESSION['last_reference'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong> in the GCash message</li>
                <li>Go to <a href="upload-payment.php" style="color:var(--accent-gold); text-decoration:underline; font-weight:600;">Upload Payment Proof</a> to submit your payment screenshot</li>
                <li>We'll verify and confirm your booking</li>
            </ol>
            
            <a href="upload-payment.php" class="btn btn-primary" style="margin-top:1.5rem; display:inline-block; text-decoration:none; padding:0.85rem 1.5rem; margin-right:0.5rem;">
                Upload Payment Proof →
            </a>
            <button class="btn" style="margin-top:1.5rem; background:none; border:1px solid rgba(232,184,60,0.4); color:rgba(232,184,60,0.8); padding:0.85rem 1.5rem; cursor:pointer;" onclick="document.getElementById('res-modal').classList.remove('is-open'); window.location='reservation.php'">
                Done
            </button>
        </div>
        <?php else: ?>

        <form method="post" id="res-form" onsubmit="return validateResForm()" enctype="multipart/form-data">
            <input type="hidden" name="action" value="reserve">

            <div class="res-modal-header">
                <h2>Reserve a Table</h2>
                <p>Choose your party size, date, and time below.</p>
            </div>

            <?php if ($reserveError): ?>
            <div class="res-error-msg">âš  <?= htmlspecialchars($reserveError, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <!-- PARTY SIZE -->
            <div class="res-section-label">Party Size</div>
            <div class="party-grid" id="party-grid">
                <?php foreach ([2,3,4,5,6,7,8] as $n): ?>
                <button type="button" class="party-tile" data-val="<?= $n ?>" onclick="selectParty(<?= $n ?>)">
                    <span class="party-num"><?= $n ?></span>
                    <span class="party-lbl">Guest<?= $n > 1 ? 's' : '' ?></span>
                </button>
                <?php endforeach; ?>
                <button type="button" class="party-tile party-tile--custom" onclick="selectParty('custom')">
                    <span class="party-num">+</span>
                    <span class="party-lbl">Custom</span>
                </button>
            </div>
            <input type="hidden" name="party" id="party-val" required>
            <div id="custom-party-wrap" style="display:none; margin-top:0.75rem;">
                <input type="number" id="custom-party-input" min="9" max="100" placeholder="Enter number of guests" style="
                    width:100%; padding:0.7rem 1rem; background:rgba(255,255,255,0.06);
                    border:1px solid rgba(255,255,255,0.12); border-radius:4px;
                    color:#fff; font-family:inherit; font-size:0.9rem; outline:none;
                ">
            </div>

            <!-- DATE & TIME -->
            <div class="res-row">
                <div class="res-field">
                    <label class="res-section-label" for="res-date">Date</label>
                    <input type="date" name="res_date" id="res-date" required class="res-input">
                </div>
                <div class="res-field">
                    <label class="res-section-label" for="res-time">Time</label>
                    <select name="res_time" id="res-time" required class="res-input">
                        <option value="">Select time</option>
                        <option>10:00 AM</option><option>10:30 AM</option>
                        <option>11:00 AM</option><option>11:30 AM</option>
                        <option>12:00 PM</option><option>12:30 PM</option>
                        <option>1:00 PM</option><option>1:30 PM</option>
                        <option>2:00 PM</option><option>2:30 PM</option>
                        <option>3:00 PM</option><option>3:30 PM</option>
                        <option>4:00 PM</option><option>4:30 PM</option>
                        <option>5:00 PM</option><option>5:30 PM</option>
                        <option>6:00 PM</option><option>6:30 PM</option>
                        <option>7:00 PM</option><option>7:30 PM</option>
                        <option>8:00 PM</option>
                    </select>
                </div>
            </div>

            <!-- SPECIAL NOTES -->
            <div class="res-field" style="margin-top:0.5rem;">
                <label class="res-section-label" for="res-notes">Special Requests <span style="opacity:0.4; font-weight:400;">(optional)</span></label>
                <textarea name="notes" id="res-notes" rows="2" placeholder="Allergies, celebrations, seating preferences…" class="res-input" style="resize:vertical;"></textarea>
            </div>

            <!-- AVAILABLE FOOD SELECTION -->
            <div class="res-field" style="margin-top:1.5rem;">
                <label class="res-section-label">Pre-order Foods <span style="opacity:0.4; font-weight:400;">(optional)</span></label>
                <p style="font-size:0.8rem; color:rgba(255,255,255,0.6); margin-bottom:0.75rem;">Select items you'd like available when you arrive (helps us prepare)</p>
                <div id="food-list" style="display:grid; grid-template-columns:1fr; gap:0.5rem; max-height:300px; overflow-y:auto; padding-right:0.5rem;">
                    <!-- Food items will be loaded here by JS -->
                </div>
            </div>

            <!-- GCASH SCREENSHOT UPLOAD -->
            <div class="res-field" style="margin-top:1.5rem;">
                <label class="res-section-label" for="gcash-screenshot">Upload GCash Screenshot <span style="opacity:0.4; font-weight:400;">(optional)</span></label>
                <div class="file-upload-wrapper">
                    <input type="file" id="gcash-screenshot" name="gcash_screenshot" accept="image/*" class="file-input-hidden" style="display:none;">
                    <label for="gcash-screenshot" class="file-upload-label">
                        <div style="display:flex; flex-direction:column; align-items:center; gap:0.5rem; pointer-events:none;">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="opacity:0.6;">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <span style="font-size:0.85rem; color:rgba(255,255,255,0.6);">Click to upload screenshot or drag & drop</span>
                            <span style="font-size:0.7rem; color:rgba(255,255,255,0.4);">PNG, JPG, GIF (max 5MB)</span>
                        </div>
                    </label>
                    <div id="file-name-display" style="margin-top:0.5rem; font-size:0.8rem; color:var(--accent-gold); display:none;"></div>
                </div>
            </div>

            <!-- DOWNPAYMENT NOTICE -->
            <div class="res-downpayment">
                <div class="res-dp-icon">&#8369;</div>
                <div>
                    <strong>&#8369;500 Downpayment Required</strong>
                    <p>Send via GCash to <strong><?= SITE_PHONE ?></strong> after submitting. Your reservation will be confirmed once payment is received.</p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:1rem; padding:1rem; font-size:0.8rem;">
                Confirm Reservation
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>

<!-- PAST RESERVATIONS -->
<?php if (!empty($myReservations)): ?>
<section style="background:var(--bg-cream); padding:4rem 2rem;">
    <div class="container" style="max-width:860px;">
        <h3 style="font-family:var(--font-display); font-size:1.75rem; color:#fff; margin-bottom:0.4rem;">My Reservations</h3>
        <p style="color:var(--text-muted); font-size:0.88rem; margin-bottom:2rem;">Your recent booking history</p>
        <div style="display:flex; flex-direction:column; gap:0.85rem;">
        <?php foreach ($myReservations as $r): ?>
            <div style="
                background:var(--bg-card); border:1px solid rgba(232,184,60,0.1);
                border-radius:4px; padding:1.15rem 1.5rem;
                display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;
            ">
                <div>
                    <div style="font-size:0.65rem; color:var(--accent-gold); letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.3rem; font-weight:700;">
                        Ref: <?= htmlspecialchars($r['reference_number'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <div style="font-size:0.72rem; color:rgba(255,255,255,0.5); margin-bottom:0.25rem;">
                        <?= htmlspecialchars($r['date'], ENT_QUOTES, 'UTF-8') ?> Â· <?= htmlspecialchars($r['time'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <div style="font-size:1rem; color:#fff; font-weight:500;">
                        Table for <?= (int)$r['party'] ?> Guest<?= $r['party'] > 1 ? 's' : '' ?>
                    </div>
                    <?php if (!empty($r['selected_foods'])): ?>
                    <div style="font-size:0.75rem; color:var(--accent-gold); margin-top:0.4rem; padding:0.4rem; background:rgba(232,184,60,0.1); border-radius:2px;">
                        đŸœ Pre-ordered foods selected
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($r['notes'])): ?>
                    <div style="font-size:0.8rem; color:var(--text-muted); margin-top:0.25rem;"><?= htmlspecialchars($r['notes'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>
                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:0.35rem;">
                    <span style="
                        font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase;
                        padding:0.35rem 0.85rem; border-radius:2px;
                        background:rgba(232,184,60,0.12); color:var(--accent-gold);
                    "><?= htmlspecialchars($r['status'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php if (($r['payment_status'] ?? '') === 'Pending'): ?>
                    <span style="
                        font-size:0.65rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase;
                        padding:0.25rem 0.6rem; border-radius:2px;
                        background:rgba(255,100,100,0.12); color:#ff7070;
                    ">Payment Pending</span>
                    <?php elseif (($r['payment_status'] ?? '') === 'Verified'): ?>
                    <span style="
                        font-size:0.65rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase;
                        padding:0.25rem 0.6rem; border-radius:2px;
                        background:rgba(100,255,100,0.12); color:#70ff70;
                    ">Payment Verified</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php endif; ?>

<?php if (!$isLoggedIn): ?>
<script>
(function () {
    var loginForm    = document.getElementById('login-form');
    var registerForm = document.getElementById('register-form');
    var tabs         = document.querySelectorAll('.auth-tab');
    var switchLinks  = document.querySelectorAll('.auth-switch-link');
    function showTab(name) {
        tabs.forEach(function (t) { t.classList.toggle('is-active', t.getAttribute('data-tab') === name); });
        loginForm.classList.toggle('is-active', name === 'login');
        registerForm.classList.toggle('is-active', name === 'register');
    }
    tabs.forEach(function (t) { t.addEventListener('click', function () { showTab(t.getAttribute('data-tab')); }); });
    switchLinks.forEach(function (l) { l.addEventListener('click', function (e) { e.preventDefault(); showTab(l.getAttribute('data-switch')); }); });
    document.querySelectorAll('.auth-eye').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var input = document.getElementById(btn.getAttribute('data-target'));
            if (!input) return;
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.querySelector('.eye-show').style.display = show ? 'none' : '';
            btn.querySelector('.eye-hide').style.display = show ? '' : 'none';
        });
    });
})();
</script>
<?php endif; ?>

<?php if ($isLoggedIn): ?>
<script>
// Open modal if there's a booking error
<?php if ($reserveError): ?>
document.getElementById('res-modal').classList.add('is-open');
<?php endif; ?>
// Open modal after successful booking
<?php if ($booked): ?>
document.getElementById('res-modal').classList.add('is-open');
<?php endif; ?>

// Set minimum date to today
var dateInput = document.getElementById('res-date');
if (dateInput) {
    var today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);
}

// Party tile selection
function selectParty(val) {
    document.querySelectorAll('.party-tile').forEach(function(t){ t.classList.remove('is-selected'); });
    var customWrap = document.getElementById('custom-party-wrap');
    var partyVal   = document.getElementById('party-val');
    var customInput = document.getElementById('custom-party-input');
    if (val === 'custom') {
        document.querySelector('.party-tile--custom').classList.add('is-selected');
        customWrap.style.display = 'block';
        customInput.focus();
        customInput.addEventListener('input', function() {
            partyVal.value = this.value;
        });
        partyVal.value = customInput.value || '';
    } else {
        document.querySelector('[data-val="' + val + '"]').classList.add('is-selected');
        customWrap.style.display = 'none';
        partyVal.value = val;
    }
}

function validateResForm() {
    var party = document.getElementById('party-val').value;
    var date  = document.getElementById('res-date').value;
    var time  = document.getElementById('res-time').value;
    if (!party || party < 1) { alert('Please select a party size.'); return false; }
    if (!date) { alert('Please select a date.'); return false; }
    if (!time) { alert('Please select a time.'); return false; }
    return true;
}

// File upload handler + Food loader
document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('gcash-screenshot');
    var fileLabel = document.querySelector('.file-upload-label');
    var fileNameDisplay = document.getElementById('file-name-display');

    if (!fileInput) return;

    // Handle file selection
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            var file = this.files[0];
            fileLabel.classList.add('has-file');
            fileNameDisplay.textContent = 'â"‚ ' + file.name;
            fileNameDisplay.style.display = 'block';
        } else {
            fileLabel.classList.remove('has-file');
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

    // Load available foods
    fetch('data/inventory.json')
        .then(response => response.json())
        .then(foods => {
            var foodList = document.getElementById('food-list');
            if (!foodList) return;
            
            var currentCategory = '';
            foods.forEach(food => {
                // Add category header if different
                if (food.category !== currentCategory) {
                    currentCategory = food.category;
                    var header = document.createElement('div');
                    header.style.cssText = 'grid-column:1/-1; font-size:0.75rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:rgba(232,184,60,0.7); margin-top:0.5rem; margin-bottom:0.3rem;';
                    header.textContent = currentCategory;
                    foodList.appendChild(header);
                }
                
                var label = document.createElement('label');
                label.style.cssText = 'display:flex; align-items:center; padding:0.6rem; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:3px; cursor:pointer; transition:all 0.2s;';
                
                var checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'foods';
                checkbox.value = food.id;
                checkbox.disabled = !food.available;
                checkbox.style.cssText = 'margin-right:0.6rem; cursor:pointer; accent-color:var(--accent-gold);';
                
                var span = document.createElement('span');
                span.style.cssText = 'flex:1; font-size:0.85rem; ' + (food.available ? '' : 'opacity:0.4; color:rgba(255,100,100,0.6);');
                span.innerHTML = food.name + ' <span style="color:rgba(255,255,255,0.4); margin-left:0.3rem;">(₱' + food.price + ')</span>' + (food.available ? '' : ' <span style="color:#ff7070; font-size:0.7rem; margin-left:0.3rem;">OUT OF STOCK</span>');
                
                label.appendChild(checkbox);
                label.appendChild(span);
                
                label.addEventListener('mouseenter', function() {
                    if (food.available) this.style.borderColor = 'rgba(232,184,60,0.3)';
                });
                label.addEventListener('mouseleave', function() {
                    this.style.borderColor = 'rgba(255,255,255,0.08)';
                });
                
                foodList.appendChild(label);
            });
        })
        .catch(err => console.log('Could not load food inventory'));
});

</script>

<style>
/* ===== RESERVATION MODAL ===== */
.res-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.75);
    backdrop-filter: blur(6px);
    z-index: 2000;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}
.res-modal-overlay.is-open {
    display: flex;
    animation: resFadeIn 0.25s ease;
}
@keyframes resFadeIn {
    from { opacity:0; }
    to   { opacity:1; }
}
.res-modal-box {
    background: #111310;
    border: 1px solid rgba(232,184,60,0.18);
    border-radius: 4px;
    width: 100%;
    max-width: 560px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 2.25rem 2.5rem;
    position: relative;
    animation: resSlideUp 0.3s ease;
}
@keyframes resSlideUp {
    from { opacity:0; transform:translateY(24px); }
    to   { opacity:1; transform:translateY(0); }
}
.res-modal-close {
    position: absolute;
    top: 1.1rem;
    right: 1.1rem;
    background: none;
    border: none;
    color: rgba(255,255,255,0.35);
    cursor: pointer;
    padding: 0.25rem;
    transition: color 0.2s;
}
.res-modal-close:hover { color: var(--accent-gold); }
.res-modal-header {
    margin-bottom: 1.5rem;
}
.res-modal-header h2 {
    font-family: var(--font-display);
    font-size: 1.9rem;
    color: #fff;
    margin-bottom: 0.25rem;
}
.res-modal-header p {
    color: rgba(255,255,255,0.4);
    font-size: 0.88rem;
}
.res-section-label {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.4);
    margin-bottom: 0.65rem;
    display: block;
}
/* Party grid */
.party-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.65rem;
    margin-bottom: 0.5rem;
}
.party-tile {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.09);
    border-radius: 4px;
    padding: 0.85rem 0.5rem;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.2rem;
    transition: all 0.2s;
    font-family: inherit;
}
.party-tile:hover {
    border-color: rgba(232,184,60,0.4);
    background: rgba(232,184,60,0.06);
}
.party-tile.is-selected {
    border-color: var(--accent-gold);
    background: rgba(232,184,60,0.12);
}
.party-num {
    font-family: var(--font-display);
    font-size: 1.5rem;
    color: #fff;
    line-height: 1;
}
.party-tile.is-selected .party-num { color: var(--accent-gold); }
.party-lbl {
    font-size: 0.6rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.35);
}
/* Date/time row */
.res-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.85rem;
    margin-top: 1.25rem;
}
.res-field { display: flex; flex-direction: column; }
.res-input {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 4px;
    padding: 0.75rem 1rem;
    color: #fff;
    font-family: inherit;
    font-size: 0.9rem;
    outline: none;
    width: 100%;
    transition: border-color 0.2s;
    color-scheme: dark;
}
.res-input:focus { border-color: var(--accent-gold); }
.res-input option { background: #111310; }
/* Downpayment notice */
.res-downpayment {
    margin-top: 1.25rem;
    background: rgba(232,184,60,0.08);
    border: 1px solid rgba(232,184,60,0.2);
    border-radius: 4px;
    padding: 1rem 1.15rem;
    display: flex;
    gap: 0.85rem;
    align-items: flex-start;
}
.res-dp-icon {
    font-size: 1.3rem;
    color: var(--accent-gold);
    font-weight: 700;
    min-width: 1.5rem;
    line-height: 1.3;
}
.res-downpayment strong { color: #fff; font-size: 0.9rem; }
.res-downpayment p { color: rgba(255,255,255,0.5); font-size: 0.82rem; margin-top:0.25rem; line-height:1.5; }
/* Error */
.res-error-msg {
    background: rgba(200,50,50,0.12);
    border: 1px solid rgba(200,50,50,0.3);
    color: #ff7070;
    border-radius: 4px;
    padding: 0.65rem 1rem;
    font-size: 0.84rem;
    margin-bottom: 1rem;
}
/* Success */
.res-success {
    text-align: center;
    padding: 1rem 0;
}
.res-success-icon {
    font-size: 2.5rem;
    color: var(--accent-gold);
    margin-bottom: 1rem;
}
.res-success h2 {
    font-family: var(--font-display);
    font-size: 2rem;
    color: #fff;
    margin-bottom: 0.75rem;
}
.res-success p { color: rgba(255,255,255,0.55); font-size:0.9rem; line-height:1.6; }
/* File upload */
.file-upload-wrapper { position: relative; }
.file-upload-label {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 120px;
    background: rgba(255,255,255,0.03);
    border: 2px dashed rgba(232,184,60,0.3);
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}
.file-upload-label:hover {
    background: rgba(255,255,255,0.05);
    border-color: var(--accent-gold);
}
.file-upload-label.has-file {
    background: rgba(232,184,60,0.08);
    border-color: var(--accent-gold);
}
@media (max-width: 520px) {
    .res-modal-box { padding: 1.5rem 1.25rem; }
    .party-grid { grid-template-columns: repeat(4, 1fr); }
    .res-row { grid-template-columns: 1fr; }
}
</style>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
