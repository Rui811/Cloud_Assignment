<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    return;
}

$customerId = $_SESSION['user_id'];

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selected_items"])) {
    $selectedItems = $_POST['selected_items'];
    $names = $_POST['item_names'];
    $images = $_POST['item_images'];
    $prices = $_POST['item_prices'];
    $quantities = $_POST['item_quantities'];
    $subtotals = $_POST['item_subtotals'];
    $remarks = $_POST['item_remarks'];
    $grandTotal = $_POST['grand_total'];
}
else {
    $_SESSION['errorToast'] = "Invalid Request!";
    header("Location: homepage.php");
    return;
}
?>