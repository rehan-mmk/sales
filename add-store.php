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

    <title>SNF | Add Store</title>
    <link href="images/snf.png" rel="icon">
    <link href="css/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/index.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/navbar.css" rel="stylesheet">
    <link href="css/modal.css" rel="stylesheet">
    <link href="css/printmodal.css" rel="stylesheet">
    <link href="css/print.css" rel="stylesheet" media="print">

    <style>
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
        <div class="sidebar-brand-text ml-3"> Dashboard </div>
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

                    
<div id="InvoiceContent" style="padding-top: 10px;"> 

    <h1 style="text-align: center; margin: 0 0 40px 0;" class="gradient-text-green">Add Store</h1>
    <?php
        if (isset($_SESSION['Status']) && $_SESSION['Status'] == 1) {
            echo '<div style="color: green; text-align: center; margin-bottom: 30px; font-weight: bold;">'.$_SESSION['message'].'</div>';
            unset($_SESSION['Status']);
            unset($_SESSION['message']);
        }
    ?>

    <form method="POST" action="process.php">
      

        <div class="form-group row mb-2" style="margin-top: 20px;">
            <div class="col-4 text-left gradient-text-green">
                <h6 class="pt-2"> Name </h6>
            </div>

            <div class="col-8">
                <div class="input-group mb-2">
                    <input type="text" name="StoreName" class="form-control" value="<?php echo isset($_POST['StoreName']) ? htmlspecialchars($_POST['StoreName']) : ''; ?>">
                </div>
                <?php
                    if (isset($_SESSION['Status']) && $_SESSION['Status'] == 2) {
                        echo '<div style="color: red;">'.$_SESSION['message'].'</div>';
                        unset($_SESSION['Status']);
                        unset($_SESSION['message']);
                    }
                ?>
            </div>
        </div>


        <p style="width: 100%; text-align: center; margin: 10px 0">
            ---- Payment Details ----
        </p>


        <div class="form-group row mb-2" style="margin-top: 20px;">
            <div class="col-4 text-left gradient-text-green">
                <h6 class="pt-2"> Total </h6>
            </div>

            <div class="col-8">
                <div class="input-group mb-2">
                    <input type="text" name="total" class="form-control">
                </div>
            </div>
        </div>



        <div class="form-group row mb-2" style="margin-top: 20px;">
            <div class="col-4 text-left gradient-text-green">
                <h6 class="pt-2"> Received </h6>
            </div>

            <div class="col-8">
                <div class="input-group mb-2">
                    <input type="text" name="received" class="form-control">
                </div>
            </div>
        </div>



        <div class="form-group row mb-2" style="margin-top: 20px;">
            <div class="col-4 text-left gradient-text-green">
                <h6 class="pt-2"> Payable </h6>
            </div>

            <div class="col-8">
                <div class="input-group mb-2">
                    <input type="text" name="payable" class="form-control">
                </div>
            </div>
        </div>

 


        <button type="submit" name="submit" value="addstore" class="btn btn-block bg-gradient-success" style="margin-top: 40px; color: #fff; border: none;">
            Add Store
        </button>
    </form>


</div>



<p id="footer" style="text-align: center; color: #fff; padding: 10px; position: fixed; bottom: 0; 
    left: 0; right: 0; z-index: 1000;" class="bg-gradient-primary">
    Copyright © SNF Cosmetics 2024
</p>















<script src="js/jquery-3.7.1.min.js"></script>
<script src="js/scripts.js"></script>

<script>



    document.addEventListener("DOMContentLoaded", function() {
        var messages = document.getElementsByClassName('message');
        Array.from(messages).forEach(function(message) {
            setTimeout(function() {
                message.classList.add('fade-out');
            }, 3000);

            message.addEventListener('transitionend', function() {
                message.style.display = 'none';
            });
        });
    });
</script>
</body>
</html>
