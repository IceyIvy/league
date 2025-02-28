<?php

    session_start();

        // Check if the user is logged in and has the Admin role
        // if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
        //     // Redirect to the login page if unauthorized
        //     header("Location: ../admin/login.php");
        //     exit();
        // }

// Include database connection file
require_once '../db_connection.php'; // Ensure correct database connection

// Handle Delete Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $deleteQuery = "DELETE FROM bpslo_league WHERE id = ?";
    $stmt = $mysqli->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Post deleted successfully!'); window.location.href = 'admin_league_list.php';</script>";
    } else {
        echo "<script>alert('Error deleting post!');</script>";
    }
    $stmt->close();
}

// Fetch league announcements from the database
$query = "SELECT id, who, what, `when`, `where`, description, created_at FROM bpslo_league ORDER BY `when` DESC";
$result = $mysqli->query($query);

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin_league_list.css">
    <title>Admin | League List</title>
    
</head>
<body>
    <div class="container">
        <h2>League Announcements</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="announcement">
                    <h3><?= htmlspecialchars($row['what']) ?></h3>
                    <p><strong>Who:</strong> <?= htmlspecialchars($row['who']) ?></p>
                    <p><strong>What:</strong> <?= htmlspecialchars($row['what']) ?></p>
                    <p><strong>When:</strong> <?= htmlspecialchars($row['when']) ?></p>
                    <p><strong>Where:</strong> <?= htmlspecialchars($row['where']) ?></p>
                    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    <p><small><em>Posted on: <?= htmlspecialchars($row['created_at']) ?></em></small></p>

                    <!-- Edit Button -->
                    <button><a href="../admin/admin_league_edit.php?id=<?= $row['id']; ?>">Edit Post</a></button>

                    <!-- Delete Button -->
                    <button class="delete-btn" onclick="openModal(<?= $row['id']; ?>)">Delete Post</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No league announcements found.</p>
        <?php endif; ?>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3>Are you sure you want to delete this post?</h3>
            <form method="POST">
                <input type="hidden" name="delete_id" id="delete_id">
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
