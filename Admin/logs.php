<?php
// Include config file
include('../DB_Connection/db_connect.php');

// Handle date filter
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

if (!empty($dateFilter)) {
    $sql = "SELECT * FROM logs WHERE DATE(log_timestamp) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dateFilter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM logs";
    $result = $conn->query($sql);
}
?>

<?php
?>
<!DOCTYPE html>
<html>

<head>
    <title>Log Viewer</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <div class="container">
        <h1 class="my-4">Logs Insight</h1>
        <!-- Back to home.php button -->
        <a href="../Login/welcome.php" class="btn btn-primary my-2">Back to home</a>

        <form method="GET" class="form-inline my-4">
            <div class="form-group">
                <label for="dateFilter" class="mr-2">Filter by Date:</label>
                <input type="date" id="dateFilter" name="date" class="form-control" value="<?= $dateFilter ?>">
            </div>
            <button type="submit" class="btn btn-primary ml-2">Filter</button>
            <button type="button" class="btn btn-success ml-2" id="downloadCSV">Download CSV</button>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Log Message</th>
                    <th>Log Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['log_id'] . "</td>";
                    echo "<td>" . $row['log_message'] . "</td>";
                    echo "<td>" . $row['log_timestamp'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById("downloadCSV").addEventListener("click", function() {
            // Get the selected date from the input field
            const selectedDate = document.getElementById("dateFilter").value;
            // Construct the URL for the CSV generation script with the selected date parameter
            const url = `generate_csv.php?date=${selectedDate}`;
            // Fetch data from the server and convert it to CSV format
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    const blob = new Blob([data], {
                        type: 'text/csv'
                    });
                    const a = document.createElement('a');
                    a.href = window.URL.createObjectURL(blob);
                    a.download = 'logs.csv';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(a.href);
                });
        });
    </script>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>