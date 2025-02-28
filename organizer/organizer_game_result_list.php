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

    // Fetch all applicants
    $teamsQuery = "SELECT * FROM bpslo_match_winners";
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
    <link rel="stylesheet" href="..//ostyles/organizer_game_result_list.css">
    <title>organizer | Game Results</title>

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
        <a href="../organizer/organizer_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">
            <button class="nav-btn" onclick="window.location.href='../organizer/organizer_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_accounts.php'">Accounts</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_manage.php'">Manage Games</button> -->

                <button class="nav-btn selected">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_declined.php'">Declined Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings.php'">Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_approved.php'">Approved Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_declined.php'">Declined Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_change_password.php'">Change Password</button>
            </div>
            <form id="logout-form" method="POST" action="../organizer/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="table-container">
                <h2>Game Results</h2>
                <table>
                    <thead>
                        <tr>
                            <!-- <th>Team ID</th> -->
                            <th>Team A</th>
                            <th>Team A Score</th>
                            <th>Team B</th>
                            <th>Team B Score</th>
                            <th>Winner</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $teamResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['team_id']; ?></td>
                                <td><?php echo $row['sport']; ?></td>
                                <td><?php echo $row['team_name']; ?></td>
                                <td><?php echo $row['division']; ?></td>
                                <td><?php echo $row['player_count']; ?></td>
                                <td><?php echo $row['wins']; ?></td>
                                <td><?php echo $row['losses']; ?></td>
                                <td>
                                    <a href="../organizer/organizer_edit_team.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                                    <!-- <br><br> -->
                                    <!-- <a href="../organizer/organizer_team_players_view.php?id=<?php echo $row['team_id']; ?>" class="edit-btn">View Players</a> -->
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>


</body>
</html>
