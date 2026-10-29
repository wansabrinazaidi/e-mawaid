<?php
// login.php
session_start();
include 'connectdatabase.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT companyName, companyID FROM user WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->bind_result($companyName, $companyID);
    $stmt->fetch();

    if ($companyID) { // Check if companyID is fetched
        $_SESSION['companyName'] = $companyName;
        $_SESSION['companyID'] = $companyID;
        $_SESSION['email'] = $email; // Set email in session

        // Debugging: Check if session variables are set
        echo "Company Name: " . $_SESSION['companyName'] . "<br>";
        echo "Company ID: " . $_SESSION['companyID'] . "<br>";
        echo "Email: " . $_SESSION['email'] . "<br>";

        header("Location: mainpage.php");
    } else {
        echo "Invalid username or password";
    }

    $stmt->close();
}

$conn->close();
?>
