<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SMC Contact Page</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header class="header">
  <nav class="navbar">
      <h2 class="logo"><a href="index.php"><i>S M</i><span class="i">C</span></a></h2>
    
    <a class="toggle-button change"> 
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span> 
    </a>

    <div class="navbar-links">
        <ul>
          <li class="current"><a href="index.php">Home</a></li>
          <li><a href="Information.php">Info</a></li>
          <li><a href="PopularApps.php"> Media Apps</a></li>
          <li><a href="ParentsHelp.php">Parent's Help</a></li>
          <li><a href="Livestreaming.php">Livestream</a></li>
          <li><a  href="Guidance.php">Guidance</a></li>  
          <li><a href="Contact.php">Contact</a></li>
          <li class="dropdown">
            <a href="">Trends</a>
            <div class="dropdown-content">
            <a href="#">Social Media Apps</a>
                  <a href="User_profile.php">User Profile Update</a>
                  <a href="#">tips</a>
            </div>
        </li>
          <li><a href="login.php">Log Out</a></li>
    
                <li class="search-icon">
                    <input type="search" placeholder="Search">
                    <label class="icon">
                      <img src="./img/search.png" alt="search" class="search">
                    </label>
                  </li>
    
            </ul>
        </div>
        
    </nav>
     
  </header>

  <form action="" method="post">
    <h1>Contact Us</h1>
    <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
    
    <label for="name">Your Name:</label>
    <input type="text" id="name" name="name" required><br>

    <label class="emm" for="email">Your Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="message">Message:</label>
    <textarea id="message" name="message" rows="4" cols="50"></textarea><br>
    
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['message'])) {
        if ($_SESSION['message'] === "Message sent successfully") {
            echo "<p class='success-message'>" . $_SESSION['message'] . "</p>";
        } else {
            echo "<p class='error-message'>" . $_SESSION['message'] . "</p>";
        }
        unset($_SESSION['message']);  
    }
    ?>

    <input type="submit" value="Send Message">

    <div class="privacy-policy">
      By submitting, you agree to our <a href="https://www.contractscounsel.com/t/us/website-privacy-policy">Privacy Policy</a>.
    </div>
    
    <?php
    
    require_once('config.php');

    if(isset($_POST['name'], $_POST['email'], $_POST['message'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        
        $user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($conn, $_POST['user_id']) : null;

        
        $sql = "INSERT INTO contact (name, email, message )
                VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
          echo "Error preparing statement: " . mysqli_error($conn);
          exit;
        }

        
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message );

        
        if (mysqli_stmt_execute($stmt)) {
          $_SESSION['message'] = "Message sent successfully!";
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
          header("Location: index.php"); 
          exit(); 
        } else {
          echo "<p class='error-message'>Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['message'])) {
            if ($_SESSION['message'] === "Message sent successfully") {
                echo "<p id='successMessage' class='success-message'>" . $_SESSION['message'] . "</p>";
            } else {
                echo "<p id='errorMessage' class='error-message'>" . $_SESSION['message'] . "</p>";
            }
            unset($_SESSION['message']);  
        }

        
        mysqli_stmt_close($stmt);
    } else {
        
        echo "<p class='error-message'>Please fill in all required fields.</p>";
    }

    mysqli_close($conn);
    ?>
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggleButton = document.querySelector('.toggle-button');
      const navbarLinks = document.querySelector('.navbar-links');
  
      toggleButton.addEventListener('click', () => {
          navbarLinks.classList.toggle('active');
      });
  });
  </script>
</body>
</html>
