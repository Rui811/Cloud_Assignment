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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        .swal2-confirm.btn-pink {
            background-color: #ff6b81 !important;
            color: white !important;
            font-weight: bold;
            border-radius: 30px !important;
            padding: 10px 30px !important;
            font-family: 'Poppins', sans-serif;
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
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                        data-bs-target="#forgotPasswordModal" style="color: #ff3b5c; font-size: 14px;">Forgot
                        Password?</a>

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

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <h5 class="modal-title text-center mb-3" id="forgotPasswordModalLabel" style="color: #ff6b81;">Forgot
                    Password</h5>
                <form id="forgotPasswordForm">
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Enter your registered email"
                            required>
                    </div>
                    <div class="mb-3 text-center">
                        <img src="generate_captcha.php" alt="CAPTCHA" id="captchaImage" class="img-fluid mb-2"
                            style="max-width: 100px; border-radius: 5px;">
                        <br>
                        <button type="button" onclick="refreshCaptcha()" class="btn btn-link text-secondary"
                            style="font-size: 14px;">↻ Refresh</button>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="captcha" class="form-control" placeholder="Enter CAPTCHA" required>
                    </div>
                    <button type="submit" class="btn btn-pink w-100" id="resetButton">Reset Now</button>
                </form>
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

    <script>
        function refreshCaptcha() {
            document.getElementById('captchaImage').src = 'generate_captcha.php?' + Date.now();
        }

        document.getElementById('forgotPasswordForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('cust_forgotPsw.php', {
                method: 'POST',
                body: formData
            }).then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
                        modal.hide();

                        Swal.fire({
                            title: '<span style="color:#ff6b81;">Reset Your Password</span>',
                            html:
                                '<input type="password" id="swal-password" class="swal2-input" placeholder="New Password" style="border-radius:10px; border:1.5px solid #ff6b81;">' +
                                '<input type="password" id="swal-confirm" class="swal2-input" placeholder="Confirm Password" style="border-radius:10px; border:1.5px solid #ff6b81;">' +
                                '<div style="font-size: 13px; color: #555; margin-top:5px;">Password must include uppercase, lowercase, number, and symbol.</div>',
                            background: '#fffafc',
                            confirmButtonText: 'Reset Password',
                            confirmButtonColor: '#ff6b81',
                            buttonsStyling: true,
                            customClass: {
                                popup: 'rounded-4',
                                confirmButton: 'btn btn-pink'
                            },
                            showCancelButton: false,
                            focusConfirm: false,
                            preConfirm: () => {
                                const pwd = document.getElementById('swal-password').value;
                                const confirm = document.getElementById('swal-confirm').value;
                                if (!pwd || !confirm) {
                                    Swal.showValidationMessage('Please fill both fields.');
                                    return false;
                                }
                                if (pwd !== confirm) {
                                    Swal.showValidationMessage('Passwords do not match.');
                                    return false;
                                }
                                return { password: pwd };
                            }
                        }).then(result => {
                            if (result.isConfirmed) {
                                fetch('reset_password_direct.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: 'new_password=' + encodeURIComponent(result.value.password)
                                })
                                    .then(res => res.json())
                                    .then(resData => {
                                        console.log("Reset response: ", resData); // helpful for debugging
                                        if (resData.status === 'success') {
                                            Swal.fire('Success!', 'Your password has been reset.', 'success');
                                        } else {
                                            Swal.fire('Error', resData.message, 'error');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Fetch error:', error);
                                        Swal.fire('Error', 'Something went wrong while resetting password.', 'error');
                                    });
                            }
                        });


                    } else {
                        Swal.fire('Error', data.message, 'error');
                        refreshCaptcha();
                    }
                });
        });


    </script>

</body>

</html>