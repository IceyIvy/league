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

    // Fetch all bookings
    $bookingsQuery = "SELECT * FROM bpslo_bookings";
    $bookingsResult = $mysqli->query($bookingsQuery);

    if (!$bookingsResult) {
        die("Query failed: " . $mysqli->error);
    }

    // Approve Booking
    if (isset($_GET['approve_id'])) {
        $bookingId = $_GET['approve_id'];

        // Fetch the booking details
        $bookingQuery = "SELECT * FROM bpslo_bookings WHERE id = ?";
        $stmt = $mysqli->prepare($bookingQuery);
        $stmt->bind_param('i', $bookingId);
        $stmt->execute();
        $bookingResult = $stmt->get_result();

        if ($bookingResult->num_rows > 0) {
            $booking = $bookingResult->fetch_assoc();

            // Insert the booking into the approved bookings table
            $insertQuery = "INSERT INTO bpslo_approved_bookings (booking_id, date, time, name, mobile_number, email, gcash_reference, payment)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $mysqli->prepare($insertQuery);
            $insertStmt->bind_param(
                'sssssssd',
                $booking['booking_id'],
                $booking['date'],
                $booking['time'],
                $booking['name'],
                $booking['mobile_number'],
                $booking['email'],
                $booking['gcash_reference'],
                $booking['payment']
            );

            if ($insertStmt->execute()) {
                //  delete the booking from the original table
                $deleteQuery = "DELETE FROM bpslo_bookings WHERE id = ?";
                $deleteStmt = $mysqli->prepare($deleteQuery);
                $deleteStmt->bind_param('i', $bookingId);
                $deleteStmt->execute();

                header("Location: organizer_bookings.php?message=Booking approved successfully");
                exit();
            } else {
                die("Error inserting into approved bookings: " . $mysqli->error);
            }
        } else {
            die("Booking not found.");
        }
    }

    // Decline Booking
    if (isset($_POST['decline_id']) && isset($_POST['reason'])) {
        $bookingId = $_POST['decline_id'];
        $reason = $_POST['reason'];

        // Fetch the booking details
        $bookingQuery = "SELECT * FROM bpslo_bookings WHERE id = ?";
        $stmt = $mysqli->prepare($bookingQuery);
        $stmt->bind_param('i', $bookingId);
        $stmt->execute();
        $bookingResult = $stmt->get_result();

        if ($bookingResult->num_rows > 0) {
            $booking = $bookingResult->fetch_assoc();

            // Insert the booking into the declined bookings table
            $insertQuery = "INSERT INTO bpslo_declined_bookings (
                            booking_id,
                            date,
                            time,
                            name,
                            mobile_number,
                            email,
                            gcash_reference,
                            payment,
                            reason)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $insertStmt = $mysqli->prepare($insertQuery);
            $insertStmt->bind_param(
                'sssssssss',
                $booking['booking_id'],
                $booking['date'],
                $booking['time'],
                $booking['name'],
                $booking['mobile_number'],
                $booking['email'],
                $booking['gcash_reference'],
                $booking['payment'],
                $reason
            );

            if ($insertStmt->execute()) {
                // delete the booking from the original table
                $deleteQuery = "DELETE FROM bpslo_bookings WHERE id = ?";
                $deleteStmt = $mysqli->prepare($deleteQuery);
                $deleteStmt->bind_param('i', $bookingId);
                $deleteStmt->execute();

                header("Location: organizer_bookings.php?message=Booking declined successfully");
                exit();
            } else {
                die("Error inserting into declined bookings: " . $mysqli->error);
            }
        } else {
            die("Booking not found.");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ostyles/organizer_bookings.css">
    <title>organizer | Bookings</title>


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

                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_organize_league.php'">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations.php'">Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_approved.php'">Approved Registrations</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_declined.php'">Declined Registrations</button>
                <button class="nav-btn selected">Bookings</button>
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
                <h2>Bookings</h2>
                <?php if (isset($_GET['message'])): ?>
                    <div class="alert">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>
                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Name</th>
                            <th>Mobile Number</th>
                            <th>Email</th>
                            <th>Gcash Reference</th>
                            <th>Payment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $bookingsResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['booking_id']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['time']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['mobile_number']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['gcash_reference']; ?></td>
                                <td><?php echo number_format($row['payment'], 2); ?></td>
                                <td>
                                    <a href="?approve_id=<?php echo $row['id']; ?>" class="edit-btn">Approve</a><br><br>
                                    <button onclick="openModal(<?php echo $row['id']; ?>)" class="edit-btn">Decline</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Decline Modal -->
    <div id="declineModal" class="modal">
        <div class="modal-content">
            <h3>Decline Booking</h3>
            <form action="" method="POST">
                <input type="hidden" name="decline_id" id="decline_id">
                <input type="text" name="reason" placeholder="Reason for decline" required>
                <button type="submit" class="submit-btn">Submit</button>
                <button type="button" class="close-btn" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(declineId) {
            const modal = document.getElementById('declineModal');
            const declineInput = document.getElementById('decline_id');
            modal.style.display = 'flex';
            declineInput.value = declineId;
        }

        function closeModal() {
            const modal = document.getElementById('declineModal');
            modal.style.display = 'none';
        }
    </script>
</body>
</html>
