<?php

    session_start();

    // Check if the user is logged in and has the Admin role
    // if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    //     // Redirect to the login page if unauthorized
    //     header("Location: ../admin/login.php");
    //     exit();
    // }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin_manage.css">
    <title>Admin | Manage</title>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
        <a href="../admin/admin_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">
                <button class="nav-btn" onclick="window.location.href='../admin/admin_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_accounts.php'">Accounts</button>
                <button class="nav-btn selected">Manage Games</button>

                <button class="nav-btn" onclick="window.location.href='../admin/admin_organize_league.php'">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_registrations_declined.php'">Declined Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_bookings.php'">Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_bookings_approved.php'">Approved Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_bookings_declined.php'">Declined Bookings</button>
            </div>
            <form method="POST" action="../admin/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>
        <main class="main-content">
            <div class="announcement-container">
                <button class="announcement-btn" onclick="window.location.href='../admin/admin_cancel_game.php'">Cancel Single Game</button>
                <button class="announcement-btn" onclick="window.location.href='../admin/admin_cancel_games.php'">Cancel Multiple Games</button>
                <!-- <button class="announcement-btn" onclick="window.location.href='../admin/admin_match_schedules.php'">View Scheduled Games</button> -->
                <button class="announcement-btn" onclick="window.location.href='../admin/admin_cancel_games.php'">View Cancelled Games</button>
                <button class="announcement-btn" onclick="window.location.href='../admin/admin_players_list.php'">Update Players</button>
            </div>
        </main>
    </div>


</body>
</html>
