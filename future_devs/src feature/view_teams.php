<?php

    // Include the database connection file
    require_once '../db_connection.php';

    // Fetch all teams from bpslo_teams
    $teamsQuery = "SELECT * FROM bpslo_teams";
    $teamResult = $mysqli->query($teamsQuery);

    if (!$teamResult) {
        die("Query failed: " . $mysqli->error);
    }

    // Organize teams into categories
    $basketballMen = [];
    $basketballWomen = [];
    $volleyballMen = [];
    $volleyballWomen = [];

    while ($row = $teamResult->fetch_assoc()) {
        if ($row['sport'] === 'Basketball' && $row['division'] === "Men's") {
            $basketballMen[] = $row;
        } elseif ($row['sport'] === 'Basketball' && $row['division'] === "Women's") {
            $basketballWomen[] = $row;
        } elseif ($row['sport'] === 'Volleyball' && $row['division'] === "Men's") {
            $volleyballMen[] = $row;
        } elseif ($row['sport'] === 'Volleyball' && $row['division'] === "Women's") {
            $volleyballWomen[] = $row;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin_view_teams.css">
    <title>Teams</title>
</head>
<body>
    <div class="container">
        <main class="main-content">
            <h2>Basketball Teams</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Sport</th>
                            <th>Division</th>
                            <th>Team Name</th>
                            <th>No. of Players</th>
                            <th>Wins</th>
                            <th>Losses</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($basketballMen as $team): ?>
                            <tr>
                                <!-- <td><?php echo $team['id']; ?></td> -->
                                <td><?php echo $team['sport']; ?></td>
                                <td><?php echo $team['division']; ?></td>
                                <td><?php echo $team['team_name']; ?></td>
                                <td><?php echo $team['player_count']; ?></td>
                                <td><?php echo $team['wins']; ?></td>
                                <td><?php echo $team['losses']; ?></td>
                                <td><a href="../user/view_team_players.php?id=<?php echo $team['team_id']; ?>" class="edit-btn">View Players</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Sport</th>
                            <th>Division</th>
                            <th>Team Name</th>
                            <th>No. of Players</th>
                            <th>Wins</th>
                            <th>Losses</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($basketballWomen as $team): ?>
                            <tr>
                                <!-- <td><?php echo $team['id']; ?></td> -->
                                <td><?php echo $team['sport']; ?></td>
                                <td><?php echo $team['division']; ?></td>
                                <td><?php echo $team['team_name']; ?></td>
                                <td><?php echo $team['player_count']; ?></td>
                                <td><?php echo $team['wins']; ?></td>
                                <td><?php echo $team['losses']; ?></td>
                                <td><a href="../user/view_team_players.php?id=<?php echo $team['team_id']; ?>" class="edit-btn">View Players</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <h2>Volleyball Teams</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Sport</th>
                            <th>Division</th>
                            <th>Team Name</th>
                            <th>No. of Players</th>
                            <th>Wins</th>
                            <th>Losses</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($volleyballMen as $team): ?>
                            <tr>
                                <!-- <td><?php echo $team['id']; ?></td> -->
                                <td><?php echo $team['sport']; ?></td>
                                <td><?php echo $team['division']; ?></td>
                                <td><?php echo $team['team_name']; ?></td>
                                <td><?php echo $team['player_count']; ?></td>
                                <td><?php echo $team['wins']; ?></td>
                                <td><?php echo $team['losses']; ?></td>
                                <td><a href="../user/view_team_players.php?id=<?php echo $team['team_id']; ?>" class="edit-btn">View Players</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Sport</th>
                            <th>Division</th>
                            <th>Team Name</th>
                            <th>No. of Players</th>
                            <th>Wins</th>
                            <th>Losses</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($volleyballWomen as $team): ?>
                            <tr>
                                <!-- <td><?php echo $team['id']; ?></td> -->
                                <td><?php echo $team['sport']; ?></td>
                                <td><?php echo $team['division']; ?></td>
                                <td><?php echo $team['team_name']; ?></td>
                                <td><?php echo $team['player_count']; ?></td>
                                <td><?php echo $team['wins']; ?></td>
                                <td><?php echo $team['losses']; ?></td>
                                <td><a href="../user/view_team_players.php?id=<?php echo $team['team_id']; ?>" class="edit-btn">View Players</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>