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
    <link rel="stylesheet" href="pets.css" />

    <script src="indexscript.js"></script>
    <script src="data_fetching.js"></script>
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
          

              <?php if ($isLoggedIn): ?>
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
    <h2>Pet Categories</h2>
        <section class="pet_categories">
        <h2>Dog's Categories (Breeds):</h2>
            <div class="category_grid">
            
                <article class="category_card">
                    <a id="labradorLink" href="category.php?species=dog&breed=Labrador" data-breed="Labrador">
                        <img src="images/labroder.jpg" alt="Labrador">
                        <h3>Labrador</h3>
                    </a>
                </article>
                <article class="category_card">
                    <a id="bullDogLink" href="category.php?species=dog&breed=Bulldog" data-breed="Bull Dog">
                        <img src="images/bulldog.jpg" alt="Bull Dog">
                        <h3>Bull Dog</h3>
                    </a>
                </article>
                <article class="category_card">
                    <a id="pitBullLink" href="category.php?species=dog&breed=Pitbull">
                        <img src="images/pit-bull.webp" alt="Pit Bull">
                        <h3>Pit Bull</h3>
                    </a>
                </article>
                <article class="category_card">
                    <a id="othersLink" href="category.php?species=dog&breed=Others">
                        <img src="images/other_dogs.jpg" alt="Others">
                        <h3>Others</h3>
                    </a>
                </article>
            </div>
        </section>

        <section class="pet_categories">
            <h2>Cat's Categories (Breeds):</h2>
            <div class="category_grid">
                <article class="category_card">
                    <a id="persianLink" href="category.php?species=cat&breed=Persian">
                        <img src="images/persian.jpg" alt="Persian">
                        <h3>Persian</h3>
                    </a>
                </article>
                <article class="category_card">
                    <a id="britishShortHairedLink" href="category.php?species=cat&breed=British%20Short%20Hair">
                        <img src="images/britishShortHaired.jpg" alt="British Short Haired">
                        <h3>British Short Haired</h3>
                    </a>
                </article>
                <article class="category_card">
                    <a id="bengalCatLink" href="category.php?species=cat&breed=Bengal%20Cat">
                        <img src="images/bengal_cat.jpg" alt="Bengal Cat">
                        <h3>Bengal Cat</h3>
                    </a>
                </article>
                <article class="category_card">
                    <a id="otherCatsLink" href="category.php?species=cat&breed=Others">
                        <img src="images/oyherCats.jpg" alt="Others">
                        <h3>Others</h3>
                    </a>
                </article>
            </div>
        </section>

        <section class="pet_categories">
            <h2>Other Species:</h2>
            <div class="category_grid">
                <article class="category_card">
                    <a id="fishLink" href="category.php?species=fish&breed=Goldfish">
                        <img src="images/gold-fish.webp" alt="Fish">
                        <h3>Goldfish</h3>
                    </a>
                </article>
                <article class="category_card">
                    <a id="ratLink" href="category.php?species=rat&breed=Pet%20Rat">
                        <img src="images/pet_rat.jpg" alt="Rat">
                        <h3>Pet Rat</h3>
                    </a>
                </article>
                <article class="category_card">
                    <a id="birdLink" href="category.php?species=bird&breed=Pet%20Bird">
                        <img src="images/pet_bird.jpg" alt="Birds">
                        <h3>Pet Bird</h3>
                    </a>
                </article>
                <article class="category_card">
                    <a id="otherPetsLink" href="category.php?species=other&breed=Others">
                        <img src="images/other_pets.jpg" alt="Others">
                        <h3>Others</h3>
                    </a>
                </article>
            </div>
        </section>
    </main>

    <footer>
        <div>
            <p>Contact Us At</p>
            <a href="tel:+918424813828"><p>+918424813828</p></a>
            <a href="mailto:kushal@gmail.com"><p>kushal@gmail.com</p></a>
        </div>
        <span>
            <a href="#"><img src="images/instagram-brands-solid.svg" /></a>
            <a href="#"><img src="images/facebook-brands-solid.svg" /></a>
            <a href="#"><img src="images/x-twitter-brands-solid.svg" /></a>
        </span>
        <p>&copy; 2024 Pet Adoption | All Rights Reserved</p>
        <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>
</body>
</html>ss