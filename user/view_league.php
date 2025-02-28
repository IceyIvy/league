<?php

    // Include database connection file
    require_once '../db_connection.php'; // Ensure this file contains the correct connection setup

    // Fetch league announcements from the database
    $query = "SELECT
                who,
                what,
                `when`,
                `where`,
                description,
                created_at
            FROM bpslo_league
            ORDER BY `when` DESC";

    $result = $mysqli->query($query);

    // Close the database connection
    $mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Announcements</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .announcement {
            margin-bottom: 20px;
            padding: 15px;
            border: 2px solid #66AD00;
            border-radius: 8px;
            background-color: #B0D77E;
        }

        .announcement h3 {
            margin-bottom: 10px;
            color: #005500;
        }

        .announcement p {
            margin: 5px 0;
            color: #333333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>League Announcements</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="announcement">
                    <h3><?= htmlspecialchars($row['what']) ?></h3>
                    <p><strong>Who:</strong> <?= htmlspecialchars($row['who']) ?></p>
                    <p><strong>When:</strong> <?= htmlspecialchars($row['when']) ?></p>
                    <p><strong>Where:</strong> <?= htmlspecialchars($row['where']) ?></p>
                    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    <p><small><em>Posted on: <?= htmlspecialchars($row['created_at']) ?></em></small></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No league announcements found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
