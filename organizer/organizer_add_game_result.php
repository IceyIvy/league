<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Organizer') {
    // Redirect to the login page if unauthorized
    header("Location: ../organizer/login.php");
    exit();
}

// Database connection
require_once '../db_connection.php';

// Fetch team names for dropdown
$query = "SELECT team_id, team_name FROM bpslo_teams ORDER BY team_name ASC";
$result = $mysqli->query($query);

$teams = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $team_a_id = $_POST['team_a'];
    $team_b_id = $_POST['team_b'];
    $team_a_score = $_POST['team_a_score'];
    $team_b_score = $_POST['team_b_score'];
    $description = trim($_POST['description']);

    $sport = $_POST['sport'];
    $division = $_POST['division'];

    $result_id = uniqid('RESULT_');

    // Validate required fields
    if (!empty($team_a_id) && !empty($team_b_id) && !empty($description)) {
        if ($team_a_id !== $team_b_id) {  // Ensure Team A and Team B are different
            // Fetch team names based on their IDs
            $stmt = $mysqli->prepare("SELECT team_name FROM bpslo_teams WHERE team_id = ?");
            $stmt->bind_param('s', $team_a_id);
            $stmt->execute();
            $stmt->bind_result($team_a_name);
            $stmt->fetch();
            $stmt->close();

            $stmt = $mysqli->prepare("SELECT team_name FROM bpslo_teams WHERE team_id = ?");
            $stmt->bind_param('s', $team_b_id);
            $stmt->execute();
            $stmt->bind_result($team_b_name);
            $stmt->fetch();
            $stmt->close();

            // Determine the winner
            if ($team_a_score > $team_b_score) {
                $winner = $team_a_name;
                $loser = $team_b_name;
            } elseif ($team_b_score > $team_a_score) {
                $winner = $team_b_name;
                $loser = $team_a_name;
            } else {
                $winner = "Draw"; // Draw case
                $loser = null;
            }

            // Insert match result into the database
            $stmt = $mysqli->prepare("INSERT INTO bpslo_match_winners
                (result_id, sport, division, team_a_name, team_b_name, team_a_score, team_b_score, winner, description)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssiiss', $result_id, $sport, $division, $team_a_name, $team_b_name, $team_a_score, $team_b_score, $winner, $description);

            if ($stmt->execute()) {
                $stmt->close();

                // Update wins/losses for both teams if there's a winner
                if ($winner !== "Draw") {
                    // Update the winning team
                    $update_winner_query = "UPDATE bpslo_teams SET wins = wins + 1 WHERE team_name = ?";
                    $stmt = $mysqli->prepare($update_winner_query);
                    $stmt->bind_param('s', $winner);
                    $stmt->execute();
                    $stmt->close();

                    // Update the losing team
                    if ($loser) {
                        $update_loser_query = "UPDATE bpslo_teams SET losses = losses + 1 WHERE team_name = ?";
                        $stmt = $mysqli->prepare($update_loser_query);
                        $stmt->bind_param('s', $loser);
                        $stmt->execute();
                        $stmt->close();
                    }
                }

                echo "<script>alert('Match result successfully recorded!'); window.location.href='organizer_dashboard.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
        } else {
            echo "<p style='color: red;'>Team A and Team B cannot be the same.</p>";
        }
    } else {
        echo "<p style='color: red;'>Please fill in all the fields.</p>";
    }

    $mysqli->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ostyles/organizer_add_game_result.css">
    <title>organizer | Add Game Result</title>
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
                <h2>Post Match Result</h2>

                <div class="form-row">
                    <div class="form-group">
                    <label for="sport">Sport:</label>
                <select id="sport" name="sport" required>
                    <option value="" disabled selected>Please Select</option>
                    <option value="Basketball">Basketball</option>
                    <option value="Volleyball">Volleyball</option>
                </select>
                    </div>
                    <div class="form-group">
                    <label for="division">Division:</label>
                <select id="division" name="division" required>
                    <option value="" disabled selected>Please Select</option>
                    <option value="Women's">Women's</option>
                    <option value="Men's">Men's</option>
                </select>
                    </div>
                </div>


                <!-- <label for="sport">Sport:</label>
                <select id="sport" name="sport" required>
                    <option value="" disabled selected>Please Select</option>
                    <option value="Basketball">Basketball</option>
                    <option value="Volleyball">Volleyball</option>
                </select> -->

                <!-- <label for="division">Division:</label>
                <select id="division" name="division" required>
                    <option value="" disabled selected>Please Select</option>
                    <option value="Women's">Women's</option>
                    <option value="Men's">Men's</option>
                </select> -->

                <div class="form-row">
                    <div class="form-group">
                    <label for="team_a">Select Team A:</label>
                <select id="team_a" name="team_a" required>
                    <option value="" disabled selected>Select Team A</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?php echo $team['team_id']; ?>"><?php echo htmlspecialchars($team['team_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                    </div>
                    <div class="form-group">
                        <label for="">Team A Score</label>
                    <input type="number" name="team_a_score" placeholder="Team A Score" required>
                    </div>
                </div>

                <!-- <label for="team_a">Select Team A:</label>
                <select id="team_a" name="team_a" required>
                    <option value="" disabled selected>Select Team A</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?php echo $team['team_id']; ?>"><?php echo htmlspecialchars($team['team_name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="number" name="team_a_score" placeholder="Team A Score" required> -->

                <div class="form-row">
                    <div class="form-group">
                    <label for="team_b">Select Team B:</label>
                <select id="team_b" name="team_b" required>
                    <option value="" disabled selected>Select Team B</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?php echo $team['team_id']; ?>"><?php echo htmlspecialchars($team['team_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                    </div>
                    <div class="form-group">
                        <label for="">Team B Score</label>
                    <input type="number" name="team_b_score" placeholder="Team B Score" required>
                    </div>
                </div>


                <!-- <label for="team_b">Select Team B:</label>
                <select id="team_b" name="team_b" required>
                    <option value="" disabled selected>Select Team B</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?php echo $team['team_id']; ?>"><?php echo htmlspecialchars($team['team_name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="number" name="team_b_score" placeholder="Team B Score" required> -->

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>

                <button class="submit-btn" type="submit">Post Game Result</button>
            </form>
        </main>
    </div>
</body>
</html>
