<?php
    include('../DB_Connection/db_connect.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $f_name = $_POST["f_name"];
        $l_name = $_POST["l_name"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $accountStatus = "requested";
        $emailVerification = "notverified";
    
        // Insert user data into the database. This method avoids SQL injections
        $sql = "INSERT INTO user_registration (f_name, l_name, email, username, password, account_status, email_verification) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $f_name, $l_name, $email, $username, $password, $accountStatus, $emailVerification);
    
        if ($stmt->execute()) {
            echo "Registration successful! <a href='../index.php'>Go back to the home page</a>";
        } else {
            echo "Error: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
    }
?>