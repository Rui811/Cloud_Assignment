<?php
session_start();
$_SESSION['user_id'] = "hdnj";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
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
                background-image: url("image/cartBackground.png"); /* later change, so ugly, still confuse need header and footer or not */
                /* background-size: cover; */
            }

            .back-home-btn {
                text-decoration: none;
                width: fit-content;
                font-size: 16px;
                font-weight: bold;
                padding-left: 10px;
                color: #2D328F;
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
                background-color: #28a745;
                color: rgb(232, 232, 232);
                font-size: 18px;
                padding: 12px 20px;
                width: 100%;
                border: none;
                border-radius: 5px;
                transition: 0.3s ease;

                &:hover {
                    background-color: #218838;
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

            .empty-cart {
                text-align: center;
                font-size: 20px;
                color: gray;
                font-style: italic;
                padding: 20px;
            }

            .checkout-selection, .checkout-selection-all {
                transform: scale(1.5);
                transition: 0.3s ease;
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

        </style>
    </head>
    <body>
        <div class="container mt-5">
            <a class="back-home-btn" href="homepage.php">
                <i class="fa-solid fa-chevron-left"></i> Back To Home
            </a>

            <div class="card shadow mt-3">
                <div class="card-body">
                    <div class="cart-container">
                        <!-- Cart Items (Fetched using AJAX) -->
                        <div class="cart-items">
                            <table class="table mb-4">
                                <thead style="background-color: #2D328F; color: white;">
                                    <tr>
                                        <th width="2%">
                                            <input type="checkbox" name="" class="checkout-selection-all" id="" />
                                        </th>
                                        <th width="40%" colspan="2">Item</th>
                                        <th width="9%">Price</th>
                                        <th width="9%">Quantity</th>
                                        <th width="9%">Subtotal</th>
                                        <th width="9%" style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-body">
                                    <!-- Items will be loaded here via AJAX -->
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="" class="checkout-selection" id="" />
                                        </td>
                                        <td width="15%"><img src="image/flower1.png" width="100"></td>
                                        <td width="25%">name</td>
                                        <td>RM 50.00</td>
                                        <td>
                                            <div class="quantity-container">
                                                <button class="quantity-btn update-qty" data-id="" data-action="decrease">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                                <input type="text" class="quantity-input" value="1" min="1" readonly>
                                                <button class="quantity-btn update-qty" data-id="" data-action="increase">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>RM 50.00</td>
                                        <td style="text-align: center;">
                                            <button class="remove-item" data-id="">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="" class="checkout-selection" id="" />
                                        </td>
                                        <td width="15%"><img src="image/flower1.png" width="100"></td>
                                        <td width="25%">name</td>
                                        <td>RM 50.00</td>
                                        <td>
                                            <div class="quantity-container">
                                                <button class="quantity-btn update-qty" data-id="" data-action="decrease">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                                <input type="text" class="quantity-input" value="1" min="1" readonly>
                                                <button class="quantity-btn update-qty" data-id="" data-action="increase">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>RM 50.00</td>
                                        <td style="text-align: center;">
                                            <button class="remove-item" data-id="">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div id="empty-cart-message" class="empty-cart" style="display: none;">
                                Nothing here.
                            </div>
                        </div>

                        <!-- Summary (Sticky) -->
                        <div class="col-md-4">
                            <div class="card p-3 summary-card">
                                <h4 class="text-center">SUMMARY</h4>
                                <hr>
                                <div id="cart-summary">
                                    <!-- <h5>Total Selected Items: <span id="total-selected">0</span></h5> -->
                                    <h5>Grand Total: RM <span id="grand-total">0.00</span></h5>
                                </div>
                                <hr>
                                <div class="checkout-container">
                                    <form method="post">
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
            $(document).ready(function() {
                function updateCheckoutSelection() {
                    let totalSelected = $('.checkout-selection:checked').length;
                    let grandTotal = 0;
                    
                    $('.checkout-selection:checked').each(function() {
                        let subtotal = $(this).closest('tr').find('td:nth-child(6)').text().replace('RM ', '').trim();
                        grandTotal += parseFloat(subtotal);
                    });

                    $('#total-selected').text(totalSelected + ' items');
                    $('#grand-total').text(grandTotal.toFixed(2));
                }

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
                        }
                    });
                });

                //update quantity (-) and (+)
                $(document).on('click', '.update-qty', function() {
                    let id = $(this).data("id");
                    let action = $(this).data("action");

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
                            
                        }
                    });
                });

                //checkout selection (checkbox)
                $(document).on('change', '.checkout-selection', function() {
                    updateCheckoutSelection();
                });

                //checkout selection ALL (checkbox)
                $(document).on('change', '.checkout-selection-all', function() {
                    let isChecked = $(this).prop('checked'); //check is checked(true) or unchecked(false)
                    $('.checkout-selection').prop('checked', isChecked);
                    updateCheckoutSelection();
                });
            });
        </script>
    </body>
</html>
