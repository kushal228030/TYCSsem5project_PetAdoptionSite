<?php
// Start session to manage logged-in users
session_start();

// Include the database connection file
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and escape form data
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Initialize variables for error handling
    $user_found = false;
    $error = '';

    // Check if the user exists in the pet_donations table (Donor)
    $sql_donor = "SELECT * FROM pet_donations WHERE donorName = '$username'";
    $result_donor = $conn->query($sql_donor);

    if ($result_donor->num_rows === 1) {
        // Fetch donor data
        $donor = $result_donor->fetch_assoc();

        // Verify password
        if (password_verify($password, $donor['password'])) {
            // Password is correct, start a session
            $_SESSION['user_id'] = $donor['id'];
            $_SESSION['username'] = $donor['donorName'];
            $_SESSION['user_type'] = 'donor';
            //$_SESSION['pet_preference'] = $donor['type_of_pet']; // Add pet preference to session

            // Redirect to the donor dashboard or the page they came from
            $redirect_url = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
            header("Location: $redirect_url");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        // Check if the user exists in the pet_adoption_applications table (Adopter)
        $sql_adopter = "SELECT * FROM pet_adoption_applications WHERE name = '$username'";
        $result_adopter = $conn->query($sql_adopter);

        if ($result_adopter->num_rows === 1) {
            // Fetch adopter data
            $adopter = $result_adopter->fetch_assoc();

            // Verify password
            if (password_verify($password, $adopter['password'])) {
                // Password is correct, start a session
                $_SESSION['user_id'] = $adopter['id'];
                $_SESSION['username'] = $adopter['username'];
                $_SESSION['user_type'] = 'adopter';
                $_SESSION['pet_preference'] = $adopter['type_of_pet']; // Add pet preference to session

                // Redirect to the adopter dashboard or the page they came from
                $redirect_url = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
                header("Location: $redirect_url");
                exit();
            } else {
                $error = "Invalid password. Please try again.";
            }
        } else {
            $error = "User not found. Please check your username.";
        }
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login_style2.css">
</head>
<body>
    <div class="login_form">
        <form class="login" action="login.php<?php echo (!empty($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''); ?>" method="post">
            <h1 class="heading">Login Page</h1> 
            <?php if (isset($error) && $error !== ''): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="container_img">
                <img src="images/dog.png" alt="Dog Image">
            </div>
            <div class="form_element"> 
                <label for="username">Enter a username:</label>
            </div>
            <div class="form_inp">
                <img src="images/user-regular.svg">
                <input type="text" id="username" required name="username" autocomplete="off">
            </div>
            <div class="form_element">
                <label for="password">Enter a Password:</label>
            </div>
            <div class="form_inp">
                <img src="images/lock-solid.svg">
                <input type="password" id="password" name="password" autocomplete="off" required>
            </div>
            <div class="form_element">
                <button type="submit" class="btn_login">Login</button>
            </div>
            <div class="form_element">
                <p>Froget Password?</p><a href="forget_pass.php">Reset your password</a>
            </div>
            <div class="form_element">
                <p>Don't have an account?</p>
                <a href="adoption_forn.php" class="signup-link">Sign up</a>
            </div>
        </form>
    </div>
</body>
</html>