<?php
session_start();
require_once 'storage.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$carId = $_GET['id'] ?? null;
$cars = json_decode(file_get_contents('cars.json'), true);
$bookings = json_decode(file_get_contents('bookings.json'), true);

if (!$carId || !isset($cars[$carId - 1])) {
    die('Car not found.');
}

$car = $cars[$carId - 1];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    if (empty($startDate) || empty($endDate)) {
        $errorMessage = "Please provide both start and end dates.";
    } else {
        $carAvailable = true;
        $conflictingBookings = [];

        foreach ($bookings as $booking) {
            $existingStartDate = new DateTime($booking['start_date']);
            $existingEndDate = new DateTime($booking['end_date']);
            $newStartDate = new DateTime($startDate);
            $newEndDate = new DateTime($endDate);

            if (
                $booking['car_id'] == $carId &&
                (($newStartDate >= $existingStartDate && $newStartDate <= $existingEndDate) ||
                    ($newEndDate >= $existingStartDate && $newEndDate <= $existingEndDate) ||
                    ($newStartDate <= $existingStartDate && $newEndDate >= $existingEndDate))
            ) {
                $carAvailable = false;
                $conflictingBookings[] = [
                    'start' => $existingStartDate->format('Y-m-d'),
                    'end' => $existingEndDate->format('Y-m-d')
                ];
            }
        }

        if ($carAvailable) {
            $newBooking = [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'user_email' => $_SESSION['user']['email'],
                'car_id' => $carId
            ];
            $bookings[] = $newBooking;
            file_put_contents('bookings.json', json_encode($bookings, JSON_PRETTY_PRINT));
            header('Location: profile.php');
            exit;
        } else {
            $errorMessage = "Sorry, this car is already booked for the following dates: ";
            foreach ($conflictingBookings as $conflict) {
                $errorMessage .= "<br>" . "From: " . $conflict['start'] . " to " . $conflict['end'];
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Car - iKarRental</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .booking-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #1f1f1f;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .booking-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .booking-container form {
            display: flex;
            flex-direction: column;
        }

        .booking-container label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .booking-container input {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .booking-container button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .booking-container button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <main class="booking-container">
        <h1>Book the car: <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h1>

        <?php if (isset($errorMessage)): ?>
            <div class="error-message">
                <?= $errorMessage ?> <!-- Do not use htmlspecialchars here -->
            </div>
        <?php endif; ?>


        <form action="book.php?id=<?= $carId ?>" method="POST">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>

            <button type="submit" class="book-btn">Book Now</button>
        </form>
    </main>
</body>

</html>