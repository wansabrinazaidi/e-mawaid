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
    echo json_encode(['message' => 'User not logged in.']);
    exit;
}

// Get the data from the request
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['ids'])) {
    $ids = implode(',', array_map('intval', $data['ids'])); // Sanitize IDs

    // Delete records for the current user ID
    $sql = "DELETE FROM tempahan WHERE id IN ($ids) AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Records deleted successfully.']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['message' => 'Error deleting records: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'No IDs provided.']);
}

$conn->close();
