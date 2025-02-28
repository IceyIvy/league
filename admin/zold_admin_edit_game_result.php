<?php
session_start();
require_once '../db_connection.php';

// Check if an ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='admin_view_game_result.php';</script>";
    exit();
}

$result_id = $_GET['id'];

// Fetch match result data
$query = "SELECT * FROM bpslo_match_winners WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $result_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Record not found.'); window.location.href='admin_view_game_result.php';</script>";
    exit();
}

$match = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sport = $_POST['sport'];
    $division = $_POST['division'];
    $team_a = $_POST['team_a'];
    $team_a_score = $_POST['team_a_score'];
    $team_b = $_POST['team_b'];
    $team_b_score = $_POST['team_b_score'];
    $winner = $_POST['winner'];
    $description = $_POST['description'];

    $updateQuery = "UPDATE bpslo_match_winners
                    SET sport = ?, division = ?, team_a_name = ?, team_a_score = ?, team_b_name = ?, team_b_score = ?, winner = ?, description = ?
                    WHERE id = ?";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param("ssssssssi", $sport, $division, $team_a, $team_a_score, $team_b, $team_b_score, $winner, $description, $result_id);

    if ($stmt->execute()) {
        echo "<script>alert('Game result updated successfully!'); window.location.href='admin_view_game_result.php';</script>";
    } else {
        echo "<script>alert('Error updating record: " . $stmt->error . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin_edit_game_result.css">
    <title>Edit Game Result</title>
</head>
<body>
    <div class="container">
        <h2>Edit Game Result</h2>
        <form method="POST">
            <label>Sport:</label>
            <input type="text" name="sport" value="<?= htmlspecialchars($match['sport']) ?>" required>

            <label>Division:</label>
            <input type="text" name="division" value="<?= htmlspecialchars($match['division']) ?>" required>

            <label>Team A:</label>
            <input type="text" name="team_a" value="<?= htmlspecialchars($match['team_a_name']) ?>" required>

            <label>Team A Score:</label>
            <input type="number" name="team_a_score" value="<?= htmlspecialchars($match['team_a_score']) ?>" required>

            <label>Team B:</label>
            <input type="text" name="team_b" value="<?= htmlspecialchars($match['team_b_name']) ?>" required>

            <label>Team B Score:</label>
            <input type="number" name="team_b_score" value="<?= htmlspecialchars($match['team_b_score']) ?>" required>

            <label>Winner:</label>
            <input type="text" name="winner" value="<?= htmlspecialchars($match['winner']) ?>" required>

            <label>Description:</label>
            <textarea name="description"><?= htmlspecialchars($match['description']) ?></textarea>

            <button type="submit">Update</button>
            <a href="admin_view_game_result.php" class="cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html>
