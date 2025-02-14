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

        if ($orderStatus === 'canceled') {
            echo "Order cannot be set to completed as it is already canceled.";
            redirect('history-counter.php', 'Order cannot be set to completed as it is already canceled.');
        } else {
            // Proceed to update the order status to completed
            $sql = "UPDATE orders SET order_status = 'completed' WHERE tracking_no = ?";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $trackingNo);

                if ($stmt->execute()) {
                    echo "Order status updated to completed.";
                    redirect('history-counter.php', 'Order status updated to completed.');
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
