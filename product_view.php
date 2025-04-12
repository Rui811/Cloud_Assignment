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
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChapaLang Graduation Bouquets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
      margin-bottom:10px ;
      margin-top:-20px;
      text-decoration: none;
      width: fit-content;
      font-size: 18px;
      font-weight: bold;
      padding-left: 0px;
      color:rgb(92, 96, 173);
      display: flex;
      align-items: center;
      gap: 5px;
      cursor: pointer;
      transition: 0.5s ease-in-out;

    &:hover {
      transform: translateX(-4px);
      color:rgb(64, 68, 151);
      }
    }
    </style>
</head>

<body>

    <main class="container">
        <div class="product-card">
          <a class="back-product-btn" href="product.php" title="back to product page">
                  <i class="fa-solid fa-shop"></i> Shop
              </a>
        
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
      </div>
                </div>
                
            </div>
        </div>
    </main>

    <?php include './footer.php'; ?>
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
                  "customerId" : customerId,
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
