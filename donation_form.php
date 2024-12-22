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
    $donorName = mysqli_real_escape_string($conn, $_POST['donorName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['pass']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $petName = mysqli_real_escape_string($conn, $_POST['petName']);
    $pet_species = mysqli_real_escape_string($conn, $_POST['pet_species']);
    $breed = mysqli_real_escape_string($conn, $_POST['breed']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $vaccination = mysqli_real_escape_string($conn, $_POST['vaccination']);
    $lastVaccinationDate = mysqli_real_escape_string($conn, $_POST['lastVaccinationDate']);
    $additionalInfo = mysqli_real_escape_string($conn, $_POST['additionalInfo']);
    
    // Get the image data
    if (isset($_FILES['pet_pic']) && $_FILES['pet_pic']['error'] === UPLOAD_ERR_OK) {
        $pet_pic = file_get_contents($_FILES['pet_pic']['tmp_name']);
    } else {
        $error = "Error uploading the pet picture.";
    }

    // Check if the email or phone already exists in the database
    $checkQuery = "SELECT 1 FROM pet_donations WHERE donorName = ? OR email = ? OR phone = ?";
    $stmt = $conn->prepare($checkQuery);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("sss", $donorName, $email, $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Email or phone number already exists. Please use a different one.";
    } else {
        // SQL query to insert data into the pet_donations table
        $insertQuery = "INSERT INTO pet_donations (donorName, email, password, address, petName, pet_species, breed, age, gender, reason, vaccination, lastVaccinationDate, additionalInfo, pet_pic) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $insertStmt = $conn->prepare($insertQuery);

        if ($insertStmt === false) {
            die("Error preparing the insert statement: " . $conn->error);
        }
        $nullValue=Null;
        // Bind the non-binary parameters (13 parameters)
        $insertStmt->bind_param("sssssssssssssb", $donorName, $email, $hashed_password, $address, $petName, $pet_species, $breed, $age, $gender, $reason, $vaccination, $lastVaccinationDate, $additionalInfo,$nullValue);

        // Send the binary image data separately
        $insertStmt->send_long_data(13, $pet_pic); // 13 is the index for the pet_pic parameter (0-based index)

        if ($insertStmt->execute()) {
            // Success message with JavaScript alert
            echo "<script>alert('Pet donation submitted successfully');</script>";
            header("Location: login.php"); // Redirect to login page
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
    <title>Pet Donation Application</title>
    <link rel="stylesheet" href="adoption_form.css">
</head>
<body>
    <div class="form-container">
        <h1>Pet Donation Application</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <!-- Applicant Information -->
            <h2>Applicant Information</h2>
            <div class="form_section">
                <label for="donorName">Name:</label>
                <input type="text" id="donorName" name="donorName" placeholder="Enter your full name" required>
            </div>

            <div class="form_section">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form_section">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" placeholder="+44 1234 567890" pattern="^\+?(\d{1,3})?[-.●]?\d{1,4}?[-.●]?\d{1,4}[-.●]?\d{1,9}$" required>
            </div>

            <div class="form_section">
                <label for="pass">Password:</label>
                <input type="password" id="pass" name="pass" placeholder="Enter a strong password" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$">
            </div>

            <div class="form_section">
                <label for="conPass">Confirm Password:</label>
                <input type="password" id="conPass" name="conPass" placeholder="Re-enter your password" required>
            </div>

            <div class="form_section">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" placeholder="Enter your address" required>
            </div>

            <!-- Pet Information -->
            <h2>Pet Information</h2>
            <div class="form_section">
                <label for="petName">Pet Name:</label>
                <input type="text" id="petName" name="petName" placeholder="Enter pet's name" required>
            </div>

            <div class="form_section">
                <label for="pet_species">Species:</label>
                <select id="pet_species" name="pet_species" onchange="updateBreedOptions()" required>
                    <option value="">Select Species</option>
                    <option value="Dog">Dog</option>
                    <option value="Cat">Cat</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div class="form_section">
                <label for="breed">Breed:</label>
                <select id="breed" name="breed" required>
                    <!-- Breed options based on species will be populated dynamically via JavaScript -->
                </select>
            </div>

            <div class="form_section">
                <label for="age">Age (years):</label>
                <input type="number" id="age" name="age" min="0" max="25" placeholder="Enter pet's age" required>
            </div>

            <div class="form_section">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="unknown">Unknown</option>
                </select>
            </div>

            <!-- Reason for Donation -->
            <h2>Reason for Donation</h2>
            <div class="form_section">
                <label for="reason">Why are you donating the pet?</label>
                <input type="text" id="reason" name="reason" placeholder="Explain your reason for donating" required>
            </div>

            <div class="form_section">
                <label for="vaccination">Vaccination Status:</label>
                <select id="vaccination" name="vaccination" onchange="showLastVaccinationDate()" required>
                    <option value="">Select Vaccination Status</option>
                    <option value="completed">Completed</option>
                    <option value="not_completed">Not Completed</option>
                </select>
            </div>

            <!-- Show Last Vaccination Date if Vaccination is completed -->
            <div class="form_section" id="lastVaccinationDateDiv" style="display: none;">
                <label for="lastVaccinationDate">Last Vaccination Date:</label>
                <input type="date" id="lastVaccinationDate" name="lastVaccinationDate">
            </div>

            <div class="form_section">
                <label for="additionalInfo">Additional Information (Allergies, Habits, etc.):</label>
                <input type="text" id="additionalInfo" name="additionalInfo" placeholder="Mention allergies, habits, etc." required>
            </div>

            <div class="form_section">
                <label for="pet_pic">Upload a Pet Picture:</label>
                <input type="file" id="pet_pic" name="pet_pic" accept="image/jpeg, image/png, image/jpg" required>
            </div>

            <!-- Submit Button -->
            <input type="submit" value="Donate Pet">

            <p>Wants to Adopt a Pet? <a href="adoption_forn.php"><b>Sign Up as a adoptor</b></a></p>

            <!-- Display error message -->
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
        </form>
    </div>

    <!-- Include your JavaScript for form validation or dynamic behaviors -->
    <script src="indexscript.js"></script>
</body>
</html>
