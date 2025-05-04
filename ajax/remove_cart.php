<?php
// include 'db_connect.php';
// $host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
// $username = "root";
// $password = "";
// $dbname = "cloud_testing";

$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$dbname = "chapalang";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(!isset($_POST['id'])) {
    $_SESSION['errorToast'] ="Invalid Request!";
    header("Location: cart.php");
    exit();
    return;
}

$id = $_POST['id'];

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
