<?php include 'partials/header.php'; ?>


<head>

    <title>LaCocina | History</title>


    <link rel="stylesheet" href="../css/dashboard.css">

    <link rel="stylesheet" href="../css/history.css">
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
                        <a href="counter.php"><i class="fa-solid fa-list"></i><span class="Menutext">Order</span></a>
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

            <div class="main_container">
                <div class="container">
                    <div class="history-card">
                        <div class="history-card-header">
                            <div class="history-card-title">
                                <h4>Orders</h4>
                            </div>
                            <div class="history-card-filters">
                                <form action="" method="GET">
                                    <div class="filter-row">
                                        <div class="filter-item">
                                            <input type="date" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : ''; ?>">
                                        </div>
                                        <div class="filter-item">
                                            <select name="payment_status">
                                                <option value="">--Select Payment status--</option>
                                                <option value="Cash Payment" <?= isset($_GET['payment_status']) && $_GET['payment_status'] == 'Cash Payment' ? 'selected' : ''; ?>>Cash Payment</option>
                                                <option value="Online Payment" <?= isset($_GET['payment_status']) && $_GET['payment_status'] == 'Online Payment' ? 'selected' : ''; ?>>Online Payment</option>
                                            </select>
                                        </div>
                                        <div class="filter-item">
                                            <input type="text" name="tracking_no" placeholder="Search by Order No." value="<?= isset($_GET['tracking_no']) ? htmlspecialchars($_GET['tracking_no']) : ''; ?>">
                                        </div>
                                        <div class="filter-item">
                                            <button type="submit" class="btn-submit">Filter</button>
                                            <a href="history-counter.php" class="btn-reset">RESET</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="history-card-body">
                            <?php
                            // PHP code to fetch and display orders
                            $orderDate = isset($_GET['date']) ? validate($_GET['date']) : '';
                            $paymentStatus = isset($_GET['payment_status']) ? validate($_GET['payment_status']) : '';
                            $trackingNo = isset($_GET['tracking_no']) ? validate($_GET['tracking_no']) : '';

                            if ($orderDate != '' && $paymentStatus == '' && $trackingNo == '') {
                                $query = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id = o.customer_id AND o.order_date='$orderDate' ORDER BY o.id DESC";
                            } elseif ($orderDate == '' && $paymentStatus != '' && $trackingNo == '') {
                                $query = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id = o.customer_id AND o.payment_mode='$paymentStatus' ORDER BY o.id DESC";
                            } elseif ($orderDate != '' && $paymentStatus != '' && $trackingNo == '') {
                                $query = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id = o.customer_id AND o.order_date='$orderDate' AND o.payment_mode='$paymentStatus' ORDER BY o.id DESC";
                            } elseif ($trackingNo != '') {
                                $query = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id = o.customer_id AND o.tracking_no LIKE '%$trackingNo%' ORDER BY o.id DESC";
                            } else {
                                $query = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id = o.customer_id ORDER BY o.id DESC";
                            }

                            $orders = mysqli_query($conn, $query);

                            if ($orders) {
                                if (mysqli_num_rows($orders) > 0) {
                            ?>
                                    <table class="history-table">
                                        <thead>
                                            <tr>
                                                <th>Order No.</th>
                                                <th>C Name</th>
                                                <th>C Phone</th>
                                                <th>Order Date</th>
                                                <th>Order Status</th>
                                                <th>Payment Mode</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orders as $orderItem) : ?>
                                                <?php
                                                $rowClass = '';
                                                if ($orderItem['order_status'] === 'completed') {
                                                    $rowClass = 'completed'; // Green
                                                } elseif ($orderItem['order_status'] === 'canceled') {
                                                    $rowClass = 'canceled'; // Red
                                                }
                                                ?>
                                                <tr class="<?= $rowClass ?>">
                                                    <td class="fw-bold"><?= $orderItem['tracking_no']; ?></td>
                                                    <td><?= $orderItem['name']; ?></td>
                                                    <td><?= $orderItem['phone']; ?></td>
                                                    <td><?= date('d M, Y', strtotime($orderItem['order_date'])); ?></td>
                                                    <td class="status"><?= $orderItem['order_status']; ?></td>
                                                    <td><?= $orderItem['payment_mode']; ?></td>
                                                    <td>
                                                        <a href="orders-view-counter.php?track=<?= $orderItem['tracking_no']; ?>" class="btn-action view">View</a>
                                                        <a href="orders-view-print.php?track=<?= $orderItem['tracking_no']; ?>" class="btn-action print">Print</a>
                                                        <a href="orders-cancel-counter.php?track=<?= $orderItem['tracking_no']; ?>" class="btn-action cancel">Cancel</a>
                                                        <a href="orders-complete-counter.php?track=<?= $orderItem['tracking_no']; ?>" class="btn-action complete">complete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                            <?php
                                } else {
                                    echo "<h5>No record available</h5>";
                                }
                            } else {
                                echo "<h5>Something went wrong</h5>";
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