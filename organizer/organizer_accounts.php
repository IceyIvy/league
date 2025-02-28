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

    // Move inactive organizers (last session > 30 days) to bpslo_organizers_inactive
    $inactiveQuery = "
        INSERT INTO bpslo_organizers_inactive (
            organizer_id,
            email,
            password,
            role,
            last_session,
            created_at,
            moved_date)
        SELECT
            organizer_id,
            email,
            password,
            role,
            last_session,
            created_at,
            NOW()
        FROM bpslo_organizers
        WHERE last_session < NOW() - INTERVAL 30 DAY
    ";
    $moveInactive = $mysqli->query($inactiveQuery);

    if (!$moveInactive) {
        die("Error moving inactive organizers: " . $mysqli->error);
    }

    // Delete inactive organizers from bpslo_organizers
    $deleteQuery = "
        DELETE FROM
            bpslo_organizers
        WHERE
            last_session < NOW() - INTERVAL 30 DAY
    ";
    $deleteInactive = $mysqli->query($deleteQuery);

    if (!$deleteInactive) {
        die("Error deleting inactive organizers: " . $mysqli->error);
    }

    // Fetch all active organizers
    $activeQuery = "SELECT * FROM bpslo_organizers";
    $activeResult = $mysqli->query($activeQuery);

    if (!$activeResult) {
        die("Active query failed: " . $mysqli->error);
    }

    // Fetch all inactive organizers
    $inactiveQuery = "SELECT * FROM bpslo_organizers_inactive";
    $inactiveResult = $mysqli->query($inactiveQuery);

    if (!$inactiveResult) {
        die("Inactive query failed: " . $mysqli->error);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ostyles/organizer_accounts.css">
    <title>organizer | Accounts</title>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
        <a href="../organizer/organizer_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn selected">Accounts</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_manage.php'">Manage</button> -->


                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_organize_league.php'">Organize League</button>
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
        <main class="main-content">
            <h1>List of Organizers</h1>

            <div class="table-container">
        <h2>Active Organizer Accounts</h2>

        <!-- Table displaying active organizer data -->
        <table>
            <thead>
                <tr>
                    <th>Organizer ID</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Last Session</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $activeResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['organizer_id']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['last_session'] ? $row['last_session'] : 'No session yet'; ?></td>
                        <td>
                            <!-- <a href="organizer_edit_organizer.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit Organizer</a> -->
                            <a href="organizer_edit_organizer.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit Organizer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <h2>Inactive Organizer Accounts</h2>

        <!-- Table displaying inactive organizer data -->
        <table>
            <thead>
                <tr>
                    <th>Organizer ID</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Last Session</th>
                    <th>Moved Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $inactiveResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['organizer_id']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['last_session'] ? $row['last_session'] : 'No session yet'; ?></td>
                        <td><?php echo $row['moved_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

        </main>
    </div>


</body>
</html>