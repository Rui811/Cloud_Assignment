<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<?php
$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$productId = $_GET['id'] ?? 0;

$sql = "SELECT * FROM product WHERE productID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<h2>Product not found.</h2>";
    exit;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChapaLang Graduation Bouquets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
      background-image: url("image/wallpaper2.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
    }

    header {
      padding: 20px;
      text-align: center;
      background-color: #fff;
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
    }

    .product-card {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 10px;
      margin: 30px auto;
      max-width: 900px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .product-img {
      max-width: 100%;
      border-radius: 10px;
    }

    .product-title {
      font-size: 1.8rem;
      font-weight: bold;
      margin-bottom: 10px;
      
    }

    .product-description {
      font-size: 1rem;
      color: #333;
    }

    .table th,
    .table td {
      vertical-align: middle;
    }

    footer {
      background-color: #333;
      color: white;
      text-align: center;
      padding: 15px;
      position: fixed;
      width: 100%;
      bottom: 0;
    }

    @media (max-width: 767px) {
      footer {
        position: static;
      }
    }
    </style>
</head>

<body>

    <main class="container">
        <div class="product-card">
            <div class="row align-items-center">
                <!-- Image on the right -->
                <div class="col-md-5 text-center mt-4 mt-md-0">
                <img src="image/<?php echo htmlspecialchars($product['image']) . '.png'; ?>" alt="<?php echo htmlspecialchars($product['productName']); ?>" class="product-img">

                </div>
                <div class="col-md-7">
                <h2 class="product-title" id="productName"><?php echo htmlspecialchars($product['productName']); ?></h2>
                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                <p>RM <?php echo number_format($product['price'], 2); ?></p>

      <div class="mt-4 d-flex align-items-center">
            <label for="quantity" class="me-2 fw-bold">Quantity:</label>
            <input type="number" id="quantity" class="form-control w-25 me-3" min="1" value="1">
            <button class="btn btn-primary" id="addToCartBtn">Add to Cart</button>
          </div>

          <div id="cartMessage" class="mt-3 text-success fw-bold"></div>
      </div>
                </div>

    <script>
        
    </script>
                
            </div>
        </div>
    </main>

    <?php include './footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      $(document).ready(function () {
        $('#addToCartBtn').on('click', function() {
          let quantity = $('#quantity').val().trim();
          let productId = <?= json_encode($productId) ?>;
          let productName = $('#productName').text();
          let productPrice = $('#productPrice').text();
          let textMsg = `Product : ${productName} x${quantity}`;

          Swal.fire({
            title: "Confirm add to cart?",
            html: textMsg,
            icon: "info",
            confirmButtonColor: "Green",
            confirmButtonText: "Confirm",
            showCancelButton: true,
            cancelButtonColor: "Crimson",
            cancelButtonText: "Cancel"
          }).then((result) => {
            if(result.isConfirmed) {
              $.ajax({
                url: "ajax/add_to_cart.php",
                type: "POST",
                data: {
                  "productId" : productId,
                  "quantity" : quantity
                },
                success: function(response) {
                  if(response == "success") {
                    Swal.fire({
                      title: "SUCCESS",
                      text: "Product successfully added to cart!",
                      icon: "success",
                      confirmButtonColor: "Green",
                      confirmButtonText: "OK"
                    });
                  }
                  else {
                    Swal.fire({
                      title: "FAILED",
                      text: "An error occured! Please try again later.",
                      icon: "error",
                      confirmButtonColor: "Green",
                      confirmButtonText: "OK"
                    });
                  }
                },
                error: function() {
                  Swal.fire({
                    title: "ERROR",
                    text: "An error occured! Please try again later.",
                    icon: "error",
                    confirmButtonColor: "Green",
                    confirmButtonText: "OK"
                  });
                }
              });
            }
          });
        });
      });
    </script>
</body>
</html>
