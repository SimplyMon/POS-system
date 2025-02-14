<?php include 'partials/header.php'; ?>



<head>

    <title>LaCocina | History</title>


    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/orders-view.css">

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
                    <li>
                        <a href="order-create.php"><i class="fa-solid fa-list"></i><span class="Menutext">Order</span></a>
                    </li>
                    <li style="background-color: #ffffff3f;">
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
                    <p>History</p>
                </div>
                <div class="logouts">
                </div>
            </div>

            <div class="main-container">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h4>Order View
                                <a href="history-counter.php" class="btn btn-danger">Back</a>
                                <a href="orders-view-print.php?track=<?= $_GET['track'] ?>" class="btn btn-info">Print</a>
                            </h4>
                        </div>
                        <div class="card-body">

                            <?php alertMessage(); ?>

                            <?php
                            if (isset($_GET['track'])) {
                                if ($_GET['track'] == '') {
                            ?>
                                    <div class="no-tracking">
                                        <h5>No tracking Number Found</h5>
                                        <a href="orders.php" class="btn btn-primary">Go back</a>
                                    </div>
                                    <?php
                                    return false;
                                }

                                $trackingNo = validate($_GET['track']);

                                $query = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id = o.customer_id
                        AND tracking_no='$trackingNo' ORDER BY o.id DESC";

                                $orders = mysqli_query($conn, $query);
                                if ($orders) {
                                    if (mysqli_num_rows($orders) > 0) {
                                        $orderData = mysqli_fetch_assoc($orders);
                                        $orderId = $orderData['id'];

                                    ?>
                                        <div class="order-details">
                                            <div class="section">
                                                <h4>Order Details</h4>
                                                <p><strong>Order No:</strong> <?= $orderData['tracking_no']; ?></p>
                                                <p><strong>Order Date:</strong> <?= $orderData['order_date']; ?></p>
                                                <p><strong>Order Status:</strong> <?= $orderData['order_status']; ?></p>
                                                <p><strong>Payment Mode:</strong> <?= $orderData['payment_mode']; ?></p>
                                            </div>
                                            <div class="section">
                                                <h4>User Details</h4>
                                                <p><strong>Customer No:</strong> <?= $orderData['name']; ?></p>
                                            </div>
                                        </div>

                                        <?php
                                        $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, o.*, oi.*, p.*
                                    FROM orders as o, order_items as oi, products as p
                                    WHERE oi.order_id = o.id AND p.id = oi.product_id
                                    AND o.tracking_no='$trackingNo'";

                                        $orderItemsRes = mysqli_query($conn, $orderItemQuery);
                                        if ($orderItemsRes) {
                                            if (mysqli_num_rows($orderItemsRes) > 0) {

                                        ?>
                                                <h4 class="order-items-title">Order Items Details</h4>
                                                <table class="order-items-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Products</th>
                                                            <th>Price</th>
                                                            <th>Quantity</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($orderItemsRes as $orderItemRow) : ?>
                                                            <tr>
                                                                <td>
                                                                    <img src="<?= $orderItemRow['image'] != '' ? '../' . $orderItemRow['image'] : '../assets/images/no-img.jpg'; ?>" class="item-image" alt="IMG">
                                                                    <?= $orderItemRow['name']; ?>
                                                                </td>
                                                                <td class="text-center">₱<?= number_format($orderItemRow['orderItemPrice'], 0) ?></td>
                                                                <td class="text-center"><?= $orderItemRow['orderItemQuantity'] ?></td>
                                                                <td class="text-center">₱<?= number_format($orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'], 0) ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>

                                                        <tr>
                                                            <td class="text-end">Total Price</td>
                                                            <td colspan="3" class="text-end">₱ <?= number_format($orderItemRow['total_amount'], 0); ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                        <?php
                                            } else {
                                                echo "<h5>No record Found</h5>";
                                                return false;
                                            }
                                        } else {
                                            echo "<h5>Something went wrong</h5>";
                                            return false;
                                        }
                                        ?>

                                <?php
                                    } else {
                                        echo "<h5>No record Found</h5>";
                                        return false;
                                    }
                                } else {
                                    echo "<h5>Something went wrong</h5>";
                                }
                            } else {
                                ?>
                                <div class="no-tracking">
                                    <h5>No tracking No Found</h5>
                                    <a href="orders.php" class="btn btn-primary">Go back</a>
                                </div>
                            <?php
                            }
                            ?>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>





    <!-- SIDE BAR -->
    <script src="../js/sidebar.js"></script>

    <?php include 'partials/footer.php'; ?>