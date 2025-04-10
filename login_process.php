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

$identifier = $_POST['identifier']; 
$password = $_POST['password'];

$sql = "SELECT * FROM customer WHERE cust_email = ? OR cust_username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $identifier, $identifier);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['cust_psw'])) {
        $_SESSION['user_id'] = $user['customer_id'];
        $_SESSION['username'] = $user['cust_username'];
        $_SESSION['email'] = $user['cust_email'];
        header("Location: homepage.php"); 
        exit();
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "No account found with that email or username.";
}

$stmt->close();
$conn->close();
?>
