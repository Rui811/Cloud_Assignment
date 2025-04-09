<?php
session_start();
include 'db.php';

$id = $_POST['id'];
$action = isset($_POST['action']) ? $_POST['action'] : "";
$value = isset($_POST['value']) ? $_POST['value'] : 0;

if ($action == "increase") {
    mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE id = '$id'");
} else if ($action == "decrease") {
    mysqli_query($conn, "UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE id = '$id'");
} 

if ($value != 0) {
    mysqli_query($conn, "UPDATE cart SET quantity = '$value' WHERE id = '$id'");
}
?>
