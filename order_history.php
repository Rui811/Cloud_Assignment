<?php

function getOrderHistory($customerId, $conn) {
    $order_sql = "
        SELECT o.order_id, o.order_date, o.total_amount, o.order_state, 
               od.product_id, od.quantity, od.unit_price, p.productName, p.image
        FROM `order` o
        JOIN `order_details` od ON o.order_id = od.order_id
        JOIN `product` p ON od.product_id = p.productID
        WHERE o.customer_id = ? 
        ORDER BY o.order_date DESC
    ";

    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $order_result = $stmt->get_result();
    $orders = [];

    while ($row = $order_result->fetch_assoc()) {
        $orders[] = $row;
    }

    $stmt->close();
    return $orders;
}

?>
