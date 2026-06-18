<?php

declare(strict_types=1);

session_start();

// Simple admin password check
define('ADMIN_PASSWORD', 'Wing@Master2024');
define('ADMIN_AUTHENTICATED', isset($_SESSION['admin_auth']) && $_SESSION['admin_auth'] === true);

$pageTitle = 'Admin Panel - Wing Master';
$loginError = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $password = $_POST['password'] ?? '';
        if ($password === ADMIN_PASSWORD) {
            $_SESSION['admin_auth'] = true;
            $_SESSION['admin_login_time'] = time();
            header('Location: admin.php');
            exit;
        } else {
            $loginError = 'Invalid password. Please try again.';
        }
    }

    if ($action === 'logout' && ADMIN_AUTHENTICATED) {
        session_destroy();
        header('Location: admin.php');
        exit;
    }

    if ($action === 'verify_payment' && ADMIN_AUTHENTICATED) {
        $resId = $_POST['reservation_id'] ?? '';
        $resFile = __DIR__ . '/data/reservations.json';
        if (file_exists($resFile)) {
            $list = json_decode(file_get_contents($resFile), true) ?: [];
            foreach ($list as &$res) {
                if ($res['id'] === $resId) {
                    $res['payment_status'] = 'Verified';
                    $res['status'] = 'Confirmed';
                    $res['verified_at'] = date('Y-m-d H:i:s');
                    break;
                }
            }
            file_put_contents($resFile, json_encode($list, JSON_PRETTY_PRINT));
            header('Location: admin.php');
            exit;
        }
    }

    if ($action === 'reject_payment' && ADMIN_AUTHENTICATED) {
        $resId = $_POST['reservation_id'] ?? '';
        $resFile = __DIR__ . '/data/reservations.json';
        if (file_exists($resFile)) {
            $list = json_decode(file_get_contents($resFile), true) ?: [];
            foreach ($list as &$res) {
                if ($res['id'] === $resId) {
                    $res['payment_status'] = 'Rejected';
                    $res['status'] = 'Cancelled';
                    break;
                }
            }
            file_put_contents($resFile, json_encode($list, JSON_PRETTY_PRINT));
            header('Location: admin.php');
            exit;
        }
    }

    // Handle inventory toggle
    if ($action === 'toggle_inventory' && ADMIN_AUTHENTICATED) {
        $foodId = $_POST['food_id'] ?? '';
        $invFile = __DIR__ . '/data/inventory.json';
        if (file_exists($invFile)) {
            $inventory = json_decode(file_get_contents($invFile), true) ?: [];
            foreach ($inventory as &$item) {
                if ($item['id'] === $foodId) {
                    $item['available'] = !($item['available'] ?? true);
                    break;
                }
            }
            file_put_contents($invFile, json_encode($inventory, JSON_PRETTY_PRINT));
            header('Location: admin.php?tab=inventory');
            exit;
        }
    }
}

// Load all reservations
$allReservations = [];
if (ADMIN_AUTHENTICATED) {
    $resFile = __DIR__ . '/data/reservations.json';
    if (file_exists($resFile)) {
        $allReservations = json_decode(file_get_contents($resFile), true) ?: [];
        // Sort by created_at descending
        usort($allReservations, function($a, $b) {
            return strtotime($b['created_at'] ?? '2000-01-01') - strtotime($a['created_at'] ?? '2000-01-01');
        });
    }
}

// Load inventory
$inventory = [];
if (ADMIN_AUTHENTICATED) {
    $invFile = __DIR__ . '/data/inventory.json';
    if (file_exists($invFile)) {
        $inventory = json_decode(file_get_contents($invFile), true) ?: [];
    }
}

$currentTab = $_GET['tab'] ?? 'reservations';

$currentPage = 'admin';
require_once __DIR__ . '/includes/header.php';

?>

<?php if (!ADMIN_AUTHENTICATED): ?>

<!-- LOGIN PAGE -->
<section style="min-height:100vh; display:flex; align-items:center; justify-content:center; background:var(--bg-base); padding:2rem;">
    <div style="
        max-width:400px; width:100%;
        background:var(--bg-card); border:1px solid rgba(232,184,60,0.1);
        border-radius:8px; padding:2.5rem;
    ">
        <div style="text-align:center; margin-bottom:2rem;">
            <h1 style="font-family:var(--font-display); font-size:1.8rem; color:#fff; margin-bottom:0.25rem;">Admin Panel</h1>
            <p style="color:rgba(255,255,255,0.5); font-size:0.9rem;">Wing Master Reservations</p>
        </div>

        <form method="post" style="display:flex; flex-direction:column; gap:1.2rem;">
            <input type="hidden" name="action" value="login">

            <?php if ($loginError): ?>
            <div style="
                background:rgba(200,50,50,0.12); border:1px solid rgba(200,50,50,0.3);
                color:#ff7070; border-radius:4px; padding:0.75rem 1rem;
                font-size:0.9rem;
            ">
                <?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php endif; ?>

            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <label style="font-size:0.8rem; font-weight:600; color:rgba(255,255,255,0.7); text-transform:uppercase; letter-spacing:0.05em;">Admin Password</label>
                <input type="password" name="password" placeholder="Enter admin password" required style="
                    padding:0.85rem 1rem; background:rgba(255,255,255,0.05);
                    border:1px solid rgba(255,255,255,0.1); border-radius:4px;
                    color:#fff; font-family:inherit; font-size:0.95rem;
                    outline:none; transition:border-color 0.2s;
                " onfocus="this.style.borderColor='var(--accent-gold)'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
            </div>

            <button type="submit" style="
                padding:0.9rem 1.2rem; background:var(--accent-gold); color:#000;
                border:none; border-radius:4px; font-weight:700; font-size:0.9rem;
                cursor:pointer; transition:all 0.2s; text-transform:uppercase;
                letter-spacing:0.05em;
            " onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                Sign In
            </button>
        </form>
    </div>
</section>

<?php else: ?>

<!-- ADMIN DASHBOARD -->
<header class="page-hero page-hero--light transition-fade-in" style="position:relative; padding:3rem 2rem;">
    <div style="text-align:center;">
        <h1 style="font-size:2.5rem; color:#fff;">Admin Panel</h1>
        <p style="color:rgba(255,255,255,0.5); font-size:1.1rem; margin-top:0.5rem;">Manage reservations, payments & inventory</p>
    </div>
</header>

<!-- ADMIN TABS -->
<div style="background:var(--bg-cream); border-bottom:1px solid rgba(232,184,60,0.1); padding:0 2rem;">
    <div class="container" style="max-width:1400px; display:flex; gap:0; border-bottom:2px solid transparent;">
        <a href="admin.php?tab=reservations" style="
            padding:1.2rem 1.5rem; color:<?= $currentTab === 'reservations' ? 'var(--accent-gold)' : 'rgba(255,255,255,0.5)' ?>;
            text-decoration:none; border-bottom:2px solid <?= $currentTab === 'reservations' ? 'var(--accent-gold)' : 'transparent' ?>;
            transition:all 0.2s; font-weight:600; font-size:0.9rem; letter-spacing:0.05em;
        ">
            📋 Reservations
        </a>
        <a href="admin.php?tab=inventory" style="
            padding:1.2rem 1.5rem; color:<?= $currentTab === 'inventory' ? 'var(--accent-gold)' : 'rgba(255,255,255,0.5)' ?>;
            text-decoration:none; border-bottom:2px solid <?= $currentTab === 'inventory' ? 'var(--accent-gold)' : 'transparent' ?>;
            transition:all 0.2s; font-weight:600; font-size:0.9rem; letter-spacing:0.05em;
        ">
            🍽 Inventory
        </a>
        <div style="flex:1;"></div>
        <form method="post" style="display:flex; align-items:center;">
            <input type="hidden" name="action" value="logout">
            <button type="submit" style="
                padding:0.8rem 1.2rem; background:rgba(255,100,100,0.15); color:#ff7070;
                border:1px solid rgba(255,100,100,0.3); border-radius:4px; cursor:pointer;
                font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;
                transition:all 0.2s;
            " onmouseover="this.style.background='rgba(255,100,100,0.25)'" onmouseout="this.style.background='rgba(255,100,100,0.15)'">
                Logout
            </button>
        </form>
    </div>
</div>

<!-- RESERVATIONS TAB -->
<?php if ($currentTab === 'reservations'): ?>
<!-- RESERVATIONS TABLE -->
<section style="background:var(--bg-cream); padding:3rem 2rem;">
    <div class="container" style="max-width:1400px;">
        <?php if (empty($allReservations)): ?>
        <div style="text-align:center; padding:3rem 2rem; color:rgba(255,255,255,0.5);">
            <p style="font-size:1.1rem;">No reservations yet.</p>
        </div>
        <?php else: ?>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:2px solid rgba(232,184,60,0.2);">
                        <th style="text-align:left; padding:1rem; color:rgba(255,255,255,0.6); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Reference</th>
                        <th style="text-align:left; padding:1rem; color:rgba(255,255,255,0.6); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Customer</th>
                        <th style="text-align:left; padding:1rem; color:rgba(255,255,255,0.6); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Date & Time</th>
                        <th style="text-align:left; padding:1rem; color:rgba(255,255,255,0.6); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Party</th>
                        <th style="text-align:left; padding:1rem; color:rgba(255,255,255,0.6); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Pre-ordered Foods</th>
                        <th style="text-align:left; padding:1rem; color:rgba(255,255,255,0.6); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Payment</th>
                        <th style="text-align:left; padding:1rem; color:rgba(255,255,255,0.6); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Screenshot</th>
                        <th style="text-align:left; padding:1rem; color:rgba(255,255,255,0.6); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($allReservations as $res): ?>
                    <tr style="border-bottom:1px solid rgba(232,184,60,0.1); transition:background 0.2s;" onmouseover="this.style.background='rgba(232,184,60,0.05)'" onmouseout="this.style.background='transparent'">
                        <td style="padding:1rem; color:#fff; font-weight:600; font-size:0.9rem; font-family:monospace; color:var(--accent-gold);">
                            <?= htmlspecialchars($res['reference_number'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                        </td>
                        <td style="padding:1rem;">
                            <div style="color:#fff; font-weight:500; font-size:0.95rem;"><?= htmlspecialchars($res['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                            <div style="color:rgba(255,255,255,0.5); font-size:0.8rem;"><?= htmlspecialchars($res['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                        </td>
                        <td style="padding:1rem; color:rgba(255,255,255,0.8); font-size:0.9rem;">
                            <?= htmlspecialchars($res['date'] ?? '', ENT_QUOTES, 'UTF-8') ?><br>
                            <span style="color:rgba(255,255,255,0.5);"><?= htmlspecialchars($res['time'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        </td>
                        <td style="padding:1rem; color:#fff; font-weight:500;">
                            <?= (int)($res['party'] ?? 0) ?> guests
                        </td>
                        <td style="padding:1rem;">
                            <?php 
                                $selectedFoods = $res['selected_foods'] ?? [];
                                if (!empty($selectedFoods)):
                                    $foodNames = [];
                                    foreach ($selectedFoods as $foodId):
                                        foreach ($inventory as $item):
                                            if ($item['id'] === $foodId):
                                                $foodNames[] = $item['name'];
                                                break;
                                            endif;
                                        endforeach;
                                    endforeach;
                                    if (!empty($foodNames)):
                            ?>
                            <div style="font-size:0.8rem; color:rgba(255,255,255,0.8); max-height:60px; overflow-y:auto;">
                                <?php foreach ($foodNames as $name): ?>
                                <div style="padding:0.25rem 0; color:var(--accent-gold);">• <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <span style="color:rgba(255,255,255,0.4);">—</span>
                            <?php endif; else: ?>
                            <span style="color:rgba(255,255,255,0.4);">No pre-orders</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:1rem;">
                            <?php $paymentStatus = $res['payment_status'] ?? 'Pending'; ?>
                            <?php if ($paymentStatus === 'Pending'): ?>
                            <span style="
                                display:inline-block; padding:0.35rem 0.75rem;
                                background:rgba(255,150,50,0.15); color:#ffaa44;
                                border-radius:3px; font-size:0.8rem; font-weight:600;
                            ">Pending</span>
                            <?php elseif ($paymentStatus === 'Verified'): ?>
                            <span style="
                                display:inline-block; padding:0.35rem 0.75rem;
                                background:rgba(100,200,100,0.15); color:#66dd66;
                                border-radius:3px; font-size:0.8rem; font-weight:600;
                            ">Verified ✓</span>
                            <?php elseif ($paymentStatus === 'Rejected'): ?>
                            <span style="
                                display:inline-block; padding:0.35rem 0.75rem;
                                background:rgba(255,100,100,0.15); color:#ff7070;
                                border-radius:3px; font-size:0.8rem; font-weight:600;
                            ">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:1rem;">
                            <?php if (!empty($res['payment_screenshot'])): ?>
                            <a href="data:image;base64,<?= base64_encode(file_get_contents(__DIR__ . '/data/uploads/' . htmlspecialchars($res['payment_screenshot'], ENT_QUOTES, 'UTF-8'))) ?>" target="_blank" style="
                                color:var(--accent-gold); text-decoration:none; font-size:0.85rem;
                                padding:0.35rem 0.6rem; border:1px solid var(--accent-gold);
                                border-radius:3px; display:inline-block;
                                transition:all 0.2s; cursor:pointer;
                            " onmouseover="this.style.background='rgba(232,184,60,0.15)'" onmouseout="this.style.background='transparent'">
                                View Image
                            </a>
                            <?php else: ?>
                            <span style="color:rgba(255,255,255,0.4); font-size:0.85rem;">No upload</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:1rem;">
                            <?php if ($paymentStatus === 'Pending'): ?>
                            <div style="display:flex; gap:0.4rem; flex-wrap:wrap;">
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="action" value="verify_payment">
                                    <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($res['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" style="
                                        padding:0.4rem 0.75rem; background:rgba(100,200,100,0.2);
                                        color:#66dd66; border:1px solid #66dd66;
                                        border-radius:3px; font-size:0.75rem; font-weight:600;
                                        cursor:pointer; transition:all 0.2s;
                                    " onmouseover="this.style.background='rgba(100,200,100,0.3)'" onmouseout="this.style.background='rgba(100,200,100,0.2)'">
                                        Verify
                                    </button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="action" value="reject_payment">
                                    <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($res['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" style="
                                        padding:0.4rem 0.75rem; background:rgba(255,100,100,0.2);
                                        color:#ff7070; border:1px solid #ff7070;
                                        border-radius:3px; font-size:0.75rem; font-weight:600;
                                        cursor:pointer; transition:all 0.2s;
                                    " onmouseover="this.style.background='rgba(255,100,100,0.3)'" onmouseout="this.style.background='rgba(255,100,100,0.2)'">
                                        Reject
                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php endif; ?>

<!-- INVENTORY TAB -->
<?php if ($currentTab === 'inventory'): ?>
<section style="background:var(--bg-cream); padding:3rem 2rem;">
    <div class="container" style="max-width:1400px;">
        <div style="margin-bottom:2rem;">
            <h3 style="font-family:var(--font-display); font-size:1.75rem; color:#fff; margin-bottom:0.4rem;">Food Inventory</h3>
            <p style="color:rgba(255,255,255,0.5); font-size:0.9rem;">Toggle food items to mark as available or out of stock</p>
        </div>

        <?php if (empty($inventory)): ?>
        <div style="text-align:center; padding:3rem 2rem; color:rgba(255,255,255,0.5); background:rgba(0,0,0,0.2); border-radius:4px;">
            <p style="font-size:1.1rem;">No inventory items found.</p>
        </div>
        <?php else: ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:1rem;">
            <?php 
                $currentCat = '';
                foreach ($inventory as $item): 
            ?>
                <div style="
                    background:var(--bg-card); border:1px solid rgba(232,184,60,0.1);
                    border-radius:4px; padding:1.5rem; transition:all 0.2s;
                " onmouseover="this.style.borderColor='rgba(232,184,60,0.3)'" onmouseout="this.style.borderColor='rgba(232,184,60,0.1)'">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem;">
                        <div style="flex:1;">
                            <div style="font-size:0.7rem; color:var(--accent-gold); font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.3rem;">
                                <?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                            <h4 style="color:#fff; font-size:1rem; margin-bottom:0.5rem;">
                                <?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>
                            </h4>
                            <div style="color:rgba(255,255,255,0.6); font-size:0.9rem;">
                                ₱<?= number_format($item['price'], 2) ?>
                            </div>
                        </div>
                        <form style="display:none;">
                            <input type="hidden" name="action" value="toggle_inventory">
                            <input type="hidden" name="food_id" value="<?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <button type="button" onclick="toggleInventory(event, this)" data-food-id="<?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?>" data-available="<?= $item['available'] ? '1' : '0' ?>" style="
                                padding:0.6rem 1rem; 
                                background:<?= $item['available'] ? 'rgba(100,255,100,0.2)' : 'rgba(255,100,100,0.2)' ?>;
                                color:<?= $item['available'] ? '#70ff70' : '#ff7070' ?>;
                                border:1px solid <?= $item['available'] ? 'rgba(100,255,100,0.4)' : 'rgba(255,100,100,0.4)' ?>;
                                border-radius:3px; cursor:pointer; font-size:0.8rem; font-weight:600;
                                text-transform:uppercase; letter-spacing:0.05em; transition:all 0.2s;
                                white-space:nowrap;
                            " onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                <?= $item['available'] ? 'Available' : 'Out of Stock' ?>
                            </button>
                        </form>
                        <button onclick="toggleInventory(event, this)" data-food-id="<?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?>" data-available="<?= $item['available'] ? '1' : '0' ?>" style="
                                padding:0.6rem 1rem; 
                                background:<?= $item['available'] ? 'rgba(100,255,100,0.2)' : 'rgba(255,100,100,0.2)' ?>;
                                color:<?= $item['available'] ? '#70ff70' : '#ff7070' ?>;
                                border:1px solid <?= $item['available'] ? 'rgba(100,255,100,0.4)' : 'rgba(255,100,100,0.4)' ?>;
                                border-radius:3px; cursor:pointer; font-size:0.8rem; font-weight:600;
                                text-transform:uppercase; letter-spacing:0.05em; transition:all 0.2s;
                                white-space:nowrap;
                            " onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                <?= $item['available'] ? 'Available' : 'Out of Stock' ?>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php endif; ?>

<script>
function toggleInventory(event, button) {
    event.preventDefault();
    const foodId = button.getAttribute('data-food-id');
    const isAvailable = button.getAttribute('data-available') === '1';
    
    const formData = new FormData();
    formData.append('action', 'toggle_inventory');
    formData.append('food_id', foodId);
    
    fetch('admin.php?tab=inventory', {
        method: 'POST',
        body: formData
    })
    .then(() => {
        // Update button appearance immediately
        const newAvailable = !isAvailable;
        button.setAttribute('data-available', newAvailable ? '1' : '0');
        button.textContent = newAvailable ? 'Available' : 'Out of Stock';
        button.style.background = newAvailable ? 'rgba(100,255,100,0.2)' : 'rgba(255,100,100,0.2)';
        button.style.color = newAvailable ? '#70ff70' : '#ff7070';
        button.style.borderColor = newAvailable ? 'rgba(100,255,100,0.4)' : 'rgba(255,100,100,0.4)';
    })
    .catch(err => console.error('Error:', err));
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
