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

        a.logOutAlert:hover {
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

        .dropdown-menu {
            min-width: 200px;
        }

        .page-content {
            display: none;
        }

        .page-content.active {
            display: block;
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
                    <li>
                    </li>
                </ul>
                <a href="#" class="logOutAlert mt-auto"><i class="bi bi-box-arrow-right me-2"></i> Log out</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <div id="homePage" class="page-content active">
                    <div class="topbar d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Dashboard</h2>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill text-white"></i>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">View Profile</a></li>
                                <li><a class="dropdown-item" href="#">Account Settings</a></li>
                            </ul>
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
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill text-white"></i>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">View Profile</a></li>
                                <li><a class="dropdown-item" href="#">Account Settings</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="container mt-4">Hi,Order</div>
                </div>

                <div id="productPage" class="page-content">
                    <div class="topbar d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Product</h2>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill text-white"></i>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">View Profile</a></li>
                                <li><a class="dropdown-item" href="#">Account Settings</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="container mt-4">Hi,Product</div>
                </div>

                <div id="userPage" class="page-content">
                    <div class="topbar d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Customer</h2>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill text-white"></i>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">View Profile</a></li>
                                <li><a class="dropdown-item" href="#">Account Settings</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="container mt-4">Hi,Customer</div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        // targetParge will be active if onclick
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
    </script>
</body>

</html>