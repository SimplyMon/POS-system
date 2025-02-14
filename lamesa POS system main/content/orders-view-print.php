<?php include 'partials/header.php'; ?>

<link rel="stylesheet" href="../css/order-print.css">


<div class="container">
    <div class="header">
        <h4>Print Orders
            <a href="history.php" class="btn btn-danger">Back</a>
        </h4>
    </div>
    <div class="body">
        <div id="myBillingArea">
            <?php
            if (isset($_GET['track'])) {
                $trackingNo = validate($_GET['track']);

                if ($trackingNo == '') {
            ?>
                    <div class="no-tracking text-center">
                        <h5>No tracking Found</h5>
                        <a href="orders.php" class="btn btn-primary">Go Back</a>
                    </div>
                <?php
                }

                $orderQuery = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id=o.customer_id AND tracking_no='$trackingNo' LIMIT 1";
                $orderQueryRes = mysqli_query($conn, $orderQuery);

                if (!$orderQueryRes) {
                    echo "<h5>Something went wrong</h5>";
                    return false;
                }

                if (mysqli_num_rows($orderQueryRes) > 0) {
                    $orderDataRow = mysqli_fetch_assoc($orderQueryRes);
                ?>
                    <table class="invoice-info">
                        <tbody>
                            <tr>
                                <td colspan="2" class="center">
                                    <h4>LaCocina Inasal</h4>
                                    <p>1911 Leon Guinto Street Corner Remedios Street, Malate, Manila</p>
                                    <p>Company LaCocina</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>Customer Details</h4>
                                    <p>Customer No: <?= $orderDataRow['name'] ?></p>

                                </td>
                                <td class="right">
                                    <h4>Invoice Details</h4>
                                    <p>Invoice no.: <?= $orderDataRow['invoice_no']; ?></p>
                                    <p>Invoice Date.: <?= date('d M Y') ?></p>
                                    <p>Taytay Rizal Manila City Sakbit Ave Marsst</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo "<h5>No data found</h5>";
                    return false;
                }

                $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, o.*, oi.*, p.* FROM orders o, order_items oi, products p WHERE oi.order_id=o.id AND p.id=oi.product_id AND o.tracking_no='$trackingNo'";
                $orderItemQueryRes = mysqli_query($conn, $orderItemQuery);

                if ($orderItemQueryRes) {
                    if (mysqli_num_rows($orderItemQueryRes) > 0) {
                    ?>
                        <div class="table-responsive">
                            <table class="product-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $totalAmount = 0;
                                    foreach ($orderItemQueryRes as $key => $row) :
                                        $totalAmount += $row['orderItemPrice'] * $row['orderItemQuantity'];
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $row['name']; ?></td>
                                            <td><?= number_format($row['orderItemPrice'], 0); ?></td>
                                            <td><?= $row['orderItemQuantity']; ?></td>
                                            <td class="bold"><?= number_format($row['orderItemPrice'] * $row['orderItemQuantity'], 0) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="4" class="bold">Grand Total</td>
                                        <td class="bold"><?= number_format($totalAmount, 0); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">Payment Mode: <b><?= $row['payment_mode']; ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                <?php
                    } else {
                        echo "<h5>No data found</h5>";
                        return false;
                    }
                } else {
                    echo "<h5>Something went wrong</h5>";
                    return false;
                }
            } else {
                ?>
                <div class="no-tracking text-center">
                    <h5>No tracking Parameter Found</h5>
                    <a href="orders.php" class="btn btn-primary">Go Back</a>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="actions">
            <button class="btn btn-info" onclick="printMyBillingArea()">Print</button>
            <button class="btn btn-primary" onclick="downloadPDF('<?= $orderDataRow['invoice_no']; ?>')">Download PDF</button>
        </div>
    </div>
</div>





<?php include 'partials/footer.php'; ?>