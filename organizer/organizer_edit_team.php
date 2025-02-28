<?php

    session_start();

    // Check if the user is logged in and has the organizer role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Organizer') {
        // Redirect to the login page if unauthorized
        header("Location: ../organizer/login.php");
        exit();
    }

    require_once '../db_connection.php'; // Database connection file

    $team = [];

    // Check if an ID is provided in the URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Secure the query using prepared statements
        $query = "SELECT * FROM bpslo_teams WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $team = $result->fetch_assoc();
        } else {
            echo "<script>alert('Team not found.');</script>";
        }

        $stmt->close();
    }

    // update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_team'])) {
        $sport = $_POST['sport'];
        $team_name = $_POST['team-name'];
        $division = $_POST['division'];
        $player_count = $_POST['player-count'];
        $wins = $_POST['wins'];
        $losses = $_POST['losses'];
        $team_id = $_POST['team-id']; // Hidden input
        $old_team_name = $_POST['old-team-name']; // Correctly fetch the old team name from the form

        // Update team details
        $updateQuery = "
            UPDATE bpslo_teams
            SET
                sport = ?,
                team_name = ?,
                division = ?,
                player_count = ?,
                wins = ?,
                losses = ?
            WHERE id = ?";

        $stmt = $mysqli->prepare($updateQuery);
        $stmt->bind_param(
            'sssiiii',
            $sport,
            $team_name,
            $division,
            $player_count,
            $wins,
            $losses,
            $team_id
        );

        if ($stmt->execute()) {
            $stmt->close(); // Close first statement before preparing another one

            // Update players' team names
            $updatePlayersQuery = "
                UPDATE bpslo_registrations_approved
                SET
                    team_name = ?
                WHERE
                    team_name = ?
                AND
                    division = ?";

            $stmt = $mysqli->prepare($updatePlayersQuery);
            $stmt->bind_param('sss', $team_name, $old_team_name, $division);

            if ($stmt->execute()) {
                echo "<script>alert('Team updated successfully!');</script>";
                echo "<script>window.location.href = 'organizer_edit_team.php?id=$team_id';</script>";
            } else {
                echo "<script>alert('Error updating players: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error updating team: " . $stmt->error . "');</script>";
        }
    }

    // delete
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['disband_team'])) {
        require_once '../db_connection.php'; // Ensure DB connection

        $disband_id = $_POST['disband_id']; // team's ID from hidden field

        // Step 1: Fetch the team's data from bpslo_teams
        $query = "SELECT * FROM bpslo_teams WHERE id = ?";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $disband_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $team = $result->fetch_assoc();
            $stmt->close(); // Close previous statement

            $team_name = $team['team_name'];
            $division = $team['division'];
            $sport = $team['sport'];
            $player_count = $team['player_count'];

            // Step 2: Delete players from bpslo_registrations_approved who belong to the same team and division
            $deletePlayersQuery = "DELETE FROM bpslo_registrations_approved
                                    WHERE
                                        team_name = ?
                                    AND
                                        division = ?
                                    AND
                                        sport = ?";

            $stmt = $mysqli->prepare($deletePlayersQuery);
            $stmt->bind_param('sss',
                                $team_name,
                                $division,
                                $sport,
                                );

            if ($stmt->execute()) {
                $stmt->close(); // Close statement after deleting players

                // Step 3: Delete the team from bpslo_teams
                $deleteTeamQuery = "DELETE FROM bpslo_teams WHERE id = ?";
                $stmt = $mysqli->prepare($deleteTeamQuery);
                $stmt->bind_param('i', $disband_id);

                if ($stmt->execute()) {
                    echo "<script>alert('Team successfully disbanded, and all players from this team have been removed.');</script>";
                    echo "<script>window.location.href = 'organizer_view_teams.php';</script>"; // Redirect to team list page
                } else {
                    echo "<script>alert('Error deleting team: " . $stmt->error . "');</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Error deleting players: " . $stmt->error . "');</script>";
            }
        } else {
            echo "<script>alert('Team not found.');</script>";
        }

        $mysqli->close(); // Close DB connection
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>organizer | Edit Team</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../ostyles/organizer_edit_team.css">
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
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_manage.php'">Manage Games</button> -->

                <button class="nav-btn selected">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_declined.php'">Declined Registrations</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings.php'">Bookings</button> -->
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_approved.php'">Approved Bookings</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_decline.php'">Decline Bookings</button> -->
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_change_password.php'">Change Password</button>
            </div>
            <form id="logout-form" method="POST" action="../organizer/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>
        <main class="main-content">
            <h1>Edit team</h1>
            <form class="registration-form" method="post">
                <input type="hidden" name="team-id" value="<?php echo $team['id']; ?>">



                <!-- Sport, Team Name, Division -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="sport">Sport:</label>
                        <select id="sport" name="sport" required>
                            <option value="" disabled <?php echo $team['sport'] == '' ? 'selected' : ''; ?>>Please Select</option>
                            <option value="Basketball" <?php echo $team['sport'] == 'Basketball' ? 'selected' : ''; ?>>Basketball</option>
                            <option value="Volleyball" <?php echo $team['sport'] == 'Volleyball' ? 'selected' : ''; ?>>Volleyball</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="team-name">Team Name:</label>
                        <input type="text" id="team-name" name="team-name" value="<?php echo $team['team_name']; ?>" placeholder="Enter Team Name" required>
                        <input type="hidden" id="team-name" name="old-team-name" value="<?php echo $team['team_name']; ?>" placeholder="verify required">
                    </div>

                    <div class="form-group">
                        <label for="division">Division:</label>
                        <select id="division" name="division" required>
                            <option value="" disabled <?php echo $team['division'] == '' ? 'selected' : ''; ?>>Please Select</option>
                            <option value="Women's" <?php echo $team['division'] == "Women's" ? 'selected' : ''; ?>>Women's</option>
                            <option value="Men's" <?php echo $team['division'] == "Men's" ? 'selected' : ''; ?>>Men's</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">

                    <div class="form-group">
                            <label for="player-count">No. of Players</label>
                            <input type="number" id="player-count" name="player-count" value="<?php echo $team['player_count']; ?>"  required>
                        </div>

                    <div class="form-group">
                        <label for="team-wins">Wins</label>
                        <input type="number" id="team-wins" name="wins" value="<?php echo $team['wins']; ?>"  required>
                    </div>

                    <div class="form-group">
                        <label for="team-lose">Lose</label>
                        <input type="number" id="team-lose" name="losses" value="<?php echo $team['losses']; ?>"  required>
                    </div>

                </div>

                <div class="button-container">
                    <button type="submit" name="update_team" class="action-btn update-btn">Update Team</button>
                    <!-- Fixed to prevent page reload when triggering the modal -->
                    <button type="button" class="edit-btn" onclick="openModal(<?php echo $team['id']; ?>)">Disband Team</button>
                </div>
            </form>
        </main>
    </div>

    <div id="disbandModal" class="modal">
    <div class="modal-content">
        <h3>Are you sure you want to disband this team?</h3>
        <form action="" method="POST">
            <input type="hidden" name="disband_id" id="disband_id">
            <button type="submit" class="submit-btn" name="disband_team">Yes</button>
            <button type="button" class="close-btn" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

    <script>
    function openModal(disbandId) {
        const modal = document.getElementById('disbandModal');
        const disbandInput = document.getElementById('disband_id');
        modal.style.display = 'flex';
        disbandInput.value = disbandId;
    }

    function closeModal() {
        const modal = document.getElementById('disbandModal');
        modal.style.display = 'none';
    }
</script>

    <script>
        function calculateAge() {
            const birthDateInput = document.getElementById("birth-date").value;
            if (birthDateInput) {
                const [year, month, day] = birthDateInput.split('-');
                const birthDateObj = new Date(year, month - 1, day);
                const today = new Date();
                let age = today.getFullYear() - birthDateObj.getFullYear();
                const monthDiff = today.getMonth() - birthDateObj.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDateObj.getDate())) {
                    age--;
                }
                document.getElementById("age").value = age;
            }
        }

        document.getElementById("birth-date").addEventListener("change", calculateAge);
    </script>
</body>
</html>
