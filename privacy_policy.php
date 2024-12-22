<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']); // Assume 'user_id' is set during login when the session is created.
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Privacy Policy - Pet Adoption Platform</title>
      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="style.css">
      <style>main {
              max-width: 1200px;
              margin: 40px auto;
              padding: 20px;
              background-color: white;
              box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
              border-radius: 8px;
          }

          main h2 {
              font-size: 28px;
              color: tomato ;
              border-bottom: 2px solid tomato ;
              padding-bottom: 5px;
              margin-bottom: 20px;
          }

          main p, main ul {
              font-size: 16px;
              line-height: 1.6;
              margin-bottom: 20px;
          }

          main ul {
              list-style: inside disc;
              padding-left: 20px;
          }

          main ul li {
              margin-bottom: 10px;
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
      <main>
          <div>
              <h2>Introduction</h2>
              <p>This privacy policy explains how we collect, use, and protect your personal information when you use our pet adoption platform, where adopters and donors can connect with one another. By using this site, you agree to the practices outlined in this policy.</p>
          </div>

          <div>
              <h2>Information We Collect</h2>
              <p>We collect the following information from users:</p>
              <ul>
                  <li>Name and contact information (email, phone number)</li>
                  <li>Profile details (including adoption preferences and donation history)</li>
                  <li>Information related to the pets you are interested in adopting</li>
                  <li>Payment information (for donations)</li>
                  <li>Public messages or interactions on the platform</li>
              </ul>
          </div>

          <div>
              <h2>Public Display of Information</h2>
              <p>Please note that certain details provided by users, such as name, contact information, and adoption or donation-related activity, may be publicly displayed on our website. Anyone visiting the site may be able to view this information. We encourage you to be mindful of the personal information you provide that may be visible to others.</p>
          </div>

          <div>
              <h2>Use of Information</h2>
              <p>We use your information to:</p>
              <ul>
                  <li>Facilitate pet adoptions and donations</li>
                  <li>Allow adopters and donors to connect with one another</li>
                  <li>Enhance and improve the functionality of our services</li>
                  <li>Communicate with you about updates, requests, or issues related to your use of the platform</li>
              </ul>
          </div>

          <div>
              <h2>Protection of Information</h2>
              <p>We take reasonable security measures to protect your information from unauthorized access, loss, and misuse. However, given that some information is displayed publicly, we cannot guarantee full privacy for publicly visible data.</p>
          </div>

          <div>
              <h2>Cookies and Tracking</h2>
              <p>We use cookies and tracking technologies to improve user experience, understand how visitors engage with the website, and offer personalized services.</p>
          </div>

          <div>
              <h2>User Consent</h2>
              <p>By using our platform and providing your information, you consent to its public display and the uses outlined in this policy. If you wish to have specific details removed from public view, please contact us.</p>
          </div>

          <div>
              <h2>Changes to this Policy</h2>
              <p>We reserve the right to update this policy at any time to reflect changes in our practices or legal requirements. Please review this policy periodically for updates.</p>
          </div>
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
      <p><a href="privacy_policy.html">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>
    <script src="nav_toogle.js">
  </body>
</html>
