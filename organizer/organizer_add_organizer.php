<?php

    session_start();

    // Check if the user is logged in and has the organizer role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Organizer') {
        // Redirect to the login page if unauthorized
        header("Location: ../organizer/login.php");
        exit();
    }

    // Include database connection
    require_once '../db_connection.php';

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Generate unique organizer ID
        $organizer_id = uniqid('REGID');  // Create a unique organizer ID with prefix 'REGID'

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL to insert into the database
        $sql = "INSERT INTO bpslo_organizers (
            organizer_id,
            email,
            role,
            password)
            VALUES (?, ?, ?, ?)";

        // Prepare statement
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("ssss",
                $organizer_id,
                $email,
                $role,
                $hashed_password);

            // Execute the query
            if ($stmt->execute()) {
                echo "Organizer account successfully created!";
            } else {
                // echo "Error: " . $stmt->error;
                echo "<script>alert('Email Already Exist! Use another Email.');</script>";
            }

            // Close statement
            $stmt->close();
        }

        // Close the database connection
        $mysqli->close();
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>organizer | Add Organizer</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../ostyles/organizer_add_organizer.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
        <a href="../organizer/organizer_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">
                <a href="../organizer/organizer_" class="nav-btn selected">Add Organizer</a>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_accounts.php'">Accounts</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_manage.php'">Manage</button> -->

                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_organize_league.php'">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_declined.php'">Declined Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings.php'">Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_approved.php'">Approved Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_declined.php'">Declined Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_change_password.php'">Change Password</button>
            </div>
            <form id="logout-form" method="POST" action="../organizer/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>
        <main class="main-content">
            <!-- <form method="POST" action="../organizer/organizer_add_organizer.php"> -->
            <form method="POST">
                <h2>Add Organizer</h2>
                <div class="form-group">
                    <span class="material-icons icon">person</span>
                    <input type="text" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <span class="material-icons icon">lock</span>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <span class="material-icons toggle-password" onclick="togglePassword()">visibility</span>
                </div>

                <div class="form-group">
                    <span class="material-icons icon">badge</span>
                    <input type="text" name="role" value="Organizer" readonly>
                </div>

                <button type="submit" class="submit-btn">Add Organizer</button>
            </form>
        </main>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const passwordIcon = document.querySelector(".toggle-password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.textContent = "visibility_off";
            } else {
                passwordField.type = "password";
                passwordIcon.textContent = "visibility";
            }
        }
    </script>


</body>
</html>
