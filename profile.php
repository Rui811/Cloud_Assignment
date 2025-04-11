<?php
include 'profile_helper.php'; 
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url("image/profile_background.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 102vh;
            margin: 0;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 250, 250, 0.81);
            z-index: -1;
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
            width: 90%;
            max-width: 1100px;
            margin: 5% auto;
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .profile-section {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .profile-image {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #333;
        }

        .box {
            flex: 1;
            background-color: rgba(249, 206, 206, 0.8);
            border-radius: 15px;
            padding: 20px;
            position: relative;
            min-height: 180px;
        }

        .settings {
            background-color: rgba(229, 209, 243, 0.66);
            flex: 0 0 30%;
            max-width: 300px;
        }

        .orders {
            background-color: rgba(250, 207, 239, 0.69);
        }

        .label-tag {
            position: absolute;
            top: -15px;
            left: 20px;
            background-color: #fff;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid #ddd;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
            margin-top: 10px;
        }

        ul li {
            margin-bottom: 10px;
        }

        ul li button {
            width: 100%;
            padding: 10px;
            background-color:rgb(255, 255, 255);
            color: grey;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            text-align: center;
            transition: background-color 0.3s;
        }

        ul li button:hover {
            background-color:rgba(238, 182, 231, 0.85);
        }

        .logout-icon {
            position: absolute;
            bottom: 80px;
            right: 150px;
            font-size: 26px;
            background-color: rgb(255, 209, 216);
            color: #fff;
            border-radius: 100%;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #666;
        }

        @media (max-width: 768px) {
            .profile-section {
                flex-direction: column;
                align-items: center;
            }

            .box {
                width: 100%;
            }

            .profile-image {
                margin-bottom: 20px;
            }
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
        <div class="profile-section">
            <img src="image/cust_pic1.jpg" alt="Profile Image" class="profile-image">

            <div class="box profile-details">
                <div class="label-tag">PROFILE DETAILS</div>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['cust_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['cust_email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['cust_phone']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['cust_address']); ?></p>
            </div>
        </div>

        <div class="profile-section" style="margin-top: 30px;">
            <div class="box settings">
                <div class="label-tag">SETTINGS</div>
                <ul>
                    <li><button onclick="window.location.href='#'">Edit Profile</button></li>
                    <li><button onclick="window.location.href='#'">Change Password</button></li>
                    <li><button onclick="window.location.href='#'">Delete Account</button></li>
                </ul>
            </div>

            <div class="box orders">
                <div class="label-tag">ORDERS</div>
                <p>No recent orders yet.</p>
            </div>
        </div>

    </div>

    <div class="logout-icon" onclick="window.location.href='logout.php';">
        <i class="fas fa-sign-out-alt"></i>
    </div>

    <footer>
        &copy; 2025 Chapalang Graduation Gifts | Made with ❤️
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>