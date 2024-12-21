<?php
session_start();
include 'config.php'; // Include your database connection setup

// Check if the user is logged in
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: login.php");
    die();
}

$msg = "";
$email = $_SESSION['SESSION_EMAIL'];

// Fetch the current user's data
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($query);

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $updateQuery = "";

    if (!empty($_FILES['profile']['name'])) {
        $targetDir = "photos/"; // Directory where the file will be stored
        $profile = basename($_FILES['profile']['name']);
        $targetFilePath = $targetDir . $profile;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Validate file type
        $allowedTypes = array('jpg', 'jpeg', 'png');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES['profile']['tmp_name'], $targetFilePath)) {
                // Update the profile picture path in the database
                $updateQuery = "UPDATE users SET name='$name', phone='$phone', profile='$profile' WHERE email='$email'";
            } else {
                $msg = "<div class='alert alert-danger'>Failed to upload profile picture.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Invalid file type. Only .jpg, .jpeg, and .png files are allowed.</div>";
        }
    }
    
    // If no file was uploaded or if it was successfully uploaded, update name and phone
    if (empty($updateQuery)) {
        $updateQuery = "UPDATE users SET name='$name', phone='$phone' WHERE email='$email'";
    }

    // Execute the update query if it was defined
    if (!empty($updateQuery)) {
        $result = mysqli_query($conn, $updateQuery);

        if ($result) {
            $msg = "<div class='alert alert-success'>Profile updated successfully!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Something went wrong. Please try again.</div>";
        }
    }
}

    // Check if a file was uploaded
    // if (!empty($_FILES['profile']['name'])) {
    //     $targetDir = "photos/"; // Directory where the file will be stored
    //     $profile = basename($_FILES['profile']['name']);
    //     $targetFilePath = $targetDir . $profile;

    //     // Move the uploaded file to the specified directory
    //     if (move_uploaded_file($_FILES['profile']['tmp_name'], $targetFilePath)) {
    //         // Update the profile picture path in the database
    //         $updateQuery = "UPDATE users SET name='$name', phone='$phone', profile='$profile' WHERE email='$email'";
    //     } else {
    //         $msg = "<div class='alert alert-danger'>Failed to upload profile picture.</div>";
    //     }
    // } else {
    //     // Update only the name and phone if no file was uploaded
    //     $updateQuery = "UPDATE users SET name='$name', phone='$phone' WHERE email='$email'";
    // }

    // $result = mysqli_query($conn, $updateQuery);

    // if ($result) {
    //     $msg = "<div class='alert alert-success'>Profile updated successfully!</div>";
    // } else {
    //     $msg = "<div class='alert alert-danger'>Something went wrong. Please try again.</div>";
    // }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Profile - Brave Coder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords" content="Update Profile Form" />

    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bounce.css" type="text/css" media="all" />
    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>
</head>

<body>

    <!-- form section start -->
    <section class="w3l-mockup-form">
        <div class="container">
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fa fa-close"></span>
                    </div>
                    <div class="w3l_form align-self">
                        <div class="left_grid_info">
                            <img src="photos/splash.png" alt="Splash" class="background">
                            <img src="photos/juice.png" alt="Juice" class="foreground">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <h2>Update Profile</h2>
                        <p>Keep your profile information up to date.</p>
                        <?php echo $msg; ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="text" class="name" name="name" placeholder="Enter Your Name" value="<?php echo $user['name']; ?>" required>
                            <input type="phone" class="phone" name="phone" placeholder="Enter Your Phone Number" value="<?php echo $user['phone']; ?>" required>
                            <label for="profile">Profile Picture:</label>
                            <input type="file" name="profile" id="profile" accept=".jpg,.jpeg,.png">
                            
                            
                            <button name="submit" class="btn" type="submit">Update</button>
                        </form>
                        <div class="social-icons">
                            <p><a href="homePage.php">Back to Home</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //form section start -->

    <script src="js/jquery.min.js"></script>
    <script>
    $(document).ready(function () {
        $('.alert-close').on('click', function () {
            $('.main-mockup').fadeOut('slow', function () {
                window.location.href = 'homePage.php'; // Redirect to homePage.php
            });
        });
    });
    </script>

</body>

</html>
