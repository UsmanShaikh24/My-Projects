<?php
include 'db.php';

// Set the headers to indicate a CSV file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=contacts.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Set the column headers for the CSV file
fputcsv($output, array('ID', 'Name', 'Email', 'Phone', 'Address'));

// Fetch the contacts from the database
$sql = "SELECT * FROM contacts";
$result = $conn->query($sql);

// If there are rows, write them to the CSV file
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Write each contact row to the CSV file
        fputcsv($output, $row);
    }
}

// Close the output stream
fclose($output);

// End the script to avoid any further output
exit();
?>
