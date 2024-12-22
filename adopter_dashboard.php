<?php
// Start the session
session_start();

// Check if the user is logged in and is an adopter
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'adopter') {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include 'db_connection.php';

// Retrieve adopter details from the database
$adopter_id = $_SESSION['user_id'];

$sql = "SELECT * FROM pet_adoption_applications WHERE id = '$adopter_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows === 1) {
    // Fetch adopter details
    $adopter = $result->fetch_assoc();
    $preferred_pet_type = $adopter['type_of_pet'];
    $application_status = $adopter['application_status']; 
    echo  $preferred_pet_type;// Get the application status
} else {
    // If no adopter details found or query failed, redirect to login
    header("Location: login.php");
    exit();
}

// Fetch pet details from the pet_donations table based on the adopter's pet preference
$pet_sql = "SELECT id, petName, address, pet_species, breed, age, gender, pet_pic 
            FROM pet_donations 
            WHERE pet_species = '$preferred_pet_type'
            and requested_adopter_id is null";
$pet_result = $conn->query($pet_sql);

// Fetch pet details that have been requested by the adopter
$requested_pet_sql = "SELECT id, petName, address, pet_species, breed, age, gender, pet_pic, requested_adopter_id 
                      FROM pet_donations 
                      WHERE requested_adopter_id = '$adopter_id'";
$requested_pet_result = $conn->query($requested_pet_sql);

// Fetch details of approved adoptions
// $approved_adoptions_sql = "SELECT petName, breed, pet_species 
//                            FROM pet_donations 
//                            WHERE requested_adopter_id = '$adopter_id'";
// $approved_adoptions_result = $conn->query($approved_adoptions_sql);

// Check the application status and handle it

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adopter Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard_style.css">
    <script>
        function showApprovedSection() {
            var approvedSection = document.getElementById("approved-adoptions");
            if (approvedSection) {
                approvedSection.style.display = "block";
            }
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="#application-status">Application Status</a></li>
            <li><a href="#available-pets">Available Pets</a></li>
            <li><a href="#requested-pets">Requested Pets</a></li>
            <li><a href="#approved-adoptions">Approved Adoptions</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header class="navbar" id="nav">
            <nav>
                <div class="logo">
                    <img src="images/Weblogo.jpg" alt="Adoption Logo" />
                    <p>Adoption</p>
                </div>
                <div class="nav-container">
                    <ul>
                        <li><a href="index.php#nav">Home</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="pets.php">Categories</a></li>
                        <li><a href="log_out.php">Logout</a></li>
                    </ul>
                </div>
            </nav>
        </header>

        <div class="dashboard">
            <h1>Welcome to Your Dashboard, <?php echo htmlspecialchars($adopter['name']); ?></h1>
            <section id="application-status" class="application-status">
                <h2>Application Status</h2>
                <?php
                if ($application_status === 'rejected') {
                    echo "<p>Your adoption request has been rejected.</p>";
                   
                } elseif ($application_status === 'accepted') {
                    echo "<p>Your adoption request has been accepted!</p>";
                } elseif ($application_status === 'pending') {
                    echo "<p>Your adoption request is pending.</p>";
                }
                ?>
            </section>

            <section id="available-pets" class="pet_list">
                <h2>Available Pets Matching Your Preference</h2>
                <?php if ($pet_result && $pet_result->num_rows > 0): ?>
                    <?php while ($pet = $pet_result->fetch_assoc()): ?>
                        <div class="card">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($pet['pet_pic']); ?>" alt="Pet Picture">
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($pet['petName']); ?></p>
                            <p><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></p>
                            <p><strong>Species:</strong> <?php echo htmlspecialchars($pet['pet_species']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($pet['address']); ?></p>
                            <a href="pet_detail.php?id=<?php echo $pet['id']; ?>" class="btn">View Details</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No pets available matching your preference at the moment.</p>
                <?php endif; ?>
            </section>

            <section id="requested-pets" class="requested_pets">
                <h2>Your Requested Pets</h2>
                <?php if ($requested_pet_result && $requested_pet_result->num_rows > 0): ?>
                    <?php if($application_status==="pending"):?>
                        <?php while ($requested_pet = $requested_pet_result->fetch_assoc()): ?>
                            <div class="card">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($requested_pet['pet_pic']); ?>" alt="Pet Picture">
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($requested_pet['petName']); ?></p>
                                <p><strong>Breed:</strong> <?php echo htmlspecialchars($requested_pet['breed']); ?></p>
                                <p><strong>Species:</strong> <?php echo htmlspecialchars($requested_pet['pet_species']); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($requested_pet['address']); ?></p>
                                <a href="pet_detail.php?id=<?php echo $requested_pet['id']; ?>" class="btn">View Details</a>
                                <p><em>Pending approval</em></p>
                                <form action="cancel_adoption.php" method="post" style="display:inline;">
                                    <input type="hidden" name="pet_id" value="<?php echo $requested_pet['id']; ?>">
                                    <button type="submit" class="btn cancel">Cancel Request</button>
                                </form>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <p>You have not requested any pets for adoption yet.</p>
                <?php endif; ?>
            </section>

            <section id="approved-adoptions" class="approved">
                <h2>Approved Adoptions</h2>
                <?php if ($application_status === 'accepted'): ?>
                    <p>Your adoption request has been approved for the following pets:</p>
                    <ul>
                    <?php while ($requested_pet = $requested_pet_result->fetch_assoc()): ?>
                        <div class="card">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($requested_pet['pet_pic']); ?>" alt="Pet Picture">
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($requested_pet['petName']); ?></p>
                            <p><strong>Breed:</strong> <?php echo htmlspecialchars($requested_pet['breed']); ?></p>
                            <p><strong>Species:</strong> <?php echo htmlspecialchars($requested_pet['pet_species']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($requested_pet['address']); ?></p>
                            <a href="accepted_request_pet.php?id=<?php echo $requested_pet['id']; ?>" class="btn">View Details</a>
                        </div>
                    <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>You do not have any approved adoptions yet.</p>
                <?php endif; ?>
            </section>
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
    </div>

    <script>
        showApprovedSection(); // Show the section if there are approved adoptions
    </script>
</body>
</html>
