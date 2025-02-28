<?php

    session_start();

    // Check if the user is logged in and has the organizer role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Organizer') {
        // Redirect to the login page if unauthorized
        header("Location: ../organizer/login.php");
        exit();
    }

    require_once '../db_connection.php'; // Database connection file

    $applicant = [];

    // Check if an ID is provided in the URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Secure the query using prepared statements
        $query = "SELECT * FROM bpslo_registrations WHERE id = ?";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $applicant = $result->fetch_assoc();
        } else {
            echo "<script>alert('Applicant not found.');</script>";
        }

        $stmt->close();
    }

    // Handle the form submission for updating
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_applicant'])) {
        $sport = $_POST['sport'];
        $team_name = $_POST['team-name'];
        $division = $_POST['division'];
        $first_name = $_POST['first-name'];
        $middle_name = $_POST['middle-name'];
        $last_name = $_POST['last-name'];
        $birth_date = $_POST['birth-date'];
        $age = $_POST['age'];
        $sex = $_POST['sex'];
        $sitio = $_POST['sitio'];
        $mobile_number = $_POST['mobile-number'];
        $email_address = $_POST['email-address'];
        $applicant_id = $_POST['applicant-id']; // Hidden input

        $updateQuery = "
            UPDATE bpslo_registrations
            SET
                sport = ?,
                team_name = ?,
                division = ?,
                first_name = ?,
                middle_name = ?,
                last_name = ?,
                birth_date = ?,
                age = ?,
                sex = ?,
                sitio = ?,
                mobile_number = ?,
                email_address = ?
            WHERE id = ?";

        $stmt = $mysqli->prepare($updateQuery);
        $stmt->bind_param(
            'ssssssssssssi',
            $sport,
            $team_name,
            $division,
            $first_name,
            $middle_name,
            $last_name,
            $birth_date,
            $age,
            $sex,
            $sitio,
            $mobile_number,
            $email_address,
            $applicant_id
        );

        if ($stmt->execute()) {
            echo "<script>alert('Applicant updated successfully!');</script>";
            echo "<script>window.location.href = 'organizer_edit_applicant.php?id=$applicant_id';</script>";
        } else {
            echo "<script>alert('Error updating applicant: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }

    // Approve applicant and update player_count in bpslo_teams
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_applicant'])) {
        require_once '../db_connection.php'; // Ensure DB connection

        $player_id = uniqid('PLAYERID');
        $sport = $_POST['sport'];
        $team_name = $_POST['team-name'];
        $division = $_POST['division'];
        $first_name = $_POST['first-name'];
        $middle_name = $_POST['middle-name'];
        $last_name = $_POST['last-name'];
        $birth_date = $_POST['birth-date'];
        $age = $_POST['age'];
        $sex = $_POST['sex'];
        $sitio = $_POST['sitio'];
        $mobile_number = $_POST['mobile-number'];
        $email_address = $_POST['email-address'];
        $applicant_id = $_POST['applicant-id']; // Hidden input

        // Insert the approved applicant into bpslo_registrations_approved
        $insertQuery = "
            INSERT INTO bpslo_registrations_approved (
                player_id,
                sport,
                team_name,
                division,
                first_name,
                middle_name,
                last_name,
                birth_date,
                age,
                sex,
                sitio,
                mobile_number,
                email_address
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($insertQuery);

        if (!$stmt) {
            die("<script>alert('Prepare failed: " . $mysqli->error . "');</script>");
        }

        $stmt->bind_param('sssssssssssss',
            $player_id,
            $sport,
            $team_name,
            $division,
            $first_name,
            $middle_name,
            $last_name,
            $birth_date,
            $age,
            $sex,
            $sitio,
            $mobile_number,
            $email_address
        );

        if ($stmt->execute()) {
            $stmt->close(); // Close the statement before proceeding

            // Step 2: Update the player count in bpslo_teams
            $updateTeamQuery = "
                UPDATE bpslo_teams
                SET
                    player_count = player_count + 1
                WHERE
                    sport = ?
                AND
                    team_name = ?
                AND
                    division = ?";

            $updateStmt = $mysqli->prepare($updateTeamQuery);
            $updateStmt->bind_param('sss', $sport, $team_name, $division);

            if ($updateStmt->execute()) {
                $updateStmt->close();

                // Step 3: Delete the applicant from bpslo_registrations
                $deleteQuery = "DELETE FROM bpslo_registrations WHERE id = ?";

                $deleteStmt = $mysqli->prepare($deleteQuery);

                if ($deleteStmt) {
                    $deleteStmt->bind_param('i', $applicant_id);
                    if ($deleteStmt->execute()) {
                        echo "<script>alert('Applicant successfully approved, added to the team, and removed from pending applications.');</script>";
                        echo "<script>window.location.href = 'organizer_registrations.php';</script>";
                    } else {
                        echo "<script>alert('Error deleting applicant: " . $deleteStmt->error . "');</script>";
                    }
                    $deleteStmt->close();
                } else {
                    echo "<script>alert('Prepare for deletion failed: " . $mysqli->error . "');</script>";
                }
            } else {
                echo "<script>alert('Error updating player count: " . $updateStmt->error . "');</script>";
            }
        } else {
            echo "<script>alert('Error inserting applicant: " . $stmt->error . "');</script>";
        }
    }




    // delete
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decline_applicant'])) {
        $decline_id = $_POST['decline_id']; // Applicant's ID from hidden field
        $reason = $_POST['reason']; // Reason for decline from form

        // Step 1: Fetch the applicant's data from bpslo_registrations
        $query = "SELECT * FROM bpslo_registrations WHERE id = ?";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $decline_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $applicant = $result->fetch_assoc();

            // Step 2: Insert the applicant's data into bpslo_registrations_declined
            $insertDeclinedQuery = "
                INSERT INTO bpslo_registrations_declined(
                                    application_id,
                                    sport,
                                    team_name,
                                    division,
                                    first_name,
                                    middle_name,
                                    last_name,
                                    mobile_number,
                                    email_address,
                                    reason)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $mysqli->prepare($insertDeclinedQuery);
            $stmt->bind_param(
                'ssssssssss',
                $applicant['application_id'],
                $applicant['sport'],
                $applicant['team_name'],
                $applicant['division'],
                $applicant['first_name'],
                $applicant['middle_name'],
                $applicant['last_name'],
                $applicant['mobile_number'],
                $applicant['email_address'],
                $reason
            );

            if ($stmt->execute()) {
                // Step 3: After inserting into declined table, delete the applicant from the registrations table
                $deleteQuery = "DELETE FROM bpslo_registrations WHERE id = ?";
                $stmt = $mysqli->prepare($deleteQuery);
                $stmt->bind_param('i', $decline_id);
                $stmt->execute();

                // Success message
                echo "<script>alert('Applicant successfully declined and moved to the declined list.');</script>";
                echo "<script>window.location.href = 'organizer_registrations.php';</script>"; // Redirect to applicant list page
            } else {
                // Error handling
                echo "<script>alert('Error declining applicant: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Applicant not found.');</script>";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>organizer | Edit Applicant</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../ostyles/organizer_edit_applicant.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
        <a href="../organizer/organizer_dashboard.php">
                <img src="../images/logo.png" alt="Sports League Organizer Logo" class="logo">
            </a>
            <div class="nav-buttons">
            <button class="nav-btn" onclick="window.location.href='../organizer/organizer_add_organizer.php'">Add Organizer</button>
                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_accounts.php'">Accounts</button>
                <!-- <button class="nav-btn" onclick="window.location.href='../organizer/organizer_manage.php'">Manage Games</button> -->


                <button class="nav-btn" onclick="window.location.href='../organizer/organizer_organize_league.php'">Organize League</button>
                <button class="nav-btn selected">Registrations</button>
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
            <h1>Edit Applicant</h1>
            <form class="registration-form" method="post">
                <input type="hidden" name="applicant-id" value="<?php echo $applicant['id']; ?>">



                <!-- Sport, Team Name, Division -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="sport">Sport:</label>
                        <select id="sport" name="sport" required>
                            <option value="" disabled <?php echo $applicant['sport'] == '' ? 'selected' : ''; ?>>Please Select</option>
                            <option value="Basketball" <?php echo $applicant['sport'] == 'Basketball' ? 'selected' : ''; ?>>Basketball</option>
                            <option value="Volleyball" <?php echo $applicant['sport'] == 'Volleyball' ? 'selected' : ''; ?>>Volleyball</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="team-name">Team Name:</label>
                        <input type="text" id="team-name" name="team-name" value="<?php echo $applicant['team_name']; ?>" placeholder="Enter Team Name" required>
                    </div>

                    <div class="form-group">
                        <label for="division">Division:</label>
                        <select id="division" name="division" required>
                            <option value="" disabled <?php echo $applicant['division'] == '' ? 'selected' : ''; ?>>Please Select</option>
                            <option value="Women's" <?php echo $applicant['division'] == "Women's" ? 'selected' : ''; ?>>Women's</option>
                            <option value="Men's" <?php echo $applicant['division'] == "Men's" ? 'selected' : ''; ?>>Men's</option>
                        </select>
                    </div>
                </div>



                <!-- Player Name -->
                <div class="form-row">
                    <div class="form-group full-width">
                        <label>Player Name:</label>
                        <div class="name-fields">
                            <input type="text" id="first-name" name="first-name" value="<?php echo $applicant['first_name']; ?>" placeholder="First Name" required>
                            <input type="text" id="middle-name" name="middle-name" value="<?php echo $applicant['middle_name']; ?>" placeholder="Middle Name">
                            <input type="text" id="last-name" name="last-name" value="<?php echo $applicant['last_name']; ?>" placeholder="Last Name" required>
                        </div>
                    </div>
                </div>

                <!-- Other Fields -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="birth-date">Birth Date:</label>
                        <input type="date" id="birth-date" name="birth-date" value="<?php echo $applicant['birth_date']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="text" id="age" name="age" value="<?php echo $applicant['age']; ?>" readonly required>
                    </div>

                    <div class="form-group">
                        <label for="sex">Sex:</label>
                        <select id="sex" name="sex" required>
                            <option value="Male" <?php echo $applicant['sex'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $applicant['sex'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                <div class="form-group">
                    <label for="sitio">Sitio:</label>
                    <select id="sitio" name="sitio" required>
                        <option value="" disabled>Select Sitio</option>
                        <option value="L. Flores" <?php echo ($applicant['sitio'] == "L. Flores") ? "selected" : ""; ?>>L. Flores</option>
                        <option value="Mahayahay 1" <?php echo ($applicant['sitio'] == "Mahayahay 1") ? "selected" : ""; ?>>Mahayahay 1</option>
                        <option value="Mahayahay 2" <?php echo ($applicant['sitio'] == "Mahayahay 2") ? "selected" : ""; ?>>Mahayahay 2</option>
                        <option value="L. Sun-ok" <?php echo ($applicant['sitio'] == "L. Sun-ok") ? "selected" : ""; ?>>L. Sun-ok</option>
                        <option value="L. Puthawan" <?php echo ($applicant['sitio'] == "L. Puthawan") ? "selected" : ""; ?>>L. Puthawan</option>
                        <option value="Magsaysay" <?php echo ($applicant['sitio'] == "Magsaysay") ? "selected" : ""; ?>>Magsaysay</option>
                        <option value="C. Groove Street" <?php echo ($applicant['sitio'] == "C. Groove Street") ? "selected" : ""; ?>>C. Groove Street</option>
                        <option value="C. Riverside" <?php echo ($applicant['sitio'] == "C. Riverside") ? "selected" : ""; ?>>C. Riverside</option>
                        <option value="C. Stallion" <?php echo ($applicant['sitio'] == "C. Stallion") ? "selected" : ""; ?>>C. Stallion</option>
                        <option value="T. Cavan" <?php echo ($applicant['sitio'] == "T. Cavan") ? "selected" : ""; ?>>T. Cavan</option>
                        <option value="Rallos" <?php echo ($applicant['sitio'] == "Rallos") ? "selected" : ""; ?>>Rallos</option>
                        <option value="Truman" <?php echo ($applicant['sitio'] == "Truman") ? "selected" : ""; ?>>Truman</option>
                    </select>
                </div>



                    <div class="form-group">
                        <label for="mobile-number">Mobile Number:</label>
                        <input type="text" id="mobile-number" name="mobile-number" value="<?php echo $applicant['mobile_number']; ?>" placeholder="Mobile Number" required>
                    </div>

                    <div class="form-group">
                        <label for="email-address">Email Address:</label>
                        <input type="text" id="email-address" name="email-address" value="<?php echo $applicant['email_address']; ?>" placeholder="Email Address" required>
                    </div>
                </div>

                <!-- Display Images -->
                <div class="form-row">
                    <div class="form-group full-width">
                        <label>Uploaded Images:</label>
                        <div class="image-container">
                            <?php if ($applicant['photo']): ?>
                                <div class="image-item">
                                    <label>Image 1 (Photo):</label>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($applicant['photo']); ?>" alt="Applicant Photo">
                                </div>
                            <?php endif; ?>

                            <?php if ($applicant['nso']): ?>
                                <div class="image-item">
                                    <label>Image 2 (NSO):</label>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($applicant['nso']); ?>" alt="NSO Document">
                                </div>
                            <?php endif; ?>

                            <?php if ($applicant['voter_cert']): ?>
                                <div class="image-item">
                                    <label>Image 3 (Voter Cert):</label>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($applicant['voter_cert']); ?>" alt="Voter Certificate">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="button-container">
                    <button type="submit" name="update_applicant" class="action-btn update-btn">Update Applicant</button>
                    <button type="submit" name="approve_applicant" class="action-btn update-btn">Approve Applicant</button>
                    <!-- Fixed to prevent page reload when triggering the modal -->
                    <button type="button" class="edit-btn" onclick="openModal(<?php echo $applicant['id']; ?>)">Decline Applicant</button>
                </div>
            </form>
        </main>
    </div>

    <div id="declineModal" class="modal">
    <div class="modal-content">
        <h3>Decline Registration</h3>
        <form action="" method="POST">
            <input type="hidden" name="decline_id" id="decline_id">
            <input type="text" name="reason" placeholder="Reason for decline" required>
            <button type="submit" class="submit-btn" name="decline_applicant">Submit</button>
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

    <script>
        function calculateAge() {
            const birthDateInput = document.getElementById("birth-date").value;
            if (birthDateInput) {
                const [year, month, day] = birthDateInput.split('-');
                const birthDateObj = new Date(year, month - 1, day);
                const today = new Date();
                let age = today.getFullYear() - birthDateObj.getFullYear();
                const monthDiff = today.getMonth() - birthDateObj.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDateObj.getDate())) {
                    age--;
                }
                document.getElementById("age").value = age;
            }
        }

        document.getElementById("birth-date").addEventListener("change", calculateAge);
    </script>
</body>
</html>
