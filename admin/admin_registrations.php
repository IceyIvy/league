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
    $applicantsQuery = "SELECT * FROM bpslo_registrations";
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
    <link rel="stylesheet" href="..//styles/admin_registrations.css">
    <title>Admin | Registrations</title>

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
        <a href="../admin/admin_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">
            <button class="nav-btn" onclick="window.location.href='../admin/admin_'">Add Organizer</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_accounts.php'">Accounts</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../admin/admin_manage.php'">Manage Games</button> -->

                <button class="nav-btn" onclick="window.location.href='../admin/admin_organize_league.php'">Organize League</button>
                <button class="nav-btn selected">Registrations</button>
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

        <!-- Main Content -->
        <main class="main-content">
            <div class="table-container">
                <h2>Applicants</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Application ID</th>
                            <th>Sport</th>
                            <th>Team Name</th>
                            <th>Division</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Age</th>
                            <th>Mobile Number</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $applicantsResult->fetch_assoc()): ?>
                            <tr>
                            <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['application_id']; ?></td>
                                <td><?php echo $row['sport']; ?></td>
                                <td><?php echo $row['team_name']; ?></td>
                                <td><?php echo $row['division']; ?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['age']; ?></td>
                                <td><?php echo $row['mobile_number']; ?></td>
                                <td><?php echo $row['email_address']; ?></td>
                                <!-- <td>
                                    <a class="new-edit-btn" href="../admin/admin_edit_applicant.php?id=<?php //echo $row['id']; ?>" class="edit-btn">Edit</a>
                                </td> -->
                                <td>
                                    <a class="new-edit-btn" href="../admin/admin_edit_applicant.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                                    <style>
                                        .new-edit-btn {
                                            display: inline-block;
                                            padding: 8px 12px;
                                            background-color: royalblue;
                                            color: white;
                                            text-decoration: none;
                                            border-radius: 5px;
                                            font-weight: bold;
                                            transition: background-color 0.3s ease;
                                            border: solid royalblue 1px;
                                        }

                                        .new-edit-btn:hover {
                                            background-color: white;
                                            color: royalblue;
                                            border: solid royalblue 1px;
                                        }
                                    </style>
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
