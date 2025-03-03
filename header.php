<header>
    <a href="index.php">iKarRental</a>
    <div class="header-right">
        <?php if (isset($_SESSION['user'])): ?>
            <button class="login-btn" id="logout">Logout</a>
                <button class="register-btn" id="profile">Profile</a>
                <?php else: ?>
                    <button class="login-btn" id="login">Login</button>
                    <button class="register-btn" id="register">Registration</button>
                <?php endif; ?>
    </div>
    <style>
        .login-btn {
            background: transparent;
            color: #fff;
        }

        .register-btn {
            background: #ffd700;
            color: #121212;
        }
    </style>
</header>