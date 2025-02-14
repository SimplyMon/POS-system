<?php
include '../config/function.php';

if (isset($_GET['track'])) {
    $trackingNo = $_GET['track'];

    // Check the current order status
    $checkStatusSql = "SELECT order_status FROM orders WHERE tracking_no = ?";
    if ($checkStmt = $conn->prepare($checkStatusSql)) {
        $checkStmt->bind_param("s", $trackingNo);
        $checkStmt->execute();
        $checkStmt->bind_result($orderStatus);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($orderStatus === 'completed') {
            echo "Order cannot be set to canceled as it is already completed.";
            redirect('history.php', 'Order cannot be set to canceled as it is already completed.');
        } else {
            // Proceed to update the order status to canceled
            $sql = "UPDATE orders SET order_status = 'canceled' WHERE tracking_no = ?";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $trackingNo);

                if ($stmt->execute()) {
                    echo "Order status updated to canceled.";
                    redirect('history.php', 'Order status updated to canceled.');
                } else {
                    echo "Error updating order status.";
                }

                $stmt->close();
            } else {
                echo "Error preparing the SQL statement.";
            }
        }
    } else {
        echo "Error preparing the SQL statement to check order status.";
    }

    $conn->close();
} else {
    echo "Tracking number not provided.";
}
?>
