<?php
session_start();

$host = "chapalang-database1.cjpdewbot84w.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$database = "chapalang";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

$email = $_POST['email'] ?? '';
$captcha = $_POST['captcha'] ?? '';

if (empty($email) || empty($captcha)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter email and CAPTCHA.']);
    exit();
}

if (strtolower($captcha) !== strtolower($_SESSION['captcha_text'])) {
    echo json_encode(['status' => 'error', 'message' => 'Incorrect CAPTCHA.']);
    exit();
}

$sql = "SELECT customer_id FROM customer WHERE cust_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $_SESSION['reset_user'] = $user['customer_id'];
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Email not found.']);
}
