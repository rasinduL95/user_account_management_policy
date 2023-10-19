<?php
// Include the database configuration file
include('../DB_Connection/db_connect.php');
session_start();
// Get the user's security questions and answers
$user_id = $_SESSION['id'];
$query = "SELECT q.question_text, a.answer_text
          FROM security_questions q
          INNER JOIN user_security_question_answers a ON q.id = a.security_question_id
          WHERE a.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$security_questions = array();
while ($row = $result->fetch_assoc()) {
    $security_questions[] = $row;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_answers = $_POST["answers"];

    // Initialize an array to store incorrect answers
    $incorrectAnswers = [];

    // Check if answers match the stored answers
    $correct = true;
    foreach ($security_questions as $index => $question) {
        if (strtolower($user_answers[$index]) !== strtolower($question["answer_text"])) {
            $correct = false;
            $incorrectAnswers[] = $question["question_text"]; // Store the incorrect question text
        }
    }

    if ($correct) {
        // All answers are correct - trigger a JavaScript alert
        echo '<script type="text/javascript">alert("All answers are correct. Access granted!");</script>';
        header("location: reset-password.php?type=reset");
        exit;
    } else {
        // Display an error message and highlight the incorrect answers
        echo "Some answers are incorrect. Please check the following questions: ";
        foreach ($incorrectAnswers as $incorrectQuestion) {
            echo '<li>' . $incorrectQuestion . '</li>';
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Security Questions</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS for the form */
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        label {
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="text"]:focus {
            border-color: #007bff;
        }

        .btn-primary {
            width: 100%;
        }

        .btn-secondary {
            width: 100%;
            margin-top: 20px;
        }

        .box {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }

        /* Error message style */
        .invalid-feedback {
            color: #dc3545;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container mt-5 box">
        <form method="post">
            <input type="hidden" name="csrf_token" value="your_csrf_token_here">
            <?php foreach ($security_questions as $index => $question) : ?>
                <div class="form-group">
                    <label for="answer<?php echo $index; ?>"><?php echo $question["question_text"]; ?></label>
                    <input type="text" class="form-control" id="answer<?php echo $index; ?>" name="answers[<?php echo $index; ?>]" required>
                    <div class="invalid-feedback">Please answer this question.</div>
                </div>
            <?php endforeach; ?>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="welcome.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>