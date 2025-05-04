<?php
$host = "localhost";       
$username = "nbuser";        
$password = "abc12345";           
$database = "cloud";  

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>
