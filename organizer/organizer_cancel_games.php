<?php

    session_start();

    // Check if the user is logged in and has the organizer role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Organizer') {
        // Redirect to the login page if unauthorized
        header("Location: ../organizer/login.php");
        exit();
    }

    // Include database connection
    require_once '../db_connection.php'; // Update the path if necessary

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_game'])) {
        $game_date = $_POST['game_date'];

        // Prepare SELECT query to get the games scheduled on the specified date
        $selectQuery = "SELECT * FROM bpslo_match_schedules WHERE `when` = ?";

        $stmt = $mysqli->prepare($selectQuery);

        if (!$stmt) {
            $message = "Error preparing select statement: " . $mysqli->error;
        } else {
            $stmt->bind_param('s', $game_date);
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
                $deleteQuery = "DELETE FROM bpslo_match_schedules WHERE `when` = ?";

                $deleteStmt = $mysqli->prepare($deleteQuery);
                $deleteStmt->bind_param('s', $game_date);

                if ($deleteStmt->execute()) {
                    $message = "All games scheduled for " . htmlspecialchars($game_date) . " have been successfully canceled and moved to the archive!";
                } else {
                    $message = "Error canceling games: " . $deleteStmt->error;
                }

                $deleteStmt->close();
            } else {
                $message = "No games found for the selected date.";
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
    <link rel="stylesheet" href="../ostyles/organizer_cancel_games.css">
    <title>organizer | Cancel Games</title>
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
                <!-- <button class="nav-btn selected">Manage Games</button> -->

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
            <form class="form-container" method="POST" action="">
                <h2>Cancel Scheduled Games</h2>
                <?php if (isset($message)): ?>
                    <p class="message"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
                <br>
                <h5 class="cancel-note">NOTE: This CANCELS a MULTIPLE LEAGUE MATCHES of a specific date. If you wish to continue, use DATE.</h5>
            <style>
                .cancel-note{
                    color: gray;
                }
            </style>
                <label for="game-date">Select Date:</label>
                <input type="date" id="game-date" name="game_date" required>

                <button class="submit-btn" type="submit" name="cancel_game">Cancel Games</button>
            </form>
        </main>
    </div>
</body>
</html>
