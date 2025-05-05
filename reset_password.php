<?php
$host = "chapalang-database1.cjpdewbot84w.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$database = "chapalang";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$newPassword = $_POST['newPassword'];

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$sql = "UPDATE admin SET admin_password = ? WHERE admin_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $hashedPassword, $email);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "fail";
}

$stmt->close();
$conn->close();
?>
