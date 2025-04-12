<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$signup_errors = $_SESSION['signup_errors'] ?? [];
$signup_success = $_SESSION['signup_success'] ?? false;

unset($_SESSION['signup_errors']);
unset($_SESSION['signup_success']);
?>


<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

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

        .error-message {
            font-size: 0.7rem;
        }

        footer {
            position: absolute;
            bottom: 10px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="image-container"></div>

        <div class="signup-container">
            <h2>Join the Fun! 🎉</h2>
            <p>Create your account and start shopping for the cutest graduation gifts!</p>

            <form id="signupForm" action="signup_process.php" method="POST">
                <div class="mb-3">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username"
                        required>
                    <div class="error-message text-danger mt-1" id="username-error"></div>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                    <div class="error-message text-danger mt-1" id="email-error"></div>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password"
                        required>
                    <div class="error-message text-danger mt-1" id="password-error"></div>
                </div>
                <button type="submit" class="btn btn-success w-100">Sign Up</button>
            </form>

            <p class="login-link">Already have an account? <a href="login.php">Login Here!</a></p>
        </div>
    </div>

    <footer>&copy; 2025 Chapalang Graduation Gifts | Made with ❤️</footer>

    <!-- Modal for signup errors -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel">Signup Error</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($signup_errors)): ?>
                        <ul class="mb-0">
                            <?php foreach ($signup_errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="loginSuccessModal" tabindex="-1" aria-labelledby="loginSuccessModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <h5 class="modal-title mb-3" id="loginSuccessModalLabel">Login Successful!</h5>
                <p id="welcomeMessage">Welcome, <span id="modalUsername"></span>!</p>
            </div>
        </div>
    </div>

    <!-- Client-side validation -->
    <script>
        document.getElementById('signupForm').addEventListener('submit', function (e) {
            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const password = document.getElementById('password');

            document.getElementById('username-error').textContent = '';
            document.getElementById('email-error').textContent = '';
            document.getElementById('password-error').textContent = '';

            const usernamePattern = /^[a-zA-Z0-9]{5,20}$/;
            const emailPattern = /^[^@]+@[^@]+\.[^@]+$/;
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (!usernamePattern.test(username.value)) {
                document.getElementById('username-error').textContent = 'Username must be 5–20 characters with no symbols.';
                e.preventDefault();
                return;
            }

            if (!emailPattern.test(email.value)) {
                document.getElementById('email-error').textContent = 'Please enter a valid email address.';
                e.preventDefault();
                return;
            }

            if (!passwordPattern.test(password.value)) {
                document.getElementById('password-error').textContent =
                    'Password must be at least 8 characters and include uppercase, lowercase, a number, and a symbol.';
                e.preventDefault();
                return;
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Show modals -->
    <?php if (!empty($signup_errors)): ?>
        <script>
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        </script>
    <?php endif; ?>

    <?php if ($signup_success): ?>
        <script>
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        </script>
    <?php endif; ?>


</body>

</html>