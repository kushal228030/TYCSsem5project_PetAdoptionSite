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

// Retrieve adopter details from the session
$adopter_id = $_SESSION['user_id'];

// Check if pet ID is provided in the POST request
if (isset($_POST['pet_id'])) {
    $pet_id = $conn->real_escape_string($_POST['pet_id']);

    // Update the pet_donations table to set requested_adopter_id to NULL
    $sql = "UPDATE pet_donations SET requested_adopter_id = NULL WHERE id = '$pet_id'";
    $sql_status="update pet_adoption_status set application_status = null where id ='$adopter_id'";

    if ($conn->query($sql) === TRUE && $conn->query($sql_status)) {
        echo "Adoption request cancelled successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No pet ID provided.";
}

// Close the database connection
$conn->close();
?>

<!-- Redirect back to the dashboard -->
<a href="adopter_dashboard.php" class="btn">Back to Dashboard</a>