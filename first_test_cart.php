<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Cart | ChapaLang</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            .back-home-btn {
                background-color: transparent;
                border: none;
                font-size: 16px;
                font-weight: bold;
                color: #2D328F;
                display: flex;
                align-items: center;
                gap: 5px;
                cursor: pointer;

                &:hover {
                    transition: 0.5s ease-in-out;
                    transform: translateX(-2px);
                }
            }

            .back-home-btn i {
                font-size: 18px;
            }

            .summary-card {
                width: 100%;
                position: sticky;
                top: 10px;
            }

            .checkout-container {
                position: sticky;
                bottom: 0;
                background-color: white;
                padding: 10px;
                border-top: 1px solid #ddd;
            }

            .checkoutBtn {
                background-color: #28a745;
                color:rgb(232, 232, 232);
                font-size: 18px;
                bottom: 0;
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
        </style>
    </head>
    <body>
        <div class="wrapper">

            <div id="content-wrapper" class="flex flex-column">
                <div class="content">

                    <div class="container mt-5">
                        
                        <div class="card shadow mb-4">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <button style="" class="back-home-btn align-middle mb-4">
                                            <i class="fa-solid  fa-chevron-left"></i> Back To Home
                                        </button>

                                        <table class="table mb-4" width="100%" cellspacing="0">
                                            <thead style="background-color: #2D328F; color: white;">
                                                <tr>
                                                    <th width="40%" colspan="2">Item</th>
                                                    <th width="7%">Price</th>
                                                    <th width="7%">Quantity</th>
                                                    <th width="7%">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                for($i = 0; $i < 2; $i++) {
                                                    echo '
                                                    <tr>
                                                        <td width="10%">
                                                            <img src="image/loginSitePic.png" style="width: 150px; height: 150px;" />
                                                        </td>
                                                        <td>Item Name</td>
                                                        <td>price</td>
                                                        <td>
                                                            <button type="button" name="">-</button>
                                                            <input type="number" name="quantity" value="" min="1" width="5px" />
                                                            <button type="button" name="">+</button>
                                                        </td>
                                                        <td>subtotal</td>
                                                    </tr>
                                                    ';
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="col-4">
                                        <div class="card p-3">
                                            <h4 class="text-center">SUMMARY</h4>
                                            <hr>
                                            <p>Item 1 x 1 RM...</p>
                                            <p>Item 2 x 2 RM...</p>
                                            <hr>
                                            <h5>Grand Total: RM...</h5>
                                            <form method="post">
                                                <button type="submit" name="checkout" class="btn checkoutBtn">
                                                    <i class="fa-solid fa-check"></i> Checkout
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!-- <h2 class="text-center">SUMMARY</h2>
                                        <hr>
                                        <p>Item 1 x 1 RM...</p>
                                        <p>Item 2 x 2 RM...</p>
                                        <hr>
                                        <h5>Grand Total: RM...</h5>
                                        <form method="post" action="">
                                            <button type="submit" name="checkout" class="btn submitBtn">
                                                <i class="fa-solid fa-check"></i> Checkout
                                            </button>
                                        </form> -->
                                    </div>
                                </div>
                                
                            </div>

                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </body>
    <footer>

    </footer>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>