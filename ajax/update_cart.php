<?php
// include 'db_connect.php';
// $host = "localhost";
// $username = "root";
// $password = "";
// $dbname = "cloud_testing";

$host = "localhost";
$username = "nbuser";
$password = "abc12345";
$dbname = "cloud";

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

$action = isset($_POST['action']) ? $_POST['action'] : "";
$value = isset($_POST['value']) ? $_POST['value'] : 0;

if ($action == "increase") {
    $sql = "UPDATE Cart SET quantity = quantity + 1 WHERE cart_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
else if ($action == "decrease") {
    $sql = "UPDATE Cart SET quantity = GREATEST(quantity - 1, 1) WHERE cart_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} 

if ($value != 0) {
    $sql = "UPDATE Cart SET quantity = ? WHERE cart_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $value, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}

$conn->close();
?>
