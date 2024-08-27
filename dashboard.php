<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
else {
    include 'conn.php';
    $sql = "SELECT * FROM stores";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>SNF | Dashboard</title>
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

                    
<div id="InvoiceContent" > 


    <?php
    if(isset($_SESSION['Status'])) {
        if($_SESSION['Status'] == 1) {
            echo '<div class="alert alert-success">'.$_SESSION['message'].'</div>';
        } else if($_SESSION['Status'] == 2) {
            echo '<div class="alert alert-danger">'.$_SESSION['message'].'</div>';
        }
        // Clear the session variables
        unset($_SESSION['Status']);
        unset($_SESSION['message']);
    }
    ?>



    <?php
        date_default_timezone_set('Asia/Karachi');
        $text = "Today's date is : "; 
        $today = date("d-F-Y g:i a");
        echo '<p style="text-align: center;" class="gradient-text-green">'. $text . $today . '</p>';
    ?>




    <div class="form-group row text-left" style="margin-top: 10px;">
        <div class="col-12 text-primary btn-group">
            <select class="form-control" name="SelectStore" id="SelectStore" style="border-radius: 0;">
            <option value="" disabled selected> Select Store </option>
                <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No store available</option>';
                    }
                ?>
            </select>
        </div>
    </div>










    <div>
        <input type="hidden" name="FormStoreId" id="FormStoreId">

        <div style=" padding: 15px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; border-radius: 0;">
            <div class="form-group row mb-2" style="margin-top: 20px;">
                <div class="col-4 text-left gradient-text-green">
                    <h6 class="pt-2"> Total </h6>
                </div>

                <div class="col-8">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> Rs </span>
                        </div>
                        <input type="text" name="total" id="total" class="form-control text-right" style="border-top-right-radius: .35rem; border-bottom-right-radius: .35rem;" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-2">
                <div class="col-4 text-left gradient-text-green">
                    <h6 class="pt-2"> Received </h6>
                </div>

                <div class="col-8">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> Rs </span>
                        </div>
                        <input type="text" name="received" id="received" class="form-control text-right" style="border-top-right-radius: .35rem; border-bottom-right-radius: .35rem;" required>
                    </div>
                </div>
            </div>


            <div class="row mb-2">
                <div class="col-12 text-right"> 
                    <button type="button" id="AddAmount" class="btn btn-primary bg-gradient-primary" style="border-radius: 0px;">
                        <i class="fas fa-fw fa-plus"></i>
                    </button>
                </div>
            </div> 

            <div class="form-group row text-left mb-2 pt-2">
                <div class="col-4 gradient-text-green">
                    <h6 class="pt-2"> Payable </h6>
                </div>

                <div class="col-8">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> Rs </span>
                        </div>
                        <input type="text" name="payable" id="payable" class="form-control text-right" style="border-top-right-radius: .35rem; border-bottom-right-radius: .35rem;" readonly required>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if($_SESSION['role'] == 1){ ?>
            <div style="display: flex; justify-content: space-between">
                <div style="text-align: right; padding: 10px 5px 0 0;"> <a href="register.php">Register</a> </div>
                <div style="text-align: right; padding: 10px 5px 0 0;"> <a href="add-store.php">Add Store</a> </div>
            </div>
        <?php 
            }
        ?>

        

        <button type="button" id="FormSubmitBtn" class="btn btn-block bg-gradient-success" style="margin-top: 40px; color: #fff; border: none;">
            SUBMIT
        </button>
    </div>


</div>



<p id="footer" style="text-align: center; color: #fff; padding: 10px; position: fixed; bottom: 0; 
    left: 0; right: 0; z-index: 1000;" class="bg-gradient-primary">
    Copyright © SNF Cosmetics 2024
</p>





<!-- Confirmation Modal Starts -->

<div id="ConfirmationModal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="container">
            <p class="gradient-text-green" id="ModalStoreName"></p>
            <h3 class="gradient-text-blue">Confirm Payment</h3>

            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <button type="button" class="ModalButton bg-gradient-success" id="CancelBtn"> Cancel </button>
                <button type="button" class="ModalButton bg-gradient-primary" id="ConfirmBtn"> Confirm </button>
            </div>
        </div>
    </div>
</div> 

<!-- Confirmation Modal Ends -->





<!-- Print Modal Starts -->
<div id="PrintModal" class="printmodal">
    <div class="print-modal-overlay"></div>
    <div class="print-modal-content">
        <div class="printcontainer" style="border: 1px solid #ccc;">

        
            <p id="PrintModalStoreName" style="margin-bottom: 20px;"> </p>


            <div style="margin: 10px; padding: 15px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; border-radius: 8px;">
               
                <div class="row mb-2" style="margin-top: 20px;">
                    <div class="col-4 text-left">
                        <h6 class="font-weight-bold pt-2"> Total </h6>
                    </div>

                    <div class="col-8" style="margin-top: 3px;">
                        <div style="display: block; border-bottom: 1px solid #d1d3e2; text-align: right;">
                            <span id="printtotal"></span>
                        </div>
                    </div>
                </div>


                <div class="row mb-2" style="margin-top: 15px;">
                    <div class="col-4 text-left">
                        <h6 class="font-weight-bold pt-2"> Received </h6>
                    </div>

                    <div class="col-8" style="margin-top: 3px;">
                        <div style="display: block; border-bottom: 1px solid #d1d3e2; text-align: right;">
                            <span>-</span>
                            <span id="printreceived"></span>
                        </div>
                    </div>
                </div>


                <div class="form-group row text-left mb-2 pt-2">
                    <div class="col-4">
                        <h6 class="font-weight-bold pt-2"> Payable </h6>
                    </div>

                    <div class="col-8" style="margin-top: 5px;">
                        <div style=" text-align: right;">
                            <span id="printpayable" class="text-right"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-around; margin-top: 30px;">
                <p> Received By </p>
                <p> <?php echo $_SESSION['name']; ?> </p>
            </div>

            <p id="ReceivedDate" style="text-align: center; margin-top: 20px;"></p>


        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <button type="button" class="PrintButton bg-gradient-primary" id="PrintCancelBtn">Close</button>
            <button type="button" class="PrintButton bg-gradient-success" id="PrintBtn"> Print </button>
        </div>
    </div>
</div> 

<!-- Print Modal Ends -->
















<script src="js/jquery-3.7.1.min.js"></script>
<script src="js/scripts.js"></script>

<script>

    document.getElementById('AddAmount').addEventListener('click', function() {
        var total = parseFloat(document.getElementById('total').value);
        var receivedValue = document.getElementById('received').value;
        
        if (receivedValue === "") {
            alert('Received amount field is empty');
            return;
        }
        
        var received = parseFloat(receivedValue);
        
        if (received > total) {
            alert('Received amount must be less than the total amount');
        } else {
            var payable = total - received;
            document.getElementById('payable').value = payable.toFixed(2);
        }
    });


    $(document).on('click', '#CancelBtn', function(){
        $('#ConfirmationModal').hide();
    });


    $(document).on('click', '#PrintBtn', function(){
        window.print();
    });

    $(document).on('click', '#PrintCancelBtn', function(){
        $('#PrintModal').hide();
        location.reload();
    });
    



    $(document).on('change', '#SelectStore', function() {
        $('#received').val('');
        $('#payable').val('');

        var StoreId = this.value;

        $('#FormStoreId').val(StoreId);
        
        if(StoreId) {
            $.ajax({
                url: 'SelectStore.php',
                type: 'POST',
                data: { StoreId: StoreId },
                dataType: 'json',
                success: function(response) {
                    if(response.Status == 1) {
                        $('#total').val(response.PaymentsData.payable);
                        $('#ModalStoreName').text(response.StoreData.name);
                    }
               
                    else if(response.Status == 2 || response.Status == 3 || response.Status == 5 || response.Status == 6 || response.Status == 7) {
                        alert(response.message);
                    } 
                    else {
                        alert('Something went wrong. Please try again later!');
                    }
                }
            });
        }
    });












    //////////////////////// Sumbit Form /////////////////////////////////

    $(document).on('click', '#FormSubmitBtn', function(){

        var total = $('#total').val();
        var received = $('#received').val();
        var payable = $('#payable').val();

        if(total === "" || received === "" || payable === "") {
            alert('Please fill in all required fields.');
            return;
        }

        $('#ConfirmationModal').show();
    });



    $(document).on('click', '#ConfirmBtn', function() {
        var FormStoreId = $('#FormStoreId').val();
        var total = $('#total').val();
        var received = $('#received').val();
        var payable = $('#payable').val();

        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: { 
                FormStoreId: FormStoreId, 
                total: total,
                received: received,
                payable: payable,
            },
            dataType: 'json',
            success: function(response) {

                if(response.Status == 4) {
                    $('#ConfirmationModal').hide();
                    
                    $('#PrintModalStoreName').text(response.StorePrintData.name);
                    $('#printtotal').text(response.PaymentPrintData.total);
                    $('#printreceived').text(response.PaymentPrintData.received);
                    $('#printpayable').text(response.PaymentPrintData.payable);

                    var created_at = new Date(response.PaymentPrintData.created_at);
                    var formattedDate = created_at.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
                    var formattedTime = created_at.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                    var formattedDateTime = formattedDate + ' ' + formattedTime;

                    $('#ReceivedDate').text(response.PaymentPrintData.created_at);

                    $('#PrintModal').show();
                } 
                else if(response.Status == 1 || response.Status == 2 || response.Status == 3 || response.Status == 5 || response.Status == 6 || response.Status == 7 || response.Status == 8) {
                    alert(response.message);
                } 
                else {
                    alert('Something went wrong. Please try again later!');
                }
            }
        });
    });






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
