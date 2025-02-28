<?php

    session_start();

    // Destroy all session data
    session_unset();
    session_destroy();

    // Redirect to the login page
    header("Location: ../admin/login.php");
    // header("Location: ../organizer/login.php");
    exit();

?>
