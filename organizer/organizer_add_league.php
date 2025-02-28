<?php

session_start();

// Check if the user is logged in and has the organizer role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Organizer') {
    // Redirect to the login page if unauthorized
    header("Location: ../organizer/login.php");
    exit();
}

// Database connection file
require_once '../db_connection.php'; // Ensure this file contains the correct connection setup

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize inputs
    $who = trim($_POST['who']);
    $what = trim($_POST['what']);
    $when = trim($_POST['when']);
    $where = trim($_POST['where']);
    $why = trim($_POST['description']); // Changed $why to $description for consistency

    // Validate that none of the fields are empty
    if (!empty($who) && !empty($what) && !empty($when) && !empty($where) && !empty($why)) {
        // Prepare SQL query to insert data
        $query = "INSERT INTO bpslo_league (league_id, who, what, `when`, `where`, description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            // Generate a unique post ID
            $league_id = uniqid('LEAGUEID_');

            // Bind parameters (Fixed missing variable and incorrect type count)
            $stmt->bind_param('ssssss', $league_id, $who, $what, $when, $where, $why);

            // Execute the query
            if ($stmt->execute()) {
                echo "<script>alert('Announcement posted successfully'); window.location.href='../organizer/organizer_dashboard.php';</script>";
            } else {
                echo "<script>alert('Error: Unable to save announcement.');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error: Failed to prepare SQL statement.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all the fields.');</script>";
    }
}

// Close the database connection
$mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ostyles/organizer_add_league.css">
    <title>organizer | Add League</title>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
        <a href="../organizer/organizer_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">
            <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_accounts.php'">Accounts</button> -->
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_manage.php'">Manage Games</button> -->

                <button class="nav-btn selected">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_declined.php'">Declined Registrations</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings.php'">Bookings</button> -->
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_approved.php'">Approved Bookings</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_declined.php'">Declined Bookings</button> -->
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_change_password.php'">Change Password</button>
            </div>
            <form id="logout-form" method="POST" action="../organizer/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>
        <main class="main-content">
            <form class="form-container" method="POST">
                <h2>Add League</h2>
                <label for="who">Who:</label>
                <input type="text" id="who" name="who" required>

                <label for="what">What:</label>
                <input type="text" id="what" name="what" required>

                <label for="when">When:</label>
                <input type="date" id="when" name="when" required>

                <label for="where">Where:</label>
                <input type="text" id="where" name="where" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>

                <button class="submit-btn" type="submit">Add League</button>
            </form>
        </main>
    </div>
</body>
</html>
