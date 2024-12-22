<?php
session_start();

include 'db_connection.php';
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Initialize PHPMailer
$mail = new PHPMailer(true);

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate email
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if the email exists in the pet_donations or pet_adoption_applications table
        $stmt = $conn->prepare("SELECT 1 FROM pet_donations WHERE email = ? UNION SELECT 1 FROM pet_adoption_applications WHERE email = ?");
        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email exists, generate a 4-digit OTP
            $otp = rand(1000, 9999);
            $otp_expiry = time() + (10 * 60); // OTP valid for 10 minutes

            // Store OTP and expiry in session
            $_SESSION['otp'] = $otp;
             $_SESSION['otp_expiry'] = $otp_expiry;
            $_SESSION['email'] = $email;

            try {
                // Server settings
                $mail->SMTPDebug = 0;                  // Enable verbose debug output
                $mail->isSMTP();                                        // Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                   // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                               // Enable SMTP authentication
                $mail->Username   = 'kushalgupta8424@gmail.com';        // SMTP username
                $mail->Password   = 'wcfbhcfsxavwzzrv';                 // SMTP password (use App Password for Gmail)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;        // Enable implicit TLS encryption
                $mail->Port       = 465;                                // TCP port to connect to

                // Recipients
                $mail->setFrom('from@example.com', 'Pet Adoption Site');
                $mail->addAddress($email);                              // Add recipient's email
                $mail->addReplyTo('info@example.com', 'Information');

                // Content
                $mail->isHTML(true);                                    // Set email format to HTML
                $mail->Subject = 'Your OTP for Password Reset';
                $mail->Body    = 'Your OTP for resetting your password is <b>' . $otp . '</b>. The OTP is valid for 10 minutes.';
                $mail->AltBody = 'Your OTP for resetting your password is ' . $otp . '. The OTP is valid for 10 minutes.';

                $mail->send();
                echo "<script>alert('An OTP has been sent to your email address.')</script>";
                
                // Redirect to the password reset page (new_pass.php)
                header("Location: new_pass.php");
                exit();
            } catch (Exception $e) {
                echo "<p>Message could not be sent. Try again </p>";
            }
        } else {
            echo '<p>Email address not found in our records.</p>';
        }

        $stmt->close();
    } else {
        echo '<p>Invalid email address format. Please enter a valid email.</p>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="adoption_form.css">
</head>
<body>
    <h1>Reset Your Password</h1>
    <form action="forget_pass.php" method="post">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Send OTP">
    </form>
</body>
</html>
