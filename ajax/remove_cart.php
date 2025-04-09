<?php
session_start();
// include 'db.php';
$host = "localhost";
$username = "root";
$password = "";
$dbname = "cloud_testing";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];

if(!isset($id)) {
    $_SESSION['errorToast'] ="Invalid Request!";
    header("Location: cart.php");
    exit();
    return;
}

$sql = "DELETE FROM Cart WHERE cart_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$conn->close();
?>
