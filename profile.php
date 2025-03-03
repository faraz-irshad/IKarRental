<?php
session_start();
require_once 'storage.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$userStorage = new Storage(new JsonIO('users.json'));
$user = $userStorage->findById($_SESSION['user']['id']);

$bookings = json_decode(file_get_contents('bookings.json'), true);
$cars = json_decode(file_get_contents('cars.json'), true);
$userBookings = array_filter($bookings, function ($booking) {
    return $booking['user_email'] === $_SESSION['user']['email'];
});

$bookedCars = [];
foreach ($userBookings as $booking) {
    $carId = $booking['car_id'];
    if (isset($cars[$carId - 1])) {
        $bookedCars[] = $cars[$carId - 1];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile_picture_url'])) {
    $profilePictureUrl = trim($_POST['profile_picture_url']);

    $user['profile_picture'] = $profilePictureUrl;
    $userStorage->update($user['id'], $user);
    $_SESSION['user']['profile_picture'] = $profilePictureUrl;
    header('Location: profile.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['car_id'])) {
    $carIdToDelete = $_POST['car_id'];
    
    $updatedBokings = [];

    foreach ($bookings as $booking) {
        if ($booking['car_id'] != $carIdToDelete) {
            $updatedBokings[] = $booking;
        };
    };

    file_put_contents('bookings.json', json_encode(array_values($updatedBookings), JSON_PRETTY_PRINT));

    header('Location: profile.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - iKarRental</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="profile_styles.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <main class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-picture-wrapper">
                    <img src="<?= $user['profile_picture'] ? htmlspecialchars($user['profile_picture']) : 'default-profile.png'; ?>" alt="Profile Picture" class="profile-picture">
                </div>
                <h2 class="profile-name"><?= htmlspecialchars($user['name']); ?></h2>
                <p class="profile-email"><?= htmlspecialchars($user['email']); ?></p>
                <button id="changePictureButton" class="change-picture-btn">Change Picture</button>
            </div>

            <!-- Show reservations if the user is not an admin -->
            <?php if ($_SESSION['user']['role'] != 'admin'): ?>
                <section class="reservation-section">
                    <h3>My Reservations</h3>

                    <?php if (count($bookedCars) > 0): ?>
                        <div class="booked-cars-list">
                            <?php foreach ($bookedCars as $car): ?>
                                <div class="car-card" data-fuel="<?= $car['fuel_type'] ?>" data-transmission="<?= $car['transmission'] ?>" data-price="<?= $car['daily_price_huf'] ?>" data-seats="<?= $car['passengers'] ?>">
                                    <img src="<?= $car['image'] ?>" alt="<?= $car['brand'] . ' ' . $car['model'] ?>">
                                    <h3><?= $car['brand'] . ' ' . $car['model'] ?></h3>
                                    <p><?= number_format($car['passengers']) ?> seats</p>
                                    <p><?= number_format($car['daily_price_huf']) ?> HUF/day</p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>You have no bookings yet.</p>
                    <?php endif; ?>
                </section>
            <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                    <?php
                    $car = [];
                    $bookedCarId = $booking['car_id'];
                    foreach ($cars as $rac) {
                        if ($bookedCarId == $rac['id']) {
                            $car = $rac;
                        };
                    };
                    ?>
                    <div class="booked-cars-list">
                        <div class="car-card" data-fuel="<?= $car['fuel_type'] ?>" data-transmission="<?= $car['transmission'] ?>" data-price="<?= $car['daily_price_huf'] ?>" data-seats="<?= $car['passengers'] ?>">
                            <img src="<?= $car['image'] ?>" alt="<?= $car['brand'] . ' ' . $car['model'] ?>">
                            <h3><?= $car['brand'] . ' ' . $car['model'] ?></h3>
                            <p><?= number_format($car['passengers']) ?> seats</p>
                            <p><?= number_format($car['daily_price_huf']) ?> HUF/day</p>
                            <p>Starting Date : <?= $booking['start_date'] ?></p>
                            <p>Ending Date : <?= $booking['end_date'] ?></p>
                            <form method="POST">
                                <button type="submit" name="action" value="delete">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Modal for changing profile picture -->
        <div id="changePictureModal" class="modal hidden">
            <div class="modal-content">
                <span id="closeModal" class="close">&times;</span>
                <h3>Change Profile Picture</h3>
                <form action="profile.php" method="POST">
                    <label for="profile_picture_url">Image URL</label>
                    <input type="text" name="profile_picture_url" id="profile_picture_url" placeholder="Enter image URL..." required>
                    <button type="submit" class="modal-submit-btn">Update</button>
                </form>
                <?php if (isset($errorMessage)): ?>
                    <p class="error-message"><?= htmlspecialchars($errorMessage); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        const changePictureButton = document.getElementById('changePictureButton');
        const modal = document.getElementById('changePictureModal');
        const closeModal = document.getElementById('closeModal');

        changePictureButton.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>
</body>

</html>