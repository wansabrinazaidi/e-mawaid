<?php
session_start(); // Start session to access session variables

$host = "localhost";
$user = "root";
$pass = "";
$db = "e-tempahan";

$conn = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'] ?? null;

if ($user_id === null) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

// Fetch records only for the logged-in user
$sql = "SELECT * FROM tempahan WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

$data = [];
if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Error fetching records: ' . $stmt->error]);
    exit;
}

// Return records as JSON
header('Content-Type: application/json');
echo json_encode($data);

$stmt->close();
$conn->close();
