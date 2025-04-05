<!-- remember me funtion × -->
<!-- forget password funtion × -> need use ajax to prompt out the sweetalert box-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Log In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
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
        }
    </style>
</head>

<body class="d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row">
            <div class="col-md-5 ms-5">
                <div class="card shadow-sm login-container rounded-4">
                    <div class="card-body">
                        <h1 class="h3 mb-3 fw-normal text-center">Admin Login Portal</h1>
                        <form>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingInput" placeholder="name@gmail.com">
                                <label for="floatingInput">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                                <label for="floatingPassword">Password</label>
                            </div>
                            <div class="form-check text-start mb-3">
                                <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                            </div>
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
    $(document).ready(function () {
        //bug -> when user click on the forget password ,the border will change the position 
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
                    Swal.fire('Submitted!', email,'success');
                    //New password and Confirm New Password 
                }
            });
        });
    });
    </script>
</body>

</html>

