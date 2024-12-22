<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'adopter') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);
$adopter_id = $_SESSION['user_id'];

if (isset($_GET['pet_id'])) {
    $pet_id = $conn->real_escape_string($_GET['pet_id']);

    // Check if the adopter already has a pending adoption request
    $pending_check_sql = "SELECT id FROM pet_donations WHERE requested_adopter_id = ?";
    $stmt = $conn->prepare($pending_check_sql);
    $stmt->bind_param("s", $adopter_id);
    $stmt->execute();
    $pending_check_result = $stmt->get_result();

    if ($pending_check_result->num_rows > 0) {
        echo "You already have a pending adoption request. You can only request one pet at a time.";
    } else {
        // Check if the pet is already requested for adoption
        $check_sql = "SELECT requested_adopter_id, email, donorName FROM pet_donations WHERE id = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $pet_id);
        $stmt->execute();
        $check_result = $stmt->get_result();

        if ($check_result->num_rows === 1) {
            $row = $check_result->fetch_assoc();

            if ($row['requested_adopter_id'] === null) {
                $email = $row['email'];
                $donor_name = $row['donorName'];

                try {
                    // Set up email server
                    $mail->SMTPDebug = 0; 
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'kushalgupta8424@gmail.com';        // SMTP username
                    $mail->Password   = 'wcfbhcfsxavwzzrv';   
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;

                    // Set email details
                    $mail->setFrom('from@example.com', 'Pet Adoption Site');
                    $mail->addAddress($email);
                    $mail->addReplyTo('info@example.com', 'Information');

                    $mail->isHTML(true);
                    $mail->Subject = 'Request for Pet Adoption';
                    $mail->Body    = 'Dear ' . $donor_name . ',<br>Your pet has been requested for adoption. Please check your dashboard for more details.';
                    $mail->AltBody = 'Dear ' . $donor_name . ', Someone has requested to adopt your pet. Please check the dashboard of the website for more information.';

                    // Send the email
                    if ($mail->send()) {
                        // Only update the pet_donations table if email is sent successfully
                        $sql = "UPDATE pet_donations SET requested_adopter_id = ? WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $adopter_id, $pet_id);

                        if ($stmt->execute()) {
                            echo "Adoption request submitted successfully and email sent.";
                        } else {
                            echo "Error updating adoption request: " . $conn->error;
                        }
                    } else {
                        echo "Error: Email not sent.";
                    }
                } catch (Exception $e) {
                    echo "<p>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
                }
            } else {
                echo "This pet is already in a requested state for adoption.";
            }
        } else {
            echo "Pet not found.";
        }
    }
} else {
    echo "No pet ID provided.";
}


$conn->close();
?>

<a href="adopter_dashboard.php" class="btn">Back to Dashboard</a>
