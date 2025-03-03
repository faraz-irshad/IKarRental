<?php
session_start();

require_once 'storage.php';

$userStorage = new Storage(new JsonIO('users.json'));
$carStorage = new Storage(new JsonIO('cars.json'));
$bookingStorage = new Storage(new JsonIO('bookings.json'));

function isLoggedIn() {
    return isset($_SESSION['user']);
}


function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}


function isAdmin() {
    return isLoggedIn() && $_SESSION['user']['role'] === 'admin';
}
?>
