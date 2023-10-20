<?php
// Include config file
include('../DB_Connection/db_connect.php');

// Function to retrieve a list of users
function getUsers()
{
    global $conn;
    $sql = "SELECT id, username, f_name,l_name,email FROM users WHERE account_status='active'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
}

// Check if a user ID is provided via POST request to deactivate users
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deactivate'])) {
        $selectedUsers = $_POST['deactivate'];

        foreach ($selectedUsers as $userId) {
            $userId = $conn->real_escape_string($userId);
            $sql = "UPDATE users SET account_status = 'inactive' WHERE id = $userId";
            $conn->query($sql);
        }
    }
}

$users = getUsers();

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Deactivation</title>
    <link rel="stylesheet" type="text/css" href="./css/styles.css">
</head>

<body>
    <h1>User List</h1>
    <form method="post" action="">
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Select</th>
            </tr>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['f_name']; ?></td>
                    <td><?php echo $user['l_name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        <input type="checkbox" name="deactivate[]" value="<?php echo $user['id']; ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <input type="submit" value="Deactivate Selected Users">
    </form>
    <form method="post" action="../Login/welcome.php">
        <input type="submit" name="home" value="Back to Home Page" class="submit-button">
    </form>
</body>

</html>