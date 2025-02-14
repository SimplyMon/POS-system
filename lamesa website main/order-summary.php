<?php require './config/function.php';

if (!isset($_SESSION['productItems'])) {
    echo '<script> window.location.href = "order.php";</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Link to AlertifyJS CSS for alert and notification styles -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/default.min.css" />
    <link rel="icon" type="image/x-icon" href="logos/lamesalogo.png">
    <link rel="stylesheet" href="css/order-summary.css">
    <title>LaMesa Inasal | MENU</title>
</head>


<body>
    <div class="home-container">
        <nav class="navigation-bar">
            <div class="logo">
                <a class="logo" href="index.php" style="text-decoration: none;">
                    <img src="logos/logo3.png" alt="" style="width: 50px; height: 100%; margin-left: 80px; margin-right:10px;">
                    <h1>La Cocina Inasal</h1>
                </a>
            </div>

            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="about.php">About Us</a>
                <a href="menu.php">Menu</a>
                <a class="btn" href="order.php">Order Now!</a>
            </div>
        </nav>

        <div class="main_container" style="margin-bottom: 200px;">
            <div class="header">
                <h4 class="title">Order Summary</h4>
                <a href="order.php" class="btn btn-danger">Back to Create Order</a>
            </div>
            <div class="body">
                <?php alertMessage(); ?>

                <div id="myBillingArea">
                    <?php
                    if (isset($_SESSION['name'])) {
                        $name = validate($_SESSION['name']);
                        $invoiceNo = validate($_SESSION['invoice_no']);

                        $customerQuery = mysqli_query($conn, "SELECT * FROM customers");
                        if ($customerQuery) {
                            $cRowData = mysqli_fetch_assoc($customerQuery);
                    ?>
                            <table class="billing-info">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="center">
                                            <h4 class="company-name">La Cocina Inasal</h4>
                                            <p class="address">1911 Leon Guinto Street Corner Remedios Street, Malate, Manila</p>
                                            <p class="address">Company Lacocina</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h4 class="section-title">Customer Information.</h4>
                                            <p class="detail">Customer No: <?= ($_SESSION['name']) ?></p>
                                        </td>
                                        <td class="end">
                                            <h4 class="section-title">Invoice Details</h4>
                                            <p class="detail">Invoice no.: <?= $invoiceNo; ?></p>
                                            <p class="detail">Invoice Date.: <?= date('d M Y') ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    <?php
                        }
                    }
                    ?>

                    <?php
                    if (isset($_SESSION['productItems'])) {
                        $sessionProducts = $_SESSION['productItems'];
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
                                    foreach ($sessionProducts as $key => $row) :
                                        $totalAmount += $row['price'] * $row['quantity'];
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $row['name']; ?></td>
                                            <td>₱<?= number_format($row['price'], 0); ?></td>
                                            <td><?= $row['quantity']; ?></td>
                                            <td class="fw-bold">₱<?= number_format($row['price'] * $row['quantity'], 0) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="4" class="bold">Grand Total</td>
                                        <td class="bold">₱<?= number_format($totalAmount, 0); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">Payment Mode: <b><?= $_SESSION['payment_mode']; ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php
                    } else {
                        echo '<h5 class="no-items">NO ITEMS ADDED</h5>';
                    }
                    ?>
                </div>

                <?php
                if (isset($_SESSION['productItems'])) :
                ?>
                    <div class="action-buttons">
                        <button type="button" class="btn btn-primary" id="saveOrder">Save</button>
                        <button class="btn btn-info" onclick="printMyBillingArea()">Print</button>
                        <button class="btn btn-warning" onclick="downloadPDF('<?= $_SESSION['invoice_no']; ?>')">Download PDF</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php
        include 'footer.php'
        ?>


        <!-- Include jQuery library -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- Include SweetAlert for alert messages -->
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

        <!-- Include AlertifyJS for alert and notification messages -->
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>

        <!-- Include html2canvas and jsPDF for PDF generation -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


        <script src="./js/orders.js"></script>




</body>

</html>