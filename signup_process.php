<?php

$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$cust_username = $_POST['username'];
$cust_email = $_POST['email'];
$cust_psw = $_POST['password'];

$hashed_password = password_hash($cust_psw, PASSWORD_DEFAULT);

$sql = "INSERT INTO customer (cust_username, cust_email, cust_psw) 
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $cust_username, $cust_email, $hashed_password);

if ($stmt->execute()) {
    header("Location: login.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
