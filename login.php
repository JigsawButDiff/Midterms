<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Basic styles for the login form */
        body { font-family: Arial, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .container { width: 300px; }
        .error { color: #a94442; background-color: #f2dede; padding: 10px; border: 1px solid #ebccd1; border-radius: 5px; margin-bottom: 20px; }
        .login-form { border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        .login-form label, .login-form input { width: 100%; display: block; }
        .login-form input { padding: 8px; margin-top: 5px; margin-bottom: 15px; }
        .login-form button { width: 100%; padding: 10px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; }
    </style>
</head>
<body>

<div class="container">
    <?php
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Initialize error message variables
    $errorMessages = [];

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form input
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        // Validate email
        if (empty($email)) {
            $errorMessages[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessages[] = "Invalid email";
        }

        // Validate password
        if (empty($password)) {
            $errorMessages[] = "Password is required";
        }

        // Check for valid login credentials (dummy validation)
        if (empty($errorMessages)) {
            $dummy_email = "user@gmail.com";
            $dummy_password = "password";

            if ($email === $dummy_email && $password === $dummy_password) {
                echo "<p>Login successful!</p>";
                // Redirect or take any other action on successful login
            } else {
                $errorMessages[] = "Invalid email or password";
            }
        }
    }
    ?>

    <!-- Display error messages -->
    <?php if (!empty($errorMessages)): ?>
        <div class="error">
            <strong>System Errors</strong>
            <ul>
                <?php foreach ($errorMessages as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="" method="post" class="login-form">
        <h2>Login</h2>
        <label for="email">Email address</label>
        <input type="text" id="email" name="email" placeholder="Enter email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password">

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>