<?php
session_start();
$cars = json_decode(file_get_contents('cars.json'), true);

if ($_SESSION['user']['role'] !== 'admin') {
    die('Access denied.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $year = $_POST['year'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $fuel_type = $_POST['fuel_type'] ?? '';
    $passengers = $_POST['passengers'] ?? '';
    $daily_price_huf = $_POST['daily_price_huf'] ?? '';
    $image = $_POST['image'] ?? '';

    if (empty($brand) || empty($model) || empty($year) || empty($transmission) || empty($fuel_type) || empty($passengers) || empty($daily_price_huf) || empty($image)) {
        $error = "All fields are required!";
    } else {
        $newId = end($cars)['id'] + 1;

        $newCar = [
            "id" => $newId,
            "brand" => $brand,
            "model" => $model,
            "year" => (int)$year,
            "transmission" => $transmission,
            "fuel_type" => $fuel_type,
            "passengers" => (int)$passengers,
            "daily_price_huf" => (int)$daily_price_huf,
            "image" => $image,
        ];

        $cars[] = $newCar;
        file_put_contents('cars.json', json_encode($cars, JSON_PRETTY_PRINT));
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Car</title>
    <link rel="stylesheet" href="add_car_styles.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST" class="add-car-form">
            <label for="brand">Brand:</label>
            <input type="text" name="brand" id="brand" required>

            <label for="model">Model:</label>
            <input type="text" name="model" id="model" required>

            <label for="year">Year:</label>
            <input type="number" name="year" id="year" required>

            <label for="transmission">Transmission:</label>
            <input type="text" name="transmission" id="transmission" required>

            <label for="fuel_type">Fuel Type:</label>
            <input type="text" name="fuel_type" id="fuel_type" required>

            <label for="passengers">Number of Passengers:</label>
            <input type="number" name="passengers" id="passengers" required>

            <label for="daily_price_huf">Daily Price (HUF):</label>
            <input type="number" name="daily_price_huf" id="daily_price_huf" required>

            <label for="image">Image URL:</label>
            <input type="text" name="image" id="image" required>

            <button type="submit" class="submit-btn">Add Car</button>
        </form>
    </main>
</body>

</html>