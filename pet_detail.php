<?php
// Start the session
session_start();

// Check if the user is logged in and is an adopter
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'adopter') {
    // Display the alert and then redirect using JavaScript
    $redirect_url = "login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']);
    echo "<script>
        alert('Login as an adopter');
        window.location.href = '$redirect_url';
    </script>";
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
    <link rel="stylesheet" href="pet_details.css">
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
          <li>
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
          </li>
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
        <div class="actions">
            <a href="request_adoption.php?pet_id=<?php echo $pet['id']; ?>" class="btn">Request Adoption</a>
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
      <a href="#"><img src="images/instagram-brands-solid.svg"></a>
      <a href="#"><img src="images/facebook-brands-solid.svg"></a>
      <a href="#"><img src="images/x-twitter-brands-solid.svg"></a>
    </span>
    <p>&copy; 2024 Pet Adoption | All Rights Reserved</p>
    <p><a href="privacy_policy.html">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
  </footer>
</body>
</html>