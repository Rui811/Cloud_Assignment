<?php
// signup.php
?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ChapaLang Graduation Gifts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url("image/wallpaper2.png");
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

        .logo {
            width: 250px;
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
            margin-left: -30%;
            margin-top: 5%;
            animation: fadeIn 0.8s ease-in-out;
        }

        .image-container {
            width: 60%;
            background-image: url("image/signupSitePic.png");
            background-size: cover;
            background-position: center;
        }

        .signup-container {
            width: 50%;
            padding: 40px;
            text-align: center;
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
            color: #90ccfd;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #90ccfd;
            transition: transform 0.2s ease-in-out;
        }

        .form-control:focus {
            transform: scale(1.05);
            border-color: #90ccfd;
            box-shadow: 0 0 8px rgba(145, 196, 255, 0.4);
        }

        .btn-success {
            background: #86c0f0;
            border: none;
            border-radius: 10px;
            padding: 10px;
            transition: 0.3s;
        }

        .btn-success:hover {
            background: #d1eaff;
            transform: scale(1.05);
        }

        .login-link {
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }

        .login-link a {
            color: #90ccfd;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        footer {
            position: absolute;
            bottom: 10px;
            font-size: 14px;
            color: #666;
        }


        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .navbar img {
                width: 200px;
            }

        }
    </style>
</head>

<body>

    <div class="wrapper">
        <div class="image-container"></div>

        <div class="signup-container">
            <h2>Join the Fun! 🎉</h2>
            <p>Create your account and start shopping for the cutest graduation gifts!</p>
            <form action="signup_process.php" method="POST">
                <div class="mb-3">
                    <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Sign Up</button>
            </form>
            <p class="login-link">Already have an account? <a href="login.php">Login Here!</a></p>
        </div>
    </div>

    <footer>
        &copy; 2025 Chapalang Graduation Gifts | Made with ❤️
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
