<?php include 'partials/header.php';
if (!isset($_SESSION['productItems'])) {
    echo '<script> window.location.href = "order-create.php";</script>';
}
?>

<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="../css/order-summary.css">
<style>
    .dashboard_sidebar_profile {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        background-color: rgba(0, 0, 0, 0.23);
        padding: 25px 35px;
        margin-top: 295px;
    }
</style>

<body>
    <div class="dashboard_container">
        <div class="dashboard_sidebar" id="dashboard_sidebar">
            <div class="dashboard_sidebar_logo" id="logo_dashs">
                <img src="../pictures/logo.png" alt="">
                <p class="title_dash">La Cocina Inasal</p>
            </div>
            <div class="dashboard_sidebar_content">
                <ul class="dashboard_sidebar_list" id="dashboard_sidebar_list">
                    <div id="hamburger">
                        <a href="" id="hamburger_button"><i class="fa-solid fa-bars"></i></a>
                    </div>
                    <div class="features">
                        <p>Features</p>
                    </div>
                    <li style="background-color: #ffffff3f;">
                        <a href="order.php"><i class="fa-solid fa-list"></i><span class="Menutext">Order</span></a>
                    </li>
                    <li>
                        <a href="history-counter.php"><i class="fa-solid fa-clock-rotate-left"></i><span class="Menutext">History</span></a>
                    </li>
                </ul>
            </div>
            <div class="profile-txt">
                <p>Profile</p>
            </div>
            <div class="logout">
                <a href="../logout.php" id="logout_button"><i class="fa-solid fa-right-from-bracket"></i><span class="Menutext">Logout</span></a>
            </div>
            <div class="dashboard_sidebar_profile">
                <img src='../<?= ($_SESSION['loggedInUser']['image']) ?>' id="logo_dash" alt="User Profile">
                <div class="title_dash">
                    <p>
                        <?php if (isset($_SESSION['loggedInUser']['name'])) {
                            echo '<p>' . ($_SESSION['loggedInUser']['name']) . '</p>';
                        } ?>
                    </p>
                    <p style="font-size: 14px; color: rgba(255, 255, 255, 0.562);">
                        <?php if (isset($_SESSION['loggedInUser']['position'])) {
                            echo htmlspecialchars($_SESSION['loggedInUser']['position']);
                        } ?>
                    </p>

                </div>
            </div>
        </div>

        <div class="dashboard_mainbar" id="dashboard_mainbar">
            <div class="dashboard_topnav">
                <div>
                </div>
                <div>
                    <p>Invoice</p>
                </div>
                <div class="logouts">
                </div>
            </div>

            <div class="main_container">
                <div class="header">
                    <h4 class="title">Order Summary
                        <a href="counter.php" class="btn btn-danger">Back to Create Order</a>
                    </h4>
                </div>
                <div class="body">

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
                                                <h4 class="company-name">LaCocina Inasal</h4>
                                                <p class="address">1911 Leon Guinto Street Corner Remedios Street, Malate, Manila</p>
                                                <p class="address">Company LaCocina</p>
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

        </div>
    </div>



    <!-- SIDE BAR -->
    <script src="../js/sidebar.js"></script>

    <script>
        function quantityIncDec(prodId, qty) {
            $.ajax({
                type: "POST",
                url: "orders-code-counter.php",
                data: {
                    'productIncDec': true,
                    'product_id': prodId,
                    'quantity': qty
                },
                success: function(response) {
                    var res = JSON.parse(response); // Corrected parsing of JSON response

                    if (res.status == 200) {
                        // Reload the entire #productArea to include the updated content and button
                        $('#productArea').load(' #productArea', function() {
                            alertify.success(res.message);
                        });
                    } else {
                        // Reload the entire #productArea to include the updated content and button
                        $('#productArea').load(' #productArea', function() {
                            alertify.error(res.message);
                        });
                    }
                }
            });
        }
    </script>

    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Include Bootstrap JavaScript bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>


    <!-- Include Simple Datatables library -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>



    <!-- Include SweetAlert for alert messages -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Include AlertifyJS for alert and notification messages -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>

    <!-- Include html2canvas and jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



    <!-- jQuery library from CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- jQuery UI library from CDN for datepicker functionality -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="../js/orders-counter.js"></script>


    <!-- js canvas -->
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>




</body>

</html>