<?php
$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$database = "chapalang";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];

$sql = "SELECT * FROM admin WHERE admin_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "found";
} else {
    echo "not_found";
}

$stmt->close();
$conn->close();
?>
