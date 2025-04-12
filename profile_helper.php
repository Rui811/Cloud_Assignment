<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: profile.php?updated=1");
    exit();
}

$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customer_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $new_name = $_POST['cust_name'];
    $new_phone = $_POST['cust_phone'];
    $new_address = trim($_POST['cust_address']);

    //format phone number
    $new_phone = preg_replace('/\D/', '', $_POST['cust_phone']);
    if (substr($new_phone, 0, 1) === '0') {
        $new_phone = substr($new_phone, 1);
    }
    $new_phone = '60' . $new_phone;

    $update_sql = "UPDATE customer SET cust_name = ?, cust_phone = ?, cust_address = ? WHERE customer_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssss", $new_name, $new_phone, $new_address, $customer_id);

    if ($stmt->execute()) {
        header("Location: profile.php?updated=1");
        exit();
    }    
    // var_dump($_SESSION);
}

$sql = "SELECT cust_username, cust_name, cust_email, cust_phone, cust_address FROM customer WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

$conn->close();
?>