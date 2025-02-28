<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    // Redirect to the login page if unauthorized
    header("Location: ../admin/login.php");
    exit();
}

require_once '../db_connection.php';

    // Fetch match results
    $teamsQuery = "SELECT * FROM bpslo_match_winners";
    $teamResult = $mysqli->query($teamsQuery);

    if (!$teamResult) {
        die("Query failed: " . $mysqli->error);
    }

    // Handle deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['result_id'])) {
        $result_id = $_POST['result_id'];

        $stmt = $mysqli->prepare("DELETE FROM bpslo_match_winners WHERE id = ?");
        $stmt->bind_param('i', $result_id);

        if ($stmt->execute()) {
            echo "<script>alert('Game result deleted successfully!'); window.location.href='admin_view_game_results.php';</script>";
        } else {
            echo "<script>alert('Error deleting result: " . $stmt->error . "');</script>";
            sleep(1000);
        }

        $stmt->close();
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin_view_game_results.css">
    <title>Admin | Game Results</title>
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
                <button class="nav-btn selected">Organize League</button>
                <button class="nav-btn" onclick="window.location.href='../admin/admin_registrations.php'">Registrations</button>
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
                <h2>Game Results</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Sport</th>
                            <th>Division</th>
                            <th>Team A</th>
                            <th>Score</th>
                            <th>Team B</th>
                            <th>Score</th>
                            <th>Winner</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $teamResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['sport']) ?></td>
                                <td><?= htmlspecialchars($row['division']) ?></td>
                                <td><?= htmlspecialchars($row['team_a_name']) ?></td>
                                <td><?= htmlspecialchars($row['team_a_score']) ?></td>
                                <td><?= htmlspecialchars($row['team_b_name']) ?></td>
                                <td><?= htmlspecialchars($row['team_b_score']) ?></td>
                                <td><?= htmlspecialchars($row['winner']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td>
                                    <!-- <a href="../admin/admin_edit_game_result.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                                    <br><br> -->
                                    <button class="delete-btn" onclick="openModal(<?= $row['id'] ?>)">Delete</button>
                                    <br><br>
                                    <a href="../admin/admin_view_teams.php">Go To Teams</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">



    <!-- <div class="modal-content">
            <h3>Are you sure you want to delete this post? To confirm, type <strong>(I have changed it already)</strong></h3> -->
            <!-- <input type="hidden" name="result_id" id="delete_id">
            <input type="text" id="confirmInput" placeholder="I have changed it already" required>
            <button type="button" class="submit-btn" onclick="validateAndSubmit()">Yes</button>
            <button type="button" class="close-btn" onclick="closeModal()">No</button>
        </div>

        <script>
            function validateAndSubmit() {
                const input = document.getElementById("confirmInput").value.trim();
                if (input !== "I have changed it already") {
                    alert("Please type exactly: 'I have changed it already' to confirm.");
                    return;
                }
                document.querySelector("form").submit(); // Submit the parent form
            }
        </script> -->

        <div class="modal-content">
            <h3>Are you sure you want to delete this post? Make sure you have updated the Teams already <i>Head to Edit Team</i></h3>
            <form method="POST">
                <input type="hidden" name="result_id" id="delete_id">
                <button type="submit" class="submit-btn">Yes</button>
                <button type="button" class="close-btn" onclick="closeModal()">No</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(deleteId) {
            document.getElementById('delete_id').value = deleteId;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>

</body>
</html>
