<?php

    // Include the database connection file
    require_once '../db_connection.php';

    // Check if a team ID is provided
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        die("<script>alert('Invalid team ID.'); window.location.href = 'view_teams.php';</script>");
    }

    $team_id = $_GET['id'];

    // Fetch the team details from bpslo_teams
    $teamQuery = "SELECT * FROM bpslo_teams WHERE team_id = ?";

    $stmt = $mysqli->prepare($teamQuery);
    $stmt->bind_param('s', $team_id);
    $stmt->execute();
    $teamResult = $stmt->get_result();

    if ($teamResult->num_rows === 0) {
        die("<script>alert('Team not found.'); window.location.href = 'view_teams.php';</script>");
    }

    $team = $teamResult->fetch_assoc();
    $team_name = $team['team_name']; // Get the team name
    $sport = $team['sport'] ?? ''; // Fetch sport (if exists)
    $division = $team['division'] ?? ''; // Fetch division (if exists)

    $stmt->close(); // Close previous statement

    // Fetch players from bpslo_registrations_approved based on team_name
    $playersQuery = "SELECT * FROM bpslo_registrations_approved WHERE team_name = ? AND sport = ? AND division = ?";

    $stmt = $mysqli->prepare($playersQuery);
    $stmt->bind_param('sss', $team_name, $sport, $division);
    $stmt->execute();
    $playersResult = $stmt->get_result();

    // Close database connection
    $stmt->close();
    $mysqli->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin_registrations_approved.css">
    <title>Approved Applicants | <?php echo htmlspecialchars($team_name); ?></title>
</head>
<body>
    <div class="container">

        <!-- Main Content -->
        <main class="main-content">
            <div class="table-container">
                <h2>Players in <?php echo htmlspecialchars($team_name); ?></h2>
                <table>
                    <thead>
                        <tr>
                            <!-- <th>PLAYER ID</th> -->
                            <th>SPORT</th>
                            <th>TEAM NAME</th>
                            <th>DIVISION</th>
                            <th>FIRST NAME</th>
                            <th>LAST NAME</th>
                            <th>AGE</th>
                            <th>MOBILE NUMBER</th>
                            <th>EMAIL</th>
                            <th>APPROVED AT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $playersResult->fetch_assoc()): ?>
                            <tr>
                                <!-- <td><?php echo htmlspecialchars($row['player_id']); ?></td> -->
                                <td><?php echo htmlspecialchars($row['sport']); ?></td>
                                <td><?php echo htmlspecialchars($row['team_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['division']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['age']); ?></td>
                                <td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['email_address']); ?></td>
                                <td><?php echo htmlspecialchars($row['approved_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

<?php
    // Close database connections
    $stmt->close();
    $mysqli->close();
?>
