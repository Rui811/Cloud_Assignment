<?php
include 'header.php';
include 'function/checkout.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Checkout | ChapaLang Graduation Gifts</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            body {
                background-image: url("image/checkout_background.png");
            }

            .back-cart-btn {
                text-decoration: none;
                font-size: 26px;
                color:rgb(115, 121, 227);
                font-weight: bold;
                transition: 0.3s ease-in-out;

                &:hover {
                    transform: translateX(-3.5px);
                    color:rgb(138, 143, 238);
                }
            }

            .paymentMethod {
                font-size: 18px;
            }

            #proceedBtn {
                background-color: #28a745;
                color: rgb(232, 232, 232);
                font-size: 18px;
                border: none;
                padding: 8px;
                width: 100%;
                border-radius: 5px;
                transition: 0.3s ease;

                &:hover {
                    background-color: #218838;
                    color: white;
                }
            }

            .remark-label {
                font-weight: 600;

                & i {
                    margin-right: 5px;
                    color: #6c757d;
                }
            }

            .remark-text {
                font-style: italic;
                color: #6c757d;
                font-size: 0.9rem;
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
        </style>
    </head>
    <body>
        <div class="container mt-4">
            <!-- <h2 class="text-center">Confirm Order</h2> -->
            <div class="d-flex justify-content-between align-items-center">
                <a class="back-cart-btn ms-2" href="cart.php" title="back to cart">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>

                <h2 class="text-center mx-auto m-0">Confirm Order</h2>

                <div style="width: 16px;"></div>
            </div>

            <div class="card shadow mt-3">
                <div class="card-body">
                    <h4 class="">Order Details</h4>
                    <div class="order-container mt-3">
                        <div class="order-items">
                            <table class="table mb-4">
                                <thead class="table-active">
                                    <tr>
                                        <th width="2%">No.</th>
                                        <th width="40%" colspan="2">Item</th>
                                        <th width="12%">Unit Price (RM)</th>
                                        <th width="12%">Quantity</th>
                                        <th width="12%">Subtotal (RM)</th>
                                    </tr>
                                </thead>
                                <tbody id="order-body">
                                    <?php
                                    foreach ($selectedItems as $index => $itemId) {
                                        echo '
                                        <tr>
                                            <td>'. ($index + 1) .'</td>
                                            <td width="10%"><img src="image/'.$images[$index].'.png" width="100"></td>
                                            <td width="30%">'.$names[$index].'<br>
                                                <span class="remark-label text-muted"><i class="fas fa-sticky-note"></i> Remark:</span>
                                                <span class="remark-text">'.$remarks[$index].'</span></td>
                                            <td>'.number_format($prices[$index], 2).'</td>
                                            <td>'.$quantities[$index].'</td>
                                            <td>'.number_format($subtotals[$index], 2).'</td>
                                        </tr>
                                        ';
                                    }
                                    ?>
                                </tbody>
                                <tfoot id="order-footer">
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold py-3 pe-5">Grand Total (RM)</td>
                                        <td><?= number_format($grandTotal, 2) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>  
                </div>
            </div>

            <div class="card shadow mt-4">
                <form action="" id="paymentMethod_form">
                    <div class="card-body">
                        <h4>Payment Method</h4>

                        <div class="payment-container row mt-3 p-3">
                            <div class="col-6 border-end border-2">
                                <input type="radio" name="paymentMethod" id="Card" value="Card" checked /> <label for="card" class="paymentMethod">Credit/Debit Card</label>

                                <div id="card-field" class="mt-3 px-2 row">
                                    <label for="cardHolder">Card holder: </label>
                                    <div class="input-group has-validation mt-1 mb-2">
                                        <span class="input-group-text px-4" style="font-size: 20px;">
                                            <i class="fa-solid fa-user"></i>
                                        </span>
                                        <div id="cardHolderError" class="form-floating">
                                            <input type="text" value="" class="form-control" id="cardHolder" placeholder="Card Holder Name" required />
                                            <label for="cardHolder">Card Holder Name</label>
                                        </div>
                                        <div class="invalid-feedback">Please enter a valid card holder name.</div>
                                    </div>

                                    <label for="cardNum">Card number: </label>
                                    <div class="input-group has-validation mt-1 mb-2">
                                        <span class="input-group-text px-4" style="font-size: 20px;">
                                            <i class="fa-solid fa-credit-card"></i>
                                        </span>
                                        <div id="cardNumError" class="form-floating">
                                            <input type="text" value="" class="form-control" id="cardNum" placeholder="Card Number" required />
                                            <label for="cardNum">Card Number</label>
                                        </div>
                                        <div class="invalid-feedback">Please enter a valid card number with 16 digits.</div>
                                    </div>

                                    <div class="col-6">
                                        <label for="expiryDate">Expiry date: </label>
                                        <div class="input-group has-validation mt-1 mb-2">
                                            <span class="input-group-text px-4" style="font-size: 20px;">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </span>
                                            <div id="expiryDateError" class="form-floating">
                                                <input type="date" value="" class="form-control" id="expiryDate" placeholder="Expiry Date" required />
                                                <label for="expiryDate">Expiry Date</label>
                                            </div>
                                            <div class="invalid-feedback">Please enter a valid expiry date.</div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <label for="cvc">CVC: </label>
                                        <div class="input-group has-validation mt-1 mb-2">
                                            <span class="input-group-text px-4" style="font-size: 20px;">
                                                <i class="fa-solid fa-lock"></i>
                                            </span>
                                            <div id="cvcError" class="form-floating">
                                                <input type="text" value="" class="form-control" id="cvc" placeholder="CVC" required />
                                                <label for="cvc">000</label>
                                            </div>
                                            <div class="invalid-feedback">Please enter a valid CVC (3 digits).</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <input type="radio" name="paymentMethod" id="TouchNGo" value="TouchNGo" /> <label for="TouchNGo" class="paymentMethod">TouchNGo</label>
                                
                                <div id="TouchNGo-field" class="mt-3 px-2">
                                    <label for="phoneNum">Phone Number: </label>
                                    <div class="input-group has-validation mt-1 mb-2">
                                        <span class="input-group-text px-4" style="font-size: 20px;">
                                            <i class="fa-solid fa-phone"></i>
                                        </span>
                                        <div id="phoneNumError" class="form-floating">
                                            <input type="text" value="" class="form-control" id="phoneNum" placeholder="Phone Number" required />
                                            <label for="phoneNum">012-3456789</label>
                                        </div>
                                        <div class="invalid-feedback">Please enter a valid phone number (e.g. 012-1234567 or 011-12345678).</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="button" id="proceedBtn">Proceed</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const customerId = <?= $customerId ?>;

            $(document).ready(function() {
                function updatePaymentMethod() {
                    let paymentMethod = $('input[name="paymentMethod"]:checked').val();

                    if(paymentMethod === 'Card') {
                        $('#card-field input').prop('disabled', false);
                        $('#TouchNGo-field input').prop('disabled', true);

                        //clear TouchNGo fields error and value
                        $('#phoneNum').removeClass('is-invalid').val('');
                        $('#phoneNumError').removeClass('is-invalid');
                    }
                    else {
                        $('#card-field input').prop('disabled', true);
                        $('#TouchNGo-field input').prop('disabled', false);

                        //clear card fields error and values
                        $('#cardHolder, #cardNum, #expiryDate, #cvc').removeClass('is-invalid').val('');
                        $('#cardHolderError, #cardNumError, #expiryDateError, #cvcError').removeClass('is-invalid');
                    }
                }

                function validateForm() {
                    let isValid = true;
                    $('.form-control').removeClass('is-invalid');
                    $('.form-floating').removeClass('is-invalid');

                    let paymentMethod = $('input[name="paymentMethod"]:checked').val();

                    if(paymentMethod === 'Card') {
                        let cardHolder = $('#cardHolder').val().trim();
                        let cardNum = $('#cardNum').val().trim();
                        let expiryDate = $('#expiryDate').val().trim();
                        let cvc = $('#cvc').val().trim();

                        if (!cardHolder) {
                            $('#cardHolder').addClass('is-invalid');
                            $('#cardHolderError').addClass('is-invalid');
                            isValid = false;
                        }

                        if (!cardNum.match(/^\d{16}$/)) {
                            $('#cardNum').addClass('is-invalid');
                            $('#cardNumError').addClass('is-invalid');
                            isValid = false;
                        }

                        if (!expiryDate || new Date(expiryDate) <= new Date()) {
                            $('#expiryDate').addClass('is-invalid');
                            $('#expiryDateError').addClass('is-invalid');
                            isValid = false;
                        }

                        if (!cvc.match(/^\d{3}$/)) {
                            $('#cvc').addClass('is-invalid');
                            $('#cvcError').addClass('is-invalid');
                            isValid = false;
                        }
                    }
                    else if(paymentMethod === 'TouchNGo') {
                        let phoneNum = $('#phoneNum').val().trim();

                        if (!phoneNum.match(/^01[0-9]-\d{7,8}$/)) {
                            $('#phoneNum').addClass('is-invalid');
                            $('#phoneNumError').addClass('is-invalid');
                            isValid = false;
                        }
                    }

                    return isValid;
                }

                //initiate the payment method
                updatePaymentMethod();

                $('input[name="paymentMethod"]').on('change', function() {
                    updatePaymentMethod();
                });

                $('#proceedBtn').on('click', function() {
                    if (!validateForm()) {
                        return;
                    }

                    let cartIds = <?= json_encode($selectedItems) ?>;
                    let paymentMethod = $('input[name="paymentMethod"]:checked').val();
                    let totalAmount = <?= json_encode($grandTotal) ?>;

                    Swal.fire({
                        title: "Order Confirmation",
                        text: "Are you sure to make payment and proceed the order?",
                        icon: "info",
                        confirmButtonColor: "Green",
                        confirmButtonText: "Confirm",
                        showCancelButton: true,
                        cancelButtonColor: "Crimson",
                        cancelButtonText: "Cancel"
                    }).then((result) => {
                        if(result.isConfirmed) {
                            $.ajax({
                                url: "ajax/create_order.php",
                                type: "POST",
                                data: {
                                    "customer_id" : customerId,
                                    "cart_ids" : cartIds,
                                    "payment_method" : paymentMethod,
                                    "total_amount" : totalAmount 
                                },
                                success: function(response) {
                                    let data = JSON.parse(response);

                                    if (data.success) {
                                        Swal.fire({
                                            title: "Order created!",
                                            text: 'Yey! Your order has successfully created!',
                                            icon: "success",
                                            confirmButtonText: "OK",
                                            confirmButtonColor: "Green"
                                        }).then(() => {
                                            window.location.href = "receipt.php?from=checkout&order_id=" + data.orderId;
                                        });                                        
                                    }
                                    else {
                                        Swal.fire({
                                            title: "Failed to create Order!",
                                            text: 'An error occurred! Please try again later.',
                                            icon: "error",
                                            confirmButtonText: "OK",
                                            confirmButtonColor: "Crimson"
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    }
                                    
                                },
                                error: function(){
                                    Swal.fire({
                                        title: "ERROR!",
                                        text: 'An error occurred! Please try again later.',
                                        icon: "error",
                                        confirmButtonText: "OK",
                                        confirmButtonColor: "Crimson"
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            });
                        }
                    });
                });
            });
            
        </script>

        <footer>
            &copy; 2025 Chapalang Graduation Gifts | Made with ❤️
        </footer>
    </body>
</html>
