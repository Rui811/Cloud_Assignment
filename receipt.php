<?php
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$customer_id = $_SESSION['user_id'];

include 'receiptFunction.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Receipt | ChapaLang Graduation Gifts</title>
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

            .receipt-card {
                background-color: #ffffff;
                padding: 10px 30px 10px 30px;
                border-radius: 12px;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
            }

            #receipt-watermark {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: url("image/chapalang_logo.png");
                background-size: 50%;
                background-position: center;
                background-repeat: no-repeat;
                opacity: 0.1;
                pointer-events: none;
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

            .card {
                box-shadow: 0 0 15px rgba(0,0,0,0.05);
            }

            .section-title {
                font-weight: 600;
                font-size: 22px;
                /* margin-top: 25px; */
                margin-bottom: 10px;
                color: #495057;
            }

            .table th {
            background-color: #f1f3f5;
            color: #495057;
        }

            .total-line {
                font-size: 18px;
                font-weight: 600;
                color: #212529;
            }

            /* .text-muted a {
                color: #6c757d;
            } */

            .bg-printable {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @media print {
                .page-break {
                    page-break-before: always;
                    break-before: page;
                }

                .no-page-break {
                    page-break-inside: avoid;
                    break-inside: avoid;
                }

                .d-flex {
                    flex-direction: row !important;
                }

                .flex-fill {
                    width: 48% !important;
                }

                body * {
                    visibility: hidden;
                }

                #printableArea, #printableArea * {
                    visibility: visible;
                }

                #printableArea {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    margin: auto;
                    padding: 0;
                }

                .btn, .back-cart-btn, .text-muted, .fa-chevron-left {
                    display: none !important;
                }

                .receipt-card {
                    box-shadow: none !important;
                    border: none !important;
                }

                .hide-on-print {
                    display: none !important;
                }
            }

        </style>
    </head>
    <body>
        <div class="container mt-5 mb-5">
            <div id="printableArea" class="card receipt-card">
                <div id="receipt-watermark"></div> <!-- Watermark -->

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 receipt-header">
                        <a class="back-cart-btn ms-2" href="<?= $from == "checkout" ? "homepage" : "profile" ?>.php" title="back to <?= $from == "checkout" ? "Home" : "Profile" ?>">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>

                        <h3 class="text-center mx-auto m-0">Receipt</h3>

                        <div style="width: 22px;"></div>
                    </div>

                    <!-- <h3 class="text-center mb-4">Receipt</h3> -->
                    <h2 class="mb-3">Order #<?= $orderId; ?></h2>
                    <p><strong>Order Date:</strong> <?= $orderDate; ?></p>
                    <p><strong>Order Status:</strong> <?= $orderStatus; ?></p>

                    <hr>

                    <p class="section-title">Issued To:</p>
                    <p><strong>Name:</strong> <?= $customerName; ?></p>
                    <p><strong>Email:</strong> <?= $customerEmail; ?></p>
                    <p><strong>Phone:</strong> <?= $customerPhone; ?></p>
                    <p><strong>Address:</strong> <?= $customerAddress ? $customerAddress : 'N/A'; ?></p>

                    <hr>

                    <p class="section-title">Order Details:</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($item = $itemsResult->fetch_assoc()) {
                                echo '<tr>
                                        <td>'.$item['productName'].' <br> <small>Remark: '.($item['remark'] ? $item['remark'] : 'No remark').'</small></td>
                                        <td>RM '.number_format($item['unit_price'], 2).'</td>
                                        <td>'.$item['quantity'].'</td>
                                        <td>RM '.number_format($item['subtotal'], 2).'</td>
                                      </tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                    <div class="d-flex flex-wrap justify-content-between gap-4 no-page-break">
                        <div class="flex-fill" style="min-width: 250px;">
                            <p class="section-title">Total Summary:</p>
                            <p class="total-line">Grand Total: RM <?= number_format($grandTotal, decimals: 2); ?></p>
                        </div>
                        <div class="flex-fill" style="min-width: 250px;">
                            <p class="section-title">Payment Info:</p>
                            <p><strong>Method:</strong> <?= $paymentMethod; ?></p>
                            <p><strong>Status:</strong> <?= $paymentStatus; ?></p>
                            <p><strong>Date:</strong> <?= $paymentDate; ?></p>
                        </div>
                    </div>

                    <div class="bg-printable no-page-break p-3 rounded mt-3">
                        <p class="section-title">Shipping Info:</p>
                        <p><strong>Shipping Address:</strong> None</p>
                        <p><strong>Shipping Method:</strong> PICK UP</p>
                        <p><strong>Estimated Delivery:</strong> None</p>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button class="btn btn-primary" onclick="window.print();">
                            <i class="fa fa-print"></i> Print Receipt
                        </button>
                    </div>
                    
                    <hr class="hide-on-print">

                    <p class="text-muted">For any questions, please contact <a href="mailto:support@chapalang.com">support@chapalang.com</a>.</p>
                </div>
            </div>
        </div>

    </body>
</html>

<?php include 'footer.php'; ?>