<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include the database connection file
include 'db_connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Get the donor's ID from the session
$donor_id = $_SESSION['user_id'];

// Retrieve the form data
$application_id = $_POST['application_id'] ?? '';
$pet_id = $_POST['pet_id'] ?? '';
$action = $_POST['action'] ?? '';

// Validate input
if (empty($application_id) || empty($pet_id) || !in_array($action, ['accept', 'reject'])) {
    echo "Invalid request.";
    exit();
}

// Determine the new application status
$new_status = ($action === 'accept') ? 'accepted' : 'rejected';

// Prepare the update query
$sql = "
    UPDATE pet_adoption_applications
    SET application_status = ?
    WHERE id = ?
";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $new_status, $application_id);

if ($stmt->execute()) {
    // Retrieve the adopter's email and name based on the application ID
    $adopter_sql = "
        SELECT email,name
        FROM pet_adoption_applications
        WHERE pet_adoption_applications.id = ?
    ";
    
    $adopter_stmt = $conn->prepare($adopter_sql);
    $adopter_stmt->bind_param('i', $application_id);
    $adopter_stmt->execute();
    $adopter_result = $adopter_stmt->get_result();

    if ($adopter_result->num_rows > 0) {
        $adopter = $adopter_result->fetch_assoc();
        $adopter_email = $adopter['email'];
        $adopter_name = $adopter['name'];

        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';                   // Set the SMTP server to send through
            $mail->SMTPAuth   = true;
            $mail->Username   = 'kushalgupta8424@gmail.com';        // SMTP username
            $mail->Password   = 'wcfbhcfsxavwzzrv';                 // SMTP password (use App Password for Gmail)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Recipients
            $mail->setFrom('from@example.com', 'Pet Adoption Site');
            $mail->addAddress($adopter_email);                      // Add recipient (adopter's email)

            // Email Content
            $mail->isHTML(true);                                   // Set email format to HTML
            $subject = ($action === 'accept') 
                ? 'Your Pet Adoption Request is Accepted!' 
                : 'Your Pet Adoption Request is Rejected';

            $message_body = ($action === 'accept') 
                ? 'Dear ' . $adopter_name . ',<br><br>Your request to adopt the pet has been accepted! Please check your dashboard for further details.'
                : 'Dear ' . $adopter_name . ',<br><br>We regret to inform you that your request to adopt the pet has been rejected. Please check your dashboard for more information.';

            $mail->Subject = $subject;
            $mail->Body    = $message_body;
            $mail->AltBody = strip_tags($message_body);            // Plain text version

            // Send email
            $mail->send();
            echo "Notification email sent to adopter.";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    // Redirect back to the dashboard
    header('Location: donor_dashboard.php');
    exit();
} else {
    echo "Failed to update application status.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
