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








} 
else {
    echo json_encode([
        'Status' => 8,
        'message' => 'Invalid request method',
    ]);
}

$conn->close();











