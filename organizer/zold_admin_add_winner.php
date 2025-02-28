<?php
session_start();

// Check if match data is set in the session
if (isset($_SESSION['match_data'])) {
    $matchData = $_SESSION['match_data'];
    // Clear the session data for security
    unset($_SESSION['match_data']);
} else {
    die("No match data found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Winner</title>
</head>
<body>
    <h1>Match Winner</h1>
    <p>Match ID: <?php echo htmlspecialchars($matchData['id']); ?></p>
    <p>Team A: <?php echo htmlspecialchars($matchData['team_a']); ?></p>
    <p>Team B: <?php echo htmlspecialchars($matchData['team_b']); ?></p>
    <p>Date: <?php echo htmlspecialchars($matchData['when']); ?></p>
    <p>Time: <?php echo htmlspecialchars($matchData['time']); ?></p>
    <p>Description: <?php echo htmlspecialchars($matchData['description']); ?></p>
    <!-- Additional code to handle winner posting can go here -->
</body>
</html>