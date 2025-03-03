<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $errors = [];

    if (empty($name) || preg_match('/\d/', $name)) {
        $errors[] = "Full Name is required and should not contain numbers.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $existingUser = $userStorage->findOne(['email' => $email]);
        if ($existingUser) {
            $message = "User with this email already exists.";
        } else {
            $userStorage->add([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash,
                'role' => 'user',
                'profile_picture' => ""
            ]);
            header('Location: login.php');
            exit();
        }
    } else {
        $message = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register_styles.css">
    
</head>

<body>

    <header>
        <a href="index.php" class="header-left">iKarRental</a>
    </header>

    <h2 style="text-align: center;">Registration Form</h2>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" id="registration-form">
        <input type="text" name="name" placeholder="Full Name" required>
        <div class="error" id="name-error"></div>

        <input type="email" name="email" placeholder="Email Address" required>
        <div class="error" id="email-error"></div>

        <input type="password" name="password" placeholder="Password" required>
        <div class="error" id="password-error"></div>

        <button type="submit">Register</button>
    </form>

    <script>
        document.getElementById('registration-form').addEventListener('input', function() {
            const nameInput = this.querySelector('input[name="name"]');
            const emailInput = this.querySelector('input[name="email"]');
            const passwordInput = this.querySelector('input[name="password"]');

            document.getElementById('name-error').textContent = '';
            document.getElementById('email-error').textContent = '';
            document.getElementById('password-error').textContent = '';

            if (nameInput.value && /\d/.test(nameInput.value)) {
                document.getElementById('name-error').textContent = 'Full Name should not contain numbers.';
            }

            if (emailInput.value && !/\S+@\S+\.\S+/.test(emailInput.value)) {
                document.getElementById('email-error').textContent = 'Invalid email address.';
            }

            if (passwordInput.value.length < 6) {
                document.getElementById('password-error').textContent = 'Password must be at least 6 characters long.';
            }
        });
    </script>

</body>

</html>