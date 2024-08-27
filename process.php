<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    date_default_timezone_set('Asia/Karachi');
    $today = date("d-F-Y g:i a");


    ///////////////////////////////// Add Dashboard Payments 
    if(isset($_POST['FormStoreId'])) {
        $StoreId = $_POST['FormStoreId'];
        $total = (float) $_POST['total'];
        $received = (float) $_POST['received'];
        $payable = (float) $_POST['payable'];


        $sql = "INSERT INTO payments (store_id, total, received, payable, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo json_encode([
                'Status' => 1,
                'message' => 'Error in preparing the SQL statement: ' . $conn->error,
            ]);
            exit;
        }

        $stmt->bind_param("idddss", $StoreId, $total, $received, $payable, $today, $today);

        if ($stmt->execute()) {
            $stmt->close(); // Close the statement after execution

            $store = "SELECT * FROM stores WHERE id = ?";
            $stmtstore = $conn->prepare($store);
            
            if (!$stmtstore) {
                echo json_encode([
                    'Status' => 2,
                    'message' => 'Error in preparing the SQL statement: ' . $conn->error,
                ]);
                exit;
            }

            $stmtstore->bind_param("i", $StoreId);
            $stmtstore->execute();
            $StoreResult = $stmtstore->get_result();

            if ($StoreResult->num_rows > 0) {
                $StorePrintData = $StoreResult->fetch_assoc();
                $id = $StorePrintData["id"];

                $payments_query = "SELECT * FROM payments WHERE store_id = ? ORDER BY id DESC LIMIT 1";
                $stmt_payments = $conn->prepare($payments_query);

                if (!$stmt_payments) {
                    echo json_encode([
                        'Status' => 3,
                        'message' => 'Error in preparing the SQL statement: ' . $conn->error,
                    ]);
                    exit;
                }

                $stmt_payments->bind_param("i", $id);
                $stmt_payments->execute();
                $payments_result = $stmt_payments->get_result();

                if ($payments_result->num_rows > 0) {
                    $last_payment_data = $payments_result->fetch_assoc();

                    echo json_encode([
                        'Status' => 4,
                        'StorePrintData' => $StorePrintData,
                        'PaymentPrintData' => $last_payment_data,
                    ]);
                } else {
                    echo json_encode([
                        'Status' => 5,
                        'message' => 'Payment added successfully, but no payment records found for the store',
                    ]);
                }

                $stmt_payments->close();
            } else {
                echo json_encode([
                    'Status' => 6,
                    'message' => 'Store not found',
                ]);
            }
            
            $stmtstore->close();
        } else {
            echo json_encode([
                'Status' => 7,
                'message' => 'Payment not added! Please try again later',
            ]);
        }
    }
















    ////////////////////////////////////// Add Store
    if (isset($_POST['submit']) && $_POST['submit'] === 'addstore') {

        $name = trim($_POST['StoreName']);
        $total = (float) $_POST['total'];
        $received = (float) $_POST['received'];
        $payable = (float) $_POST['payable'];

        if (empty($name)) {
            $_SESSION['Status'] = 2;
            $_SESSION['message'] = "Store name is required.";
            header("Location: add-store.php");
            exit();
        }

        $conn->begin_transaction(); 

        try {
            $sql = "INSERT INTO stores (name, created_at, updated_at) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception('Error in preparing the SQL statement: ' . $conn->error);
            }
            $stmt->bind_param("sss", $name, $today, $today);
            if (!$stmt->execute()) {
                throw new Exception('Error executing the SQL statement: ' . $stmt->error);
            }
            $storeId = $stmt->insert_id; // Get the last inserted store ID
            $stmt->close();

            // Insert into payments table
            $sql = "INSERT INTO payments (store_id, total, received, payable, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception('Error in preparing the SQL statement: ' . $conn->error);
            }
            $stmt->bind_param("idddss", $storeId, $total, $received, $payable, $today, $today);
            if (!$stmt->execute()) {
                throw new Exception('Error executing the SQL statement: ' . $stmt->error);
            }
            $stmt->close();

            $conn->commit(); // Commit the transaction

            $_SESSION['Status'] = 1;
            $_SESSION['message'] = "Store and payment details added successfully.";

        } 
        catch (Exception $e) {
            $conn->rollback(); // Roll back the transaction on error
            $_SESSION['Status'] = 2;
            $_SESSION['message'] = "Failed to add store and payment details. Please try again.";
        }

        header("Location: add-store.php");
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











