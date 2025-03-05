<?php
include 'db.php';

// File name for the backup
$backupFile = 'backup_contacts_' . date('Y-m-d_H-i-s') . '.csv';

// Open the file for writing
$file = fopen($backupFile, 'w');

// Fetch data from the contacts table
$sql = "SELECT * FROM contacts";
$result = $conn->query($sql);

// Write the column headers
fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Address']);

// Write data to the file
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($file, $row);
    }
}

// Close the file
fclose($file);

// Prompt the user to download the file
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename=' . $backupFile);
header('Pragma: no-cache');
readfile($backupFile);

// Delete the backup file from the server after download
unlink($backupFile);

// Close the database connection
$conn->close();
?>
