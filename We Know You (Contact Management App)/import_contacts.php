<?php
include 'db.php';
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle CSV file upload
if (isset($_POST['submit'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    
    if (is_uploaded_file($file)) {
        if (($handle = fopen($file, 'r')) !== FALSE) {
            $header = fgetcsv($handle); // Read the header row

            // Ensure header contains expected columns
            if ($header && in_array('Name', $header) && in_array('Email', $header) && in_array('Phone', $header) && in_array('Address', $header)) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $name = $conn->real_escape_string($data[0]);
                    $email = $conn->real_escape_string($data[1]);
                    $phone = $conn->real_escape_string($data[2]);
                    $address = $conn->real_escape_string($data[3]);

                    $sql = "INSERT INTO contacts (name, email, phone, address, user_id) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssssi', $name, $email, $phone, $address, $_SESSION['user_id']);
                    
                    if ($stmt->execute()) {
                        echo '<div class="alert alert-success">Contact imported successfully: ' . htmlspecialchars($name) . '</div>';
                    } else {
                        echo '<div class="alert alert-danger">Error importing contact: ' . htmlspecialchars($name) . '. ' . $stmt->error . '</div>';
                    }
                }
                fclose($handle);
                echo '<div class="alert alert-success">All contacts imported successfully.</div>';
            } else {
                echo '<div class="alert alert-danger">CSV file header is missing required columns.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Failed to open the CSV file.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">No file uploaded.</div>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import Contacts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to custom CSS -->
    <link rel="icon" href="favicon.png.png" type="image/png">
</head>
<body>
<div class="container">
    <h2 class="title">Import Contacts</h2>
    
    <form method="POST" action="import_contacts.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="csv_file">Choose CSV File</label>
            <input type="file" name="csv_file" id="csv_file" class="form-control" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Import</button>
    </form>
    
    <a href="index.php" class="btn btn-secondary mt-3">Back to Contact List</a>
</div>
</body>
</html>
