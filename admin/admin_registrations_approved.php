<?php

session_start();

// Check if the user is logged in and has the Admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    // Redirect to the login page if unauthorized
    header("Location: ../admin/login.php");
    exit();
}

    // Include the database connection file
    require_once '../db_connection.php';

    // Fetch all applicants
    $applicantsQuery = "SELECT * FROM bpslo_registrations_approved";
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
    <link rel="stylesheet" href="..//styles/admin_registrations_approved.css">
    <title>Admin | Approved Registrations</title>

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
        <a href="../admin/admin_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">

            <button class="nav-btn" onclick="window.location.href='../admin/admin_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_accounts.php'">Accounts</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../admin/admin_manage.php'">Manage Games</button> -->


                <button class="nav-btn" onclick="window.location.href='../admin/admin_organize_league.php'">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_registrations.php'">Registrations</button>
                <button class="nav-btn selected">Approved Registrations</button>
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

        <!-- Main Content -->
        <main class="main-content">
            <div class="table-container">
                <h2>Approved Applicants</h2>
                <table>
                    <thead>
                        <tr>
                            <!-- <th>No.</th> -->
                            <th>PLAYER ID</th>
                            <th>SPORT</th>
                            <th>TEAM NAME</th>
                            <th>DIVISION</th>
                            <th>FIRST NAME</th>
                            <th>LAST NAME</th>
                            <th>AGE</th>
                            <th>MOBILE NUMBER</th>
                            <th>EMAIl</th>
                            <th>APPROVED AT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $applicantsResult->fetch_assoc()): ?>
                            <tr>

                                <td><?php echo $row['player_id']; ?></td>
                                <td><?php echo $row['sport']; ?></td>
                                <td><?php echo $row['team_name']; ?></td>
                                <td><?php echo $row['division']; ?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['age']; ?></td>
                                <td><?php echo $row['mobile_number']; ?></td>
                                <td><?php echo $row['email_address']; ?></td>
                                <td><?php echo $row['approved_at']; ?></td>
                                <!-- <td>
                                    <a href="../admin/admin_edit_applicant.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
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
