<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
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
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- THIS IS SIDE BARRR -->
            <div class="col-md-2 sidebar p-3">
                <h4 class="text-purple fw-bold mb-4">CHAPALANG ADMIN</h4>
                <a href="#" class="active"><i class="bi bi-house-door-fill me-2"></i> Dashboard</a>
                <a href="#"><i class="bi bi-table me-2"></i>Order</a>
                <a href="#"><i class="bi bi-grid me-2"></i>Product</a>
                <a href="#"><i class="bi bi-person-circle me-2"></i> Customers</a>
                <a href="#"><i class="bi bi-box-arrow-right me-2"></i> Log out</a>
            </div>

            <!--Dashboard Content -->
            <div class="col-md-10 p-4">
                <!-- Top Bar -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Dashboard</h2>
                    <div class="d-flex align-items-center gap-3">
                    <!-- User avatar dropdown -->
                        <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#">View Profile</a></li>
                            <li><a class="dropdown-item" href="#">Account Settings</a></li>
                        </ul>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Section -->
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
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>