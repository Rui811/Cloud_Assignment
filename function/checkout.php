<?php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selected_items"])) {
    $selectedItems = $_POST['selected_items'];
    $names = $_POST['item_names'];
    $prices = $_POST['item_prices'];
    $quantities = $_POST['item_quantities'];
    $subtotals = $_POST['item_subtotals'];
    $grandTotal = $_POST['grand_total'];
}
else {
    $_SESSION['errorToast'] = "Invalid Request!";
    header("Location: homepage.php");
    return;
}
?>