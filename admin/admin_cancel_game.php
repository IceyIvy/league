<?php

    session_start();

    // Check if the user is logged in and has the Admin role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
        // Redirect to the login page if unauthorized
        header("Location: ../admin/login.php");
        exit();
    }

    // Include database connection
    require_once '../db_connection.php'; // Update the path if necessary

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_game'])) {
        $game_date = $_POST['game_date'];
        $game_time = $_POST['game_time'];  // Get the selected time from the form

        // Prepare SELECT query to get the games scheduled on the specified date and time
        $selectQuery = "SELECT * FROM bpslo_match_schedules
                        WHERE
                            `when` = ?
                        AND
                            `time` = ?";

        $stmt = $mysqli->prepare($selectQuery);

        if (!$stmt) {
            $message = "Error preparing select statement: " . $mysqli->error;
        } else {
            $stmt->bind_param('ss', $game_date, $game_time); // Bind both date and time
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Loop through the games and insert them into the bpslo_matches_cancelled table
                while ($row = $result->fetch_assoc()) {
                    $match_id = $row['match_id'];
                    $team_a = $row['team_a'];
                    $team_b = $row['team_b'];
                    $when = $row['when'];
                    $time = $row['time'];
                    $description = $row['description'];

                    // Insert the canceled game into bpslo_matches_cancelled
                    $insertCancelledQuery = "
                        INSERT INTO bpslo_match_cancelled (
                                        match_id,
                                        team_a,
                                        team_b,
                                        `when`,
                                        `time`,
                                        description
                                        ) VALUES (?, ?, ?, ?, ?, ?)";

                    $insertStmt = $mysqli->prepare($insertCancelledQuery);
                    $insertStmt->bind_param('ssssss',
                                    $match_id,
                                    $team_a,
                                    $team_b,
                                    $when,
                                    $time,
                                    $description);
                    $insertStmt->execute();
                    $insertStmt->close();
                }

                // Now delete the games from bpslo_match_schedules
                $deleteQuery = "DELETE FROM bpslo_match_schedules
                                            WHERE
                                                `when` = ?
                                            AND
                                                `time` = ?";

                $deleteStmt = $mysqli->prepare($deleteQuery);
                $deleteStmt->bind_param('ss', $game_date, $game_time); // Bind both date and time

                if ($deleteStmt->execute()) {
                    $message = "The game scheduled for " . htmlspecialchars($game_date) . " at " . htmlspecialchars($game_time) . " has been successfully canceled and moved to the archive!";
                } else {
                    $message = "Error canceling the game: " . $deleteStmt->error;
                }

                $deleteStmt->close();
            } else {
                $message = "No games found for the selected date and time.";
            }

            $stmt->close();
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin_cancel_game.css">
    <title>Admin | Cancel Game</title>
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
                <!-- <button class="nav-btn selected">Manage Games</button> -->

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
        <main class="main-content">
            <form class="form-container" method="POST" action="">
                <h2>Cancel A Single Scheduled Game</h2>
                <?php if (isset($message)): ?>
                    <p class="message"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
                <br>
                <h5 class="cancel-note">NOTE: This CANCELS a LEAGUE MATCH of a specific date and time. If you wish to continue, use DATE and TIME.</h5>
            <style>
                .cancel-note{
                    color: gray;
                }
            </style>
                <label for="game-date">Select Date:</label>
                <input type="date" id="game-date" name="game_date" required>

                <label for="game-time">Select Time:</label>
                <input type="time" id="game-time" name="game_time" required>

                <button class="submit-btn" type="submit" name="cancel_game">Cancel Game</button>
            </form>
        </main>
    </div>
</body>
</html>
