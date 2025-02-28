<?php

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
    <link rel="stylesheet" href="..//styles/applicants_approved.css">
    <title>Applicants Approved</title>

</head>
<body>
    <div class="container">
        <!-- Main Content -->
        <main class="main-content">
            <div class="table-container">
                <h2>Approved Applicants</h2>
                <table>
                    <thead>
                        <tr>
                            <th>PLAYER ID</th>
                            <th>SPORT</th>
                            <th>TEAM NAME</th>
                            <th>DIVISION</th>
                            <th>FIRST NAME</th>
                            <th>LAST NAME</th>
                            <!-- <th>AGE</th>
                            <th>MOBILE NUMBER</th>
                            <th>EMAIl</th> -->
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
                                <!-- <td><?php echo $row['age']; ?></td>
                                <td><?php echo $row['mobile_number']; ?></td>
                                <td><?php echo $row['email_address']; ?></td> -->
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
