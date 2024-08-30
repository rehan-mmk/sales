<?php
session_start();

if (isset($_SESSION['email'])) {
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
    <title>SNF | Forget Password</title>
    <link href="images/snf.png" rel="icon">
    
    <link href="css/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/index.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">


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



<div id="InvoiceContent" style="padding-top: 0;"> 

    <h1 style="text-align: center; margin: 10px 0 20px 0;" class="gradient-text-green">Forget Password</h1>
    <?php
        if (isset($_SESSION['success'])) {
            echo '<p class="message" style="color: green; text-align: center;">'.$_SESSION['success'].'</p>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['failed'])) {
            echo '<p class="message" style="color: red; text-align: center;">'.$_SESSION['failed'].'</p>';
            unset($_SESSION['failed']);
        }
        if (isset($_SESSION['usererror'])) {
            echo '<p class="message" style="color: red; text-align: center;">'.$_SESSION['usererror'].'</p>';
            unset($_SESSION['usererror']);
        }
    ?>
    <div style="margin-bottom: 40px;"></div>

    <form method="post" action="auth.php" class="form">

        
        <div class="form-group row mb-2" style="margin-top: 20px;">
            <div class="col-12 text-left gradient-text-green">
                <h6 class="pt-2"> Email </h6>
            </div>

            <div class="col-12" style="margin-top: 5px;">
                <div class="input-group mb-2">
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>
        </div>
         
        <a href="index.php" style="display: block; text-align: right; margin: 40px 0 20px 0;"> Login </a>


        <button type="submit" name="submit" value="sendpasswordmail" class="btn btn-block bg-gradient-success" style="color: #fff; border: none; border-radius: 0; font-size: 22px;">
            Send OTP
        </button>
    </form>


</div>




<script>


    document.addEventListener("DOMContentLoaded", function() {
        var messages = document.getElementsByClassName('message');
        Array.from(messages).forEach(function(message) {
            setTimeout(function() {
                message.classList.add('fade-out');
            }, 5000);

            message.addEventListener('transitionend', function() {
                message.style.display = 'none';
            });
        });
    });
</script>




</body>
</html>