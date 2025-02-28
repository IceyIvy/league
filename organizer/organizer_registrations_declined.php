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
    $applicantsQuery = "SELECT * FROM bpslo_registrations_declined";
    $applicantsResult = $mysqli->query($applicantsQuery);

    if (!$applicantsResult) {
        die("Query failed: " . $mysqli->error);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..//ostyles/organizer_registrations_declined.css">
    <title>organizer | Declined Registrations</title>

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
        <a href="../organizer/organizer_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">
            <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_accounts.php'">Accounts</button> -->
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_manage.php'">Manage Games</button> -->

                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_organize_league.php'">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn selected">Declined Registrations</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings.php'">Bookings</button> -->
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_approved.php'">Approved Bookings</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_declined.php'">Declined Bookings</button> -->
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_change_password.php'">Change Password</button>
            </div>
            <form id="logout-form" method="POST" action="../organizer/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="table-container">
                <h2>Declined Applicants</h2>
                <table>
                    <thead>
                        <tr>
                            <th>APPLICATION ID</th>
                            <th>SPORT</th>
                            <th>TEAM NAME</th>
                            <th>DIVISION</th>
                            <th>FIRST NAME</th>
                            <th>LAST NAME</th>
                            <th>MOBILE NUMBER</th>
                            <th>EMAIl</th>
                            <th>REASON</th>
                            <th>DECLINED AT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $applicantsResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['application_id']; ?></td>
                                <td><?php echo $row['sport']; ?></td>
                                <td><?php echo $row['team_name']; ?></td>
                                <td><?php echo $row['division']; ?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['mobile_number']; ?></td>
                                <td><?php echo $row['email_address']; ?></td>
                                <td><?php echo $row['reason']; ?></td>
                                <td><?php echo $row['declined_at']; ?></td>
                                <!-- <td>
                                    <a href="../organizer/organizer_edit_applicant.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
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
