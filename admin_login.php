<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Log In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
            background-image: url("image/adminBackground.jpg");
            background-size: cover;
            background-repeat: no-repeat;
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .login-container {
            animation: zoomIn 0.6s ease-out;
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border: none;
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-md-5 ms-5">
                <div class="card shadow-sm login-container rounded-4">
                    <div class="card-body">
                        <h1 class="h3 mb-3 fw-normal text-center">Admin Login Portal</h1>
                        <form id="adminLoginForm">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" placeholder="username@123"
                                    name="username">
                                <label for="floatingInput">Username</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingPassword" placeholder="Password"
                                    name="password">
                                <label for="floatingPassword">Password</label>
                            </div>
                            <!-- <div class="form-check text-start mb-3">
                                <input class="form-check-input" type="checkbox" value="remember-me"
                                    id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                            </div> -->
                            <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
                            <hr class="my-3">
                            <div class="text-center">
                                <a href="#" class="text-decoration-none forget_password"><i>Forget Password?</i></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let attempts = 0;

        $('#adminLoginForm').on('submit', function (e) {
            e.preventDefault();

            const username = $('#floatingInput').val();
            const password = $('#floatingPassword').val();

            $.ajax({
                url: "check_admin_login.php",
                method: "POST",
                data: {
                    "username": username,
                    "password": password
                },
                success: function (response) {
                    console.log("Server response:", response);
                    if (response.trim() === 'success') {
                        Swal.fire('Success', 'Login successful!', 'success')
                            .then(() => window.location.href = 'admin.php');
                    } else {
                        attempts++;
                        Swal.fire('Error', 'Invalid username or password', 'error');
                        if (attempts >= 5) {
                            Swal.fire({
                                title: 'Too many attempts',
                                text: 'Have you forgotten your password?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, reset it',
                                cancelButtonText: 'No, try again'
                            }).then((res) => {
                                if (res.isConfirmed) {
                                    $('.forget_password').trigger('click');
                                }
                                attempts = 0;
                            });
                        }

                    }
                }
            });
        });

        $(document).ready(function () {
            $('.forget_password').on('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Forgot Password',
                    text: 'Please enter your email address:',
                    input: 'email',
                    inputPlaceholder: 'name@example.com',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Email is required!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const email = result.value;
                        $.ajax({
                            url: 'check_admin_email.php',
                            method: 'POST',
                            data: { "email": email },
                            success: function (res) {
                                if (res.trim() === 'found') {
                                    Swal.fire({
                                        title: 'Reset Password',
                                        html: `
                                                <div style="margin-bottom: 10px;">
                                                    <input type="password" id="newPassword" class="swal2-input" placeholder="New Password" style="padding-left: 10px; border-radius: 10px;">
                                                </div>
                                                <div style="margin-bottom: 10px;">
                                                    <input type="password" id="confirmPassword" class="swal2-input" 
                                                        placeholder="Confirm Password" 
                                                        style="padding-left: 10px; border-radius: 10px;">
                                                </div>
                                                <div id="validationError" 
                                                    style="background: #ffecec; color: #cc0000; 
                                                            padding: 10px; border-radius: 8px; 
                                                            font-size: 13px; line-height: 1.4; 
                                                            margin-top: 10px; display: none; text-align: left;">
                                                </div>
                                            `,
                                        focusConfirm: false,
                                        showCancelButton: true,
                                        preConfirm: () => {
                                            const newPassword = document.getElementById('newPassword').value;
                                            const confirmPassword = document.getElementById('confirmPassword').value;
                                            const errorDiv = document.getElementById('validationError');
                                            errorDiv.innerHTML = '';
                                            errorDiv.style.display = 'none';

                                            if (!newPassword || !confirmPassword) {
                                                Swal.showValidationMessage('Both fields are required!');
                                            } else if (newPassword !== confirmPassword) {
                                                Swal.showValidationMessage('Passwords do not match!');
                                            }

                                            let errors = [];
                                            if (newPassword.length < 8) {
                                                errors.push('❌  Password must be at least 8 characters long.');
                                            }
                                            if (!/[A-Z]/.test(newPassword)) {
                                                errors.push('❌  Include at least one uppercase letter.');
                                            }
                                            if (!/[a-z]/.test(newPassword)) {
                                                errors.push('❌  Include at least one lowercase letter.');
                                            }
                                            if (!/[0-9]/.test(newPassword)) {
                                                errors.push('❌  Include at least one number.');
                                            }
                                            if (!/[^a-zA-Z0-9]/.test(newPassword)) {
                                                errors.push('❌  Include at least one special character.');
                                            }
                                            if (errors.length > 0) {
                                                errorDiv.innerHTML = errors.join('<br>');
                                                errorDiv.style.display = 'block';
                                                return false; // prevent modal from closing
                                            }

                                            return { newPassword, confirmPassword };
                                        }
                                    }).then((passResult) => {
                                        if (passResult.isConfirmed) {
                                            //send new password 
                                            $.post('reset_password.php', {
                                                email: email,
                                                newPassword: passResult.value.newPassword
                                            }, function (result) {
                                                if (result.trim() === 'success') {
                                                    Swal.fire('Success', 'Password has been reset.', 'success');
                                                } else {
                                                    Swal.fire('Error', 'Failed to reset password.', 'error');
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    Swal.fire('Invalid email', 'This email does not exist.', 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Error', 'Server error occurred.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>