<?php
$host = "chapalang-database1.cjpdewbot84w.us-east-1.rds.amazonaws.com";       
$username = "main";        
$password = "chapalang-password";           
$database = "chapalang";  

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>
