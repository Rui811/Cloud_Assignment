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
$adminID = $_SESSION['admin_id'];
$oldPassword = $_POST['old_password'];
$newPassword = $_POST['new_password'];

$stmt = $conn->prepare("SELECT admin_password FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $adminID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if ($row && password_verify($oldPassword, $row['admin_password'])) {
    // Update to new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateStmt = $conn->prepare("UPDATE admin SET admin_password = ? WHERE admin_id = ?");
    $updateStmt->bind_param("ss", $hashedNewPassword, $adminID);
    $updateStmt->execute();

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}

?>