<?php
session_start();
header('Content-Type: application/json');

// Display all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch POST data
    $username = $_POST['cust_username'] ?? '';
    $email = $_POST['cust_email'] ?? '';
    $name = $_POST['cust_name'] ?? '';
    $phone = $_POST['cust_phone'] ?? '';
    $address = $_POST['cust_address'] ?? '';
    $password = $_POST['cust_password'] ?? '';

    // Validate the inputs
    if (empty($username) || empty($email) || empty($name) || empty($phone) || empty($address) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Sanitize phone number
    $sanitizedPhone = preg_replace('/[^0-9]/', '', $phone);
    if (str_starts_with($sanitizedPhone, '0')) {
        $sanitizedPhone = substr($sanitizedPhone, 1);
    }
    $finalPhone = '60' . $sanitizedPhone;

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Default status set to 1 (Active)
    $status = 1;

    // Insert into the database with status 1
    $stmt = $conn->prepare("INSERT INTO customer (cust_username, cust_email, cust_name, cust_phone, cust_address, cust_psw, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $username, $email, $name, $finalPhone, $address, $hashedPassword, $status);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Customer added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add customer: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
