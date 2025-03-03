<?php
$cars = json_decode(file_get_contents('cars.json'), true);
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iKarRental</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <section id="filter-section">
            <form id="filter-form">
                <div class="filter-group">
                    <label for="seats">Seats:</label>
                    <select id="seats" name="seats">
                        <option value="">All</option>
                        <option value="1">1 seats</option>
                        <option value="2">2 seats</option>
                        <option value="3">3 seats</option>
                        <option value="4">4 seats</option>
                        <option value="5">5 seats</option>
                        <option value="6">6 seats</option>
                        <option value="7">7 seats</option>
                        <option value="8">8 seats</option>
                        <option value="9">9 seats</option>
                        <option value="10">10 seats</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="fuel">Fuel Type:</label>
                    <select id="fuel" name="fuel">
                        <option value="">All</option>
                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Electric">Electric</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="transmission">Transmission:</label>
                    <select id="transmission" name="transmission">
                        <option value="">All</option>
                        <option value="Manual">Manual</option>
                        <option value="Automatic">Automatic</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="price-range">Max Price (HUF/day):</label>
                    <input type="range" id="price-range" name="price" min="10000" max="50000" value="50000" step="1000">
                    <span id="price-display">50,000 HUF</span>
                </div>

                <button type="button" id="apply-filters">Apply Filters</button>
            </form>
        </section>

        <div id="cars-container">
            <?php foreach ($cars as $car): ?>
                <div class="car-card" data-fuel="<?= $car['fuel_type'] ?>" data-transmission="<?= $car['transmission'] ?>" data-price="<?= $car['daily_price_huf'] ?>" data-seats="<?= $car['passengers'] ?>">
                    <img src="<?= $car['image'] ?>" alt="<?= $car['brand'] . ' ' . $car['model'] ?>">
                    <h3><?= $car['brand'] . ' ' . $car['model'] ?></h3>
                    <p><?= number_format($car['passengers']) ?> seats</p>
                    <p><?= number_format($car['daily_price_huf']) ?> HUF/day</p>

                    <?php if ($_SESSION['user']['role'] === 'admin') : ?>
                        <a href="car-details.php?id=<?= $car['id'] ?>" class="edit-details-btn">Edit</a>
                    <?php else: ?>
                        <a href="car-details.php?id=<?= $car['id'] ?>" class="view-details-btn">View Details</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($_SESSION['user']['role'] === 'admin') : ?>
            <button id='add-car'>Add a car</button>
        <?php endif; ?>
    </main>

    <script src="script.js"></script>
</body>

</html>