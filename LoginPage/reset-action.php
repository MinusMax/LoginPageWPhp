<?php
session_start();

if (!isset($_SESSION['otp']) || !isset($_SESSION['email'])) {
    header("Location: forgot_password.php"); 
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
    if ($_POST['otp'] == $_SESSION['otp']) {

        $new_password = $_POST['new-password'];


        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $host = "localhost";
        $user = "root";
        $password = "";
        $dbname = "user_database";

        $conn = new mysqli($host, $user, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $_SESSION['email']);
        $stmt->execute();

        echo "<p>Password updated successfully!</p>";
        unset($_SESSION['otp']); 
        unset($_SESSION['email']);
    } else {
        echo "<p style='color: red;'>Invalid OTP. Please try again.</p>";
    }
}
?>