<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChapaLang Graduation Gifts</title>
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
            padding: 30px 20px;
            text-align: center;
        }

        .advertisement img {
            max-width: 70%;
            height: auto;
            margin: auto;
        }

        .carousel-control-prev,
        .carousel-control-next {
            margin-top: 20%;
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

        .products {
            padding: 50px 0;
        }

        .card img {
            max-width: 100%;
            height: auto;
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
                    <img src="image/adv1.png" class="d-block" alt="Advertisement 1">
                </div>
                <div class="carousel-item">
                    <img src="image/adv2.png" class="d-block" alt="Advertisement 2">
                </div>
                <div class="carousel-item">
                    <img src="image/adv3.png" class="d-block" alt="Advertisement 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#advCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#advCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <section id="shop" class="products container">
        <h2 class="text-center">Our Products</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img src="product1.jpg" class="card-img-top" alt="Product">
                    <div class="card-body">
                        <h5 class="card-title">Graduation Bear</h5>
                        <p class="card-text">Soft plush bear with graduation cap.</p>
                        <a href="#" class="btn btn-success">Buy Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="product2.jpg" class="card-img-top" alt="Product">
                    <div class="card-body">
                        <h5 class="card-title">Graduation Frame</h5>
                        <p class="card-text">Customizable graduation photo frame.</p>
                        <a href="#" class="btn btn-success">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="categories" class="container text-center my-5">
        <h2>Shop by Category</h2>
        <div class="row mt-4">
            <div class="col-md-3 col-6">
                <div class="card p-3">
                    <img src="/image/plushies.png" alt="Graduation Plushies" class="img-fluid">
                    <h5 class="mt-2">Plushies</h5>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card p-3">
                    <img src="/image/frames.png" alt="Graduation Frames" class="img-fluid">
                    <h5 class="mt-2">Frames</h5>
                </div>
            </div>
            <div class="col-md-3 col-6 mt-4 mt-md-0">
                <div class="card p-3">
                    <img src="/image/bouquets.png" alt="Bouquets" class="img-fluid">
                    <h5 class="mt-2">Bouquets</h5>
                </div>
            </div>
            <div class="col-md-3 col-6 mt-4 mt-md-0">
                <div class="card p-3">
                    <img src="/image/custom.png" alt="Custom Gifts" class="img-fluid">
                    <h5 class="mt-2">Custom Gifts</h5>
                </div>
            </div>
        </div>
    </section>


    <div class="cart-icon">
        <a href="cart.html">
            <img src="image/cart.png" alt="Cart">
        </a>
    </div>
</body>

<?php include 'footer.php'; ?>