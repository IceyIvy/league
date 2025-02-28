<?php

    session_start();

    // Check if the user is logged in and has the organizer role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Organizer') {
        // Redirect to the login page if unauthorized
        header("Location: ../organizer/login.php");
        exit();
    }

    include '../db_connection.php'; // Database connection file

    $applicant = [];

    // Check if an ID is provided in the URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Secure the query using prepared statements
        $query = "SELECT * FROM bpslo_registrations_approved WHERE id = ?";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $applicant = $result->fetch_assoc();
        } else {
            echo "<script>alert('Player not found.');</script>";
        }

        $stmt->close();
    }

    // Handle the form submission for updating
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_player'])) {
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
        $player_id = $_POST['player-id']; // Hidden input

        $updateQuery = "
            UPDATE bpslo_registrations_approved
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
        $stmt->bind_param('ssssssssssssi',
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
            $player_id
        );

        if ($stmt->execute()) {
            echo "<script>alert('Player updated successfully!');</script>";
            echo "<script>window.location.href = 'organizer_player_edit.php?id=$player_id';</script>";
        } else {
            echo "<script>alert('Error updating player: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }



    // Delete player
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_player'])) {
        require_once '../db_connection.php'; // Ensure DB connection

        $decline_id = $_POST['decline_id']; // Applicant's ID from hidden field

        // Step 1: Fetch the applicant's data from bpslo_registrations_approved
        $query = "SELECT * FROM bpslo_registrations_approved WHERE id = ?";
        $stmt = $mysqli->prepare($query);

        if (!$stmt) {
            die("<script>alert('Prepare failed: " . $mysqli->error . "');</script>");
        }

        $stmt->bind_param('i', $decline_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $applicant = $result->fetch_assoc();
            $stmt->close(); // Close the select statement

            $sport = $applicant['sport'];
            $team_name = $applicant['team_name'];
            $division = $applicant['division'];

            // Step 2: Delete the applicant from the bpslo_registrations_approved table
            $deleteQuery = "DELETE FROM bpslo_registrations_approved WHERE id = ?";
            $deleteStmt = $mysqli->prepare($deleteQuery);

            if (!$deleteStmt) {
                die("<script>alert('Prepare failed for delete: " . $mysqli->error . "');</script>");
            }

            $deleteStmt->bind_param('i', $decline_id);

            if ($deleteStmt->execute()) {
                $deleteStmt->close(); // Close delete statement

                // Step 3: Update the player count in bpslo_teams
                $updateTeamQuery = "
                    UPDATE bpslo_teams
                    SET
                    player_count = player_count - 1 WHERE sport = ?
                    AND team_name = ?
                    AND division = ?
                    AND player_count > 0";

                $updateStmt = $mysqli->prepare($updateTeamQuery);

                if (!$updateStmt) {
                    die("<script>alert('Prepare failed for update: " . $mysqli->error . "');</script>");
                }

                $updateStmt->bind_param('sss', $sport, $team_name, $division);

                if ($updateStmt->execute()) {
                    echo "<script>alert('Player successfully removed and team count updated.');</script>";
                } else {
                    echo "<script>alert('Error updating team player count: " . $updateStmt->error . "');</script>";
                }

                $updateStmt->close(); // Close update statement
            } else {
                echo "<script>alert('Error removing player: " . $deleteStmt->error . "');</script>";
            }
        } else {
            echo "<script>alert('Player not found.');</script>";
        }

        $mysqli->close(); // Close DB connection
        echo "<script>window.location.href = 'organizer_players_list.php';</script>"; // Redirect to player list page
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>organizer | Edit Player</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../ostyles/organizer_player_edit.css">

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
            <h1>Update Player</h1>
            <form class="registration-form" method="post">
                <input type="hidden" name="player-id" value="<?php echo $applicant['id']; ?>">



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
                        <input type="text" id="sitio" name="sitio" value="<?php echo $applicant['sitio']; ?>" placeholder="Sitio" required>
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
                <!-- <div class="form-row">
                    <div class="form-group full-width">
                        <label>Uploaded Images:</label>
                        <div class="image-container">
                            <?//php if ($applicant['photo']): ?>
                                <div class="image-item">
                                    <label>Image 1 (Photo):</label>
                                    <img src="data:image/jpeg;base64,<? //php echo base64_encode($applicant['photo']); ?>" alt="Applicant Photo">
                                </div>
                            <?//php endif; ?>

                            <?//php if ($applicant['nso']): ?>
                                <div class="image-item">
                                    <label>Image 2 (NSO):</label>
                                    <img src="data:image/jpeg;base64,<?//php echo base64_encode($applicant['nso']); ?>" alt="NSO Document">
                                </div>
                            <?//php endif; ?>

                            <?//php if ($applicant['voter_cert']): ?>
                                <div class="image-item">
                                    <label>Image 3 (Voter Cert):</label>
                                    <img src="data:image/jpeg;base64,<?//php echo base64_encode($applicant['voter_cert']); ?>" alt="Voter Certificate">
                                </div>
                            <?//php endif; ?>
                        </div>
                    </div>
                </div> -->

                <div class="button-container">
                    <button type="submit" name="update_player" class="action-btn update-btn">Update Player</button>
                    <!-- Fixed to prevent page reload when triggering the modal -->
                    <button type="button" class="edit-btn" onclick="openModal(<?php echo $applicant['id']; ?>)">Delete Player</button>
                </div>
            </form>
        </main>
    </div>

    <div id="declineModal" class="modal">
    <div class="modal-content">
        <h3>Are you sure you want to delete this player?</h3>
        <form action="" method="POST">
            <input type="hidden" name="decline_id" id="decline_id">
            <button type="submit" class="submit-btn" name="delete_player">Yes</button>
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
