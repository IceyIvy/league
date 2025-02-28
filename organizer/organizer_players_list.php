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
    <link rel="stylesheet" href="../ostyles/organizer_players_list.css">
    <title>organizer | Players List</title>
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
                <!-- <button class="nav-btn selected">Manage Games</button> -->

                <button class="nav-btn selected">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_declined.php'">Declined Registrations</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings.php'">Bookings</button> -->
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_approved.php'">Approved Bookings</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_declined.php'">Declined Bookings</button> -->
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_change_password.php'">Change Password</button>
            </div>
            <form id="logout-form" method="POST" action="../organizer/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>
        <main class="main-content">
        <div class="table-container">
                <h2>Players</h2>
                <table>
                    <thead>
                        <tr>
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
                                <td><?php echo $row['player_id']; ?></td>
                                <td><?php echo $row['sport']; ?></td>
                                <td><?php echo $row['team_name']; ?></td>
                                <td><?php echo $row['division']; ?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['age']; ?></td>
                                <td><?php echo $row['mobile_number']; ?></td>
                                <td><?php echo $row['email_address']; ?></td>
                                <!-- <td>
                                    <a href="../organizer/organizer_player_edit.php?id=<?php //echo $row['id']; ?>" class="edit-btn">Edit</a>
                                </td> -->

                                <td>
                                    <a class="new-edit-btn" href="../organizer/organizer_player_edit.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                                    <style>
                                        .new-edit-btn, .new-decline-btn {
                                        display: inline-block;
                                        padding: 8px 12px;
                                        font-size: 14px;
                                        font-weight: bold;
                                        text-decoration: none;
                                        border-radius: 5px;
                                        transition: background-color 0.3s ease, color 0.3s ease;
                                        border: solid 1px;
                                        cursor: pointer;
                                    }

                                    /* Approve Button */
                                    .new-edit-btn {
                                        background-color: royalblue;
                                        color: white;
                                        border-color: royalblue;
                                    }

                                    .new-edit-btn:hover {
                                        background-color: white;
                                        color: royalblue;
                                    }

                                    /* Decline Button */
                                    .new-decline-btn {
                                        background-color: crimson;
                                        color: white;
                                        border-color: crimson;
                                        border: solid crimson 1px;
                                    }

                                    .new-decline-btn:hover {
                                        background-color: white;
                                        color: crimson;
                                    }
                                    </style>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
        </main>
    </div>
</body>
</html>
