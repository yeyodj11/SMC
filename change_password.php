<?php
session_start();
require_once('config.php'); 

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (count($_POST) > 0) {
    $currentPassword = $_POST["current_password"];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE password='$currentPassword'");
    
    if (!$result) {
        die("Error in SQL query: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);
        mysqli_query($conn, "UPDATE users SET password='$newPassword' WHERE password='$currentPassword'");
        $message = "Password Changed";
        
        
        header("Location: login.php");
        exit();
    } else {
        $message = "Current Password is not correct";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css" />
    <script>
        function validatePassword() {
            var currentPassword = document.frmChange.current_password;
            var newPassword = document.frmChange.new_password;
            var confirmPassword = document.frmChange.confirm_password;

            if (!currentPassword.value) {
                currentPassword.focus();
                document.getElementById("currentPasswordError").innerHTML = "required";
                return false;
            } else if (!newPassword.value) {
                newPassword.focus();
                document.getElementById("newPasswordError").innerHTML = "required";
                return false;
            } else if (!confirmPassword.value) {
                confirmPassword.focus();
                document.getElementById("confirmPasswordError").innerHTML = "required";
                return false;
            }
            if (newPassword.value != confirmPassword.value) {
                newPassword.value = "";
                confirmPassword.value = "";
                newPassword.focus();
                document.getElementById("confirmPasswordError").innerHTML = "not same";
                return false;
            }
            return true;
        }
    </script>
</head>
<body>


<form action="" method="post" name="frmChange" onsubmit="return validatePassword();">
    <h1>Change Password</h1>
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required><br>

    <label for="current_password">Current Password</label>
    <input type="password" id="currentPasswordError" name="current_password" required><br>

    <label for="new_password">New Password</label>
    <input type="password" id="newPasswordError" name="new_password" required><br>

    <label for="confirm_password">Confirm Password</label>
    <input type="password" id="confirmPasswordError" name="confirm_password" required><br>
    <br>

    <input type="submit" value="Submit">
    <p class="lop">Already have an account? <a href='login.php'>Login</a></p>
    <br>
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
