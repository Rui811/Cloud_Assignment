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

//check is admin or not
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
}

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
$product_sql = "SELECT p.*, c.catName 
                FROM product p 
                JOIN category c ON p.category = c.catName 
                WHERE (c.catName = ? OR ? = 'All') 
                AND (p.productName LIKE ?)";

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
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" >

    <style>
        body {
            background-color: #f9fbfd;
        }

        .sidebar {
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

        a.logOutAlert:hover, a.profile:hover {
            background-color: #dc3545;
            color: #fff;
        }

        .main-content {
            padding: 20px;
        }

        .topbar {
            padding: 15px 20px;
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
        }

        .page-content {
            display: none;
        }

        .page-content.active {
            display: block;
        }

        .buttonAddProduct
        {background-color: #673de6;}
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
                <div class="mt-auto">
                    <a href="#" class="profile" data-bs-toggle="modal" data-bs-target="#accountSettingModal">
                        <i class="bi bi-gear-fill me-2"></i> Account Setting
                    </a>
                    
                    <a href="#" class="logOutAlert mt-auto" id="logoutBtn"><i class="bi bi-box-arrow-right me-2"></i> Log out</a>
                </div>
                
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <div id="homePage" class="page-content active">
                    <div class="topbar d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Dashboard</h2>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
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
                            <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
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

                <div id="productPage" class="page-content">
                    <div class="topbar d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Product</h2>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                            <span class="fw-semibold"><?= $_SESSION['admin']; ?></span>
                        </div>
                    </div>
                    <div class="container mt-4">
                    <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 50px;">
                            <h4 class="mb-0" >Product List</h4>
                            <a href="add_product.php" class="btn btn-primary" style="background-color: #673de6;">Add Product</a>
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
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?php echo 'P' . str_pad($product['productID'], 4, '0', STR_PAD_LEFT); ?></td>
                                            <td><?php echo htmlspecialchars($product['productName']); ?></td>
                                            <td><?php echo htmlspecialchars($product['catName']); ?></td>
                                            <td><?php echo number_format($product['price'], 2); ?></td>
                                            <td>
                                                <?php if ($product['status'] == 1): ?>
                                                <span class="text-success fw-bold">Active</span>
                                                <?php else: ?>
                                                <span class="text-danger fw-bold">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><a href="update_product.php?id=<?php echo $product['productID']; ?>" class="btn btn-success btn-sm me-1">
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
                            <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                            <span class="fw-semibold"><?= $_SESSION['admin']; ?></span>
                        </div>
                    </div>
                    <div class="container mt-4">Hi,Customer</div>
                </div>

            </div>
        </div>
    </div>
    
    <!-- Account Setting Modal -->
    <div class="modal fade" id="accountSettingModal" tabindex="-1" aria-labelledby="accountSettingModalLabel" aria-hidden="true">
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
                            <input type="password" class="form-control" id="adminPassword" placeholder="Enter new password">
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
    document.getElementById('accountSettingsForm').addEventListener('submit', function(e) {
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
                window.location.href = 'admin_login.php';
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

    </script>
</body>

</html>

