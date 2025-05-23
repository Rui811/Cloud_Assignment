<?php
session_start();
$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$database = "chapalang";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$adminID = $_SESSION['admin_id'];
$newPhone = $_POST['admin_phone'] ?? '';

$valid = false;
if (preg_match('/^011-\d{8}$/', $newPhone) ||
    preg_match('/^01[02456789]-\d{7}$/', $newPhone)) {
    $valid = true;
}

if (!$valid) {
    echo "invalid";
    exit;
}

if (!empty($newPhone)) {
    $stmt = $conn->prepare("UPDATE admin SET admin_phone = ? WHERE admin_id = ?");
    $stmt->bind_param("si", $newPhone, $adminID);

    if ($stmt->execute()) {
        echo "success"; 
    } else {
        echo "failed";    
    }
    $stmt->close();
} else {
    echo "invalid"; 
}

$conn->close();
?>