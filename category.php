<?php
// Include the database connection file
include 'db_connection.php';
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$typeUser = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';

// Initialize variables for species, breed, pagination
$species = '';
$breed = '';
$limit = 12; // Number of pets to display per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Check if species and breed are set in the GET parameters
if (isset($_GET['species']) && isset($_GET['breed'])) {
    $species = $conn->real_escape_string($_GET['species']);
    $breed = $conn->real_escape_string($_GET['breed']);
}

// Fetch pets from the database based on species, breed, and pagination
$sql = "SELECT id, petName, pet_species, breed, age, gender, vaccination, pet_pic 
        FROM pet_donations 
        WHERE pet_species = '$species' AND breed = '$breed' 
        LIMIT $start, $limit";
$result = $conn->query($sql);

// Total number of pets for pagination
$total_pets_sql = "SELECT COUNT(id) AS total_pets 
                   FROM pet_donations 
                   WHERE pet_species = '$species' AND breed = '$breed'";
$total_pets_result = $conn->query($total_pets_sql);
$total_pets = $total_pets_result->fetch_assoc()['total_pets'];
$total_pages = ceil($total_pets / $limit);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Listing</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="category.css">
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
                        <div class="dropdown">
                            <a href="#" onclick="toggleDropdown(event)">
                                <img src="images/user (1).png" alt="User Profile" />
                            </a>
                            <div id="dropdown-content" class="dropdown-content">
                                <?php if ($typeUser == 'donor'): ?>
                                    <a href="donor_dashboard.php">Dashboard</a>
                                <?php elseif ($typeUser == 'adopter'): ?>
                                    <a href="adopter_dashboard.php">Dashboard</a>
                                <?php endif; ?>
                                <a href="log_out.php">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        
                        <a href="login.php"><img src="images/user (1).png" alt="Login" /></a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>
</div>

<div class="container">
    <h1>Available Pets for Adoption</h1>
    <div class="pet-listing">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="pet-card">';
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['pet_pic']) . '" alt="Pet Picture">';
                echo '<p><strong>Name:</strong> ' . htmlspecialchars($row['petName']) . '</p>';
                echo '<p><strong>Species:</strong> ' . htmlspecialchars($row['pet_species']) . '</p>';
                echo '<p><strong>Breed:</strong> ' . htmlspecialchars($row['breed']) . '</p>';
                echo '<p><strong>Age:</strong> ' . htmlspecialchars($row['age']) . ' years</p>';
                echo '<p><strong>Gender:</strong> ' . htmlspecialchars($row['gender']) . '</p>';
                echo '<p><strong>Vaccination Status:</strong> ' . htmlspecialchars($row['vaccination']) . '</p>';
                echo '<a href="pet_detail.php?id=' . urlencode($row['id']) . '" class="btn">View Details</a>';
                echo '</div>';
            }
        } else {
            echo "<p>No pets available for adoption in this category at the moment.</p>";
        }
        ?>
    </div>

    <!-- Pagination controls -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?species=<?php echo urlencode($species); ?>&breed=<?php echo urlencode($breed); ?>&page=<?php echo $page-1; ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?species=<?php echo urlencode($species); ?>&breed=<?php echo urlencode($breed); ?>&page=<?php echo $i; ?>"
               class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?species=<?php echo urlencode($species); ?>&breed=<?php echo urlencode($breed); ?>&page=<?php echo $page+1; ?>">Next</a>
        <?php endif; ?>
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
    <p><a href="privacy_policy.html">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
  </footer>
<script src="nav_toogle.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
