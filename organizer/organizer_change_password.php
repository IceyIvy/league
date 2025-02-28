<?php

    session_start();
    require_once '../db_connection.php';

    // Redirect if not logged in
    if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Organizer') {
        header("Location: ../organizer/login.php");
        exit();
    }

    $message = "";

    // Handle password change
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_SESSION['email'];
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            $message = "New password and confirmation do not match.";
        } else {
            $stmt = $mysqli->prepare("SELECT password FROM bpslo_organizers WHERE email = ?");

            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1 && password_verify($currentPassword, $result->fetch_assoc()['password'])) {
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $mysqli->prepare("UPDATE
                                                    bpslo_organizers
                                                SET
                                                    password = ?
                                                WHERE
                                                    email = ?");

                $updateStmt->bind_param('ss',
                                        $newPasswordHash,
                                        $email);

                if ($updateStmt->execute()) {
                    $message = "Password updated successfully.";
                } else {
                    $message = "Error updating password.";
                }
            } else {
                $message = "Incorrect current password.";
            }
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>organizer | Change Password</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../ostyles/organizer_change_password.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
        <a href="../organizer/organizer_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">
            <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_accounts.php'">Accounts</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_manage.php'">Manage Games</button> -->

            <button class="nav-btn" onclick="window.location.href='../organizer/organizer_organize_league.php'">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_declined.php'">Declined Registrations</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings.php'">Bookings</button> -->
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_approved.php'">Approved Bookings</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_declined.php'">Declined Bookings</button> -->
                <button class="nav-btn selected">Change Password</button>
            </div>
            <form id="logout-form" method="POST" action="../organizer/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>
        <main class="main-content">
            <form method="POST">
            <h2>Change Password</h2>
                <?php if ($message): ?>
                    <p class="message"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>

                <div class="form-group">
                    <span class="material-icons icon">lock</span>
                    <input type="password" name="current_password" id="password1" placeholder="Current Password" required>
                    <span class="material-icons toggle-password toggle-password1" onclick="togglePassword1()">visibility</span>
                </div>

                <div class="form-group">
                    <span class="material-icons icon">lock</span>
                    <input type="password" name="new_password" id="new-password" placeholder="New Password" required>
                    <span class="material-icons toggle-password toggle-password2" onclick="togglePassword2()">visibility</span>
                </div>

                <div class="form-group">
                    <span class="material-icons icon">lock</span>
                    <input type="password" name="confirm_password" id="password2" placeholder="Confirm Password" required>
                    <span class="material-icons toggle-password toggle-password3" onclick="togglePassword3()">visibility</span>
                </div>



                <button type="submit" class="submit-btn">Change Password</button>
            </form>
        </main>
    </div>

    <script>
    function togglePassword1() {
        const passwordField = document.getElementById("password1");
        const passwordIcon = document.querySelector(".toggle-password1");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            passwordIcon.textContent = "visibility_off";
        } else {
            passwordField.type = "password";
            passwordIcon.textContent = "visibility";
        }
    }

    function togglePassword2() {
        const passwordField = document.getElementById("new-password");
        const passwordIcon = document.querySelector(".toggle-password2");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            passwordIcon.textContent = "visibility_off";
        } else {
            passwordField.type = "password";
            passwordIcon.textContent = "visibility";
        }
    }

    function togglePassword3() {
        const passwordField = document.getElementById("password2");
        const passwordIcon = document.querySelector(".toggle-password3");
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
