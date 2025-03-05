<?php
include 'db.php'; // Ensure this path is correct

// Check if the request method is POST and if 'ids' is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $ids = $_POST['ids'];

    // Sanitize and create a list of IDs
    $ids_list = implode(',', array_map('intval', $ids));

    // Prepare the SQL DELETE statement
    $sql = "DELETE FROM contacts WHERE id IN ($ids_list)";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the index page with a success message
        header("Location: index.php?message=Records deleted successfully");
        exit(); // Ensure no further code is executed after the redirect
    } else {
        // Display an error message
        echo "Error deleting records: " . $conn->error;
    }
} else {
    // Handle the case where no contacts were selected
    echo "No contacts selected or invalid request.";
}
?>