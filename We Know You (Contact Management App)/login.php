<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - We Know You Contact Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="favicon.png.png" type="image/png">
    <style>
        .app-name {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            color: white;
            background-color: black;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .login-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .login-form input {
            margin-bottom: 15px;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- App Name Header -->
        <div class="app-name">
            We Know You Contact Management System
        </div>

        <div class="login-container">
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="success-message">
                    Account created successfully! Please log in now.
                </div>
            <?php endif; ?>

            <div class="login-header">
                <h2>Login</h2>
            </div>
            <form method="POST" action="login_process.php" class="login-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="register.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
