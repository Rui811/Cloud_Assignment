<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<?php
$isLoggedIn = isset($_SESSION['user_id']) ? 'true' : 'false';

$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$productId = $_GET['id'] ?? 0;
$customer_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";


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
$isCustomizable = strpos($product['category'], '3') !== false;

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChapaLang Graduation Bouquets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    body {
      background-image: url("image/productViewBg.png");
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
      margin:auto;
      margin-bottom:70px;
      margin-top:30px;
      max-width: 900px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .product-img {
      max-width: 100%;
      height:420px;
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

    .back-product-btn {
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
    </style>
</head>

<body>

    <main class="container">
      
      <div class="d-flex justify-content-between align-items-center" style="margin-top:30px;">
        <div style="margin-left:190px;">
                  <a class="back-product-btn ms-2" href="product.php" title="back to cart">
                      <i class="fa-solid fa-chevron-left"></i>
                  </a>
          </div><div style="margin-right:550px;">
                  <h2 class="text-center mx-auto m-0">Product Detail</h2>
          </div>
              </div>
      <div class="product-card">
        
            <div class="row align-items-center">
                <!-- Image on the right -->
                <div class="col-md-5 text-center mt-4 mt-md-0">
                <img src="image/<?php echo htmlspecialchars($product['image']) . '.png'; ?>" alt="<?php echo htmlspecialchars($product['productName']); ?>" class="product-img">

                </div>
                <div class="col-md-7">
                <h2 class="product-title" id="productName"><?php echo htmlspecialchars($product['productName']); ?></h2>
                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                <b style="font-size:20px;">RM <?php echo number_format($product['price'], 2); ?></b>

                <?php if ($isCustomizable): ?>
                <div class="mt-3">
                <label for="remark" class="form-label fw-bold">Remark (Optional):</label>
                <textarea id="remark" class="form-control" rows="3" placeholder="👉 You can remark with your preferred message to custom personal gift"></textarea>
                </div>
                <?php endif; ?>

      <div class="mt-4 d-flex align-items-center">
            <label for="quantity" class="me-2 fw-bold">Quantity:</label>
            <input type="number" id="quantity" class="form-control w-25 me-3" min="1" value="1">
            <button class="btn btn-primary" id="addToCartBtn" style="background-color: #fc9bb6 !important;border:none;color:black;font- ">Add to Cart</button>
          </div>
      </div>
                </div>
                
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      const isLoggedIn = <?= $isLoggedIn ?>;

      $(document).ready(function () {
        $('#addToCartBtn').on('click', function() {

          //check logIn or not
          if (!isLoggedIn) {
            Swal.fire({
              title: "Please log in first",
              text: "You must be logged in to add items to your cart.",
              icon: "warning",
              confirmButtonText: "Go to Login"
            }).then(() => {
              window.location.href = "login.php";
            });
            
            return;
          }

          let quantity = $('#quantity').val().trim();
          let customerId = <?= json_encode($customer_id) ?>;
          let productId = <?= json_encode($productId) ?>;
          let productName = $('#productName').text();
          let productPrice = $('#productPrice').text();
          let remark = $('#remark').length > 0 ? $('#remark').val().trim() : "";
          let textMsg = `Product : ${productName} x${quantity}`;

          if (remark) {
        textMsg += `<br>Remark: ${remark}`;
      }

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
                  "customerId" : customerId,
                  "productId" : productId,
                  "quantity" : quantity,
                  "remark" : remark
                },
                success: function(response) {
                  if(response == "success") {
                    Swal.fire({
                      title: "SUCCESS",
                      text: "Product successfully added to cart!",
                      icon: "success",
                      confirmButtonColor: "Green",
                      confirmButtonText: "OK"
                    }).then(() => {
                      window.location.href = "product.php";
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
