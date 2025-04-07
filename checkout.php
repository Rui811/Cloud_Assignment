<?php
include 'header.php';
include 'function/checkout.php';
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
                /* background-image: url("image/cartBackground.png"); */
            }

            .back-cart-btn {
                text-decoration: none;
                font-size: 26px;
                font-weight: bold;
                transition: 0.3s ease-in-out;

                &:hover {
                    transform: translateX(-3.5px);
                }
            }

            .proceedBtn {
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
                <!-- <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <a class="back-cart-btn" href="cart.php">
                            <i class="fa-solid fa-chevron-left"></i> Back to Cart
                        </a>

                        <h2 class="text-center mx-auto m-0">Confirm Order</h2>

                        <div style="width: 130px;"></div>
                    </div>
                </div> -->

                <div class="card-body">
                    <h4 class="">Order Details</h4>
                    <div class="order-container">
                        <div class="order-items">
                            <table class="table mb-4">
                                <thead style="background-color: #2D328F; color: white;">
                                    <tr>
                                        <th width="2%">No.</th>
                                        <th width="40%" colspan="2">Item</th>
                                        <th width="9%">Price</th>
                                        <th width="9%">Quantity</th>
                                        <th width="9%">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="order-body">
                                    <?php
                                    foreach ($selectedItems as $index => $itemId) {
                                        echo '
                                        <tr>
                                            <td>'. ($index + 1) .'</td>
                                            <td width="15%"><img src="image/flower1.png" width="100"></td>
                                            <td width="25%">'.$names[$index].'</td>
                                            <td>RM '.$prices[$index].'</td>
                                            <td>'.$quantities[$index].'</td>
                                            <td>RM '.$subtotals[$index].'</td>
                                        </tr>
                                        ';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>  
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-body">
                    <h4>Payment Method</h4>

                    <div class="payment-container row mt-3">
                        <div class="col-6 border-end border-2">
                            <input type="radio" name="paymentMethod" value="card" checked /> Credit/Debit Card

                            <div class="card-field mt-3 px-2">
                                <label for="cardHolder">Card holder: </label>
                                <div class="input-group has-validation mt-1 mb-2">
                                    <span class="input-group-text px-4" style="font-size: 20px;">
                                        <i class="fa-solid fa-user"></i>
                                    </span>
                                    <div class="form-floating <?= isset($error['cardHolder']) ? 'is-invalid' : '' ?>">
                                        <input type="text" class="form-control <?= isset($error['cardHolder']) ? 'is-invalid' : '' ?>" id="cardHolder" placeholder="Card Holder Name" required>
                                        <label for="cardHolder">Card Holder Name</label>
                                    </div>
                                    <div class="invalid-feedback">...</div>
                                </div>

                                <label for="cardNum">Card number: </label>
                                <div class="input-group has-validation mt-1 mb-2">
                                    <span class="input-group-text px-4" style="font-size: 20px;">
                                        <i class="fa-solid fa-credit-card"></i>
                                    </span>
                                    <div class="form-floating <?= isset($error['cardNum']) ? 'is-invalid' : '' ?>">
                                        <input type="text" class="form-control <?= isset($error['cardNum']) ? 'is-invalid' : '' ?>" id="cardNum" placeholder="Card Number" required>
                                        <label for="cardNum">Card Number</label>
                                    </div>
                                    <div class="invalid-feedback">...</div>
                                </div>

                                <label for="expiryDate">Expiry date: </label>

                                <label for="cvc">CVC: </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <input type="radio" name="paymentMethod" value="TouchNGo" /> TouchNGo
                            
                            <div class="TouchNGo-field mt-2">
                                <label for="phoneNum">Phone Number: </label>
                                <input type="tel" name="" id="" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="" class="proceedBtn">Proceed</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            
        </script>

        <footer>
            &copy; 2025 Chapalang Graduation Gifts | Made with ❤️
        </footer>
    </body>
</html>
