<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "user_database";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        header("Location: HomePage.html");  // Redirect to homepage
        exit();
    } else {
        // Incorrect password
        header("Location: relogin.html?error=" . urlencode("Incorrect Username or Password"));
        exit();
    }
} else {
    // Incorrect username
    header("Location: relogin.html?error=" . urlencode("Incorrect Username or Password"));
    exit();
}

$conn->close();
?>
