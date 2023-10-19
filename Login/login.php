<?php
// Initialize the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

// Include config file
include('../DB_Connection/db_connect.php');

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password, failed_login_attempts, is_locked, lockout_timestamp, password_changed_date,email_verification FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $failed_login_attempts, $is_locked, $lockout_timestamp, $password_changed_date, $email_verified);

                    if (mysqli_stmt_fetch($stmt)) {

                        // Prepare and execute a query to get the lockout duraion from the password policy table
                        $lockoutQuery = "SELECT lockout_duration_minutes FROM password_policy WHERE is_active = ?";
                        if ($stmt = $conn->prepare($lockoutQuery)) {
                            $isActive = 1;
                            $stmt->bind_param("i", $isActive);
                            $stmt->execute();
                            $stmt->bind_result($lockoutDuraion);
                            $stmt->fetch();
                            $stmt->close();
                        } else {
                            die("Error preparing the policy query: " . $conn->error);
                        }

                        if ($is_locked && (time() - strtotime($lockout_timestamp)) < ($lockoutDuraion * 60)) {
                            // Account is locked
                            $login_err = "Your account is temporarily locked. Please try again later.";
                        } elseif (!isEmailVerified($email_verified)) {
                            $login_err = "Please verify your email to login.";
                        } elseif (password_verify($password, $hashed_password)) {

                            // Prepare and execute a query to get the password expiration duration from the password policy table
                            $policyQuery = "SELECT expiration_days FROM password_policy WHERE is_active = ?";
                            if ($stmt = $conn->prepare($policyQuery)) {
                                $isActive = 1;
                                $stmt->bind_param("i", $isActive);
                                $stmt->execute();
                                $stmt->bind_result($expirationDays);
                                $stmt->fetch();
                                $stmt->close();
                            } else {
                                die("Error preparing the policy query: " . $conn->error);
                            }

                            // Fetch the user's password change date from the user table (replace with your table and column names)
                            $userQuery = "SELECT password_changed_date FROM users WHERE id = ?";
                            if ($stmt = $conn->prepare($userQuery)) {
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                                $stmt->bind_result($passwordChangeDate);
                                $stmt->fetch();
                                $stmt->close();
                            } else {
                                die("Error preparing the user query: " . $conn->error);
                            }

                            if (isPasswordExpired($passwordChangeDate, $expirationDays)) {
                                // Password has expired, redirect to password change page
                                #header("location: reset-password.php");
                                // Store data in session variables
                                $_SESSION["loggedin"] = false;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                                header("location: reset-password.php?type=expire");
                            } else {
                                // Password is correct, so start a new session
                                session_start();

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;

                                // Reset failed login attempts
                                resetFailedLoginAttempts($id);

                                // Redirect user to welcome page
                                header("location: welcome.php");
                            }
                        } else {
                            // Password is not valid
                            incrementFailedLoginAttempts($id, $failed_login_attempts);

                            // Prepare and execute a query to get the maximum lockout attempt from the password policy table
                            $lockoutQuery = "SELECT lockout_attempts FROM password_policy WHERE is_active = ?";
                            if ($stmt = $conn->prepare($lockoutQuery)) {
                                $isActive = 1;
                                $stmt->bind_param("i", $isActive);
                                $stmt->execute();
                                $stmt->bind_result($lockoutAttempts);
                                $stmt->fetch();
                                $stmt->close();
                            } else {
                                die("Error preparing the policy query: " . $conn->error);
                            }

                            if ($failed_login_attempts >= $lockoutAttempts) {
                                lockoutUser($id);
                                $login_err = "Invalid username or password. Your account is temporarily locked.";
                            } else {
                                $login_err = "Invalid username or password.";
                            }
                        }
                    }
                } else {
                    $login_err = "Invalid username or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }

    mysqli_close($conn);
}

// Function to check if the email is verified
function isEmailVerified($email_verified)
{
    return $email_verified == "verified";
}

// Function to check if the password has expired
function isPasswordExpired($passwordChangeDate, $expirationDays)
{
    return (time() - strtotime($passwordChangeDate)) > ($expirationDays * 24 * 60 * 60);
}

// Function to reset failed login attempts
function resetFailedLoginAttempts($id)
{
    global $conn;
    $reset_attempts_sql = "UPDATE users SET failed_login_attempts = 0 WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $reset_attempts_sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Function to increment failed login attempts
function incrementFailedLoginAttempts($id, $failed_login_attempts)
{
    global $conn;
    $increment_attempts_sql = "UPDATE users SET failed_login_attempts = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $increment_attempts_sql)) {
        $failed_login_attempts++;
        mysqli_stmt_bind_param($stmt, "ii", $failed_login_attempts, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Function to lockout user
function lockoutUser($id)
{
    global $conn;
    $lockout_sql = "UPDATE users SET is_locked = 1, lockout_timestamp = NOW() WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $lockout_sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/styles.css">

    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="../Registration/register.php">Sign up now</a>.</p>
            <p>Forgot your password? <a href="index.php">Click here</a> to reset it.</p>
        </form>
    </div>
</body>

</html>