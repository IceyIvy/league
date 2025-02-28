<?php

    // Include the database connection file
    require_once '../db_connection.php';

    // Fetch all approved bookings
    $approvedBookingsQuery = "
        SELECT
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin_bookings_approved.css">
    <title>Approved Bookings</title>
</head>
<body>
    <div class="container">
        <!-- Main Content -->
        <main class="main-content">
            <div class="table-container">
                <h2>Approved Bookings</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Date</th>
                            <th>Time</th>
                            <!-- <th>Name</th>
                            <th>Mobile Number</th>
                            <th>Email</th>
                            <th>Gcash Reference</th>
                            <th>Payment</th> -->
                            <th>Date Approved</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $approvedBookingsResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['booking_id']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['time']; ?></td>
                                <!-- <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['mobile_number']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['gcash_reference']; ?></td>
                                <td><?php echo number_format($row['payment'], 2); ?></td> -->
                                <td><?php echo $row['approved_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
