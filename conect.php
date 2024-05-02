<?php 
$hostname = "localhost";
$username = "root";
$password = "";
$database = "smc2";



$smcprojectbd = new mysqli("localhost","root","", "smc2");

    if($smcprojectbd->connect_error){
        die("Connection failed: " . $smcprojectbd->connect_error);
    }


    $database = "CREATE DATABASE IF NOT EXISTS smc2";
    // Check if database is created successfully
    if($smcprojectbd->query($database) === TRUE){
        echo "<p>Database created successfully</p>";
    }else{
        die("<p>Error creating database: </p>" . $smcprojectbd->error);
    }


    $smcprojectbd = new mysqli("localhost","root","","smc2");
    // Check if database is selected
    if($smcprojectbd->connect_error){
        die ("<p>Could not select database: </p>" . $smcprojectbd->connect_error);
    }else{
        echo "<p>Database 'smc' successfully selected</p>";
    }


$database =
  " CREATE TABLE IF NOT EXISTS register (
  id int(7) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  firstname varchar(30) NOT NULL,
  lastname varchar(30) NOT NULL,
  username varchar(30) NOT NULL,
  password varchar(30) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1";


    
    if($smcprojectbd->query($database) === TRUE){
        echo "<p>register table successfully created";
    }else{
        die("<p>Could not create table register: </p>" . $smcprojectbd->error);
    }

          

  $database ='CREATE TABLE IF NOT EXISTS contact (
  id int(7) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  email varchar(50) NOT NULL,
  message text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1'; 

          if ($smcprojectbd->query($database) === TRUE) {
              echo '<p class="success">Table contact created successfully</p>';
          } else {
              echo '<p class="error">Error creating table: ' . $smcprojectbd->error . '</p>';
          }

          $database = 'CREATE TABLE IF NOT EXISTS newsletter (
  id int(2) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  email varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1';
  
          if ($smcprojectbd->query($database) === TRUE) {
              echo '<p class="success">Newsletter created successfully</p>';
          } else {
              echo '<p class="error">Error creating table: ' . $smcprojectbd->error . '</p>';
          }



$database ='CREATE TABLE IF NOT EXISTS loginlogs (
    id int(7) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    TryTime varchar(30) NOT NULL,
    IpAddress varchar(30) NOT NULL
  ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1'; 
  
            if ($smcprojectbd->query($database) === TRUE) {
                echo '<p class="success">loginlogs created successfully</p>';
            } else {
                echo '<p class="error">Error creating table: ' . $smcprojectbd->error . '</p>';
            }

            
            header('Location: register.php');

// echo "<p><a  class='login' href='login.php'>Login</a></p><br/>";
// echo "<p><a   class='login'  href='register.php'>Register</a></p>";

//Close connection
$smcprojectbd->close();
?>
