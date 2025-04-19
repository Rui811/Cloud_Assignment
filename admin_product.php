<!DOCTYPE html>
<html lang="en">
<?php
$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";
$conn = new mysqli($host, $username, $password, $database);

$editMode = false;
$product = ['productID' => '', 'productName' => '', 'price' => '', 'image' => '', 'category' => ''];

if (isset($_GET['edit'])) {
    $editID = $_GET['edit'];
    $editMode = true;
    $stmt = $conn->prepare("SELECT * FROM product WHERE productID = ?");
    $stmt->bind_param("i", $editID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $description = $product['description'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $selectedCategories = $_POST['categories'];
    $categoryString = implode(',', $selectedCategories);
    $editID = $_POST['editID'] ?? null;
    $description = $_POST['description'];

    $imgBaseName = $product['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imgTmp = $_FILES['image']['tmp_name'];
        $imgName = $_FILES['image']['name'];
        $ext = pathinfo($imgName, PATHINFO_EXTENSION);

        if ($ext !== 'png')
            die("Only .png files are allowed.");

        $imgBaseName = pathinfo($imgName, PATHINFO_FILENAME);
        $targetPath = 'image/' . $imgBaseName . '.png';
        if (!move_uploaded_file($imgTmp, $targetPath))
            die("Failed to upload image.");
    }

    if ($editID) {
        $sql = "UPDATE product SET productName=?, price=?, description=?, image=?, category=? WHERE productID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsssi", $productName, $price, $description, $imgBaseName, $categoryString, $editID);
        $stmt->execute();
        header("Location: admin_product.php?success=updated");
    } else {
        $sql = "INSERT INTO product (productName, price, description, image, category, status) VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsss", $productName, $price, $description, $imgBaseName, $categoryString);
        $stmt->execute();
        header("Location: admin_product.php?success=added");
    }
    exit();
}

$categoryResult = $conn->query("SELECT * FROM category");
$existingCategoryIDs = explode(',', $product['category']);
?>

<head>
    <meta charset="UTF-8">
    <title><?= $editMode ? 'Edit Product' : 'Add Product' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    body {
        background-image: url("image/addProductBg.png");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100vh;
    }

    .product-card {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        margin: 70px auto;
        max-width: 900px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: #6c757d;
        color: white;
        font-weight: 600;
        border: none;
        transition: background-color 0.3s ease, opacity 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #5a6268;
        opacity: 0.8;
    }
</style>

<body>
    <div class="product-card">
        <div class="container mt-4">
            <h2><?= $editMode ? 'Edit Product' : 'Add New Product' ?></h2>
            <form method="POST" enctype="multipart/form-data" class="container mt-5" onsubmit="return validateForm()">
                <input type="hidden" name="editID" value="<?= $product['productID'] ?? '' ?>">

                <div class="row align-items-stretch">

                    <!-- Left column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Product Name:</label>
                            <input type="text" name="productName" class="form-control"
                                value="<?= htmlspecialchars($product['productName'] ?? '') ?>" required>
                            <div class="text-danger" id="error-name"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price (RM):</label>
                            <input type="number" step="0.01" name="price" class="form-control"
                                value="<?= htmlspecialchars($product['price'] ?? '') ?>" required>
                            <div class="text-danger" id="error-price"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description:</label>
                            <textarea name="description" rows="4"
                                class="form-control"><?= htmlspecialchars($description ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Right column -->
                    <div class="col-md-6 d-flex flex-column justify-content-between" style="min-height: 100%;">
                        <div>
                            <div class="mb-3">
                                <label class="form-label">Image (PNG only):</label>
                                <input type="file" name="image" accept=".png" class="form-control">
                                <div class="text-danger mt-1" id="error-image"></div>
                                <?php if ($editMode && !empty($product['image'])): ?>
                                    <p class="mt-2">Current Image: <?= htmlspecialchars($product['image']) ?>.png</p>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Categories:</label><br>
                                <?php while ($row = $categoryResult->fetch_assoc()): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="categories[]"
                                            value="<?= $row['catID'] ?>" <?= isset($existingCategoryIDs) && in_array($row['catID'], $existingCategoryIDs) ? 'checked' : '' ?>>
                                        <label class="form-check-label"><?= htmlspecialchars($row['catName']) ?></label>
                                    </div>
                                <?php endwhile; ?>
                                <div class="text-danger mt-1" id="error-category"></div>
                            </div>
                        </div>

                        <!-- Button aligned bottom right -->
                        <div class="mt-auto d-flex justify-content-end">
                            <button type="submit"
                                class="btn btn-primary"><?= $editMode ? 'Update Product' : 'Add Product' ?></button>
                        </div>
                    </div>
            </form>

        </div>
    </div>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');

        if (success === 'added') {
            Swal.fire({
                title: "SUCCESS",
                text: "Product successfully added!",
                icon: "success",
                confirmButtonColor: "green",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "admin_product.php";
            });
        }

        if (success === 'updated') {
            Swal.fire({
                title: "SUCCESS",
                text: "Product successfully updated!",
                icon: "success",
                confirmButtonColor: "green",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "admin.php";
            });
        }

        function validateForm() {
            let isValid = true;

            document.getElementById("error-name").innerText = "";
            document.getElementById("error-price").innerText = "";
            document.getElementById("error-category").innerText = "";
            document.getElementById("error-image").innerText = "";

            const name = document.querySelector('[name="productName"]').value.trim();
            const price = parseFloat(document.querySelector('[name="price"]').value);
            const categoryCheckboxes = document.querySelectorAll('[name="categories[]"]:checked');
            const imageInput = document.querySelector('[name="image"]');
            const editID = document.querySelector('[name="editID"]').value;

            if (!name) {
                document.getElementById("error-name").innerText = "Product name cannot be empty.";
                isValid = false;
            }

            if (isNaN(price) || price <= 0) {
                document.getElementById("error-price").innerText = "Price must be greater than 0.";
                isValid = false;
            }

            if (categoryCheckboxes.length === 0) {
                document.getElementById("error-category").innerText = "Please select at least one category.";
                isValid = false;
            }

            const file = imageInput.files[0];
            if (!editID && !file) {
                document.getElementById("error-image").innerText = "Please upload a PNG image.";
                isValid = false;
            } else if (file) {
                const ext = file.name.split('.').pop().toLowerCase();
                if (ext !== "png") {
                    document.getElementById("error-image").innerText = "Only PNG images are allowed.";
                    isValid = false;
                }
            }

            return isValid;
        }


    </script>

</body>

</html>