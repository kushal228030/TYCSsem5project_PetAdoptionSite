<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']); // Assume 'user_id' is set during login when the session is created.
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome to Pet Adoption Site</title>
  <link rel="stylesheet" href="style.css" />
  <script src="indexscript.js"></script>
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
          <li><a href="#nav">Home</a></li>
          <li><a href="#">Contact Us</a></li>
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

  <div class="theme">
    <div class="thought">
      <h1>Adopt a Pet</h1>
      <br />
      <pre><h2>Every Pet Deserves a Good Home.</h2></pre>
      <br />
      <button class="btn_adopt" type="button" onclick="window.location='pets.html'">Adopt One</button>
      <br /><br /><br />
    </div>
  </div>

  <div class="Ab">
    <center>
      <h1>Your Pet Adoption Journey Starts Here With An Ease</h1>
    </center>
    <div class="questions">
  <img src="images/dog_with_poster.png" alt="Dog with poster" />

  <div class="steps-container">
    <h2>Steps to adopt a pet:</h2>

    <div class="ss">
      <img src="images/search_icon.png" alt="Search Icon">
      <div class="sub_ss">
        <h3>Search Pet</h3>
        <p>Adopt a dog or cat who's right for you. Simply enter your city above to start your search.</p>
      </div>
    </div>

    <div class="ss">
      <img src="images/availability_icon.png" alt="Connect Icon">
      <div class="sub_ss">
        <h3>Connect</h3>
        <p>Once you find a pet, click "show number" to get contact info for their pet parent or rescue. Contact them to learn more about how to meet and adopt the pet.</p>
      </div>
    </div>

    <div class="ss">
      <img src="images/Adopt_love.png" alt="Adopt Love Icon">
      <div class="sub_ss">
        <h3>AdoptLove</h3>
        <p>The rescue or pet parents will walk you through their adoption process. Prepare your home for the arrival of your fur baby to help them adjust to their new family.</p>
      </div>
    </div>

    <div class="ss">
      <img src="images/consulation.png" alt="Consultation Icon">
      <div class="sub_ss">
        <h3>Free Vet Consultation</h3>
        <p>ThePetNest will help your pet to settle down in its new home. Once you complete the Adoption journey, reach out to us for free vet consultation.</p>
      </div>
    </div>
  </div>
</div>


    <div class="how">
      <center>
        <h1>How it works?</h1>
        <p>Bringing a new furry friend into your home is simple and rewarding. Follow the easy steps mentioned above to ensure a smooth adoption process.</p>
      </center><br>
      <hr> <br>
      <details><summary><h2>Why Should You Adopt a Dog or Cat?</h2></summary><br>
      <p>Did you know that over 2000 people per hour in India run a search right here looking to adopt a pet? Pet adoption is becoming the preferred way to find a new pet. Adoption will always be more convenient than buying a puppy for sale from a pet shop or finding a kitten for sale from a litter. Pet adoption brings less stress and more savings! So what are you waiting for? Go find that perfect pet for home!</p></details>
      <hr>
      <br>
      <details><summary><h2>How old do I need to be to adopt a pet?</h2></summary
      <br>
      <p>You need to be 18+ years old to adopt a pet.</p></details>
      <hr>
      <br>
      <details><summary><h2>What information do I need to provide on the adoption application?</h2></summary>

      <p>You will need to provide personal information, details about your living situation, and information about your experience with pets. We may also ask for references.</p></details>
      <hr><br><details><summary><h2>What should I expect after adopting a pet?
</h2></summary><p>
After adopting, you should expect to spend time helping your new pet adjust to their new home. This may include training, socialization, and establishing a routine.
</p></details>
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
//     function toggleDropdown(event) {
//   event.preventDefault();
//   const dropdown = document.getElementById('dropdown-content');
//   dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
// }

// // Close the dropdown if the user clicks outside of it
// window.onclick = function(event) {
//   if (!event.target.matches('.dropdown img')) {
//     const dropdowns = document.getElementsByClassName("dropdown-content");
//     for (let i = 0; i < dropdowns.length; i++) {
//       const openDropdown = dropdowns[i];
//       if (openDropdown.style.display === 'block') {
//         openDropdown.style.display = 'none';
//       }
//     }
//   }
// }
  </script>
</body>
</html>
