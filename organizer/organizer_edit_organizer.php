<?php

    session_start();

    // Check if the user is logged in and has the organizer role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Organizer') {
        // Redirect to the login page if unauthorized
        header("Location: ../organizer/login.php");
        exit();
    }

    // Include the database connection file
    require_once '../db_connection.php';

    // Check if form data is submitted (for update)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the data from the form
        $id = $_POST['id'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $last_session = $_POST['last_session'];
        $password = $_POST['password']; // Password field

        // If last session is empty, keep the current value
        if (empty($last_session)) {
            // Get the current last session from the database if it's not provided
            $query = "SELECT last_session FROM bpslo_organizers WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $last_session = $row['last_session']; // Use the current last session
        }

        // Hash the password if it's not empty
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE bpslo_organizers
                            SET
                                email = ?,
                            role = ?, last_session = ?, password = ?
                            WHERE
                            id = ?";

            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ssssi',
                                $email,
                                $role,
                                $last_session,
                                $password,
                                $id);
        } else {
            // If no new password, do not update it
            $query = "UPDATE bpslo_organizers
                            SET
                                email = ?,
                            role = ?, last_session = ?
                            WHERE
                                id = ?";

            $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssi',
                            $email,
                            $role,
                            $last_session,
                            $id);
        }

        // Execute the update query
        if ($stmt->execute()) {
            // Redirect back to the accounts page if update is successful
            header('Location: organizer_accounts.php');
            exit; // Ensure the script stops after redirect
        } else {
            echo "Error updating organizer: " . $mysqli->error;
        }
    }

    // Get the organizer ID from the URL
    $organizer_id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($organizer_id) {
        // Query to fetch the organizer details
        $query = "SELECT * FROM bpslo_organizers WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $organizer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the organizer exists
        if ($result->num_rows > 0) {
            $organizer = $result->fetch_assoc();
        } else {
            echo "Organizer not found.";
            exit;
        }
    } else {
        echo "No organizer ID provided.";
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>organizer | Edit Organizer</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../ostyles/organizer_edit_organizer.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
        <a href="../organizer/organizer_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn selected">Accounts</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_organize_games.php'">Manage Games</button>

                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_organize_league.php'">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_declined.php'">Declined Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings.php'">Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_approved.php'">Approved Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_declined.php'">Declined Bookings</button>
            </div>
            <form id="logout-form" method="POST" action="../organizer/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>
        <main>
            <div class="main-content">
                <h1>Edit Organizer</h1>
            </div>
            <div class="form-container">
                <form method="post">

                    <input type="hidden" name="id" value="<?php echo $organizer['id']; ?>">

                    <div class="form-group">
                        <span class="material-icons icon">person</span>
                        <input type="email" name="email" value="<?php echo $organizer['email']; ?>" required>
                    </div>

                    <div class="form-group">
                        <span class="material-icons icon">lock</span>
                        <!-- <input type="password" id="password" placeholder="Leave blank to keep current password" required> -->
                        <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
                        <span class="material-icons toggle-password" onclick="togglePassword()">visibility</span>
                    </div>

                    <div class="form-group">
                        <input type="datetime-local" name="last_session" value="<?php echo $organizer['last_session']; ?>">
                    </div>

                    <div class="form-group">
                        <span class="material-icons icon">badge</span>
                        <input type="text" name="role" value="Organizer" readonly>
                    </div>
                    <button type="submit" class="submit-btn">Update</button>
                </form>
            </div>
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