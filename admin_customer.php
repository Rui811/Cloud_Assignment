<?php
session_start();
header('Content-Type: application/json'); // Ensure we return JSON

$conn = new mysqli('192.168.192.73', 'nbuser', 'abc12345', 'cloud');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Handle status change via GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['status'])) {
    $customerID = (int) $_GET['id'];
    $currentStatus = (int) $_GET['status'];
    $newStatus = $currentStatus === 1 ? 0 : 1;

    $stmt = $conn->prepare("UPDATE customer SET status = ? WHERE customer_id = ?");
    $stmt->bind_param("ii", $newStatus, $customerID);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: admin.php");
    exit;
}

// Handle update from SweetAlert + JS fetch
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $custId = $_POST['customer_id'] ?? '';
    $name = $_POST['cust_name'] ?? '';
    $rawPhone = $_POST['cust_phone'] ?? '';
    $address = $_POST['cust_address'] ?? '';

    $sanitizedPhone = preg_replace('/[^0-9]/', '', $rawPhone);
    if (str_starts_with($sanitizedPhone, '0')) {
        $sanitizedPhone = substr($sanitizedPhone, 1);
    }
    $finalPhone = '60' . $sanitizedPhone;

    if (empty($custId) || empty($name) || empty($finalPhone) || empty($address)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE customer SET cust_name = ?, cust_phone = ?, cust_address = ? WHERE customer_id = ?");
    $stmt->bind_param("sssi", $name, $finalPhone, $address, $custId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update customer info.']);
    }

    $stmt->close();
    $conn->close();
    exit;
}
