<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - We Know You Contact Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to custom CSS -->
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
            white-space: nowrap; /* Prevent text from wrapping */
            overflow: hidden; /* Hide any overflowing text */
            text-overflow: ellipsis; /* Display ellipsis if text overflows */
        }

        .register-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .register-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .register-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .register-form input {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- App Name Header -->
        <div class="app-name">
            We Know You Contact Management System
        </div>

        <div class="register-container">
            <div class="register-header">
                <h2>Register</h2>
            </div>
            <form method="POST" action="register_process.php" class="register-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
