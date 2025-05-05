<?php
session_start();

$host = "chapalang-database1.cjpdewbot84w.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$database = "chapalang";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];

$customer_id = $_SESSION['user_id'];

$sql = "SELECT cust_psw FROM customer WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (!password_verify($current_password, $user['cust_psw'])) {
        echo 'Current password is incorrect.';
        exit();
    }

    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

    if (!preg_match($passwordRegex, $new_password)) {
        echo 'Password must be at least 8 characters, include an uppercase letter, a lowercase letter, a number, and a special character.';
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update_sql = "UPDATE customer SET cust_psw = ? WHERE customer_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ss", $hashed_password, $customer_id);

    if ($stmt->execute()) {
        echo 'success';  
    } else {
        echo 'Failed to change password.';
    }
} else {
    echo 'User not found.';
}

$stmt->close();
$conn->close();
?>
