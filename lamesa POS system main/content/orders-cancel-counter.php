<?php
include '../config/function.php';

// handle cancel orders
if (isset($_GET['track'])) {
    $trackingNo = $_GET['track'];

    $sql = "UPDATE orders SET order_status = 'canceled' WHERE tracking_no = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $trackingNo);

        if ($stmt->execute()) {
            echo "Order status updated to cancel.";
            redirect('history-counter.php', 'No product found');
        } else {
            echo "Error updating order status.";
        }

        $stmt->close();
    } else {
        echo "Error preparing the SQL statement.";
    }

    $conn->close();
} else {
    echo "Tracking number not provided.";
}
