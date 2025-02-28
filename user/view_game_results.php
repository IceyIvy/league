<?php

session_start();

// Check if the user is logged in and has the Admin role
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
//     // Redirect to the login page if unauthorized
//     header("Location: ../admin/login.php");
//     exit();
// }


    // Include the database connection file
    require_once '../db_connection.php';

    // Fetch all applicants
    $teamsQuery = "SELECT * FROM bpslo_match_winners";
    $teamResult = $mysqli->query($teamsQuery);

    if (!$teamResult) {
        die("Query failed: " . $mysqli->error);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..//styles/view_game_results.css">
    <title>Game Results</title>

</head>
<body>
    <div class="container">


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
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $teamResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['sport']; ?></td>
                                <td><?php echo $row['division']; ?></td>
                                <td><?php echo $row['team_a_name']; ?></td>
                                <td><?php echo $row['team_a_score']; ?></td>
                                <td><?php echo $row['team_b_name']; ?></td>
                                <td><?php echo $row['team_b_score']; ?></td>
                                <td><?php echo $row['winner']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <!-- <td>
                                    <a href="../admin/admin_edit_game_result.php?id=<?php echo $row['result_id']; ?>" class="edit-btn">Edit</a>
                                    <br><br>
                                    <button class="delete-btn" onclick="openModal(<?= $row['id']; ?>)">Delete Post</button>
                                </td> -->
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

     <!-- Delete Confirmation Modal -->
     <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3>Are you sure you want to delete this post?</h3>
            <form method="POST">
                <input type="hidden" name="result_id" id="delete_id">
                <button type="submit" class="submit-btn" name="delete_post">Yes</button>
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
