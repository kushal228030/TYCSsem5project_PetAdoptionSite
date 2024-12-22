<?php
// Include database connection file
include 'db_connection.php';

// Initialize an error message variable
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize and fetch data from POST
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['pass']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password
    $household_members = mysqli_real_escape_string($conn, $_POST['household_members']);
    $housing = mysqli_real_escape_string($conn, $_POST['housing']);
    $type_of_pet = mysqli_real_escape_string($conn, $_POST['typeOfPet']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']); // Assuming phone is posted
    $donorName = mysqli_real_escape_string($conn, $_POST['name']); // Assuming donorName is the same as name

    // Check if the username, mobile, or email already exists in pet_donations or pet_adoption_applications
    $checkQuery = "SELECT 1 FROM pet_donations WHERE donorName = ? OR email = ? OR phone = ? 
                   UNION 
                   SELECT 1 FROM pet_adoption_applications WHERE name = ? OR email = ? OR phone = ?";
    $stmt = $conn->prepare($checkQuery);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $donorName, $email, $phone, $donorName, $email, $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // If any results are returned, the username, email, or phone is already taken
        $error = "Username, email, or phone number already exists. Please use a different one.";
    } else {
        // SQL query to insert data into the pet_adoption_applications table
        $insertQuery = "INSERT INTO pet_adoption_applications (name, email, password, household_members, housing, type_of_pet, phone) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);

        if ($insertStmt === false) {
            die("Error preparing the insert statement: " . $conn->error);
        }

        $insertStmt->bind_param("sssssss", $name, $email, $hashed_password, $household_members, $housing, $type_of_pet, $phone);

        if ($insertStmt->execute()) {
            // Success message with JavaScript alert
            echo "<script>alert('New application submitted successfully');</script>";
            header("Location: login.php");
            exit;
        } else {
            $error = "Error: " . $insertQuery . "<br>" . $conn->error;
        }

        // Close the insert statement
        $insertStmt->close();
    }

    // Close the statement after checking
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pet Adoption Form</title>
  <link rel="stylesheet" href="adoption_form.css">
  <script src="indexscript.js" defer></script>
</head>
<body>
  

  <?php
  if (!empty($error)) {
      echo "<div class='error-message'>$error</div>";
  }
  ?>
   <h1>Pet Adoption Application</h1>

  <form action="" method="post" onsubmit="return adoption_validateForm();">
 
    <div class="form_section">
    
      <h2>Applicant Information</h2>
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>
      
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <label for="phone">Contact no:</label>
      <input type="tel" id="phone" name="phone" required>
      
      <label for="pass">Password:</label>
      <input type="password" id="pass" name="pass" required minlength="8">
      
      <label for="conPass">Confirm Password:</label>
      <input type="password" id="conPass" name="conPass" required minlength="8">
    </div>
    
    <div class="form_section">
      <h2>Household Information</h2>
      <label for="household_members">Number of people in household:</label>
      <input type="number" id="household_members" name="household_members" required min="1">
      
      <label for="housing">Housing situation:</label>
      <select id="housing" name="housing" required>
        <option value="">Select...</option>
        <option value="rent">Renting</option>
        <option value="own">Own</option>
        <option value="other">Other</option>
      </select>
    </div>
    
    <div class="form_section">
      <h2>Pet Preferences</h2>
      <label for="pet_type">Preferred pet type:</label>
      <select id="pet_type" name="typeOfPet" required>
        <option value="">Select...</option>
        <option value="cat">Cat</option>
        <option value="dog">Dog</option>
        <option value="other">Other</option>
      </select>
    </div>
    
    <input type="submit" value="Submit Application">
    
    <p>Wants to Donate a Pet? <a href="donation_form.php"><b>Sign Up as a donor</b></a></p>
  </form>
</body>
</html>
