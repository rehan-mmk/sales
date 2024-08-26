<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['StoreId'])) {
        $StoreId = $_POST['StoreId'];
        
        $store = "SELECT * FROM stores WHERE id = ?";
        $payments = "SELECT * FROM payments WHERE store_id = ? ORDER BY id DESC LIMIT 1";

        if ($stmtstore = $conn->prepare($store)) {
            $stmtstore->bind_param("i", $StoreId);
            $stmtstore->execute();
            $StoreResult = $stmtstore->get_result();
            
            if ($StoreResult->num_rows > 0) {
                $StoreData = $StoreResult->fetch_assoc();
                $id = $StoreData["id"];
                
                if ($stmtpayments = $conn->prepare($payments)) {
                    $stmtpayments->bind_param("i", $id);
                    $stmtpayments->execute();
                    $PaymentsResult = $stmtpayments->get_result();
                    
                    if ($PaymentsResult->num_rows > 0) {
                        $PaymentsData = $PaymentsResult->fetch_assoc();

                        echo json_encode([
                            'Status' => 1,
                            'StoreData' => $StoreData,
                            'PaymentsData' => $PaymentsData,
                        ]);
                    } else {
                        echo json_encode([
                            'Status' => 2,
                            'message' => 'Payment record not found',
                        ]);
                    }
                    $stmtpayments->close();
                } else {
                    echo json_encode([
                        'Status' => 3,
                        'message' => 'Error preparing statement for payments query',
                    ]);
                }
            } else {
                echo json_encode([
                    'Status' => 4,
                    'message' => 'Store not found',
                ]);
            }
            $stmtstore->close();
        } else {
            echo json_encode([
                'Status' => 5,
                'message' => 'Error preparing statement for store query',
            ]);
        }
        
    } else {
        echo json_encode([
            'Status' => 6,
            'message' => 'StoreId not provided',
        ]);
    }

} else {
    echo json_encode([
        'Status' => 7,
        'message' => 'Invalid request method',
    ]);
}

$conn->close();


