<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    date_default_timezone_set('Asia/Karachi');
    $today = date("d-F-Y g:i a");



    ////////////////////////  login  ////////////////////////////
    
    if (isset($_POST['submit']) && $_POST['submit'] === 'Login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $user['fullname'];
                $_SESSION['role'] = $user['role_id'];
                header("Location: dashboard.php");
                exit();
            } else {
                $_SESSION['old_email'] = $email;
                $_SESSION['old_password'] = $password;
                $_SESSION['error'] = "Password or Email is incorrect";
            }
        } else {
            $_SESSION['old_email'] = $email;
            $_SESSION['old_password'] = $password;
            $_SESSION['error'] = "Password or Email is incorrect";
        }
        $stmt->close();
        
        header("Location: index.php");
        exit();
    }



    //////////////////////////////   Register   ////////////////////////////////////

    if (isset($_POST['submit']) && $_POST['submit'] === 'register') {
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $fullname = $_POST['fullname'];
        $role = $_POST['role'];

        $sql = "INSERT INTO users (email, password, fullname, role_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $email, $password, $fullname, $role, $today, $today);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Staff Registered Successfully";
            header("Location: register.php");
            exit();
        } else {
            $_SESSION['old_name'] = $fullname;
            $_SESSION['old_email'] = $email;
            $_SESSION['old_password'] = $password;
            header("Location: register.php");
            exit();
        }

        $stmt->close();
    }
















    // Change Password
    if (isset($_POST['submit']) && $_POST['submit'] === 'changepassword') {
        $current_password = $_POST['CurrentPassword'];
        $new_password = $_POST['NewPassword'];
        $confirm_password = $_POST['ConfirmNewPassword'];
        $email = $_SESSION['email'];

        if ($new_password !== $confirm_password) {
            $_SESSION['current_password'] = $current_password;
            $_SESSION['new_password'] = $new_password;
            $_SESSION['confirm_password'] = $confirm_password;
            $_SESSION['error'] = "New password and confirm New Password must match";
            header("Location: change-password.php");
            exit();
        }

        $sql = "SELECT password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($current_password, $user['password'])) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE users SET password = ?, updated_at = ? WHERE email = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("sss", $new_hashed_password, $today, $email);

                if ($update_stmt->execute()) {
                    $_SESSION['success'] = "Password changed successfully";
                    header("Location: change-password.php");
                    exit();
                } else {
                    $_SESSION['failed'] = "Failed to change the password. Please try again.";
                }
                $update_stmt->close();
            } 
            else {
                $_SESSION['current_password'] = $current_password;
                $_SESSION['new_password'] = $new_password;
                $_SESSION['confirm_password'] = $confirm_password;
                $_SESSION['currentpassword'] = "Current password is incorrect";
            }
        } else {
            $_SESSION['usererror'] = "User not found";
        }
        $stmt->close();
        
        header("Location: change-password.php");
        exit();
    }
































    if (isset($_POST['submit']) && $_POST['submit'] == 'sendpasswordmail') {
        $email = $_POST['email'];

        // Check if email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email exists, generate OTP
            $otp = rand(100000, 999999);

            // Save OTP in database for the user
            $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_created_at = NOW() WHERE email = ?");
            $stmt->bind_param("is", $otp, $email);
            if ($stmt->execute()) {

                $_SESSION['reset_email'] = $email;

                // Send OTP to the email
                $subject = "Your OTP Code";
                $message = "Your OTP code is $otp. This code will expire in 10 minutes.";
                $headers = "From: rehanullahk03@gmail.com";

                if (mail($email, $subject, $message, $headers)) {
                    // $_SESSION['success'] = "OTP has been sent to your email.";
                    header("Location: verify-otp.php");
                    exit();
                } else {
                    $_SESSION['failed'] = "Failed to send OTP. Please try again.";
                }
            } else {
                $_SESSION['failed'] = "Failed to generate OTP. Please try again.";
            }
        } else {
            // Email does not exist
            $_SESSION['usererror'] = "Email does not exist.";
        }

        $stmt->close();
        $conn->close();

        $_SESSION['old_email'] = $email; // Retain the email in the form
        header("Location: forget-password.php"); // Redirect back to the forget password page
        exit();
    }



















    // Verify OTP
    if (isset($_POST['submit']) && $_POST['submit'] === 'verifyotp') {
        $entered_otp = $_POST['otp'];

        // Get the email from the session
        $email = $_SESSION['reset_email'];

        $stmt = $conn->prepare("SELECT otp, otp_created_at FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($otp, $otp_created_at);
        $stmt->fetch();
        $stmt->close();

        if ($otp) {
            // Check if OTP matches and is within the 10-minute time limit
            $current_time = new DateTime();
            $otp_time = new DateTime($otp_created_at);
            $interval = $otp_time->diff($current_time);

            if ($otp == $entered_otp && $interval->i <= 10) {
                // OTP is valid
                $_SESSION['success'] = "OTP verified successfully!";
                // Proceed to allow the user to reset their password or redirect them to the password reset page
                header("Location: reset-password.php");
                exit();
            } else {
                $_SESSION['failed'] = "Invalid or expired OTP. Please try again.";
            }
        } else {
            $_SESSION['failed'] = "No OTP found for this email. Please request a new one.";
        }

        header("Location: verify-otp.php");
        exit();
    }













    if (isset($_POST['submit']) && $_POST['submit'] === 'resetpassword') {
        // Make sure the user has gone through OTP verification
        if (!isset($_SESSION['reset_email'])) {
            $_SESSION['error'] = "Unauthorized access.";
            header("Location: index.php");
            exit();
        }
    
        $new_password = $_POST['NewPassword'];
        $confirm_password = $_POST['ConfirmNewPassword'];
        $email = $_SESSION['reset_email']; // Email saved after OTP verification
    
        // Check if new password and confirm password match
        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = "New password and confirm password do not match.";
            header("Location: reset-password.php");
            exit();
        }
    
        // Hash the new password
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
        // Update the user's password in the database
        $sql = "UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_hashed_password, $email);
    
        if ($stmt->execute()) {
            unset($_SESSION['reset_email']); // Clear the email from the session
            header("Location: index.php");
            exit();
        } else {
            // If there was an error during the update
            $_SESSION['error'] = "Failed to reset password. Please try again.";
            header("Location: reset-password.php");
            exit();
        }
    
        $stmt->close();
        $conn->close();
    }









} 
else {
    echo json_encode([
        'Status' => 8,
        'message' => 'Invalid request method',
    ]);
}

$conn->close();











