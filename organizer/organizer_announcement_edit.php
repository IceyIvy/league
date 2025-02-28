<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Organizer') {
    // Redirect to the login page if unauthorized
    header("Location: ../organizer/login.php");
    exit();
}
require_once '../db_connection.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='organizer_announcement_list.php';</script>";
    exit();
}

$id = $_GET['id'];

// Fetch the existing announcement data
$query = "SELECT * FROM bpslo_posts WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Post not found!'); window.location.href='organizer_announcement_list.php';</script>";
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $what = $_POST['what'];
    $when = $_POST['when'];
    $where = $_POST['where'];
    $why = $_POST['why'];

    // Update announcement
    $updateQuery = "UPDATE bpslo_posts SET what = ?, `when` = ?, `where` = ?, why = ? WHERE id = ?";
    $updateStmt = $mysqli->prepare($updateQuery);
    $updateStmt->bind_param("ssssi", $what, $when, $where, $why, $id);

    if ($updateStmt->execute()) {
        echo "<script>alert('Announcement updated successfully!'); window.location.href='organizer_announcement_list.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating record: " . $updateStmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ostyles/organizer_announcement_edit.css">
    <title>organizer | Edit Announcement</title>
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
            <form class="form-container" method="POST">
                <h2>Edit Announcement</h2>

                <label for="what">What:</label>
                <input type="text" id="what" name="what" value="<?= htmlspecialchars($row['what']) ?>" required>

                <label for="when">When:</label>
                <input type="date" id="when" name="when" value="<?= htmlspecialchars($row['when']) ?>" required>

                <label for="where">Where:</label>
                <input type="text" id="where" name="where" value="<?= htmlspecialchars($row['where']) ?>" required>

                <label for="why">Why:</label>
                <textarea id="why" name="why" rows="4" required><?= htmlspecialchars($row['why']) ?></textarea>

                <button class="submit-btn" type="submit">Update Announcement</button>
                <!-- <a href="organizer_announcement_list.php" class="cancel-btn">Cancel</a> -->
            </form>
        </main>
    </div>
</body>
</html>
