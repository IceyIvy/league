<?php

    session_start();

   // Check if the user is logged in and has the Admin role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
        // Redirect to the login page if unauthorized
        header("Location: ../admin/login.php");
        exit();
    }

    // Database connection
    require_once '../db_connection.php'; // Ensure it contains the correct connection setup

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve and sanitize input data
        $team_name = trim($_POST['team-name']);
        $sport = trim($_POST['sport']);
        $division = trim($_POST['division']);
        $player_count = trim($_POST['player-count']);

        $team_id = uniqid('TEAMID');  // Create a unique application ID

        // Validate required fields
        if (!empty($team_name) && !empty($sport) && !empty($division)) {
            // Prepare SQL query to insert a team
            $query = "INSERT INTO bpslo_teams (
                                    team_id,
                                    team_name,
                                    sport,
                                    division,
                                    player_count)
                                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ssssi',
                                $team_id,
                                $team_name,
                                $sport,
                                $division,
                                $player_count);

            if ($stmt->execute()) {
                $success_message = "Team added successfully!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Please fill in all the fields.";
        }
    }

    $mysqli->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin_add_team.css">
    <title>Admin | Add Team</title>
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
            <form method="POST" action="../admin/logout.php">
                <button id="logout-form" type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>
        <main class="main-content">
        <form class="form-container" method="POST">
        <h2>Add New Team</h2>
        <label for="team-name">Team Name:</label>
        <input type="text" id="team-name" name="team-name" required>

        <label for="sport">Sport:</label>
        <select id="sport" name="sport" required>
            <option value="" disawbled selected>Select Sport</option>
            <option value="Basketball">Basketball</option>
            <option value="Volleyball">Volleyball</option>
        </select>

        <label for="division">Division:</label>
        <select id="division" name="division" required>
            <option value="" disabled selected>Select Division</option>
            <option value="Men's">Men's</option>
            <option value="Women's">Women's</option>
        </select>

        <label for="player-count">Player Count:</label>
        <input type="number" id="player-count" name="player-count" required>

        </select>

        <button class="submit-btn" type="submit">Add Team</button>
    </form>

        </main>
    </div>

    <?php if (isset($success_message)): ?>
        <script>alert('<?php echo $success_message; ?>');</script>
    <?php elseif (isset($error_message)): ?>
        <script>alert('<?php echo $error_message; ?>');</script>
    <?php endif; ?>

</body>
</html>