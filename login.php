<?php
session_start();
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
} else {
    $error_message = '';
}
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
            animation: fadeIn 1s ease-in-out;
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

        h2 {
            font-weight: 600;
            color: #ff6b81;
        }

        #loginForm {
            margin-top: 40px;
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

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
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

        .modal-content {
            background-color: rgb(255, 255, 255);
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            animation: modalAnimation 0.5s ease-out;
        }

        .modal-title {
            color: #ff6b81;
            font-weight: 600;
        }

        #modalUsername {
            font-weight: bold;
            color: rgb(255, 91, 151);
        }

        .btn-pink {
            background-color: #ff6b81;
            border-radius: 30px;
            padding: 10px 30px;
            color: white;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-pink:hover {
            background-color: rgb(255, 231, 235);
            color: black;
            transform: scale(1.05);
        }

        @keyframes modalAnimation {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
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

            <form id="loginForm">
                <div class="mb-3">
                    <input type="text" name="identifier" class="form-control" placeholder="Username or Email" required>
                    <div class="error-message" id="username-error"></div>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required
                        id="passwordField">
                    <div class="error-message" id="password-error"></div>
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

    <!-- Success Modal -->
    <div class="modal fade" id="loginSuccessModal" tabindex="-1" aria-labelledby="loginSuccessModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4" style="border-radius: 15px; background: #fff2f8;">
                <h5 class="modal-title mb-3" id="loginSuccessModalLabel" style="color: #ff6b81; font-weight: 600;">Login
                    Successful!</h5>
                <p id="welcomeMessage" style="color: #ff6b81; font-size: 16px;">Welcome, <span id="modalUsername"
                        style="font-weight: bold;"></span>!</p>
                <div class="mt-3">
                    <button id="okButton" class="btn btn-pink" data-bs-dismiss="modal"
                        style="background: #ff6b81; border: none; border-radius: 30px; padding: 10px 30px; font-weight: bold; color: white; transition: all 0.3s ease;">
                        Okay, Let’s Go!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch('login_process.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    //clear previous msg
                    document.getElementById('username-error').textContent = '';
                    document.getElementById('password-error').textContent = '';

                    if (data.status === 'success') {
                        //show the success modal
                        const successModal = new bootstrap.Modal(document.getElementById('loginSuccessModal'));
                        successModal.show();

                        //update the username in the modal
                        document.getElementById('modalUsername').textContent = data.username;

                        document.getElementById('okButton').addEventListener('click', function () {
                            window.location.href = 'homepage.php';
                        });

                        setTimeout(() => {
                            window.location.href = 'homepage.php';
                        }, 3000);
                    } else {
                        if (data.field === 'identifier') {
                            document.getElementById('username-error').textContent = data.message;
                        } else if (data.field === 'password') {
                            document.getElementById('password-error').textContent = data.message;
                        }

                        document.getElementById('passwordField').value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>

</body>

</html>