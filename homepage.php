<?php
include 'header.php';

$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$displayProducts = [];
$product_sql = "SELECT * FROM product WHERE status = 1";
$product_result = $conn->query($product_sql);

if ($product_result->num_rows > 0) {
    while ($row = $product_result->fetch_assoc()) {
        $displayProducts[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChapaLang Graduation Gifts</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
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

        .advertisement {
            background: #ffffff;
            padding: 10px;
            text-align: center;
            width: 100%;
            max-width: 100%;
            height: auto;
        }

        .advertisement img {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            margin: auto;
        }

        .carousel-control-prev,
        .carousel-control-next {
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(128, 128, 128, 0.3);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(50%);
        }

        #categories {
            text-align: center;
            margin-top: 20px;
            padding-bottom: 30px;
            font-family: 'Poppins', sans-serif;
        }

        #categories .category-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            gap: 40px;
        }

        #categories .category-container div {
            position: relative;
            text-align: center;
        }

        #categories .category-container img {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            transition: opacity 0.3s ease;
        }

        #categories .category-container div:hover img {
            opacity: 0.7;
            /* Image fades slightly on hover */
        }

        #categories .category-container a {
            text-decoration: none;
            color: black;
        }

        #categories .category-container a:hover {
            color: rgb(78, 57, 57);
            font-weight: bold;
        }

        #categories .category-container div::after {
            content: "Go";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: balck;
            font-weight: bold;
            font-size: 18px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #categories .category-container div:hover::after {
            opacity: 2;
        }

        #shop {
            background-color: #f8f9fa;
            padding: 30px 0;
            /* max-width: 100%; */
            width: 100%;
            margin: 0 auto;
            padding-left: 0;
            padding-right: 0;
        }


        .cart-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: #fff4fa;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }

        .cart-icon img {
            width: 40px;
            height: 40px;
        }

        .cart-icon:hover {
            transform: scale(1.1);
            background: #ffdae0;
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 35px;
                height: 35px;
            }
        }

        @media (max-width: 576px) {
            .navbar img {
                width: 200px;
            }

            .products h2 {
                font-size: 1.8rem;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 30px;
                height: 30px;
            }
        }

        .product-container {
            display: grid;
            margin-left: 30px;
            grid-template-columns: repeat(3, 1fr);
            /* 4 items per row */
            gap: 30px;
            flex: 1;
        }

        .product img {
            width: 100%;
            height: auto;
            max-height: 250px;
            border-radius: 5px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
        }


        .product:hover img {
            transform: scale(1.05);
        }

        .product h2 {
            margin: 15px 0;
            font-size: 18px;
            font-weight: bold;
        }

        .product p {
            font-size: 16px;
            font-weight: bold;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-container .product {
            animation: fadeIn 0.5s ease-out;
        }

        .more-link {
            text-align: center;
            margin-top: 20px;
            border: 0.5px solid black;
        }

        /* Cart Icon */
        .cart-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: #fff4fa;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }

        .cart-icon img {
            width: 40px;
            height: 40px;
        }

        .cart-icon:hover {
            transform: scale(1.1);
            background: #ffdae0;
        }

        /* Media Queries for Smaller Screens */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 35px;
                height: 35px;
            }

            .product-container {
                grid-template-columns: repeat(2, 1fr);
                /* 2 products per row on smaller screens */
            }
        }

        @media (max-width: 576px) {
            .navbar img {
                width: 200px;
            }

            .products h2 {
                font-size: 1.8rem;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 30px;
                height: 30px;
            }

            .product-container {
                grid-template-columns: 1fr;
                /* 1 product per row on very small screens */
            }
        }

        #about {
            background-color: rgb(255, 255, 255);
            padding: 50px 0;
            animation: fadeIn 1.5s ease-out;
        }

        #about .about-content {
            max-width: 1000px;
            margin: 0 auto;
        }

        #about h2 {
            font-size: 2.5rem;
            color: #333;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .about-images {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            opacity: 0;
            animation: fadeInLeftRight 1.5s forwards;
        }

        .about-content-left,
        .about-content-right {
            flex: 1;
            padding: 20px;
            text-align: left;
            margin-top: 20px;
        }

        .about-content-left h3,
        .about-content-right h3 {
            font-size: 2rem;
            color: #333;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .about-content-left p,
        .about-content-right p {
            font-size: 1.2rem;
            color: #555;
            line-height: 1.6;
        }

        .about-img-left,
        .about-img-right {
            flex: 1;
            position: relative;
        }

        .about-img-left img,
        .about-img-right img {
            width: 100%;
            max-width: 350px;
            height: auto;
            border-radius: 8px;
            transition: transform 1.0s ease;
        }

        .about-img-left img:hover,
        .about-img-right img:hover {
            transform: scale(1.05);
        }

        @keyframes fadeInLeftRight {
            0% {
                opacity: 0;
                transform: translateX(-50px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Scroll-based fade-in for mobile */
        @media (max-width: 768px) {
            #about .about-images {
                flex-direction: column;
                animation: fadeInMobile 1.5s forwards;
            }

            .about-img-left img,
            .about-img-right img {
                width: 80%;
                margin-bottom: 20px;
            }

            .about-content-left,
            .about-content-right {
                text-align: center;
                padding: 10px;
            }
        }

        @keyframes fadeInMobile {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product .btn-success {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
        }

        .product .btn-success:hover {
            background-color: rgb(187, 191, 194);
            border-color: #545b62;
            color: #fff;
        }

        .navbar, .product h2, .product p {
            font-family: 'Poppins', sans-serif;
        }

        .add-to-cart {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <section class="advertisement">
        <div id="advCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#advCarousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#advCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#advCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="image/headerPic3.gif" class="d-block" alt="Advertisement 1">
                </div>
                <div class="carousel-item">
                    <img src="image/headerPic2.gif" class="d-block" alt="Advertisement 2">
                </div>
                <div class="carousel-item">
                    <img src="image/headerPic.gif" class="d-block" alt="Advertisement 3">
                </div>
            </div>
        </div>
    </section>

    <section id="categories" class="container">
        <h2>Shop by Category</h2>
        <div class="category-container">
            <div>
                <a href="product.php?category=Plushies">
                    <img src="image/cat_plushie.jpg" alt="Plushies">
                    <h5>Plushies</h5>
                </a>
            </div>
            <div>
                <a href="product.php?category=Frame">
                    <img src="image/cat_frames.jpg" alt="Frames">
                    <h5>Frames</h5>
                </a>
            </div>
            <div>
                <a href="product.php?category=CryBaby">
                    <img src="image/cat_crybaby.jpg" alt="Crybaby">
                    <h5>Crybaby</h5>
                </a>
            </div>
            <div>
                <a href="product.php?category=Bouquets">
                    <img src="image/cat_bouquets.jpg" alt="Bouquets">
                    <h5>Bouquets</h5>
                </a>
            </div>
            <div>
                <a href="product.php?category=Keychain">
                    <img src="image/cat_keychains.jpg" alt="Keychains">
                    <h5>Keychains</h5>
                </a>
            </div>
            <div>
                <a href="product.php?category=Customize+Gift">
                    <img src="image/cat_customize.jpg" alt="Customize">
                    <h5>Customize</h5>
                </a>
            </div>
        </div>
    </section>

    <section id="shop" class="products container">
        <h2 class="text-center">Our Products</h2>
        <div class="product-container">
            <?php
            $productCounter = 0;
            foreach ($displayProducts as $product):
                if ($productCounter >= 6)
                    break;
                $productCounter++;
                ?>
                <div class="product">
                    <a href="product_view.php?id=<?php echo $product['productID']; ?>" class="product-link">
                        <div class="image-container">
                            <img src="image/<?php echo htmlspecialchars($product['image']) . '.png'; ?>"
                                alt="<?php echo htmlspecialchars($product['productName']); ?>">
                        </div>
                    </a>
                    <h2><?php echo htmlspecialchars($product['productName']); ?></h2>
                    <p>RM<?php echo number_format($product['price'], 2); ?></p>
                    <a href="javascript:void(0);" class="btn btn-success add-to-cart"
                        data-product-id="<?php echo $product['productID']; ?>" data-quantity="1"
                        data-customer-id="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>"
                        data-remark="">
                        Add to Cart
                    </a>

                </div>
            <?php endforeach; ?>
        </div>

        <div class="more-link">
            <a href="product.php" class="btn btn-grey">View More Products</a>
        </div>
    </section>

    <section id="about" class="container text-center">
        <div class="about-content">
            <h2>About ChapaLang</h2>

            <!-- First Row: Left Image, Right Content -->
            <div class="about-images">
                <div class="about-img-left">
                    <img src="image/about1.png" alt="ChapaLang Graduation Gift Left" class="about-img">
                </div>
                <div class="about-content-right">
                    <h3>Unique & Personalized Graduation Gifts</h3>
                    <p>ChapaLang offers a collection of beautifully designed graduation gifts that celebrate
                        achievements with a personal touch. Each gift is thoughtfully crafted to make special moments
                        last, from the first step towards success to the final capstone of their academic journey.</p>
                </div>
            </div>

            <!-- Second Row: Right Image, Left Content -->
            <div class="about-images">
                <div class="about-content-left">
                    <h3>Why Choose ChapaLang?</h3>
                    <p>Our gifts are perfect for graduations, birthdays, or any special occasion. At ChapaLang, we
                        ensure that each gift is a meaningful representation of joy, success, and milestones. Choose
                        from our range of customized floral bouquets, personalized gifts, and keepsakes designed to make
                        any celebration unforgettable.</p>
                </div>
                <div class="about-img-right">
                    <img src="image/about2.png" alt="ChapaLang Graduation Gift Right" class="about-img">
                </div>
            </div>
        </div>
    </section>


    <div class="cart-icon">
        <a href="cart.php">
            <img src="image/cart.png" alt="Cart">
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('.add-to-cart').on('click', function () {
                var productId = $(this).data('product-id');
                var quantity = $(this).data('quantity');
                var customerId = $(this).data('customer-id');
                var remark = $(this).data('remark');

                if (!customerId) {
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

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to add this product to your cart?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Add it!',
                    cancelButtonText: 'No, Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'ajax/add_to_cart.php',
                            type: 'POST',
                            data: {
                                productId: productId,
                                quantity: quantity,
                                customerId: customerId,
                                remark: remark
                            },
                            success: function (response) {
                                if (response === 'success') {
                                    Swal.fire({
                                        title: "Success!",
                                        text: "Product successfully added to your cart!",
                                        icon: "success",
                                        confirmButtonText: "OK"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: "There was an error adding the product to the cart. Please try again later.",
                                        icon: "error",
                                        confirmButtonText: "OK"
                                    });
                                }
                            },
                            error: function () {
                                Swal.fire({
                                    title: "Error",
                                    text: "Something went wrong. Please try again later.",
                                    icon: "error",
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

<?php include 'footer.php'; ?>