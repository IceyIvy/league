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

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure match ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Match ID is missing.");
}

$matchId = $_GET['id'];

// Fetch the match data from the database
$matchQuery = "SELECT * FROM bpslo_match_schedules WHERE id = ?";
$stmt = $mysqli->prepare($matchQuery);
$stmt->bind_param("i", $matchId);
$stmt->execute();
$matchResult = $stmt->get_result();

if ($matchResult->num_rows > 0) {
    $matchData = $matchResult->fetch_assoc();
} else {
    die("Match schedule not found.");
}

// Fetch teams for the dropdown
$teamsQuery = "SELECT * FROM bpslo_teams";
$teamsResult = $mysqli->query($teamsQuery);
$teams = $teamsResult->fetch_all(MYSQLI_ASSOC);

// Handle match update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_match'])) {
    $teamA = htmlspecialchars($_POST['team_a']);
    $teamB = htmlspecialchars($_POST['team_b']);
    $when = $_POST['when'];
    $time = $_POST['time'];
    $description = htmlspecialchars($_POST['description']);

    // Update match schedule
    $updateQuery = "UPDATE bpslo_match_schedules SET team_a = ?, team_b = ?, `when` = ?, `time` = ?, description = ? WHERE id = ?";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param("sssssi", $teamA, $teamB, $when, $time, $description, $matchId);

    if ($stmt->execute()) {
        echo "<script>alert('Match schedule updated successfully!'); window.location.href='organizer_match_schedules.php?id=$matchId';</script>";
        exit;
    } else {
        echo "<script>alert('Failed to update match schedule: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ostyles/organizer_match_schedule_edit.css">
    <title>organizer | Edit Match Schedule</title>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
        <a href="../organizer/organizer_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">
                <button class="nav-btn selected">Organize League</button>

                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_accounts.php'">Accounts</button> -->
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_manage.php'">Manage Games</button> -->

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

        <!-- Main Content -->
        <main class="main-content">
            <!-- Update Match Schedule Form -->
            <form class="form-container" method="POST">
                <h2>Edit Match Schedule</h2>

                <label for="team_a">Select Team A:</label>
                <select id="team_a" name="team_a" required>
                    <option value="" disabled>Select Team A</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= htmlspecialchars($team['team_name']); ?>"
                            <?= ($matchData['team_a'] == $team['team_name']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($team['team_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="team_b">Select Team B:</label>
                <select id="team_b" name="team_b" required>
                    <option value="" disabled>Select Team B</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= htmlspecialchars($team['team_name']); ?>"
                            <?= ($matchData['team_b'] == $team['team_name']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($team['team_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="when">When:</label>
                <input type="date" id="when" name="when" value="<?= $matchData['when']; ?>" required>

                <label for="time">Time:</label>
                <input type="time" id="time" name="time" value="<?= $matchData['time']; ?>" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($matchData['description']); ?></textarea>

                <button class="submit-btn" type="submit" name="update_match">Update Match Schedule</button>
            </form>
        </main>
    </div>
</body>
</html>
