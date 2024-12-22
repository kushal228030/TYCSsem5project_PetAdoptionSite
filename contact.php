<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']); 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Initialize PHPMailer
$mail = new PHPMailer(true);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate email
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Server settings
            $mail->SMTPDebug = 0;                  // Enable verbose debug output
            $mail->isSMTP();                       // Send using SMTP
            $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth   = true;              // Enable SMTP authentication
            $mail->Username   = 'kushalgupta8424@gmail.com'; // SMTP username
            $mail->Password   = 'wcfbhcfsxavwzzrv'; // SMTP password (use App Password for Gmail)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
            $mail->Port       = 465;              // TCP port to connect to

            // Recipients
            $mail->setFrom('from@example.com', 'Pet Adoption Site');
            $mail->addAddress('kushalgupta8424@gmail.com'); // Add recipient's email (your site’s contact email)
            $mail->addReplyTo($email, $name); // Set the reply-to address

            // Content
            $mail->isHTML(true);   // Set email format to HTML
            $mail->Subject = 'Contact Form Submission from ' . $name;
            $mail->Body    = '<p><strong>Name:</strong> ' . $name . '</p>' .
                             '<p><strong>Email:</strong> ' . $email . '</p>' .
                             '<p><strong>Phone:</strong> ' . $phone . '</p>' .
                             '<p><strong>Message:</strong><br>' . nl2br($message) . '</p>';
            $mail->AltBody = 'Name: ' . $name . "\n" .
                             'Email: ' . $email . "\n" .
                             'Phone: ' . $phone . "\n" .
                             'Message: ' . $message;

            $mail->send();
            echo "<script>alert('Your message has been sent successfully.')</script>";
        } catch (Exception $e) {
            echo "<p>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo '<p>Invalid email address format. Please enter a valid email.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Contact Us - Pet Adoption</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .contact-info, .contact-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .contact-info {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .contact-info div {
            flex: 1;
            min-width: 250px;
            margin: 10px;
        }
        .contact-info div h3 {
            color: #555;
        }
        .contact-info div p {
            color: #777;
        }
        .contact-form {
            text-align: left;
        }
        .contact-form label {
            display: block;
            margin: 10px 0 5px;
        }
        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .contact-form button {
            padding: 12px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .contact-form button:hover {
            background-color: #218838;
        }
        .social-media {
            text-align: center;
        }
        .social-media a {
            margin: 0 10px;
            color: #555;
            text-decoration: none;
        }
        .social-media a:hover {
            color: #333;
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .contact-info {
                flex-direction: column;
            }
            .contact-info div {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
<div class="navbar" id="nav">
    <nav>
      <div class="logo">
        <img src="images/Weblogo.jpg" alt="Adoption Logo" />
        <p>FurrFinders</p>
      </div>
      <div class="nav-container">
        <ul>
          <li><a href="index.php#nav">Home</a></li>
          <li><a href="contact.php">Contact Us</a></li>
          <li><a href="about_us.php">About</a></li>
          <li><a href="pets.php">Categories</a></li>
          <li> <?php if ($isLoggedIn): ?>
                <!-- Dropdown for logged-in users -->
               <?php $typeUser=$_SESSION['user_type']; ?>
                <div class="dropdown">
                  <a href="#" onclick="toggleDropdown(event)">
                    <img src="images/user (1).png" alt="User Profile" />
                  </a>
                  <div id="dropdown-content" class="dropdown-content">
                    <?php if($typeUser == 'donor'): ?>
                      <a href="donor_dashboard.php">Dashboard</a>
                    <?php elseif($typeUser == 'adopter'): ?>
                      <a href="adopter_dashboard.php">Dashboard</a>
                    <?php endif; ?>
                    <a href="log_out.php">Logout</a>
                  </div>
                </div>
              <?php else: ?>
                <!-- Login button for users not logged in -->
                <a href="login.php"><img src="images/user (1).png" alt="Login" /></a>
              <?php endif; ?>
          </li>
        </ul>
      </div>
    </nav>
  </div>

    <div class="container">
        <h1>Contact Us</h1>

        <!-- Contact Information Section
        <div class="contact-info">
            <div>
                <h3>Phone</h3>
                <p><strong>Adoption Inquiries:</strong> (123) 456-7890</p>
                <p><strong>Donor Support:</strong> (123) 456-7891</p>
            </div>
            <div>
                <h3>Email</h3>
                <p><strong>General Inquiries:</strong> info@petadoption.com</p>
                <p><strong>Adoption Support:</strong> adoptions@petadoption.com</p>
                <p><strong>Donor Support:</strong> donations@petadoption.com</p>
            </div>
            <div>
                <h3>Visit Us</h3>
                <p>123 Pet Lane, Animal City, AC 12345</p>
                <p><strong>Business Hours:</strong></p>
                <p>Mon - Fri: 9:00 AM - 5:00 PM</p>
                <p>Sat: 10:00 AM - 4:00 PM</p>
                <p>Sun: Closed</p>
            </div>
        </div> -->

        <!-- Social Media Section -->
        <div class="social-media">
            <h2>Follow Us</h2>
            <a href="https://facebook.com/petadoption" target="_blank">Facebook</a>
            <a href="https://instagram.com/petadoption" target="_blank">Instagram</a>
            <a href="https://twitter.com/petadopt" target="_blank">Twitter</a>
        </div>

        <!-- Contact Form Section -->
        <div class="contact-form">
            <h2>Send Us a Message</h2>
            <form id="contactForm" method="post" action="contact.php">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" placeholder="Your full name" required>

                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" placeholder="Your email address" required>

                <label for="phone">Your Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Your phone number">

                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="5" placeholder="Your message or inquiry" required></textarea>

                <button type="submit" >Submit</button>
            </form>
        </div>
    </div>
    <footer>
    <div>
      <p>Contact Us At</p>
      <a href="tel:+918424813828"><p>+918424813828</p></a>
      <a href="mailto:kushal@gmail.com"><p>kushal@gmail.com</p></a>
    </div>
    <span>
      <a href="#"><img src="images/instagram-brands-solid.svg"></a>
      <a href="#"><img src="images/facebook-brands-solid.svg"></a>
      <a href="#"><img src="images/x-twitter-brands-solid.svg"></a>
    </span>
    <p>&copy; 2024 Pet Adoption | All Rights Reserved</p>
    <p><a href="privacy_policy.php">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
  </footer>
  <script src="nav_toogle.js">

</body>
</html>
