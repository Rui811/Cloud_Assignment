<?php
include 'profile_helper.php';
include 'order_history.php';

$customerId = $_SESSION['user_id'];
$orders = getOrderHistory($customerId, $conn);

$grouped_orders = [];

foreach ($orders as $order) {
    if (!isset($grouped_orders[$order['order_id']])) {
        $grouped_orders[$order['order_id']] = [
            'order_id' => $order['order_id'],
            'order_date' => $order['order_date'],
            'total_amount' => $order['total_amount'],
            'order_state' => $order['order_state'],
            'products' => []
        ];
    }
    $grouped_orders[$order['order_id']]['products'][] = [
        'product_id' => $order['product_id'],
        'image' => $order['image'],
        'productName' => $order['productName'],
        'quantity' => $order['quantity'],
        'unit_price' => $order['unit_price']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ChapaLang Graduation Gifts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url("image/profile_background.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 102vh;
            margin: 0;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 250, 250, 0.81);
            z-index: -1;
        }

        .navbar {
            min-height: 80px;
            max-height: 98px;
            padding: 15px 0;
            background: #ffffff;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .wrapper {
            width: 90%;
            max-width: 1100px;
            margin: 5% auto;
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .profile-section {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .profile-image {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #333;
            margin-top: 25px;
        }

        .box {
            flex: 1;
            background-color: rgba(249, 206, 206, 0.8);
            border-radius: 15px;
            padding: 20px;
            position: relative;
            min-height: 180px;
        }

        .settings {
            background-color: rgba(229, 209, 243, 0.66);
            flex: 0 0 30%;
            max-width: 250px;
        }

        .orders {
            background-color: rgba(250, 207, 239, 0.69);
            overflow-y: auto;
            max-height: 400px;
            padding: 15px;
            border-radius: 10px;
        }

        .order-history-container {
            max-height: 200px;
            overflow-y: scroll;
            padding-right: 10px;
            margin-top: 20px;
        }

        .order-item {
            padding: 10px;
            background-color: #fff;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .order-item p {
            margin: 5px 0;
        }

        .order-item ul {
            list-style-type: none;
            padding-left: 0;
        }

        .order-item ul li {
            margin: 5px 0;
        }

        .label-tag {
            position: absolute;
            top: -15px;
            left: 20px;
            background-color: #fff;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 14px;
            border: 3px solid #ddd;
        }

        .label-tag-order {
            position: absolute;
            top: 5px;
            left: 590px;
            background-color: #fff;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 14px;
            border: 3px solid #ddd;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
            margin-top: 10px;
        }

        ul li {
            margin-bottom: 10px;
        }

        ul li button {
            width: 100%;
            padding: 10px;
            background-color: rgb(255, 255, 255);
            color: grey;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            text-align: center;
            transition: background-color 0.3s;
        }

        ul li button:hover {
            background-color: rgba(238, 182, 231, 0.85);
        }

        .logout-icon {
            position: absolute;
            bottom: 80px;
            right: 150px;
            font-size: 26px;
            background-color: rgb(255, 209, 216);
            color: #fff;
            border-radius: 100%;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #666;
        }

        @media (max-width: 768px) {
            .profile-section {
                flex-direction: column;
                align-items: center;
            }

            .box {
                width: 100%;
            }

            .profile-image {
                margin-bottom: 20px;
            }
        }

        footer {
            position: absolute;
            bottom: 10px;
            font-size: 14px;
            color: #666;
        }

        .btn-pink {
            background-color: #f78fb3;
            color: white;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-pink:hover {
            background-color: rgb(255, 219, 228);
        }

        .form-label {
            font-weight: 500;
            color: #6b4c7c;
        }

        .modal {
            display: none;
        }

        .modal.show {
            display: block;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            border: none;
            border-radius: 20px;
        }

        .modal-title {
            font-size: 22px;
        }

        textarea.form-control:focus,
        input.form-control:focus {
            border-color: #f78fb3;
            box-shadow: 0 0 0 0.2rem rgba(247, 143, 179, 0.25);
        }

        .order-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <div class="profile-section">
            <img src="image/cust_pic1.jpg" alt="Profile Image" class="profile-image">

            <div class="box profile-details">
                <div class="label-tag">PROFILE DETAILS</div>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['cust_username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['cust_email']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['cust_name']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['cust_phone']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['cust_address']); ?></p>
            </div>
        </div>

        <div class="profile-section" style="margin-top: 30px;">
            <div class="box settings">
                <div class="label-tag">SETTINGS</div>
                <ul>

                    <button type="button" class="btn w-100 py-2"
                        style="background-color: rgb(255, 255, 255); color: grey; border-radius: 5px; font-weight: 600; margin-bottom: 10px;"
                        data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        Edit Profile
                    </button>
                    <li><button type="button" onclick="showChangePasswordPopup()">Change Password</button></li>
                    <li><button type="button" onclick="deleteAccount()">Delete Account</button></li>
                </ul>
            </div>

            <div class="box orders">
                <div class="label-tag-order">ORDER HISTORY</div>
                <div class="order-history-container">
                    <?php
                    foreach ($grouped_orders as $order):
                        ?>
                        <div class="order-item">
                            <p><strong>Order ID: #</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
                            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                            <p><strong>Total Amount:</strong> RM <?php echo number_format($order['total_amount'], 2); ?></p>
                            <p><strong>Order Status:</strong> <?php echo htmlspecialchars($order['order_state']); ?></p>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th> </th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($order['products'] as $product):
                                        ?>
                                        <tr>
                                            <td><img src="image/<?php echo htmlspecialchars($product['image']); ?>.png"
                                                    alt="<?php echo htmlspecialchars($product['productName']); ?>"
                                                    class="order-item-img">
                                            </td>

                                            <td><?php echo htmlspecialchars($product['productName']); ?></td>

                                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>

                                            <td>RM <?php echo number_format($product['unit_price'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <a href="receipt.php?order_id=<?php echo $order['order_id']; ?>"
                                class="btn btn-pink w-100 mt-3">
                                View Receipt
                            </a>

                        </div>
                    <?php endforeach; ?>
                </div>

            </div>


        </div>

    </div>



    <div class="logout-icon" onclick="window.location.href='logout.php';">
        <i class="fas fa-sign-out-alt"></i>
    </div>

    <footer>
        &copy; 2025 Chapalang Graduation Gifts | Made with ❤️
    </footer>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg" style="background-color:rgb(255, 255, 255);">
                <form method="POST" action="profile.php">
                    <div class="modal-header border-0">
                        <h5 class="modal-title w-100 text-center fw-bold" id="editProfileModalLabel"
                            style="color: #c06c84;">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-3">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control rounded-pill bg-light text-muted"
                                value="<?php echo htmlspecialchars($user['cust_username']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control rounded-pill bg-light text-muted"
                                value="<?php echo htmlspecialchars($user['cust_email']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="cust_name" class="form-control rounded-pill"
                                value="<?php echo htmlspecialchars($user['cust_name']); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill bg-light border-end-0">+60</span>
                                <input type="text" name="cust_phone" class="form-control rounded-end-pill"
                                    pattern="\d{7,10}" maxlength="10" required value="<?php
                                    echo htmlspecialchars(
                                        (substr($user['cust_phone'], 0, 2) === '60') ? substr($user['cust_phone'], 2) : $user['cust_phone']
                                    );
                                    ?>" title="Enter 7 to 10 digits after +60">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="cust_address" class="form-control rounded-4"
                                rows="3"><?php echo trim(htmlspecialchars($user['cust_address'])); ?></textarea>
                        </div>

                        <div class="modal-footer border-0 d-flex justify-content-center gap-2 pb-4">
                            <button type="submit" name="update_profile" class="btn btn-pink rounded-pill px-4">Save
                                Changes</button>
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->


    <script>
        document.querySelector('input[name="cust_phone"]').addEventListener('input', function (e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });

    </script>

    <!-- sweetalert for successful update profile-->
    <?php if (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated!',
                    text: 'Your changes have been saved.',
                    confirmButtonColor: '#f78fb3',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });

                if (history.replaceState) {
                    const cleanURL = window.location.origin + window.location.pathname;
                    history.replaceState(null, null, cleanURL);
                }
            });
        </script>
    <?php endif; ?>

    <script>
        function showChangePasswordPopup() {
            Swal.fire({
                title: 'Change Password',
                html: `
            <input type="password" id="current" class="swal2-input" placeholder="Current Password">
            <input type="password" id="newpass" class="swal2-input" placeholder="New Password">
            <input type="password" id="confirm" class="swal2-input" placeholder="Confirm New Password">`,
                showCancelButton: true,
                confirmButtonText: 'Update',
                focusConfirm: false,
                preConfirm: () => {
                    const current = Swal.getPopup().querySelector('#current').value;
                    const newpass = Swal.getPopup().querySelector('#newpass').value;
                    const confirm = Swal.getPopup().querySelector('#confirm').value;

                    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                    if (!current || !newpass || !confirm) {
                        Swal.showValidationMessage('Please fill all fields');
                    } else if (!passwordRegex.test(newpass)) {
                        Swal.showValidationMessage('Password must be at least 8 characters, include an uppercase letter, a lowercase letter, a number, and a special character');
                    } else if (newpass !== confirm) {
                        Swal.showValidationMessage('New password and confirmation do not match');
                    }

                    return { current, newpass };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('cust_changePsw.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `current_password=${encodeURIComponent(result.value.current)}&new_password=${encodeURIComponent(result.value.newpass)}`
                    })
                        .then(res => res.text())
                        .then(data => {
                            if (data.trim() === 'success') {
                                Swal.fire('Updated!', 'Password changed successfully!', 'success');
                            } else {
                                Swal.fire('Error', data, 'error');
                            }
                        });
                }
            });
        }

        function deleteAccount() {
            Swal.fire({
                title: 'Are you sure you want to deactivate your account?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, deactivate it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deactivateForm').submit();
                }
            });
        }

    </script>

    <form id="deactivateForm" action="delete_account.php" method="POST" style="display: none;">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>