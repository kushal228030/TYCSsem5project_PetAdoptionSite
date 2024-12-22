<?php
// Start the session
session_start();

// Check if the user is logged in and is an adopter
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'adopter') {
    // Redirect to login with the redirect parameter
    $redirect_url = "login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']);
    header("Location: $redirect_url");
    exit();
}

// Include the database connection file
include 'db_connection.php';

// Retrieve pet details from the database
if (isset($_GET['id'])) {
    $pet_id = $conn->real_escape_string($_GET['id']);

    $sql = "SELECT * FROM pet_donations WHERE id = '$pet_id'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $pet = $result->fetch_assoc();
    } else {
        echo "Pet not found.";
        exit;
    }
} else {
    echo "No pet ID provided.";
    exit;
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pet Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .details {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .details img {
            width: 50vw; /* Adjusted to 50vw */
            height: auto;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
        }
        .user-details {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="navbar" id="nav">
    <nav>
        <div class="logo">
            <img src="images/Weblogo.jpg" alt="Adoption Logo" />
            <p>Adoption</p>
        </div>
        <div class="nav-container">
            <ul>
                <li><a href="index.php#nav">Home</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="about_us.php">About</a></li>
                <li><a href="pets.php">Categories</a></li>
                <li><a href="log_out.php">Logout</a></li>
            </ul>
        </div>
    </nav>
</div>
<div class="dashboard">
    <h1>Pet Details</h1>
    <div class="details">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($pet['pet_pic']); ?>" alt="Pet Picture">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($pet['petName']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($pet['address']); ?></p>
        <p><strong>Species:</strong> <?php echo htmlspecialchars($pet['pet_species']); ?></p>
        <p><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($pet['gender']); ?></p>
        <p><strong>Vaccination Status:</strong> <?php echo htmlspecialchars($pet['vaccination']); ?></p>
        <p><strong>Last Vaccination Date:</strong> <?php echo htmlspecialchars($pet['lastVaccinationDate']); ?></p>
        <p><strong>Additional Info:</strong> <?php echo htmlspecialchars($pet['additionalInfo']); ?></p>
    </div>

    <!-- User Details Section -->
    <div class="user-details">
        <h2>Contact Information</h2>
        <p><strong>Donor Name:</strong> <?php echo htmlspecialchars($pet['donorName']); ?></p>
        <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($pet['email']); ?>"><?php echo htmlspecialchars($pet['email']); ?></a></p>
        <p><strong>Contact Number:</strong> <a href="tel:<?php echo htmlspecialchars($pet['phone']); ?>"><?php echo htmlspecialchars($pet['phone']); ?></a></p>
    </div>

    <a href="adopter_dashboard.php" class="btn">Back to Dashboard</a>
</div>
<footer>
    <div>
        <p>Contact Us At</p>
        <a href="tel:+918424813828"><p>+918424813828</p></a>
        <a href="mailto:kushal@gmail.com"><p>kushal@gmail.com</p></a>
    </div>
    <span>
        <a href="#"><img src="images/instagram-brands-solid.svg" alt="Instagram"></a>
        <a href="#"><img src="images/facebook-brands-solid.svg" alt="Facebook"></a>
        <a href="#"><img src="images/x-twitter-brands-solid.svg" alt="Twitter"></a>
    </span>
    <p>&copy; 2024 Pet Adoption | All Rights Reserved</p>
    <p><a href="privacy_policy.html">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
</footer>
</body>
</html>
