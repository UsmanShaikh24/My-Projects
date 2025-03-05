<?php
include 'db.php'; // Include the database connection

// SQL to truncate the contacts table
$sql = "TRUNCATE TABLE contacts";

if ($conn->query($sql) === TRUE) {
    echo "Contacts table has been reset.";
} else {
    echo "Error resetting contacts table: " . $conn->error;
}

// Close the connection
$conn->close();

// Redirect to the homepage
header("Location: index.php");
exit();
?>
