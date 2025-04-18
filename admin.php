<?php
session_start();
$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categoryMap = [];
$categoryQuery = $conn->query("SELECT catID, catName FROM category");
while ($cat = $categoryQuery->fetch_assoc()) {
    $categoryMap[$cat['catID']] = $cat['catName'];
}

$admin = []; // Initialize $admin
$adminName = $_SESSION['admin'] ?? null;
$sql = "SELECT * FROM admin WHERE admin_username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $adminName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<!DOCTYPE html><html><head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          </head><body>
        <script>
            setTimeout(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: 'You do not have admin privileges.',
                    confirmButtonText: 'Go to Login'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'admin_login.php';
                    }
                });
            }, 100);
        </script>
        </body></html>";
    exit;
} else {
    $admin = $result->fetch_assoc(); // Fetch admin data
}
$stmt->close();


$productID = $_GET['id'] ?? 0;
$currentStatus = $_GET['status'] ?? null;

if ($productID && $currentStatus !== null) {
    $newStatus = ($currentStatus == 1) ? 0 : 1;

    $sql = "UPDATE product SET status = ? WHERE productID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $newStatus, $productID);
    $stmt->execute();

    $stmt->close();
}

// Get filters
$selectedCategory = $_GET['category'] ?? "All";
$searchQuery = $_GET['search'] ?? "";

// Get categories
$category_sql = "SELECT * FROM category";
$category_result = $conn->query($category_sql);
$categories = ["All"];
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row['catName'];
}

// Get products
$product_sql = "SELECT * FROM product 
                WHERE (category = ? OR ? = 'All') 
                AND (productName LIKE ?)";


$stmt = $conn->prepare($product_sql);
$likeSearch = '%' . $searchQuery . '%';
$stmt->bind_param("sss", $selectedCategory, $selectedCategory, $likeSearch);
$stmt->execute();
$result = $stmt->get_result();

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f9fbfd;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            padding-top: 20px;
            overflow-y: auto;
            min-height: 100vh;
            background-color: #f4f7fe;
        }

        .sidebar a {
            color: #333;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background-color: #673de6;
            color: #fff;
        }

        a.logOutAlert:hover,
        a.profile:hover {
            background-color: #dc3545;
            color: #fff;
        }

        .topbar {
            padding: 15px 20px;
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
        }

        .page-content {
            display: none;
            padding: 20px;
            margin-left: 240px;
        }

        .page-content.active {
            display: block;
        }

        .buttonAddProduct {
            background-color: #673de6;
        }

        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            background-color: white;
            border: none;
            padding: 10px 0;
            min-width: 240px;
        }

        .dropdown-menu .dropdown-item {
            color: black;
            padding: 10px 20px;
            transition: background-color 0.3s ease, padding-left 0.2s ease;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #e3323a;
            padding-left: 26px;
            color: #ffffff;
            border-radius: 8px;
        }

        .dropdown-toggle::after {
            display: none;
            /* hide arrow*/
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            text-decoration: none;
        }

        .profile:hover {
            color: #ccc;
        }

        input.is-invalid {
            border: 2px solid red;
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .form-control.is-invalid~.invalid-feedback {
            display: block;
        }

        .form-text.text-danger {
            display: none; /* Initially hide the custom error messages */
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Side Navigation Bar -->
            <div class="col-md-2 sidebar p-3 d-flex flex-column">
                <h4 class="fw-bold mb-4">CHAPALANG ADMIN</h4>
                <ul class="navbar-nav">
                    <li><a href="#" class="nav-link active" data-target="homePage">
                            <i class="bi bi-house-door-fill me-2"></i> Dashboard</a>
                    </li>
                    <li><a href="#" class="nav-link" data-target="orderPage">
                            <i class="bi bi-table me-2"></i> Order</a>
                    </li>
                    <li><a href="#" class="nav-link" data-target="productPage">
                            <i class="bi bi-grid me-2"></i>Product</a>
                    </li>
                    <li><a href="#" class="nav-link" data-target="userPage">
                            <i class="bi bi-person-circle me-2"></i>Customers</a>
                    </li>
                </ul>

                <div class="dropdown mt-auto">
                    <a class="profile dropdown-toggle" href="#" role="button" id="accountSettingDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear-fill me-2"></i> Account Setting
                    </a>

                    <ul class="dropdown-menu dropdown-menu" aria-labelledby="accountSettingDropdown">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changeProfileModal">
                                <i class="bi bi-person-lines-fill me-2"></i> Change Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="bi bi-lock-fill me-2"></i> Change Password
                            </a>
                        </li>
                    </ul>
                    
                    <a href="#" class="logOutAlert mt-auto" id="logoutBtn"><i class="bi bi-box-arrow-right me-2"></i> Log out</a>

                </div>

            </div>


            <!-- Change Profile Modal -->
            <div class="modal fade" id="changeProfileModal" tabindex="-1" aria-labelledby="changeProfileModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Change Profile</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="profileForm">
                                    <div class="mb-3">
                                        <label for="adminName" class="form-label">Name</label>
                                        <!-- <small class="text-muted">This field cannot be changed.</small> -->
                                        <input type="text" class="form-control" id="adminName"
                                            value="<?= htmlspecialchars($admin['admin_name'] ?? '') ?>"" readonly>
                                        
                                </div>
                                <div class=" mb-3">
                                        <label for="adminUsername" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="adminUsername"
                                            value="<?= htmlspecialchars($admin['admin_username'] ?? '') ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="adminEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="adminEmail"
                                            value="<?= htmlspecialchars($admin['admin_email'] ?? '') ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="adminPhone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="admin_phone" id="adminPhone"
                                            value="<?= htmlspecialchars($admin['admin_phone'] ?? '') ?>">
                                    </div>
                                    <input type="hidden" id="originalAdminPhone"
                                        value="<?= htmlspecialchars($admin['admin_phone'] ?? '') ?>">

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" form="profileForm" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            
            <!-- Change Password Modal -->
            <div class="modal fade" id="changePasswordModal" tabindex="-1"aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-shield-lock-fill me-2"></i> Change Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        
                        <div class="modal-body">
                            <form id="passwordForm" method="POST" action="update_password.php">
                                <div class="mb-3">
                                    <label for="oldPassword" class="form-label"><i class="bi bi-key-fill me-1"></i>Old Password</label>
                                    <input type="password" class="form-control" id="oldPassword" name="old_password" placeholder="Enter your Old password" required> 
                                    
                                    <div id="oldPasswordMatchError" class="form-text text-danger" style="display: none;">Incorrect old password.</div>
                                </div>
                                             
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label"><i class="bi bi-key-fill me-1"></i>New Password</label>
                                    <input type="password" class="form-control" id="newPassword" name="new_password" placeholder="Enter your new password" required readonly>
                                    
                                    <div id="samePasswordError" class="form-text text-danger" style="display: none;">New password cannot be the same as the old password.</div>
                                    
                                    <!-- Validation Message -->
                                    <small class="text-muted">Password must be at least 8 characters long.</small><br>
                                    <small id="uppercaseReq" class="text-danger"><i class="bi bi-x-octagon-fill me-1"></i> At least one uppercase letter</small><br>
                                    <small id="lowercaseReq" class="text-danger"><i class="bi bi-x-octagon-fill me-1"></i> At least one lowercase letter</small><br>
                                    <small id="numberReq" class="text-danger"><i class="bi bi-x-octagon-fill me-1"></i> At least one number</small><br>
                                    <small id="specialCharReq" class="text-danger"><i class="bi bi-x-octagon-fill me-1"></i> At least one special character</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label"><i class="bi bi-key-fill me-1"></i> Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm your new password" required readonly>
                                    
                                    <div id="passwordMatchError" class="form-text text-danger" style="display: none;">New password and confirm password do not match.</div>
                                </div>
                                
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger" id="updatePasswordBtn" disabled>Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div>
                <div id="homePage" class="page-content active">
                    <div class="topbar d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Dashboard</h2>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center"
                                style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                            <span class="fw-semibold"><?= $_SESSION['admin']; ?></span>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="row g-4">
                            <div class="col-md-3">
                                <div class="bg-white shadow-sm rounded p-3">
                                    <p class="mb-1 text-muted">Sales</p>
                                    <h4>RM 12,300.00</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="bg-white shadow-sm rounded p-3">
                                    <p class="mb-1 text-muted">New User</p>
                                    <h4>111</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="bg-white shadow-sm rounded p-3">
                                    <p class="mb-1 text-muted">Product</p>
                                    <h4>10</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="orderPage" class="page-content">
                    <div class="topbar d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Order</h2>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center"
                                style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                            <span class="fw-semibold"><?= $_SESSION['admin']; ?></span>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <h4 class="mb-3">Order List</h4>
                        <div class="table-responsive">
                            <table id="orderTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer ID</th>
                                        <th>Order Date</th>
                                        <th>Total Amount</th>
                                        <th>Order Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Example rows -->
                                    <tr>
                                        <td>ORD001</td>
                                        <td>CUST123(Ali)</td>
                                        <td>2025-04-08</td>
                                        <td>RM 299.99</td>
                                        <td>Completed</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>orderID</td>
                                        <td>customerID(customerName)</td>
                                        <td>orderDate</td>
                                        <td>totalAmount</td>
                                        <td>orderStatus</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="productPage" class="page-content" style="margin-bottom:50px">
                    <div class="topbar d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Product</h2>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center"
                                style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                            <span class="fw-semibold"><?= $_SESSION['admin']; ?></span>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 50px;">
                            <h4 class="mb-0">Product List</h4>
                            <a href="admin_product.php" class="btn btn-primary" style="background-color: #673de6;">Add
                                Product</a>
                        </div>
                        <div class="table-responsive">
                            <table id="productTable" class="table table-striped">
                                <thead>

                                    <tr>
                                        <th>Product ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price (RM)</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $index => $product): ?>
                                        <tr>
                                            <td><?php echo 'P' . str_pad($index + 1, 4, '0', STR_PAD_LEFT); ?></td>
                                            <td><?php echo htmlspecialchars($product['productName']); ?></td>
                                            <td>
                                                <?php
                                                $catNames = [];

                                                if (!empty($product['category'])) {
                                                    $catIds = explode(',', $product['category']);

                                                    foreach ($catIds as $catId) {
                                                        $catId = trim($catId);
                                                        if (!empty($catId) && isset($categoryMap[$catId])) {
                                                            $catNames[] = $categoryMap[$catId];
                                                        }
                                                    }
                                                }

                                                echo !empty($catNames) ? implode(', ', $catNames) : '-';
                                                ?>
                                            </td>
                                            <td><?php echo number_format($product['price'], 2); ?></td>
                                            <td>
                                                <?php if ($product['status'] == 1): ?>
                                                    <span class="text-success fw-bold">Active</span>
                                                <?php else: ?>
                                                    <span class="text-danger fw-bold">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><a href="admin_product.php?edit=<?php echo $product['productID']; ?>"
                                                    class="btn btn-success btn-sm me-1">
                                                    <i class="bi bi-pencil-square"></i> Update
                                                </a>

                                                <a href="javascript:void(0);"
                                                    onclick="confirmStatusChange(<?php echo $product['productID']; ?>, <?php echo $product['status']; ?>)"
                                                    class="btn btn-sm <?php echo $product['status'] == 1 ? 'btn-danger' : 'btn-success'; ?>">
                                                    <?php echo $product['status'] == 1 ? 'Inactivate' : 'Activate'; ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="userPage" class="page-content">
                    <div class="topbar d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Customer</h2>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center"
                                style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                            <span class="fw-semibold"><?= $_SESSION['admin']; ?></span>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">Customer List</h4>
                            <button class="btn btn-primary" style="background-color: #673de6;"
                                onclick="openAddCustomerModal()">Add Customer</button>
                        </div>
                        <div class="table-responsive">
                            <table id="customerTable" class="table table-striped">

                                <thead>
                                    <tr>
                                        <th>Customer ID</th>
                                        <th>Username</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cust_query = $conn->query("SELECT * FROM customer ORDER BY customer_id ASC");
                                    while ($cust = $cust_query->fetch_assoc()):
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($cust['customer_id']) ?></td>
                                            <td><?= htmlspecialchars($cust['cust_username']) ?></td>
                                            <td><?= htmlspecialchars($cust['cust_name']) ?></td>
                                            <td><?= htmlspecialchars($cust['cust_email']) ?></td>
                                            <td><?= htmlspecialchars($cust['cust_phone']) ?></td>
                                            <td><?= htmlspecialchars($cust['cust_address']) ?></td>
                                            <td>
                                                <?php if ($cust['status'] == 1): ?>
                                                    <span class="text-success fw-bold">Active</span>
                                                <?php else: ?>
                                                    <span class="text-danger fw-bold">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <a href="javascript:void(0);"
                                                        onclick='openUpdateCustomerModal(<?= json_encode($cust); ?>)'
                                                        class="btn btn-success btn-sm">

                                                        <i class="bi bi-pencil-square"></i> Update
                                                    </a>
                                                    <a href="javascript:void(0);"
                                                        onclick="confirmCustomerStatusChange(<?= $cust['customer_id']; ?>, <?= $cust['status']; ?>)"
                                                        class="btn btn-sm <?= $cust['status'] == 1 ? 'btn-danger' : 'btn-success'; ?>">
                                                        <?= $cust['status'] == 1 ? 'Deactivate' : 'Activate'; ?>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Account Setting Modal -->
    <div class="modal fade" id="accountSettingModal" tabindex="-1" aria-labelledby="accountSettingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="accountSettingModalLabel">Account Settings</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="accountSettingsForm">
                        <div class="mb-3">
                            <label for="adminName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="adminName" value="AliAbuAkau">
                        </div>

                        <div class="mb-3">
                            <label for="adminEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="adminEmail" value="admin@example.com">
                        </div>

                        <div class="mb-3">
                            <label for="adminPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="adminPassword"
                                placeholder="Enter new password">
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="accountSettingsForm" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(document).ready(function () {
            $('#orderTable').DataTable();
        });

        //save changes at account settings
        document.getElementById('accountSettingsForm').addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire('Saved!', 'Your settings have been updated.', 'success');
        });

        // targetPage will be active if onclick
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
                this.classList.add('active');

                const targetId = this.getAttribute('data-target');

                document.querySelectorAll('.page-content').forEach(page => {
                    page.classList.remove('active');
                });

                const targetPage = document.getElementById(targetId);
                if (targetPage) targetPage.classList.add('active');
            });
        });

        //log out button
        document.getElementById('logoutBtn').addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure you want to \n log out?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'admin_logout.php';
                }
            });
        });

        $(document).ready(function () {
            $('#productTable').DataTable();
        });

        function confirmStatusChange(productID, currentStatus) {
            Swal.fire({
                title: `Are you sure you want to ${currentStatus == 1 ? 'inactivate' : 'activate'} this product?`,
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({
                        title: "SUCCESS",
                        text: `Product successfully marked as ${currentStatus == 1 ? 'inactivate' : 'activate'}!`,
                        icon: "success",
                        confirmButtonColor: "Green",
                        confirmButtonText: "OK"
                    }).then(function () {
                        window.location.href = `admin.php?id=${productID}&status=${currentStatus}`;
                    });

                }
            });
        }

        $(document).ready(function () {
            $('#customerTable').DataTable();
        });

        function confirmCustomerStatusChange(customerID, currentStatus) {
            Swal.fire({
                title: `Are you sure you want to ${currentStatus == 1 ? 'deactivate' : 'activate'} this customer?`,
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `admin_customer.php?id=${customerID}&status=${currentStatus}`;
                }
            });
        }

        function openUpdateCustomerModal(customer) {
            Swal.fire({
                title: 'Update Customer Info',
                html: `
            <form id="updateCustomerForm">
                <input type="hidden" name="customer_id" value="${customer.customer_id}">
                <div class="mb-2 text-start">
                    <label>Username</label>
                    <input class="form-control" type="text" value="${customer.cust_username}" disabled>
                </div>
                <div class="mb-2 text-start">
                    <label>Email</label>
                    <input class="form-control" type="email" value="${customer.cust_email}" disabled>
                </div>
                <div class="mb-2 text-start">
                    <label>Full Name</label>
                    <input class="form-control" type="text" name="cust_name" value="${customer.cust_name}" required>
                </div>
                <div class="mb-2 text-start">
                    <label>Phone</label>
                    <div class="input-group">
                        <span class="input-group-text">+60</span>
                        <input class="form-control" type="text" name="cust_phone" 
                            value="${customer.cust_phone.replace(/^60/, '')}" required>
                    </div>
                </div>
                <div class="mb-2 text-start">
                    <label>Address</label>
                    <textarea class="form-control" name="cust_address" rows="2" required>${customer.cust_address}</textarea>
                </div>
            </form>
        `,
                showCancelButton: true,
                confirmButtonText: 'Save Changes',
                preConfirm: () => {
                    const form = document.getElementById('updateCustomerForm');
                    const formData = new FormData(form);
                    return fetch('admin_customer.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status !== 'success') {
                                throw new Error(data.message);
                            }
                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(error.message);
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Updated!', 'Customer information updated successfully.', 'success')
                        .then(() => location.reload());
                }
            });
        }

        function openAddCustomerModal() {
            Swal.fire({
                title: 'Add New Customer',
                html: `
        <form id="addCustomerForm">
            <div class="mb-2 text-start">
                <label>Username</label>
                <input class="form-control" type="text" name="cust_username" required>
            </div>
            <div class="mb-2 text-start">
                <label>Email</label>
                <input class="form-control" type="email" name="cust_email" required>
            </div>
            <div class="mb-2 text-start">
                <label>Full Name</label>
                <input class="form-control" type="text" name="cust_name" required>
            </div>
            <div class="mb-2 text-start">
                <label>Phone</label>
                <div class="input-group">
                    <span class="input-group-text">+60</span>
                    <input class="form-control" type="text" name="cust_phone" required>
                </div>
            </div>
            <div class="mb-2 text-start">
                <label>Address</label>
                <textarea class="form-control" name="cust_address" rows="2" required></textarea>
            </div>
            <div class="mb-2 text-start">
                <label>Password</label>
                <input class="form-control" type="password" name="cust_password" required>
            </div>
        </form>
        `,
                showCancelButton: true,
                confirmButtonText: 'Add Customer',
                preConfirm: () => {
                    const form = document.getElementById('addCustomerForm');
                    const formData = new FormData(form);

                    return fetch('add_customer.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status !== 'success') {
                                throw new Error(data.message);
                            }
                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(error.message);
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Success!', 'New customer has been added.', 'success')
                        .then(() => location.reload());
                }
            });
        }

        function validatePhoneNumber(phone) {
            const regex011 = /^011-\d{8}$/;
            const regexOther = /^01[02456789]-\d{7}$/;
            return regex011.test(phone) || regexOther.test(phone);
        }

        document.getElementById('profileForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            const phoneInput = document.getElementById('adminPhone');
            const phone = phoneInput.value;
            const originalPhoneInput = document.getElementById('originalAdminPhone');
            const originalPhone = originalPhoneInput ? originalPhoneInput.value : null;

            if (originalPhone !== null && phone === originalPhone) {
                Swal.fire({
                    icon: 'info',
                    title: 'Number Unchanged',
                    text: 'The new phone number you entered is the same as the current number.'
                });
                return;
            }

            if (!validatePhoneNumber(phone)) {
                phoneInput.classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Phone Number',
                    text: 'Please enter a valid phone number (e.g., 010-4567899 or 011-45446989)'
                });
                return;
            }

            const formData = new FormData(this);
            fetch('admin_profile.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Profile Updated!',
                            text: 'Your profile information has been updated successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = 'admin.php';
                        });
                    } else if (data === 'failed') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: 'There was an error updating your profile. Please try again later.'
                        });
                    } else if (data === 'invalid') {
                        phoneInput.classList.add('is-invalid');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Input',
                            text: 'The phone number you entered is not in the correct format.'
                        });
                    } else {
                        console.error('Unexpected response from server:', data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong',
                            text: 'An unexpected error occurred. Please try again.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error sending request:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'There was a network error. Please check your connection and try again.'
                    });
                });
        });

        //CHANGE PASSWORD
        document.addEventListener("DOMContentLoaded", function () {
            //get input password
            const oldPassword = document.getElementById("oldPassword");
            const newPassword = document.getElementById("newPassword");
            const confirmPassword = document.getElementById("confirmPassword");
            const updateBtn = document.getElementById("updatePasswordBtn");
            
            //error check
            const oldPasswordMatchError = document.getElementById("oldPasswordMatchError");
            const samePasswordError = document.getElementById("samePasswordError");
            const passwordMatchError = document.getElementById("passwordMatchError");

            //requirement of the new password
            const uppercaseReq = document.getElementById("uppercaseReq");
            const lowercaseReq = document.getElementById("lowercaseReq");
            const numberReq = document.getElementById("numberReq");
            const specialCharReq = document.getElementById("specialCharReq");

            let validNewPassword = false;
            let passwordsMatch = false;

            oldPassword.addEventListener("keyup", function () {
                const value = oldPassword.value.trim();

                if (value.length === 0) {
                    newPassword.readOnly = true;
                    confirmPassword.readOnly = true;
                    updateBtn.disabled = true;
                } else {
                    newPassword.readOnly = false;
                }
            });

            // Validate new password
            newPassword.addEventListener("input", function () {
                const newVal = newPassword.value;
                const oldVal = oldPassword.value;

                // Don't allow same as old password
                if (newVal === oldVal) {
                    samePasswordError.style.display = "block";
                } else {
                    samePasswordError.style.display = "none";
                }

                const hasUpper = /[A-Z]/.test(newVal);
                const hasLower = /[a-z]/.test(newVal);
                const hasNumber = /[0-9]/.test(newVal);
                const hasSpecial = /[!@#$%^&*_(),.?":{}|<>]/.test(newVal);
                const mixLength = newVal.length >= 8;

                // Toggle styles
                uppercaseReq.className = hasUpper ? "text-success" : "text-danger";
                lowercaseReq.className = hasLower ? "text-success" : "text-danger";
                numberReq.className = hasNumber ? "text-success" : "text-danger";
                specialCharReq.className = hasSpecial ? "text-success" : "text-danger";

                validNewPassword = hasUpper && hasLower && hasNumber && hasSpecial && mixLength && newVal !== oldVal;

                if (validNewPassword) {
                    confirmPassword.readOnly = false;
                } else {
                    confirmPassword.readOnly = true;
                    updateBtn.disabled = true;
                }
            });

            // Confirm password match with new password
            confirmPassword.addEventListener("input", function () {
                if (confirmPassword.value === newPassword.value && validNewPassword) {
                    passwordMatchError.style.display = "none";
                    updateBtn.disabled = false;
                    passwordsMatch = true;
                } else {
                    passwordMatchError.style.display = "block";
                    updateBtn.disabled = true;
                    passwordsMatch = false;
                }
            });
        });

        document.getElementById("passwordForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch("admin_password.php", {
                method: "POST",
                body: formData,
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Password Updated!",
                        text: "Your password has been successfully updated.",
                        confirmButtonColor: "#673de6"
                    }).then(() => {
                        window.location.href = 'admin.php';
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Invalid Password",
                        text: "Your old password is incorrect.",
                        confirmButtonColor: "#673de6"
                    });
                }
            })
            .catch(error => {
                console.error("Error updating password:", error);
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong. Please try again later.",
                    confirmButtonColor: "#673de6"
                });
            });
        });
    </script>
</body>

</html>