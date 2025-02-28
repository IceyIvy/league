<?php

    // Database connection settings
    $host = 'localhost'; // Database host, usually 'localhost'
    $username = 'root';  // Your MySQL username
    $password = '';      // Your MySQL password (leave empty for local server)
    $database = 'league_database'; // The database name (ensure this matches your setup)

    // Create a connection
    $mysqli = new mysqli($host, $username, $password, $database);

    // Check if the connection was successful
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Optional: Set the character set to UTF-8 for proper encoding
    $mysqli->set_charset("utf8");

?>
