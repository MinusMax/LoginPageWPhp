<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $host = "localhost";  
    $user = "root";       
    $password = "";       
    $dbname = "user_database";  

    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $otp = rand(100000, 999999);

        $subject = "Your OTP for Password Reset";
        $message = "Your OTP to reset your password is: $otp";
        $headers = "From: no-reply@yourdomain.com";

        if (mail($email, $subject, $message, $headers)) {
            session_start();
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            header("Location: verify-password.php");
            exit;
        } else {
            echo "<p style='color: red;'>Failed to send OTP. Please try again later.</p>";
        }
    } else {
        // Redirect to reForgot.html with an error message
        header("Location: reForgot.html?error=Incorrect+Email+Address");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
