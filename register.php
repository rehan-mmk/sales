<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
if($_SESSION['role'] !== 1) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SNF | Register</title>
    <link href="images/snf.png" rel="icon">
    
    <link href="css/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/index.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/navbar.css" rel="stylesheet">

    <style>
        .form-control:focus {
            border-top: 0;
            border-left: 0;
            border-right: 0;
            border-bottom: 1px solid #009E60; /* Keep the bottom border same on focus */
            box-shadow: none; /* Remove the default focus shadow */
            outline: none; /* Remove the default outline */
        }

        .input-group-text {
            cursor: pointer;
            border: none;
            background: transparent;
        }

        .message {
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }
        .fade-out {
            opacity: 0;
        }

    </style>
</head>
<body>
             
<nav id="navbar" class="navbar navbar-expand bg-gradient-primary topbar mb-4" style="color: #fff;">

    <a href="dashboard.php" class="sidebar-brand d-flex align-items-center justify-content-center"  style="text-decoration: none; font-size: 18px; font-weight: bold; color: #fff;">
        <div class="sidebar-brand-text ml-3">SNF</div>
    </a>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown">
            <a class="nav-link" href="#" role="button">
                <span class="mr-2 d-none d-lg-inline small" style="color: #fff;">
                    <?php echo $_SESSION['name']; ?>
                </span>
                <img onclick="myFunction()" class=" dropbtn img-profile rounded-circle" src="images/1498967277.jpg">
            </a>

            <!-- Dropdown - User Information -->
            <div id="myDropdown" class="dropdown-content">
                <a class="dropdown-item" href="change-password.php" style="margin-top: 10px;">
                    <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i> Change Password
                </a>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="logout.php" style="display: block; text-align: center;">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                </a>
            </div>
        </li>
    </ul>

</nav>






<div id="InvoiceContent" style="padding-top: 0;"> 

    <h1 style="text-align: center; margin: 10px 0 20px 0;" class="gradient-text-green">Register</h1>
    <?php
        if (isset($_SESSION['success'])) {
            echo '<p class="message" style="color: green; text-align: center;">'.$_SESSION['success'].'</p>';
            unset($_SESSION['success']);
        }
    ?>
    <div style="margin-bottom: 40px;"></div>

    <form method="post" action="auth.php" class="form">

        <div class="form-group row" style="margin-top: 20px; margin-bottom: 20px;">
            <div class="col-4 text-left gradient-text-green">
                <h6 style="padding-top: 20px;"> Full Name </h6>
            </div>

            <div class="col-8">
                <div class="input-group mb-2">
                    <input type="text" name="fullname" value="<?php if (isset($_SESSION['old_name'])) { echo htmlspecialchars($_SESSION['old_name']); unset($_SESSION['old_name']); } ?>" class="form-control" style="border-top: 0; border-left: 0; border-right: 0;" required>
                    <i style="position: absolute; right: 5px; bottom: 5px;" class="fas fa-fw fa-user"></i> 
                </div>
            </div>
        </div>

        <div class="form-group row" style="margin-top: 20px; margin-bottom: 20px;">
            <div class="col-4 text-left gradient-text-green">
                <h6 style="padding-top: 20px;"> Email </h6>
            </div>

            <div class="col-8">
                <div class="input-group mb-2">
                    <input type="email" name="email" value="<?php if (isset($_SESSION['old_email'])) { echo htmlspecialchars($_SESSION['old_email']); unset($_SESSION['old_email']); } ?>" class="form-control" style="border-top: 0; border-left: 0; border-right: 0;" required>
                    <i style="position: absolute; right: 5px; bottom: 5px;" class="fas fa-fw fa-envelope"></i> 
                </div>
            </div>
        </div>

        <div class="form-group row mb-2">
            <div class="col-4 text-left gradient-text-green">
                <h6 style="padding-top: 20px;"> Password </h6>
            </div>

            <div class="col-8">
                <div class="input-group mb-2">
                    <input type="password" name="password"  id="password" value="<?php if (isset($_SESSION['old_password'])) { echo htmlspecialchars($_SESSION['old_password']); unset($_SESSION['old_password']); } ?>" class="form-control" style="border-top: 0; border-left: 0; border-right: 0;" required>
                    <i id="togglePassword" style="position: absolute; right: 5px; bottom: 5px; cursor: pointer;" class="fas fa-fw fa-eye-slash"></i> 
                    <i id="togglePasswordVisible" style="display:none; position: absolute; right: 5px; bottom: 5px; cursor: pointer;" class="fas fa-fw fa-eye"></i> 
                </div>
            </div>
        </div>     
        
         
        
        <div class="form-group row" style="margin-top: 20px; margin-bottom: 20px;">
            <div class="col-4 text-left gradient-text-green">
                <h6 style="padding-top: 20px;"> Role </h6>
            </div>

            <div class="col-8" style="margin-top: 15px;">
                <select class="form-control" name="role" style="border-radius: 0;" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="1">Admin</option>
                    <option value="2">Salesman</option>
                </select>
            </div>
        </div>

        <button type="submit" name="submit" value="register" class="btn btn-block bg-gradient-success" style="margin: 60px 0; color: #fff; border: none; border-radius: 0; font-size: 22px;">
            Register
        </button>
    </form>


</div>



<p id="footer" style="text-align: center; color: #fff; padding: 10px; position: fixed; bottom: 0; 
    left: 0; right: 0;" class="bg-gradient-primary">
    Copyright © SNF Cosmetics 2024
</p>






<script src="js/jquery-3.7.1.min.js"></script>
<script src="js/scripts.js"></script>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordVisible = document.getElementById('togglePasswordVisible');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        togglePassword.style.display = 'none';
        togglePasswordVisible.style.display = 'block';
    } else {
        passwordField.type = 'password';
    }
});

document.getElementById('togglePasswordVisible').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordVisible = document.getElementById('togglePasswordVisible');

    if (passwordField.type === 'text') {
        passwordField.type = 'password';
        togglePassword.style.display = 'block';
        togglePasswordVisible.style.display = 'none';
    } else {
        passwordField.type = 'text';
    }
});




document.addEventListener("DOMContentLoaded", function() {
    var messages = document.getElementsByClassName('message');
    Array.from(messages).forEach(function(message) {
        setTimeout(function() {
            message.classList.add('fade-out');
        }, 4000);

        message.addEventListener('transitionend', function() {
            message.style.display = 'none';
        });
    });
});

</script>




</body>
</html>