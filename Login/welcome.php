<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; text-align: center; }
        .container {
    display: flex;
    justify-content: center;
    align-items: center;
}
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <div class="container" >

            <?php
            // Check the type value and display HTML elements accordingly
            if ($_SESSION["role"] == 1) {
                echo '
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <a href="../Admin/deactivate.php" class="btn btn-warning btn-block">Remove User</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <a href="../Admin/logs.php" class="btn btn-warning btn-block">Check Logs</a>
                        </div>
                    </div>
                </div>';
            }
            ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <a href="security_questions_form.php" class="btn btn-warning btn-block">Reset Your Password</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <a href="logout.php" class="btn btn-danger btn-block">Sign Out</a>
                    </div>
                </div>
            </div>

    </div>
</body>
</html>
