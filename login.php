<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Basic styles for the login form and dashboard */
        body { font-family: Arial, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .container { width: 80%; max-width: 800px; text-align: center; }
        .error { color: #a94442; background-color: #f2dede; padding: 10px; border: 1px solid #ebccd1; border-radius: 5px; margin-bottom: 20px; }
        .login-form, .dashboard { border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        .login-form label, .login-form input { width: 100%; display: block; margin: 10px 0; }
        .login-form input { padding: 8px; }
        .login-form button { width: 100%; padding: 10px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; }

        /* Dashboard Layout */
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .logout-button { padding: 10px 15px; background-color: #dc3545; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        .dashboard { display: flex; flex-direction: row; gap: 20px; }
        .dashboard-section { flex: 1; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .dashboard-section h3 { margin-top: 0; }
        .dashboard button { width: 100%; padding: 15px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; margin-top: 10px; font-size: 16px; }
    </style>
</head>
<body>

<div class="container">
    <?php
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Start session to maintain login state
    session_start();

    // Initialize variables
    $errorMessages = [];
    $isLoggedIn = isset($_SESSION['isLoggedIn']) ? $_SESSION['isLoggedIn'] : false;
    $loggedInUser = isset($_SESSION['loggedInUser']) ? $_SESSION['loggedInUser'] : "";

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
        // Get form input
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        // Dummy user credentials for login
        $dummy_email = "user@gmail.com";
        $dummy_password = "password";

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

        // Check for valid login credentials
        if (empty($errorMessages)) {
            if ($email === $dummy_email && $password === $dummy_password) {
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['loggedInUser'] = $email;
                $isLoggedIn = true;
                $loggedInUser = $email;
            } else {
                $errorMessages[] = "Invalid email or password";
            }
        }
    }

    // Handle logout
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
        session_unset(); // Clear all session variables
        session_destroy(); // Destroy the session
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to login page
        exit;
    }
    ?>

    <?php if ($isLoggedIn): ?>
        <!-- Dashboard Header with Welcome Message and Logout Button -->
        <div class="dashboard-header">
            <h2>Welcome to the System: <?php echo htmlspecialchars($loggedInUser); ?></h2>
            <form method="post" action="">
                <button type="submit" name="logout" class="logout-button">Logout</button>
            </form>
        </div>

        <!-- Dashboard Content -->
        <div class="dashboard">
            <div class="dashboard-section">
                <h3>Add a Subject</h3>
                <p>This section allows you to add a new subject in the system. Click the button below to proceed with the adding process.</p>
                <button>Add Subject</button>
            </div>

            <div class="dashboard-section">
                <h3>Register a Student</h3>
                <p>This section allows you to register a new student in the system. Click the button below to proceed with the registration process.</p>
                <button>Register</button>
            </div>
        </div>
    <?php else: ?>
        <!-- Original Login Form -->
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
        
        <form action="" method="post" class="login-form">
            <h2>Login</h2>
            <label for="email">Email address</label>
            <input type="text" id="email" name="email" placeholder="Enter email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password">

            <button type="submit" name="login">Login</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html> 