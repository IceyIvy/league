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

    // Fetch all approved bookings
    $approvedBookingsQuery = "
        SELECT
            id,
            booking_id,
            date,
            time,
            name,
            mobile_number,
            email,
            gcash_reference,
            payment,
            approved_at
        FROM
            bpslo_approved_bookings
        ORDER BY
            approved_at DESC";
    $approvedBookingsResult = $mysqli->query($approvedBookingsQuery);

    if (!$approvedBookingsResult) {
        die("Query failed: " . $mysqli->error);
    }

    // Decline Booking
    if (isset($_POST['decline_id']) && isset($_POST['reason'])) {
        $bookingId = $_POST['decline_id'];
        $reason = $_POST['reason'];

        // Fetch the booking details
        $bookingQuery = "SELECT * FROM bpslo_approved_bookings WHERE id = ?";
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
                $deleteQuery = "DELETE FROM bpslo_approved_bookings WHERE id = ?";
                $deleteStmt = $mysqli->prepare($deleteQuery);
                $deleteStmt->bind_param('i', $bookingId);
                $deleteStmt->execute();

                header("Location: organizer_bookings_approved.php?message=Booking cancelled successfully");
                exit();
            } else {
                die("Error inserting into declined bookings: " . $mysqli->error);
            }
        } else {
            die("Booking not found.");
        }
    }


// cancelation of multiple games
    // if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_game'])) {
    //     $game_date = $_POST['game_date'];  // Get the game date from the hidden field in the modal

    //     // Prepare SELECT query to get the games scheduled on the specified date
    //     $selectQuery = "SELECT * FROM bpslo_approved_bookings WHERE `date` = ?";

    //     $stmt = $mysqli->prepare($selectQuery);

    //     if (!$stmt) {
    //         $message = "Error preparing select statement: " . $mysqli->error;
    //     } else {
    //         $stmt->bind_param('s', $game_date);
    //         $stmt->execute();
    //         $result = $stmt->get_result();

    //         if ($result->num_rows > 0) {

    //             $cancellation_reason = $_POST['cancellation_reason'];

    //             // Loop through the games and insert them into the bpslo_declined_bookings table
    //             while ($row = $result->fetch_assoc()) {
    //                 $booking_id = $row['booking_id'];
    //                 $date = $row['date'];
    //                 $time = $row['time'];
    //                 $name = $row['name'];
    //                 $mobile_number = $row['mobile_number'];
    //                 $email = $row['email'];
    //                 $gcash_reference = $row['gcash_reference'];
    //                 $payment = $row['payment'];


    //                 // Insert the canceled game into bpslo_declined_bookings
    //                 $insertCancelledQuery = "
    //                     INSERT INTO bpslo_declined_bookings (
    //                         booking_id, date, `time`, name, mobile_number, email, gcash_reference, payment, reason
    //                     ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    //                 $insertStmt = $mysqli->prepare($insertCancelledQuery);
    //                 $insertStmt->bind_param('sssssssss',
    //                     $booking_id, $date, $time, $name, $mobile_number, $email, $gcash_reference, $payment, $cancellation_reason);
    //                 $insertStmt->execute();
    //                 $insertStmt->close();
    //             }

    //             // Now delete the games from bpslo_approved_bookings
    //             $deleteQuery = "DELETE FROM bpslo_approved_bookings WHERE `date` = ?";

    //             $deleteStmt = $mysqli->prepare($deleteQuery);
    //             $deleteStmt->bind_param('s', $game_date);

    //             if ($deleteStmt->execute()) {
    //                 $message = "All games scheduled for " . htmlspecialchars($game_date) . " have been successfully canceled and moved to the archive!";
    //             } else {
    //                 $message = "Error canceling games: " . $deleteStmt->error;
    //             }

    //             $deleteStmt->close();
    //         } else {
    //             $message = "No games found for the selected date.";
    //         }

    //         $stmt->close();
    //     }
    // }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ostyles/organizer_bookings_approved.css">
    <title>organizer | Approved Bookings</title>
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
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_registrations_declined.php'">Declined Registrations</button>
                <!-- <button class="nav-btn" onclick="window.location.href='organizer_bookings.php'">Bookings</button> -->
                <button class="nav-btn selected">Approved Bookings</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_bookings_declined.php'">Declined Bookings</button> -->
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_change_password.php'">Change Password</button>
            </div>
            <form id="logout-form" method="POST" action="../organizer/logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </aside>





        <!-- Main Content -->
        <main class="main-content">

        <!-- CANCEL MULTIPLE GAMES START-->
        <!-- <form class="form-container" method="POST" action="">
            <h2>Cancel Scheduled Bookings</h2>
            <?php if (isset($message)): ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <label for="game-date">Select Date:</label>
            <input type="date" id="game-date" name="game_date" required>

            <button type="button" class="submit-btn" onclick="openCancelModal()">Cancel Games</button>
        </form> -->

        <!-- Cancel MULTIPLE Confirmation Modal -->
        <div id="cancelModal" class="modal">
            <div class="modal-content">
                <h3>Are you sure you want to cancel games on this date?</h3>
                <form method="POST" action="">
                <input type="text" name="cancellation_reason" placeholder="Reason for cancelling scheduled booking" required>
                    <input type="hidden" name="cancel_id" id="cancel_id"> <!-- Set game_date to this hidden input -->
                    <input type="hidden" name="game_date" id="game_date_modal"> <!-- Pass game_date from the form -->
                    <button type="submit" class="submit-btn" name="cancel_game">Yes</button>
                    <button type="button" class="close-btn" onclick="closeCancelModal()">No</button>
                </form>
            </div>
        </div>

        <script>
            function openCancelModal() {
                const gameDate = document.getElementById('game-date').value;  // Get the selected game date
                if (gameDate) {
                    document.getElementById('cancel_id').value = gameDate;  // Set the cancel_id (game_date)
                    document.getElementById('game_date_modal').value = gameDate;  // Set the hidden game_date for the modal
                    document.getElementById('cancelModal').style.display = 'flex';  // Open the modal
                } else {
                    alert("Please select a game date.");
                }
            }

            function closeCancelModal() {
                document.getElementById('cancelModal').style.display = 'none';  // Close the modal
            }
        </script>


    <!-- CANCEL MULTIPLE GAMES END-->





            <div class="table-container">
                <h2>Approved Bookings</h2>
                <?php if (isset($_GET['message'])): ?>
                    <div class="alert">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>
                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Date <br> YY/MM/DD</th>
                            <th>Time</th>
                            <th>Name</th>
                            <th>Mobile Number</th>
                            <th>Email</th>
                            <th>Gcash Reference</th>
                            <th>Payment</th>
                            <th>Date Approved</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $approvedBookingsResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['booking_id']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['time']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['mobile_number']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['gcash_reference']; ?></td>
                                <td><?php echo number_format($row['payment'], 2); ?></td>
                                <td><?php echo $row['approved_at']; ?></td>
                                <!-- <td>
                                <button onclick="openModal(<?php echo $row['id']; ?>)" class="edit-btn">Cancel Schedule</button>
                                </td> -->
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
            <h3>Cancel Scheduled Booking</h3>
            <form action="" method="POST">
                <input type="hidden" name="decline_id" id="decline_id">
                <input type="text" name="reason" placeholder="Reason for cancelling scheduled booking" required>
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
