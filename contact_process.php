<?php


require_once('config.php');


$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$message = mysqli_real_escape_string($conn, $_POST['message']);

// Check if user ID is provided and sanitize it
$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($conn, $_POST['user_id']) : null;

// Create prepared statement
$sql = "INSERT INTO contact (name, email, message )
        VALUES (?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
  echo "Error preparing statement: " . mysqli_error($conn);
  exit;
}

// Bind parameters to prepared statement
mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message );

// Execute prepared statement
if (mysqli_stmt_execute($stmt)) {
  echo "Message sent successfully!";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>