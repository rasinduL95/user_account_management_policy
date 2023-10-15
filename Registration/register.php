<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <script src="validation.js"></script>
</head>
<body>
    <h1>User Registration</h1>
    <form action="register_process.php" method="POST" onsubmit="return validateForm();">
        <label for="f_name">First Name:</label>
        <input type="text" name="f_name" id="f_name" required><br><br>

        <label for="l_name">Last Name:</label>
        <input type="text" name="l_name" id="l_name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required><br><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>