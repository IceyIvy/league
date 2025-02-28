<?php
// Include database connection file
require_once '../db_connection.php'; // Ensure it contains the correct connection setup

// Fetch match schedules from the database
$query = "SELECT
    team_a,
    team_b,
    `when`,
    `time`,
    description
FROM
    bpslo_match_schedules
ORDER BY
    `when` ASC,
    `time` ASC";

$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Match Schedules</title>
    <link rel="stylesheet" href="styles.css"> <!-- link to external CSS for better separation -->
</head>
<body>
    <div class="container">
        <h1>Match Schedules</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="match-card">
                    <div class="match-header">
                        <span class="team"><?= htmlspecialchars($row['team_a']); ?></span>
                        <span class="vs">VS</span>
                        <span class="team"><?= htmlspecialchars($row['team_b']); ?></span>
                    </div>
                    <div class="match-details">
                        <p><strong>Date:</strong> <?= date('F j, Y', strtotime($row['when'])); ?></p>
                        <p><strong>Time:</strong> <?= date('h:i A', strtotime($row['time'])); ?></p>
                    </div>
                    <div class="match-description">
                        <p><?= htmlspecialchars($row['description']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No matches scheduled at the moment.</p>
        <?php endif; ?>

        <?php $mysqli->close(); ?>
    </div>
</body>
</html>

<style>
    :root {
        --light-green: #B0D77E;
        --green: #66AD00;
        --white: #FFFFFF;
        --font-family: 'Poppins', sans-serif;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: var(--font-family);
    }

    body {
        background-color: var(--light-green);
        padding: 20px;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        text-align: center;
    }

    h1 {
        color: var(--green);
        margin-bottom: 20px;
    }

    .match-card {
        background-color: var(--white);
        border: 2px solid var(--green);
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        margin: 20px 0;
        padding: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .match-card:hover {
        transform: scale(1.03);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    .match-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .team {
        font-size: 1.5em;
        font-weight: bold;
        color: var(--green);
    }

    .vs {
        font-size: 1.2em;
        font-weight: bold;
        color: #999;
    }

    .match-details {
        margin-top: 10px;
        font-size: 1em;
        color: #333;
    }

    .match-details p {
        margin-bottom: 5px;
    }

    .match-description {
        margin-top: 15px;
        font-size: 0.95em;
        color: #555;
        line-height: 1.5;
        text-align: justify;
    }
</style>
