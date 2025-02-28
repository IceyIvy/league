<?php

    session_start();

    // Check if the user is logged in and has the Admin role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
        // Redirect to the login page if unauthorized
        header("Location: ../admin/login.php");
        exit();
    }

    // Database connection
    require_once '../db_connection.php'; // Ensure it contains the correct connection setup

    // Fetch team names for dropdown
    $query = "SELECT
        id,
        team_name
    FROM
        bpslo_teams
    ORDER BY
        team_name
    ASC";

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
        $when = $_POST['when'];
        $time = $_POST['time'];
        $description = trim($_POST['description']);

        // Validate required fields
        if (!empty($team_a_id) && !empty($team_b_id) && !empty($when) && !empty($time) && !empty($description)) {
            if ($team_a_id !== $team_b_id) {
                // Fetch team names based on their IDs
                $stmt = $mysqli->prepare("SELECT
                                            team_name
                                        FROM
                                            bpslo_teams
                                        WHERE id = ?");

                $stmt->bind_param('i', $team_a_id);
                $stmt->execute();
                $stmt->bind_result($team_a_name);
                $stmt->fetch();
                $stmt->close();

                $stmt = $mysqli->prepare("SELECT
                                            team_name
                                        FROM
                                            bpslo_teams
                                        WHERE id = ?");

                $stmt->bind_param('i', $team_b_id);
                $stmt->execute();
                $stmt->bind_result($team_b_name);
                $stmt->fetch();
                $stmt->close();

                $match_id = uniqid('MATCH_');  // Create a unique application ID

                // Insert into the match schedule table
                $query = "INSERT INTO bpslo_match_schedules (
                                        match_id,
                                        team_a,
                                        team_b,
                                        `when`,
                                        `time`,
                                        description)
                                    VALUES (?, ?, ?, ?, ?, ?)";

                $stmt = $mysqli->prepare($query);
                $stmt->bind_param('ssssss',
                                    $match_id,
                                    $team_a_name,
                                    $team_b_name,
                                    $when,
                                    $time,
                                    $description);

                if ($stmt->execute()) {
                    $success_message = "Match schedule created and announced successfully!";
                } else {
                    $error_message = "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $error_message = "Team A and Team B cannot be the same.";
            }
        } else {
            $error_message = "Please fill in all the fields.";
        }

        $mysqli->close();
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin_add_schedule_announcement.css">
    <title>Admin | Add Schedule Announcement</title>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
        <a href="../admin/admin_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">

            <button class="nav-btn" onclick="window.location.href='../admin/admin_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_accounts.php'">Accounts</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../admin/admin_manage.php'">Manage Games</button> -->

                <button class="nav-btn selected">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_registrations_declined.php'">Declined Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_bookings.php'">Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_bookings_approved.php'">Approved Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_bookings_declined.php'">Declined Bookings</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_change_password.php'">Change Password</button>
            </div>
            <form id="logout-form" method="POST" action="../admin/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>
        <main class="main-content">
            <!-- <form class="form-container" method="POST">
                <label for="team_a">Team A Name:</label>
                <input type="text" id="team_a" name="team_a" required>

                <label for="">VS</label>

                <label for="team_b">Team B Name:</label>
                <input type="text" id="team_b" name="team_b" required>

                <label for="when">When:</label>
                <input type="date" id="when" name="when" required>

                <label for="where">Time:</label>
                <input type="time" id="time" name="time" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>

                <button class="submit-btn" type="submit" value="create_announce">Create and Announce Match Schedule</button>
            </form> -->

            <form class="form-container" method="POST">
            <h2>Post Match Schedule</h2>
            <label for="team_a">Select Team A:</label>
            <select id="team_a" name="team_a" required>
                <option value="" disabled selected>Select Team A</option>
                <?php foreach ($teams as $team): ?>
                    <option value="<?php echo $team['id']; ?>"><?php echo htmlspecialchars($team['team_name']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="">VS</label>

            <label for="team_b">Select Team B:</label>
            <select id="team_b" name="team_b" required>
                <option value="" disabled selected>Select Team B</option>
                <?php foreach ($teams as $team): ?>
                    <option value="<?php echo $team['id']; ?>"><?php echo htmlspecialchars($team['team_name']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="when">When:</label>
            <input type="date" id="when" name="when" required>

            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <button class="submit-btn" type="submit">Create and Announce Match Schedule</button>
        </form>

            <!-- Display messages -->
    <?php if (isset($success_message)): ?>
        <script>alert('<?php echo $success_message; ?>');</script>
    <?php elseif (isset($error_message)): ?>
        <script>alert('<?php echo $error_message; ?>');</script>
    <?php endif; ?>

        </main>
    </div>



</body>
</html>