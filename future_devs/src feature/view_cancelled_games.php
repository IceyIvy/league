<?php

session_start();

// Check if the user is logged in and has the Admin role
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
//     // Redirect to the login page if unauthorized
//     header("Location: ../admin/login.php");
//     exit();
// }


    // Include the database connection file
    require_once '../db_connection.php';

    // Fetch all applicants
    $teamsQuery = "SELECT * FROM bpslo_match_cancelled";
    $teamResult = $mysqli->query($teamsQuery);

    if (!$teamResult) {
        die("Query failed: " . $mysqli->error);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..//styles/admin_view_cancelled_games.css">
    <title>Cancelled Games</title>

</head>
<body>
    <div class="container">


        <!-- Main Content -->
        <main class="main-content">
            <div class="table-container">
                <h2>Admin Cancelled Games</h2>
                <table>
                    <thead>
                        <tr>
                            <!-- <th>Sport</th>
                            <th>Division</th> -->
                            <th>Team A</th>
                            <th>Team B</th>
                            <th>Date</th>
                            <th>time</th>
                            <th>Description</th>
                            <th>Cancelled At</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $teamResult->fetch_assoc()): ?>
                            <tr>

                                <td><?php echo $row['team_a']; ?></td>
                                <td><?php echo $row['team_b']; ?></td>
                                <td><?php echo $row['when']; ?></td>
                                <td><?php echo $row['time']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['canceled_at']; ?></td>
                                <!-- <td>
                                    <a href="../admin/admin_edit_team.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                                    <br><br>
                                    <a href="../admin/admin_team_players_view.php?id=<?php echo $row['team_id']; ?>" class="edit-btn">View Players</a>
                                </td> -->
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>


</body>
</html>
