<?php
include 'db.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Handle adding a contact
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "INSERT INTO contacts (name, email, phone, address, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $name, $email, $phone, $address, $user_id);
    $stmt->execute();
}

// Search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM contacts WHERE user_id = ? AND (name LIKE ? OR email LIKE ? OR phone LIKE ? OR address LIKE ?)";
} else {
    $sql = "SELECT * FROM contacts WHERE user_id = ?";
}

// Prepare and execute the query for contacts
$stmt = $conn->prepare($sql);

if ($search) {
    // If searching, bind 5 parameters
    $search_param = "%$search%";
    $stmt->bind_param('issss', $user_id, $search_param, $search_param, $search_param, $search_param);
} else {
    // If not searching, bind 1 parameter
    $stmt->bind_param('i', $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

// Prepare and execute the count query
$count_sql = "SELECT COUNT(*) FROM contacts WHERE user_id = ?";
$count_stmt = $conn->prepare($count_sql);

if ($search) {
    $count_stmt->bind_param('issss', $user_id, $search_param, $search_param, $search_param, $search_param);
} else {
    $count_stmt->bind_param('i', $user_id);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$row = $count_result->fetch_row();
$total_contacts = $row[0];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Know You Contact Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to custom CSS -->
    <link rel="icon" href="favicon.png.png" type="image/png">
    <style>
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
            position: relative;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #343a40;
            color: white;
            z-index: 10;
        }

        @media (max-width: 768px) {
            .title {
                font-size: 1.5rem;
            }

            .search-container {
                width: 100%;
            }

            .search-input {
                width: 100%;
                margin-bottom: 10px;
            }

            .button-container {
                width: 100%;
                text-align: center;
            }

            .action-buttons a {
                display: block;
                width: 100%;
                margin-bottom: 10px;
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table th, .table td {
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="title text-center">We Know You Contact Management System</h2>
    
    <!-- Logout Button -->
    <div class="text-right mb-3">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    
    <!-- Search Form -->
    <div class="search-container">
        <form method="GET" action="index.php" class="search-form">
            <input type="text" name="search" class="form-control search-input" placeholder="Search contacts..." value="<?php echo htmlspecialchars($search); ?>">
            <div class="button-container mt-2">
                <button type="submit" class="btn btn-primary search-button">Search</button>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons mt-3 d-flex flex-wrap justify-content-between">
        <a href="add_contact.php" class="btn btn-primary">Add Contact</a>
        <a href="export_contacts.php" class="btn btn-success">Export to CSV</a>
        <a href="import_contacts.php" class="btn btn-info">Import Contacts</a>
        <a href="backup_contacts.php" class="btn btn-warning">Backup Data</a>
        <a href="reset_data.php" class="btn btn-danger" onclick="return confirmDelete()">Reset Data</a>
    </div>

    <!-- Delete Selected Contacts Form -->
    <form method="POST" action="delete_contacts.php" class="mt-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone no</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="<?php echo htmlspecialchars($row['id']); ?>"></td>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td>
                                <a href="edit_contact.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No contacts found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-danger mt-3" onclick="return confirm('Are you sure you want to delete selected contacts?')">Delete Selected</button>
    </form>
</div>

<div class="footer text-center mt-4">
    <p>&copy; 2024 Vishwakarma Institute of Technology</p>
</div>

<script>
    // Script to select or deselect all checkboxes
    document.getElementById('select-all').addEventListener('click', function(event) {
        let checkboxes = document.querySelectorAll('input[name="ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
    });

    // Confirmation prompt for reset data
    function confirmDelete() {
        const confirmation = confirm("WARNING: This will delete all contacts permanently! Have you backed up the data?");
        if (confirmation) {
            const furtherConfirmation = confirm("Are you absolutely sure you want to delete all data? This action is irreversible.");
            return furtherConfirmation;
        }
        return false;
    }
</script>
</body>
</html>
