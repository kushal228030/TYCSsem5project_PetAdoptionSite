<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']); // Assume 'user_id' is set during login when the session is created.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Pet Adoption</title>
    <link rel="stylesheet" href="style.css">
     <!-- Link your CSS file here -->
     <link rel="stylesheet" href="about.css">
</head>
<body>
    <header>
        
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

    </header>

    <main>
        <section class="about-us">
            <h1>About Us</h1>
            <p>Welcome to <strong>FurrFinders</strong>, where we bring together loving pet adopters and caring pet donors to create forever homes for animals in need.</p>

            <h2>Our Mission</h2>
            <p>At <strong>FurrFinders</strong>, we believe every pet deserves a loving home. Whether you're looking to adopt or donate a pet, our platform helps connect you with the right match, ensuring a smooth and supportive process for both adopters and donors.</p>

            <h2>How We Help</h2>
            <ul>
                <li><strong>For Adopters:</strong> Easily browse available pets, view detailed profiles, and request to adopt your next companion.</li>
                <li><strong>For Donors:</strong> Share your pet's story and find responsible adopters who can provide the care and attention your pet needs.</li>
            </ul>
            <p>We provide direct communication between donors and adopters, enabling both parties to contact each other, discuss details, and ensure the best fit for the pet.</p>

            <h2>Why Choose Us?</h2>
            <ul>
                <li><strong>User-Friendly Platform:</strong> Our intuitive design makes it easy for both adopters and donors to connect and complete the adoption process.</li>
                <li><strong>Direct Connection:</strong> Adopters and donors can communicate directly, without any third-party verification, allowing for transparency and trust.</li>
            </ul>

            <h2>Contact Us</h2>
            <p>Have questions or need assistance? Reach out to us at <a href="mailto:kushalgupta8424@gmail.com">kushalgupta8424@gmail.com</a>, and we’ll be happy to assist you.</p>

            <p>Together, we can make a difference—one adoption at a time.</p>
        </section>
    </main>

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
