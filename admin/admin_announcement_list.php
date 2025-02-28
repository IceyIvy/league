<?php

    session_start();

        // Check if the user is logged in and has the Admin role
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
            // Redirect to the login page if unauthorized
            header("Location: ../admin/login.php");
            exit();
        }

// Include database connection file
require_once '../db_connection.php'; // Ensure correct database connection

// Handle Delete Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $deleteQuery = "DELETE FROM bpslo_posts WHERE id = ?";
    $stmt = $mysqli->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Post deleted successfully!'); window.location.href = 'admin_announcement_list.php';</script>";
    } else {
        echo "<script>alert('Error deleting post!');</script>";
    }
    $stmt->close();
}

$query = "SELECT
                id,
                what,
                `when`,
                `where`,
                why,
                created_at
            FROM bpslo_posts
            ORDER BY
            `when` DESC";

    $result = $mysqli->query($query);


// Close the database connection
$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="..//styles/admin_announcement_list.css">
    <title>Admin | Announcements List</title>

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
                <!-- <button class="nav-btn" onclick="window.location.href='../admin/admin_manage.php'">Manage Games</button> -->

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
        <h2>Match Announcements List</h2>


        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="announcement">
                    <h3><?= htmlspecialchars($row['what']) ?></h3>
                    <p><strong>When:</strong> <?= htmlspecialchars($row['when']) ?></p>
                    <p><strong>Where:</strong> <?= htmlspecialchars($row['where']) ?></p>
                    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($row['why'])) ?></p>
                    <p><small><em>Posted on: <?= htmlspecialchars($row['created_at']) ?></em></small></p>

                    <!-- Edit Button -->
                    <button><a href="../admin/admin_announcement_edit.php?id=<?= $row['id']; ?>">Edit Post</a></button>

                    <!-- Delete Button -->
                    <button class="delete-btn" onclick="openModal(<?= $row['id']; ?>)">Delete Post</button>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <p>No league announcements found.</p>
        <?php endif; ?>
        </main>

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
