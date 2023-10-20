<?php
// Include config file
include('../DB_Connection/db_connect.php');

// Get the date parameter
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

// Build the SQL query to filter by date
if (!empty($dateFilter)) {
    $sql = "SELECT * FROM logs WHERE DATE(log_timestamp) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dateFilter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no date is provided, retrieve all logs
    $sql = "SELECT * FROM logs";
    $result = $conn->query($sql);
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="logs.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('ID', 'Log Message', 'Log Date'));

while ($row = $result->fetch_assoc()) {
    fputcsv($output, array($row['log_id'], $row['log_message'], $row['log_timestamp']));
}

fclose($output);

// Close the database connection
$conn->close();
