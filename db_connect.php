<?php
$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";       
$username = "nbuser";        
$password = "abc12345";           
$database = "cloud";  

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>
