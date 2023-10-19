<?php
// Initialize the session
session_start();


if (isset($_GET['type'])) {
    $type_request = $_GET['type'];
} else {
    $type_request = 'default_value';
}


if ($type_request == "reset") {
    // Check if the user is logged in, otherwise redirect to login page
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
}


// Include config file
include('../DB_Connection/db_connect.php');

// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } elseif (empty($new_password_err) && ($new_password != trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Password did not match.";
    } elseif (!validatePassword($new_password, $conn)) {
        $confirm_password_err = "Password policy did not complete.";
    } else {

        // Check input errors before updating the database
        if (empty($new_password_err) && empty($confirm_password_err)) {
            // Prepare an update statement
            $sql = "UPDATE users SET password = ?,password_changed_date = NOW() WHERE id = ?";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

                // Set parameters
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Password updated successfully. Destroy the session, and redirect to login page
                    session_destroy();
                    header("location: login.php");
                    exit();
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }


    // Close connection
    mysqli_close($conn);
}


function validatePassword($password, $conn)
{
    // Fetch the password policy from the database
    $query = "SELECT * FROM password_policy WHERE is_active = 1";
    $result = $conn->query($query);

    if ($result->num_rows == 0) {
        echo "No active password policy found.";
        return true;
    }

    $policy = $result->fetch_assoc();

    // Validate the password against the policy
    $passwordLength = strlen($password);

    if ($passwordLength < $policy['min_length'] || $passwordLength > $policy['max_length']) {
        echo "Password length must be between " . $policy['min_length'] . " and " . $policy['max_length'] . " characters.";
        return false;
    }

    if ($policy['require_upper_case'] && !preg_match('/[A-Z]/', $password)) {
        echo "Password must contain at least one uppercase letter.";
        return false;
    }

    if ($policy['require_lower_case'] && !preg_match('/[a-z]/', $password)) {
        echo "Password must contain at least one lowercase letter.";
        return false;
    }

    if ($policy['require_digit'] && !preg_match('/[0-9]/', $password)) {
        echo "Password must contain at least one digit.";
        return false;
    }

    if ($policy['require_special_char'] && !preg_match('/[' . preg_quote($policy['allowed_special_chars'], '/') . ']/', $password)) {
        echo "Password must contain at least one special character from the allowed list: " . $policy['allowed_special_chars'];
        return false;
    }

    // If all checks pass, the password is valid
    return true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/styles_reset.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
        }

        .warning {
            background-color: #ffcccb;
            color: #f00;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    // Check the ype value and display HTML elements accordingly
    if ($type_request === 'expire') {
        // Display HTML for the "yourToken" case
        echo '<div class="warning">
            Warning: Your Password Has Expired
          </div>';
    } ?>


    <div class="wrapper">
        <?php
        // Check the type value and display HTML elements accordingly
        if ($type_request === 'expire') {
            echo '<h2>Change Password</h2>';
            echo '<p>Please fill out this form to change your expired password.</p>';
        } else {
            // Handle other cases or display a default message
            echo '<h2>Reset Password</h2>';
            echo '<p>Please fill out this form to reset your password.</p>';
        }
        ?>


        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>