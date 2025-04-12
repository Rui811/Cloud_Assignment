<?php
session_start();
$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Check if username exists
$stmt = $conn->prepare("SELECT customer_id FROM customer WHERE cust_username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $errors[] = "Username already taken.";
}
$stmt->close();

// Check if email exists
$stmt = $conn->prepare("SELECT customer_id FROM customer WHERE cust_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $errors[] = "Email already registered.";
}
$stmt->close();

if (!empty($errors)) {
    $_SESSION['signup_errors'] = $errors;
    header("Location: signup.php");
    exit();
}

// If no error, hash password and insert
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO customer (cust_username, cust_email, cust_psw) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hashedPassword);
$stmt->execute();
$stmt->close();

$_SESSION['signup_success'] = true;
header("Location: signup.php");
exit();
?>
