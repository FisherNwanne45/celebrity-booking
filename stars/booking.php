<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$id = $_GET['id'] ?? 0;
$celebrity = getCelebrityById($pdo, $id);

if (!$celebrity) {
    header("Location: index.php");
    exit;
}

$paymentMethods = getPaymentMethods($pdo);

// Initialize variables
$error = '';
$formData = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'date' => '',
    'fan_card' => 'Regular', // Default to Regular
    'pay' => '',
    'details' => '',
    'merchandise' => [
        'T-shirt' => 0,
        'Face Cap' => 0
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid form submission. Please try again.";
    } else {
        // Basic validation
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $fan_card = trim($_POST['fan_card'] ?? '');
        $pay = trim($_POST['pay'] ?? '');
        $details = trim($_POST['details'] ?? '');
        $merchandise = [
            'T-shirt' => isset($_POST['merchandise']['T-shirt']) ? (int)$_POST['merchandise']['T-shirt'] : 0,
            'Face Cap' => isset($_POST['merchandise']['Face Cap']) ? (int)$_POST['merchandise']['Face Cap'] : 0
        ];

        $errors = [];

        if (empty($name)) $errors[] = "Name is required";
        if (empty($fan_card)) $errors[] = "Fan Card Selection is required";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
        if (empty($phone)) $errors[] = "Phone number is required";
        if (empty($date) || strtotime($date) < time()) $errors[] = "Valid future date is required";
        if (empty($details)) $errors[] = "Event details are required";
        if (empty($pay)) $errors[] = "Payment method is required";

        if (empty($errors)) {
            $data = [
                'celebrity_id' => $id,
                'user_name' => htmlspecialchars($name),
                'user_email' => htmlspecialchars($email),
                'user_phone' => htmlspecialchars($phone),
                'event_date' => htmlspecialchars($date),
                'fan_card' => htmlspecialchars($fan_card),
                'pay' => htmlspecialchars($pay),
                'event_details' => htmlspecialchars($details),
                'merchandise' => json_encode($merchandise)
            ];

            if (createBooking($pdo, $data)) {
                require_once '../includes/mailer.php';
                if (sendBookingConfirmation($pdo, $data, $celebrity)) {
                    header("Location: booking-success.php");
                    exit;
                } else {
                    $error = "Booking created, but failed to send confirmation email.";
                }
            } else {
                $error = "Failed to create booking. Please try again.";
            }
        } else {
            $error = implode("<br>", $errors);
            // Keep form data for repopulation
            $formData = [
                'name' => htmlspecialchars($name),
                'email' => htmlspecialchars($email),
                'phone' => htmlspecialchars($phone),
                'date' => htmlspecialchars($date),
                'fan_card' => htmlspecialchars($fan_card),
                'pay' => htmlspecialchars($pay),
                'details' => htmlspecialchars($details),
                'merchandise' => $merchandise
            ];
        }
    }
}
require_once 'header.php';

// Calculate prices for fan card options
$regularPrice = $celebrity['fee'];
$vipPrice = $celebrity['fee'] * 1.10;
$premiumPrice = $celebrity['fee'] * 1.25;
$goldPrice = $celebrity['fee'] * 1.50;
?>

<div class="container py-5" style="margin-top: 80px;">
    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"> <?= htmlspecialchars($celebrity['name']) ?> Fan Membership</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" id="bookingForm">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                        <div class="mb-3">
                            <label for="name" class="form-label">Your Full Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="<?= $formData['name'] ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= $formData['email'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    value="<?= $formData['phone'] ?>" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Event Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="<?= $formData['date'] ?>" required
                                    min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="fan_card" class="form-label">Select Fan Card</label>
                                <select name="fan_card" id="fan_card" class="form-select" onchange="updateSummary()">
                                    <option value="Regular" data-price="<?= $regularPrice ?>"
                                        <?= ($formData['fan_card'] == 'Regular') ? 'selected' : '' ?>>
                                        Regular — $<?= number_format($regularPrice, 2) ?>
                                    </option>
                                    <option value="VIP" data-price="<?= $vipPrice ?>"
                                        <?= ($formData['fan_card'] == 'VIP') ? 'selected' : '' ?>>
                                        VIP — $<?= number_format($vipPrice, 2) ?>
                                    </option>
                                    <option value="Premium" data-price="<?= $premiumPrice ?>"
                                        <?= ($formData['fan_card'] == 'Premium') ? 'selected' : '' ?>>
                                        Premium — $<?= number_format($premiumPrice, 2) ?>
                                    </option>
                                    <option value="Gold" data-price="<?= $goldPrice ?>"
                                        <?= ($formData['fan_card'] == 'Gold') ? 'selected' : '' ?>>
                                        Gold — $<?= number_format($goldPrice, 2) ?>
                                    </option>
                                </select>
                            </div>
                        </div><br>
                        <!-- NEW: Membership Perks Section -->
                        <div class="mb-6" id="perksContainer">
                            <h5>Membership Perks <span id="fanCardLabel2">(Regular)</span></h5>
                            <div class="mt-3" id="perksContent">
                                <p class="text-muted">Select a fan card to see exclusive perks!</p>
                            </div>
                        </div><br>
                        <div class="mb-3">
                            <label for="details" class="form-label">Event Details</label>
                            <textarea class="form-control" id="details" name="details" rows="6" required
                                placeholder="Do you have an upcoming event and need to book <?= htmlspecialchars($celebrity['name']) ?>? Tell us about your event including your budget, location, nearest airport and any relevant information..."><?= $formData['details'] ?></textarea>
                        </div>

                        <!-- Merchandise with Quantity Selection -->
                        <div class="mb-4">
                            <label class="form-label">Concessional Items (Optional)</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card h-100 merchandise-card">
                                        <div class="card-body text-center p-3">
                                            <div class="merchandise-image-container mb-3">
                                                <img src="./images/white-tshirt.png" alt="White T-shirt"
                                                    class="merchandise-base-img">
                                                <?php if ($celebrity['picture']): ?>
                                                <img src="<?= htmlspecialchars($celebrity['picture']) ?>"
                                                    alt="<?= htmlspecialchars($celebrity['name']) ?>"
                                                    class="merchandise-overlay-img">
                                                <?php endif; ?>
                                            </div>
                                            <h5 class="card-title">Customized T-shirt</h5>
                                            <p class="text-muted">$<?= $shirt ?></p>
                                            <div class="input-group mt-2">
                                                <button type="button" class="btn btn-outline-secondary decrement"
                                                    data-item="T-shirt">-</button>
                                                <input type="number" class="form-control quantity-input"
                                                    name="merchandise[T-shirt]" id="tshirt-qty"
                                                    value="<?= $formData['merchandise']['T-shirt'] ?>" min="0" max="10"
                                                    data-price="<?= $shirt ?>" onchange="updateSummary()">
                                                <button type="button" class="btn btn-outline-secondary increment"
                                                    data-item="T-shirt">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 merchandise-card">
                                        <div class="card-body text-center p-3">
                                            <div class="merchandise-image-container mb-3">
                                                <img src="./images/face-cap.png" alt="Face Cap"
                                                    class="merchandise-base-img">
                                                <?php if ($celebrity['picture']): ?>
                                                <img src="<?= htmlspecialchars($celebrity['picture']) ?>"
                                                    alt="<?= htmlspecialchars($celebrity['name']) ?>"
                                                    class="merchandise-overlay-img">
                                                <?php endif; ?>
                                            </div>
                                            <h5 class="card-title">Face Cap</h5>
                                            <p class="text-muted">$<?= $cap ?></p>
                                            <div class="input-group mt-2">
                                                <button type="button" class="btn btn-outline-secondary decrement"
                                                    data-item="Face Cap">-</button>
                                                <input type="number" class="form-control quantity-input"
                                                    name="merchandise[Face Cap]" id="cap-qty"
                                                    value="<?= $formData['merchandise']['Face Cap'] ?>" min="0" max="10"
                                                    data-price="<?= $cap ?>" onchange="updateSummary()">
                                                <button type="button" class="btn btn-outline-secondary increment"
                                                    data-item="Face Cap">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment method -->
                        <div class="mb-4">
                            <h5>Payment Method</h5>
                            <p>Select how you would like to pay:</p>
                            <ul class="list-group">
                                <?php foreach ($paymentMethods as $method): ?>
                                <li class="list-group-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="pay"
                                            id="method<?= $method['id'] ?>"
                                            value="<?= htmlspecialchars($method['name']) ?>"
                                            <?= ($formData['pay'] == $method['name']) ? 'checked' : '' ?> required>
                                        <label class="form-check-label" for="method<?= $method['id'] ?>">
                                            <?= htmlspecialchars($method['name']) ?>
                                        </label>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Submit Booking Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow sticky-top" style="top: 90px;">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Booking Summary</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            <?php if ($celebrity['picture']): ?>
                            <img src="<?= htmlspecialchars($celebrity['picture']) ?>"
                                alt="<?= htmlspecialchars($celebrity['name']) ?>" class="rounded" width="80">
                            <?php else: ?>
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-person text-light" style="font-size: 2rem;"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5><?= htmlspecialchars($celebrity['name']) ?></h5>
                            <span class="badge bg-primary"><?= htmlspecialchars($celebrity['category']) ?></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Fee Details</h5>
                        <ul class="list-group mt-3">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Meet and Greet Fee:</span>
                                <span class="fw-bold" id="baseFee">$<?= number_format($regularPrice, 2) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span id="fanCardLabel">Fan Card (Regular):</span>
                                <span id="fanCardBudget">+ $0.00</span>
                            </li>
                            <div id="merchandiseContainer"></div>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Estimated Total:</span>
                                <span class="fw-bold" id="totalFee">$<?= number_format($regularPrice, 2) ?></span>
                            </li>
                        </ul>
                    </div>



                    <div class="mb-4">
                        <h5>Payment Method</h5>
                        <p>Selected payment option:</p>
                        <div class="alert alert-info p-2" id="paymentSummary">
                            <?= $formData['pay'] ? htmlspecialchars($formData['pay']) : 'Please select a payment method' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.merchandise-card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.merchandise-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.merchandise-image-container {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto;
}

.merchandise-base-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.merchandise-overlay-img {
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 35%;
    height: 35%;
    object-fit: contain;
    border-radius: 50%;
}

.input-group button {
    width: 40px;
}

/* NEW: Perks styling */
.perk-card {
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    padding: 15px;
    margin-bottom: 15px;
}

.perk-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    border-color: #0d6efd;
}

.perk-icon {
    font-size: 2rem;
    margin-bottom: 15px;
    color: #0d6efd;
    text-align: center;
}

.perk-title {
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
    text-align: center;
}

.perk-description {
    color: #666;
    font-size: 0.9rem;
    text-align: center;
}

.perks-grid {
    display: grid;
    gap: 15px;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}
</style>

<script>
const basePrice = <?= $regularPrice ?>;
const merchandisePrices = {
    'T-shirt': <?= $shirt ?>,
    'Face Cap': <?= $cap ?>
};

// Define membership perks
const perks = [{
        icon: 'bi-gift',
        title: 'Exclusive Merchandise',
        description: 'Once-a-Year Exclusive Merchandise'
    },
    {
        icon: 'bi-megaphone',
        title: 'Personalized Shoutout',
        description: 'Special Personalized Shoutout'
    },
    {
        icon: 'bi-star',
        title: 'VIP Content Access',
        description: 'VIP Access to Exclusive Content & Behind-the-Scenes'
    },
    {
        icon: 'bi-ticket',
        title: 'Priority Access',
        description: 'Priority Access to New Releases & Early Event Invites'
    }
];

// Map perks to fan card types
const perkMap = {
    'Regular': [0], // First perk only
    'VIP': [0, 1], // First two perks
    'Premium': [0, 1, 2], // First three perks
    'Gold': [0, 1, 2, 3] // All perks
};

// Add quantity increment/decrement functionality
document.querySelectorAll('.increment, .decrement').forEach(button => {
    button.addEventListener('click', function() {
        const item = this.dataset.item;
        const input = document.querySelector(`input[name="merchandise[${item}]"]`);
        let value = parseInt(input.value) || 0;

        if (this.classList.contains('increment')) {
            value = Math.min(value + 1, 10);
        } else {
            value = Math.max(value - 1, 0);
        }

        input.value = value;
        updateSummary();
    });
});

function updateSummary() {
    // Get selected fan card
    const fanCardSelect = document.getElementById('fan_card');
    const selectedOption = fanCardSelect.options[fanCardSelect.selectedIndex];
    const fanCardType = selectedOption.value;
    const fanCardPrice = parseFloat(selectedOption.dataset.price);
    const fanCardExtra = fanCardPrice - basePrice;
    const celebrityName = "<?= htmlspecialchars($celebrity['name']) ?>";

    // Update perk descriptions with celebrity name
    perks[0].description = `Once-a-Year Exclusive ${celebrityName} Merchandise`;
    perks[1].description = `Special Personalized Shoutout from ${celebrityName}`;

    // Get merchandise quantities
    const merchandiseItems = [];
    let merchandiseTotal = 0;

    document.querySelectorAll('.quantity-input').forEach(input => {
        const quantity = parseInt(input.value) || 0;
        if (quantity > 0) {
            const itemName = input.name.match(/\[(.*?)\]/)[1];
            const itemPrice = merchandisePrices[itemName];
            const itemTotal = itemPrice * quantity;

            merchandiseTotal += itemTotal;
            merchandiseItems.push({
                name: itemName,
                price: itemPrice,
                quantity: quantity,
                total: itemTotal
            });
        }
    });

    // Calculate total
    const total = fanCardPrice + merchandiseTotal;

    // Update fee display
    document.getElementById('baseFee').textContent = `$${basePrice.toFixed(2)}`;
    document.getElementById('fanCardLabel').textContent = `Fan Card (${fanCardType}):`;
    document.getElementById('fanCardLabel2').textContent = `(${fanCardType})`;
    document.getElementById('fanCardBudget').textContent = fanCardExtra > 0 ? `+ $${fanCardExtra.toFixed(2)}` : '$0.00';
    document.getElementById('totalFee').textContent = `$${total.toFixed(2)}`;

    // Update merchandise display
    const merchandiseContainer = document.getElementById('merchandiseContainer');
    merchandiseContainer.innerHTML = '';

    if (merchandiseItems.length > 0) {
        merchandiseItems.forEach(item => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between';
            li.innerHTML =
                `<span>${item.name} (${item.quantity}x):</span><span>$${item.total.toFixed(2)}</span>`;
            merchandiseContainer.appendChild(li);
        });
    } else {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between';
        li.innerHTML = '<span>No additional items</span><span>$0.00</span>';
        merchandiseContainer.appendChild(li);
    }

    // Update perks display
    const perksContainer = document.getElementById('perksContent');
    perksContainer.innerHTML = '';

    const selectedPerkIndexes = perkMap[fanCardType] || [];

    if (selectedPerkIndexes.length > 0) {
        const perksGrid = document.createElement('div');
        perksGrid.className = 'perks-grid';

        selectedPerkIndexes.forEach(index => {
            const perk = perks[index];
            const perkCard = document.createElement('div');
            perkCard.className = 'perk-card';
            perkCard.innerHTML = `
                <div class="perk-icon">
                    <i class="bi ${perk.icon}"></i>
                </div>
                <div class="perk-title">${perk.title}</div>
                <div class="perk-description">${perk.description}</div>
            `;
            perksGrid.appendChild(perkCard);
        });

        perksContainer.appendChild(perksGrid);
    } else {
        perksContainer.innerHTML = '<p class="text-muted">No perks available for this card</p>';
    }

    // Update payment method display
    const selectedPayment = document.querySelector('input[name="pay"]:checked');
    if (selectedPayment) {
        document.getElementById('paymentSummary').textContent = selectedPayment.value;
    }
}

// Initialize summary and add event listeners
document.addEventListener('DOMContentLoaded', function() {
    updateSummary();

    const paymentRadios = document.querySelectorAll('input[name="pay"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', updateSummary);
    });
});
</script>

<?php include 'footer.php'; ?>