<?php
session_start();
$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com"; 
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';

$sql = "SELECT * FROM admin WHERE admin_username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();

    if (isset($admin) && password_verify($pass, $admin['admin_password'])) {
        $_SESSION['admin'] = $admin['admin_username'];
        $_SESSION['admin_id'] = $admin['admin_id'];
        echo 'success';
    } else {
        echo 'fail'; // password mismatch
    }
} else {
    echo 'fail'; // user not found
}

$stmt->close();
$conn->close();
?>