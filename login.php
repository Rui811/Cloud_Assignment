<?php

?>
<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ChapaLang Graduation Gifts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url("image/wallpaper.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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

        .wrapper {
            display: flex;
            width: 75%;
            max-width: 900px;
            height: 60vh;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            margin-left: 28%;
            margin-top: 5%;
        }

        .login-container {
            width: 50%;
            padding: 40px;
            text-align: center;
        }

        .image-container {
            width: 55%;
            background-image: url("image/loginSitePic.png");
            background-size: cover;
            background-position: center;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .wrapper {
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            font-weight: 600;
            color: #ff6b81;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #ff6b81;
        }

        .btn-primary {
            background: #ff6b81;
            border: none;
            border-radius: 10px;
            padding: 10px;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #ff3b5c;
            transform: scale(1.05);
        }

        .signup-link {
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }

        .signup-link a {
            color: #ff3b5c;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        footer {
            position: absolute;
            bottom: 10px;
            font-size: 14px;
            color: #666;
        }

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
                width: 90%;
            }

            .login-container,
            .image-container {
                width: 100%;
            }

            .image-container {
                height: 300px;
            }
        }
    </style>
</head>



<body>

    <div class="wrapper">
        <div class="login-container">
            <h2>Welcome Back! 🎀</h2>
            <p>Login to continue shopping for cute gifts!</p>
            <form action="login_process.php" method="POST">
                <div class="mb-3">
                    <input type="text" name="identifier" class="form-control" placeholder="Username or Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <div class="text-center mt-2">
                    <a href="forgot_password.php" class="text-decoration-none"
                        style="color: #ff3b5c; font-size: 14px;">Forgot Password?</a>
                </div>

            </form>
            <p class="signup-link">Don't have an account? <a href="signup.php">Sign Up Now!</a></p>
        </div>

        <div class="image-container"></div>
    </div>

    <footer>
        &copy; 2025 Chapalang Graduation Gifts | Made with ❤️
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>