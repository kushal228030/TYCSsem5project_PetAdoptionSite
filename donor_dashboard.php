<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include the database connection file
include 'db_connection.php';

// Get the donor's ID from the session
$donor_id = $_SESSION['user_id'];

// Fetch the pet donation and adoption application details
$sql_pet = "
    SELECT * 
    FROM pet_donations
    WHERE id = $donor_id
";
$sql_application = "
    SELECT * 
    FROM pet_adoption_applications
    WHERE id = (
        SELECT requested_adopter_id
        FROM pet_donations 
        WHERE id = $donor_id
    ) 
    AND application_status IS NULL
";
$sql_application_accepted = "
    SELECT * 
    FROM pet_adoption_applications
    WHERE id = (
        SELECT requested_adopter_id
        FROM pet_donations 
        WHERE id = $donor_id
    ) 
    AND application_status = 'accepted'
";

// Execute the queries
$result_pet = $conn->query($sql_pet);
$pet_info = $result_pet->fetch_assoc();

$result_application = $conn->query($sql_application);
$application = $result_application->fetch_assoc();

$result_application_accepted = $conn->query($sql_application_accepted);
$application_accepted = $result_application_accepted->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="donor_dashboard.css">
</head>
<body>

<div class="sidebar">
    <h2>Dashboard</h2>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="#details">Pet Details</a></li>
        <li><a href="#application-card">Applications</a></li>
        <li><a href="log_out.php">Logout</a></li>
    </ul>
</div>
<div class="main-content">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <!-- <p>You are logged in.</p> -->
    <div class="details" id="details">
        <h2>Pet Details</h2>
        <img src="data:image/jpeg;base64,<?php echo base64_encode($pet_info['pet_pic']); ?>" alt="Pet Picture">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($pet_info['petName']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($pet_info['address']); ?></p>
        <p><strong>Species:</strong> <?php echo htmlspecialchars($pet_info['pet_species']); ?></p>
        <p><strong>Breed:</strong> <?php echo htmlspecialchars($pet_info['breed']); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($pet_info['age']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($pet_info['gender']); ?></p>
        <p><strong>Vaccination Status:</strong> <?php echo htmlspecialchars($pet_info['vaccination']); ?></p>
        <p><strong>Last Vaccination Date:</strong> <?php echo htmlspecialchars($pet_info['lastVaccinationDate']); ?></p>
        <p><strong>Additional Info:</strong> <?php echo htmlspecialchars($pet_info['additionalInfo']); ?></p>
    </div>

    <h2>Pending Adoption Application for Your Pet</h2>
    <?php if ($application): ?>
        <div class="application-card" id="application-card">
            <h3>Adopter Details</h3>
            <p><strong>Adopter ID:</strong> <?php echo htmlspecialchars($application['id']); ?></p>
            <p><strong>Adopter Name:</strong> <?php echo htmlspecialchars($application['name']); ?></p>
            <p><strong>Adopter Email:</strong> <a href="mailto:<?php echo htmlspecialchars($application['email']); ?>"><?php echo htmlspecialchars($application['email']); ?></a></p>
            <p><strong>Adopter Phone:</strong> <a href="tel:<?php echo htmlspecialchars($application['phone']); ?>"><?php echo htmlspecialchars($application['phone']); ?></a></p>
            <p><strong>Adopter Housing:</strong> <?php echo htmlspecialchars($application['housing']); ?></p>
            <p><strong>Adopter Household Members:</strong> <?php echo htmlspecialchars($application['household_members']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($application['application_status']); ?></p>

            <form action="process_application.php" method="post">
                <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($application['id']); ?>">
                <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($pet_info['id']); ?>">
                <button type="submit" name="action" value="accept">Accept Request</button>
                <button type="submit" name="action" value="reject">Reject Request</button>
            </form>
        </div>
    <?php else: ?>
        <p>No adoption applications at the moment.</p>
    <?php endif; ?>

    <h2>Accepted Adoption Application for Your Pet</h2>
    <?php if ($application_accepted): ?>
        <div class="application-card" id="application-card">
            <h3>Adopter Details</h3>
            <p><strong>Adopter ID:</strong> <?php echo htmlspecialchars($application_accepted['id']); ?></p>
            <p><strong>Adopter Name:</strong> <?php echo htmlspecialchars($application_accepted['name']); ?></p>
            <p><strong>Adopter Email:</strong> <a href="mailto:<?php echo htmlspecialchars($application_accepted['email']); ?>"><?php echo htmlspecialchars($application_accepted['email']); ?></a></p>
            <p><strong>Adopter Phone:</strong> <a href="tel:<?php echo htmlspecialchars($application_accepted['phone']); ?>"><?php echo htmlspecialchars($application_accepted['phone']); ?></a></p>
            <p><strong>Adopter Housing:</strong> <?php echo htmlspecialchars($application_accepted['housing']); ?></p>
            <p><strong>Adopter Household Members:</strong> <?php echo htmlspecialchars($application_accepted['household_members']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($application_accepted['application_status']); ?></p>
        </div>
    <?php else: ?>
        <p>No accepted adoption applications at the moment.</p>
    <?php endif; ?>

</div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
