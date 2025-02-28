<?php

    session_start();

    // Check if the user is logged in and has the Admin role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
        // Redirect to the login page if unauthorized
        header("Location: ../admin/login.php");
        exit();
    }

    // Include database connection file
    require_once '../db_connection.php';

    // Check if the ID is set in the URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch the existing data for the specific post
        $query = "SELECT * FROM bpslo_league WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $id); // bind the ID to the query
        $stmt->execute();
        $result = $stmt->get_result();

        // If the post exists, fetch the data
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            // If no post found, redirect or show an error
            echo "Post not found!";
            exit;
        }
    }

    // If the form is submitted, update the post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $who = $_POST['who'];
        $what = $_POST['what'];
        $when = $_POST['when'];
        $where = $_POST['where'];
        $description = $_POST['description'];

        // Update the post in the database
        $updateQuery = "UPDATE bpslo_league SET who = ?, what = ?, `when` = ?, `where` = ?, description = ? WHERE id = ?";
        $updateStmt = $mysqli->prepare($updateQuery);
        $updateStmt->bind_param("sssssi", $who, $what, $when, $where, $description, $id); // bind the new values and the post ID
        $updateStmt->execute();

        // Redirect after updating
        header("Location: admin_league_list.php");
        exit;

}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin_league_edit.css">
    <title>Admin | Edit League</title>

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
        <a href="../admin/admin_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">

            <button class="nav-btn" onclick="window.location.href='../admin/admin_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_accounts.php'">Accounts</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../admin/admin_manage.php'">Manage Games</button> -->

                <button class="nav-btn selected">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_registrations_declined.php'">Declined Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_bookings.php'">Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_bookings_approved.php'">Approved Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_bookings_declined.php'">Declined Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_change_password.php'">Change Password</button>
            </div>
            <form id="logout-form" method="POST" action="../admin/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
        <form class="form-container" method="POST">
            <h2>Edit League</h2>

            <label for="who">Who:</label>
            <input type="text" id="who" name="who" value="<?= htmlspecialchars($row['who']) ?>" required>

            <label for="what">What:</label>
            <input type="text" id="what" name="what" value="<?= htmlspecialchars($row['what']) ?>" required>

            <label for="when">When:</label>
            <input type="date" id="when" name="when" value="<?= $row['when'] ?>" required>

            <label for="where">Where:</label>
            <input type="text" id="where" name="where" value="<?= htmlspecialchars($row['where']) ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($row['description']) ?></textarea>

            <button class="submit-btn" type="submit">Update League Post</button>

        </form>
    </main>
    </div>



</body>
</html>

