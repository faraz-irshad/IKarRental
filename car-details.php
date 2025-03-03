<?php
session_start();
$cars = json_decode(file_get_contents('cars.json'), true);
$carId = $_GET['id'] ?? null;

if (!$carId || !isset($cars[$carId - 1])) {
    die('Car not found.');
}

$car = $cars[$carId - 1];
require_once 'storage.php';

if ($_SESSION['user']['role'] === 'admin') {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['action']) && $_POST['action'] === 'update') {
            $brand = $_POST['brand'];
            $model = $_POST['model'];
            $fuel = $_POST['fuel'];
            $transmission = $_POST['transmission'];
            $year = $_POST['year'];
            $passengers = $_POST['passengers'];
            $daily_price_huf = $_POST['daily_price_huf'];
            $url = $_POST['url'];

            if (!empty($brand)) {
                $car['brand'] = $brand;
            }
            if (!empty($model)) {
                $car['model'] = $model;
            }
            if (!empty($fuel)) {
                $car['fuel_type'] = $fuel;
            }
            if (!empty($transmission)) {
                $car['transmission'] = $transmission;
            }
            if (!empty($year)) {
                $car['year'] = $year;
            }
            if (!empty($passengers)) {
                $car['passengers'] = $passengers;
            }
            if (!empty($daily_price_huf)) {
                $car['daily_price_huf'] = $daily_price_huf;
            }
            if (!empty($url)) {
                $car['image'] = $url;
            }


            $cars[$carId - 1] = $car;

            file_put_contents('cars.json', json_encode($cars, JSON_PRETTY_PRINT));

            header('Location: index.php');
            exit;
        }

        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            unset($cars[$carId - 1]);
            file_put_contents('cars.json', json_encode($cars, JSON_PRETTY_PRINT));

            header('Location: index.php'); 
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Details</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <div class="car-details">
                <img src="<?= $car['image'] ?>" alt="<?= $car['brand'] . ' ' . $car['model'] ?>">
                <form method="POST" id="car-editing">
                    <div class="car-info">
                        <p>Brand: </p>
                        <input type="text" name="brand" placeholder="<?= htmlspecialchars($car['brand']) ?>">

                        <p>Model: </p>
                        <input type="text" name="model" placeholder="<?= htmlspecialchars($car['model']) ?>">

                        <p>Fuel: </p>
                        <input type="text" name="fuel" placeholder="<?= htmlspecialchars($car['fuel_type']) ?>">

                        <p>Shifter: </p>
                        <input type="text" name="transmission" placeholder="<?= htmlspecialchars($car['transmission']) ?>">

                        <p>Year of manufacture: </p>
                        <input type="number" name="year" placeholder="<?= htmlspecialchars($car['year']) ?>">

                        <p>Number of seats: </p>
                        <input type="number" name="passengers" placeholder="<?= htmlspecialchars($car['passengers']) ?>">

                        <p>Price: </p>
                        <input type="number" name="daily_price_huf" placeholder="HUF <?= number_format($car['daily_price_huf']) ?>/day">

                        <p>Image URL: </p>
                        <input type="text" name="url" placeholder="<?= htmlspecialchars($car['image']) ?>">

                        <div class="actions">
                            <!-- Update Button -->
                            <button type="submit" name="action" value="update" class="select-date-btn">Save</button>

                            <!-- Delete Button -->
                            <button type="submit" name="action" value="delete" class="book-btn">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="car-details">
                <img src="<?= $car['image'] ?>" alt="<?= $car['brand'] . ' ' . $car['model'] ?>">
                <div class="car-info">
                    <h1><?= $car['brand'] . ' ' . $car['model'] ?></h1>
                    <p>Fuel: <?= $car['fuel_type'] ?></p>
                    <p>Shifter: <?= $car['transmission'] ?></p>
                    <p>Year of manufacture: <?= $car['year'] ?></p>
                    <p>Number of seats: <?= $car['passengers'] ?></p>
                    <p><strong>HUF <?= number_format($car['daily_price_huf']) ?>/day</strong></p>
                    <div class="actions">
                        <a href="book.php?id=<?= $car['id'] ?>" class="book-btn">Book it</a>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
<script>
    const bookBtn = document.getElementById('book-btn');
    if (bookBtn) {
        bookBtn.addEventListener('click', function() {
            window.location.href = 'book.php';
        });
    }
</script>

</html>