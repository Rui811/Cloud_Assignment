<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM cart WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);

$cartItems = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cartItems[] = $row;
}

echo json_encode($cartItems);
?>
