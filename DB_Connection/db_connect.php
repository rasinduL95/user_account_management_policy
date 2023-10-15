<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "week6";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>