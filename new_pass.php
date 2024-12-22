<?php
// Include the database connection file
include 'db_connection.php';

// Start the session
session_start();

// Check if OTP and new password are posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $email = $_SESSION['email'];  // Get the email from session set during OTP generation

    // Password validation regex
    $password_regex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,}$/";

    // Validate password format
    if (!preg_match($password_regex, $new_password)) {
        $error_message = 'Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one digit, and one special character.';
    } else {
        // Fetch OTP and expiry time from session
        if (isset($_SESSION['otp'], $_SESSION['otp_expiry'])) {
            $db_otp = $_SESSION['otp'];
            // $otp_expiry = $_SESSION['otp_expiry'];

            // Validate OTP
            if ($db_otp == $otp) {
                // OTP is valid, hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Check if the user is a donor
                $stmt = $conn->prepare("SELECT 1 FROM pet_donations WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $donor_result = $stmt->get_result();

                if ($donor_result->num_rows > 0) {
                    // User is a donor, update the password in the pet_donations table
                    $stmt = $conn->prepare("UPDATE pet_donations SET password = ? WHERE email = ?");
                    $stmt->bind_param("ss", $hashed_password, $email);
                    if ($stmt->execute()) {
                        $success_message = 'Your password has been updated successfully as a donor.';
                    } else {
                        $error_message = 'Failed to update password for donor. Please try again.';
                    }
                } else {
                    // User is not a donor, check if they are an adopter
                    $stmt = $conn->prepare("SELECT 1 FROM pet_adoption_applications WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $adopter_result = $stmt->get_result();

                    if ($adopter_result->num_rows > 0) {
                        // User is an adopter, update the password in the pet_adoption_applications table
                        $stmt = $conn->prepare("UPDATE pet_adoption_applications SET password = ? WHERE email = ?");
                        $stmt->bind_param("ss", $hashed_password, $email);
                        if ($stmt->execute()) {
                            $success_message = 'Your password has been updated successfully as an adopter.';
                            header("Location: login.php");
                            exit();
                        } else {
                            $error_message = 'Failed to update password for adopter. Please try again.';
                        }
                    } else {
                        $error_message = 'No account found for this email address.';
                    }
                }
            } else {
                $error_message = 'Invalid OTP. Please try again.';
            }
        } else {
            $error_message = 'OTP has expired. Please request a new OTP.';
            header("Location: forget_pass.php");
            exit();
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <link rel="stylesheet" href="adoption_form.css">
    <script>
        function validateForm() {
            const newPassword = document.getElementById("new_password").value;
            const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,}$/;

            if (!passwordRegex.test(newPassword)) {
                alert("Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h1>Update Your Password</h1>

    <?php if (isset($success_message)) { echo "<p>$success_message</p>"; } ?>
    <?php if (isset($error_message)) { echo "<p>$error_message</p>"; } ?>

    <form action="new_pass.php" method="post" onsubmit="return validateForm()">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="otp">Enter the OTP sent to your email:</label>
        <input type="text" id="otp" name="otp" required><br><br>

        <label for="new_password">Enter your new password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <input type="submit" value="Update Password">
        <p>Wants to login? <a href="login.php" class="login-link">Login</a></p>
    </form>
</body>
</html>
