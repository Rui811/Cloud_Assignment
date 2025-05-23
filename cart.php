<?php
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$customer_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cart | ChapaLang Graduation Gifts</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            body {
                background-image: url("image/wallpaper2.png");
                background-size: 2500px 2500px;
                background-repeat: no-repeat;
                height: 100vh;
                margin: 0;
            }

            .back-product-btn {
                text-decoration: none;
                width: fit-content;
                font-size: 18px;
                font-weight: bold;
                padding-left: 8px;
                color:rgb(92, 96, 173);
                display: flex;
                align-items: center;
                gap: 5px;
                cursor: pointer;
                transition: 0.5s ease-in-out;

                &:hover {
                    transform: translateX(-4px);
                    color:rgb(64, 68, 151);
                }
            }

            .cart-container {
                display: flex;
                gap: 20px;
                flex-wrap: wrap;
            }

            .cart-items {
                flex: 1;
                max-height: 500px;
                overflow-y: auto;
                padding-right: 10px;
            }

            .cart-items table {
                margin-bottom: 0;
            }

            .cart-items thead th {
                position: sticky;
                top: 0;
                background-color: #2D328F;
                color: white;
                z-index: 1;
            }

            .summary-card {
                width: 100%;
                position: sticky;
                top: 10px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .checkout-container {
                background-color: white;
                padding: 10px;
                border-top: 1px solid #ddd;
                margin-top: auto;
            }

            .checkoutBtn {
                background-color: #2D328F;
                color: rgb(232, 232, 232);
                font-size: 18px;
                padding: 12px 20px;
                width: 100%;
                border: none;
                border-radius: 5px;
                transition: 0.3s ease-in-out;

                &:hover {
                    transform: scale(1.01, 1.01);
                    background-color:rgb(21, 26, 136);
                    color: white;
                }
            }

            @media (max-width: 768px) {
                .cart-container {
                    flex-direction: column;
                }
                .cart-items {
                    max-height: none;
                    overflow-x: auto;
                }
            }

            @media (min-width: 768px) {
                .summary-card {
                    min-height: 500px;
                }
            }

            /* .empty-cart {
                text-align: center;
                font-size: 20px;
                color: gray;
                font-style: italic;
                padding: 20px;
            } */
            #empty-summary-message {
                height: 180px;
            }

            #cart-summary-items {
                height: 180px;
                overflow-y: auto;
                padding-right: 5px;
            }

            .checkout-selection, .checkout-selection-all {
                appearance: none;
                -webkit-appearance: none;
                -moz-appearance: none;
                width: 14px;
                height: 14px;
                border-radius: 4px;
                cursor: pointer;
                position: relative;
                outline: none;
                transform: scale(1.5);
                transition: 0.3s ease-in-out;

                &:hover {
                    transform: scale(1.55);
                    border-color: #1a1e6b;
                    background-color: rgb(238, 238, 238);
                }
            }

            .checkout-selection {
                border: 2px solid #2D328F;

                &:checked {
                    background-color: #2D328F;
                    border-color: #2D328F;

                    &:hover {
                        border-color: #1a1e6b;
                        background-color: #1a1e6b;
                    }

                    &::after {
                        content: '\2713'; /* ✓ checkmark */
                        position: absolute;
                        color: white;
                        font-size: 14px;
                        line-height: 10px;
                    }
                }
            }

            .checkout-selection-all {
                border-color: white;
                background-color: white;

                &:checked {
                    background-color: white;
                    border-color: white;

                    &:hover {
                        border-color:rgb(238, 238, 238);
                        background-color: rgb(238, 238, 238);
                    }

                    &::after {
                        content: '\2713'; /* ✓ checkmark */
                        position: absolute;
                        top: 0;
                        left: 2px;
                        color: #2D328F;
                        font-size: 14px;
                        line-height: 14px;
                    }
                }
            }

            .quantity-container {
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .quantity-btn {
                background-color: #2D328F;
                width: 35px;
                color: white;
                border: none;
                padding: 5px 10px;
                font-size: 16px;
                cursor: pointer;
                transition: 0.3s ease-in-out;
                border-radius: 5px;

                &:hover {
                    background-color: #1a1e6b;
                }
            }

            .quantity-input {
                width: 50px;
                text-align: center;
                font-size: 16px;
                padding: 5px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            .remove-item {
                border: none;
                background-color: transparent;
            }

            footer {
                font-family: 'Poppins', sans-serif;
                position: absolute;
                font-size: 14px;
                color: #666;
                display: flex;
                justify-content: center;
                width: 100%;
                margin-top: 10px;
                padding: 10px;
            }

            .cart-item-remark {
                margin-top: 4px;
                font-size: 0.9rem;
                font-style: italic;
            }

        </style>
    </head>
    <body>
        <div class="container mt-4">

            <div class="card shadow mt-3">
                <div class="card-body">
                    <a class="back-product-btn" href="product.php" title="back to product page">
                        <i class="fa-solid fa-shop"></i> Shop
                    </a>
                    
                    <div class="cart-container mt-3">
                        <!-- Cart Items (Fetched using AJAX) -->
                        <div class="cart-items">
                            <table class="table mb-4">
                                <thead>
                                    <tr>
                                        <th style="min-width: 40px;" class="text-center align-middle">
                                            <input type="checkbox" name="" class="checkout-selection-all" id="" />
                                        </th>
                                        <th width="40%" colspan="2">Item</th>
                                        <th width="12%">Price</th>
                                        <th width="9%">Quantity</th>
                                        <th width="12%">Subtotal</th>
                                        <th width="9%" style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-body">
                                    <!-- Items will be loaded here via AJAX -->
                                </tbody>
                            </table>
                            <div id="empty-cart-message" class="empty-cart" style="display: none;">
                                Nothing here.
                            </div>
                        </div>

                        <!-- Summary (Sticky) -->
                        <div class="col-md-4">
                            <div class="card p-3 summary-card">
                                <h4 class="text-center mb-0">SUMMARY</h4>
                                <hr class="mt-0">
                                <div id="empty-summary-message" class="empty-summary text-center text-muted py-3" style="display: none;">
                                    <i class="fa-solid fa-cart-shopping fa-2x mb-2 mt-4"></i>
                                    <p class="mb-0">No items selected.</p>
                                    <small>Select items from your cart to see the summary here.</small>
                                </div>
                                <div id="cart-summary-items">
                                    
                                </div>
                                <hr>
                                <div id="cart-summary">
                                    <!-- <h5>Total Selected Items: <span id="total-selected">0</span></h5> -->
                                    <h5>Grand Total: RM <span id="grand-total">0.00</span></h5>
                                </div>
                                
                                <div class="checkout-container mt-2">
                                    <form method="post" action="checkout.php" id="checkout-form">
                                        <button type="submit" name="checkout" class="btn checkoutBtn">
                                            <i class="fa-solid fa-check"></i> Checkout (<span id="total-selected">0 items</span>)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const customerId = <?= $customer_id ?>;
            let selectedCartIds = [];

            $(document).ready(function() {
                loadCart();

                function storeSelectedCartIds() {
                    selectedCartIds = [];

                    $('.checkout-selection:checked').each(function() {
                        selectedCartIds.push($(this).val());
                    });
                }

                function loadCart() {
                    $.ajax({
                        url: 'fetch_cart.php',
                        type: 'POST',
                        data: {
                            "customer_id" : customerId
                        },
                        dataType: 'json',
                        success: function(cartData) {
                            let items = "";

                            if (cartData.length === 0) {
                                $("#cart-body").html("");
                                $("#empty-cart-message").show();
                            }
                            else {
                                $("#empty-cart-message").hide();

                                cartData.forEach(item => {
                                    let subtotal = item.price * item.quantity;

                                    items += `
                                        <tr class="cart-item-row">
                                            <td class="text-center align-middle">
                                                <input type="checkbox" name="selected_items[]" class="checkout-selection" value="${item.cart_id}" />
                                            </td>
                                            <td width="15%">
                                                <input type="hidden" name="item_images[]" value="${item.image}" />
                                                <img src="image/${item.image}.png" width="100">
                                            </td>
                                            <td width="25%">
                                                <input type="hidden" name="item_names[]" value="${item.productName}" />
                                                <div>
                                                    <div style="font-weight: 600; font-size: 1rem;">
                                                        ${item.productName}
                                                    </div>
                                                    <div class="cart-item-remark text-muted">
                                                        Remark: ${item.remark ? item.remark : "-"}
                                                    </div>
                                                    <input type="hidden" name="item_remarks[]" value="${item.remark ? item.remark : "-"}" />
                                                </div>
                                            </td>
                                            <td>
                                                <input type="hidden" name="item_prices[]" value="${item.price}" />RM ${item.price.toFixed(2)}
                                            </td>
                                            <td>
                                                <div class="quantity-container">
                                                    <button class="quantity-btn update-qty" data-id="${item.cart_id}" data-action="decrease">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                    <input type="text" name="item_quantities[]" class="quantity-input" value="${item.quantity}" min="1" readonly />
                                                    <button class="quantity-btn update-qty" data-id="${item.cart_id}" data-action="increase">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="hidden" name="item_subtotals[]" value="${subtotal}">RM ${subtotal.toFixed(2)}
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="remove-item" data-id="${item.cart_id}">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </td>
                                        </tr>`;
                                });

                                $("#cart-body").html(items);

                                //restore user selected items
                                selectedCartIds.forEach(function(id) {
                                    $('.checkout-selection[value="' + id + '"]').prop('checked', true);
                                });

                                //restore selection-all checkbox
                                $('.checkout-selection-all').prop('checked', $('.checkout-selection:checked').length === $('.checkout-selection').length);

                                updateCheckoutSelection();
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: "ERROR",
                                text: "An error occured! Please try again later.",
                                icon: "error",
                                confirmButtonColor: "Green",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                }
                
                function updateCheckoutSelection() {
                    let totalSelected = $('.checkout-selection:checked').length;
                    var selectedItems = $('.checkout-selection:checked');
                    let grandTotal = 0;
                    const summaryItemsContainer = document.getElementById('cart-summary-items');
                    summaryItemsContainer.innerHTML = ''; //clear previous summary items
                    const emptySummaryMessage = document.getElementById('empty-summary-message');

                    //toggle the empty summary message
                    if (totalSelected > 0) {
                        emptySummaryMessage.style.display = 'none';
                        document.getElementById('cart-summary-items').style.display = 'block';
                    } else {
                        emptySummaryMessage.style.display = 'block';
                        document.getElementById('cart-summary-items').style.display = 'none';
                    }
                    
                    $('.checkout-selection:checked').each(function() {
                        let subtotal = $(this).closest('tr').find('td:nth-child(6)').text().replace('RM ', '').trim();
                        grandTotal += parseFloat(subtotal);
                    });

                    selectedItems.each(function(index) {
                        var $row = $(this).closest('.cart-item-row');

                        var name = $row.find("input[name='item_names[]']").val();
                        var quantity = $row.find("input[name='item_quantities[]']").val();
                        var remark = $row.find("input[name='item_remarks[]']").val();
                        var subtotal = $row.find("input[name='item_subtotals[]']").val();

                        //to check that if this is the last item
                        var totalItems = selectedItems.length;
                        var isLast = index === totalItems - 1;

                        //if quantities updated, the summary quantity not updated
                        $('#cart-summary-items').append(`
                            <div class="${isLast ? '' : 'border-bottom pb-3 mb-3'}">
                                <div class="d-flex justify-content-between">
                                    <span>${name}</span>
                                    <div class="text-end">x${quantity} <br><span class="text-muted">RM ${parseFloat(subtotal).toFixed(2)}</span></div>
                                </div>
                                <div class="" style="font-size: 0.85rem; color: #6c757d; font-style: italic; padding-left: 2px;">
                                    Remark: ${remark}
                                </div>
                            </div>
                        `);
                    });

                    $('#total-selected').text(totalSelected + ' items');
                    $('#grand-total').text(grandTotal.toFixed(2));
                }

                //initial the grand total amount
                updateCheckoutSelection();

                //quantity input field
                $(document).on('click', '.quantity-input', function() {
                    let inputField = $(this);
                    let id = $(this).closest('.quantity-container').find('.update-qty').data("id");

                    Swal.fire({
                        title: "Enter Quantity",
                        text: "Stock available : ",
                        icon: "info",
                        input: "number",
                        inputAttributes: {
                            min: 1,
                            step: 1
                        },
                        inputValue: inputField.val(),
                        showCancelButton: true,
                        confirmButtonText: "Update",
                        cancelButtonText: "Cancel",
                        preConfirm: (value) => {
                            if (!value || value < 1) {
                                Swal.showValidationMessage("Please enter a valid quantity (1 or more)");
                            }
                            return value;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let value = result.value;

                            inputField.val(value);

                            $.ajax({
                                url: "update_cart.php",
                                type: "POST",
                                data: {
                                    "id" : id,
                                    "value" : value
                                },
                                success: function(response) {
                                    if(response == "success") {
                                        storeSelectedCartIds();
                                        loadCart();
                                    }
                                    else{
                                        Swal.fire({
                                            title: "ERROR",
                                            text: "Something wrong! Please try again later.",
                                            icon: "error",
                                            confirmButtonText: "OK",
                                            confirmButtonColor: "Green"
                                        });
                                    }
                                }
                            });
                        }
                    });
                });

                //update quantity (-) and (+)
                $(document).on('click', '.update-qty', function() {
                    let id = $(this).data("id");
                    let action = $(this).data("action");

                    $.ajax({
                        url: "update_cart.php",
                        type: "POST",
                        data: {
                            "id" : id,
                            "action" : action
                        },
                        success: function(response) {
                            if(response == "success") {
                                storeSelectedCartIds();
                                loadCart();
                            }
                            else{
                                Swal.fire({
                                    title: "ERROR",
                                    text: "Something wrong! Please try again later.",
                                    icon: "error",
                                    confirmButtonText: "OK",
                                    confirmButtonColor: "Green"
                                });
                            }
                        }
                    });
                });

                //remove item (trash icon)
                $(document).on('click', '.remove-item', function() {
                    let id = $(this).data("id");

                    Swal.fire({
                        title: "Remove Item?",
                        text: "Are you sure you want to remove this item from the cart?",
                        icon: "warning",
                        confirmButtonText: "Yes",
                        confirmButtonColor: "Green",
                        showCancelButton: true,
                        cancelButtonColor: "Crimson",
                        cancelButtonText: "No"
                    }).then((result) => {
                        if(result.isConfirmed) {
                            $.ajax({
                                url: "remove_cart.php",
                                type: "POST",
                                data: {
                                    "id" : id
                                },
                                success: function(response) {
                                    if(response == "success") {
                                        storeSelectedCartIds();
                                        loadCart();
                                    }
                                    else{
                                        Swal.fire({
                                            title: "ERROR",
                                            text: "Something wrong! Please try again later.",
                                            icon: "error",
                                            confirmButtonText: "OK",
                                            confirmButtonColor: "Green"
                                        });
                                    }
                                }
                            });
                        }
                    });
                });

                //checkout selection (checkbox)
                $(document).on('change', '.checkout-selection', function() {
                    updateCheckoutSelection();

                    if ($('.checkout-selection:checked').length === $('.checkout-selection').length) {
                        $('.checkout-selection-all').prop('checked', true);
                    }
                    else {
                        $('.checkout-selection-all').prop('checked', false);
                    }
                });

                //checkout selection ALL (checkbox)
                $(document).on('change', '.checkout-selection-all', function() {
                    let isChecked = $(this).prop('checked'); //check is checked(true) or unchecked(false)
                    $('.checkout-selection').prop('checked', isChecked);
                    updateCheckoutSelection();
                });
            });

            $("#checkout-form").on('submit', function(e) {
                e.preventDefault();

                var $form = $(this);
                var selectedItems = $('.checkout-selection:checked');

                if(selectedItems.length === 0) {
                    Swal.fire({
                        title: "None Items Selected",
                        text: "Please select at least one item to checkout.",
                        icon: "error",
                        confirmButtonText: "OK",
                        confirmButtonColor: "Green"
                    });
                    
                    return;
                }

                // $form.empty();

                selectedItems.each(function(index) {
                    var $row = $(this).closest('.cart-item-row');

                    var itemId = $(this).val();
                    var name = $row.find("input[name='item_names[]']").val();
                    var image = $row.find("input[name='item_images[]']").val();
                    var price = $row.find("input[name='item_prices[]']").val();
                    var quantity = $row.find("input[name='item_quantities[]']").val();
                    var subtotal = $row.find("input[name='item_subtotals[]']").val();
                    var remark = $row.find("input[name='item_remarks[]']").val();
                    var grandTotal = $('#grand-total').text();

                    $form.append('<input type="hidden" name="selected_items[]" value="' + itemId + '"/>');
                    $form.append('<input type="hidden" name="item_names[]" value="' + name + '"/>');
                    $form.append('<input type="hidden" name="item_images[]" value="' + image + '"/>');
                    $form.append('<input type="hidden" name="item_prices[]" value="' + price + '"/>');
                    $form.append('<input type="hidden" name="item_quantities[]" value="' + quantity + '"/>');
                    $form.append('<input type="hidden" name="item_subtotals[]" value="' + subtotal + '"/>');
                    $form.append('<input type="hidden" name="item_remarks[]" value="' + remark + '"/>');
                    $form.append('<input type="hidden" name="grand_total" value="' + grandTotal + '"/>');
                });

                $form.off('submit').submit();
            });
        </script>
        
        <footer>
            &copy; 2025 Chapalang Graduation Gifts | Made with ❤️
        </footer>
    </body>
</html>
