<?php
session_start();
require_once 'storage.php';

$userStorage = new Storage(new JsonIO('users.json'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'Please fill in all fields.';
        header('Location: login.php');
        exit;
    }

    $user = $userStorage->findOne(['email' => $email]);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'role' => $user['role']
        ];
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['login_error'] = 'Invalid email or password.';
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - iKarRental</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        a {
            text-decoration: none;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #fff;
            background-color: #121212;
            line-height: 1.6;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #1f1f1f;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        .header-left {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
        }

        .header-right a {
            margin-left: 10px;
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            text-decoration: none;
        }

        .login-btn {
            background: transparent;
            color: #fff;
        }

        .register-btn {
            background: #ffd700;
            color: #121212;
        }

        main {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #1f1f1f;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #ffd700;
        }

        .form-container {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            color: #ccc;
        }

        input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #2b2b2b;
            color: #fff;
        }

        .submit-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            background: #ffd700;
            color: #121212;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: #e6c200;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <header>
        <a href="index.php" class="header-left">iKarRental</a>
    </header>

    <main>
        <h1>Login</h1>
        <form action="login.php" method="POST" class="form-container">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="submit-btn">Login</button>


            <?php if (isset($_SESSION['login_error'])): ?>
                <p class="error-message"><?= htmlspecialchars($_SESSION['login_error']) ?></p>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>
        </form>
    </main>
</body>

</html>