<?php
include 'partials/header.php';

$accounts = getAll('accounts');

if (!$accounts) {
    echo '<h4>SOMETHING WENT WRONG</h4>';
    return false;
}

if (mysqli_num_rows($accounts) > 0) {
    $item = mysqli_fetch_assoc($accounts);

    // Query to fetch total_amount for completed orders, grouped by month
    $query = "SELECT DATE_FORMAT(order_date, '%Y-%m') as month, SUM(total_amount) as total_amount 
        FROM orders
        WHERE order_status = 'completed'
        GROUP BY DATE_FORMAT(order_date, '%Y-%m')
        ORDER BY month";
    $result = mysqli_query($conn, $query);

    $dataPoints = array();
    $months = array(
        "01" => "January",
        "02" => "February",
        "03" => "March",
        "04" => "April",
        "05" => "May",
        "06" => "June",
        "07" => "July",
        "08" => "August",
        "09" => "September",
        "10" => "October",
        "11" => "November",
        "12" => "December"
    );

    $yearlyData = array();
    $currentYear = date('Y'); // Current year


    while ($row = mysqli_fetch_assoc($result)) {
        $month = $row['month'];
        $year = substr($month, 0, 4);
        $monthNumber = substr($month, 5, 2);
        $label = $months[$monthNumber] . " " . $year;
        $yearlyData[$label] = $row['total_amount'];
    }

    // Generate data points for all months
    for ($i = 1; $i <= 12; $i++) {
        $monthNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
        $label = $months[$monthNumber] . " " . $currentYear;
        if (!isset($yearlyData[$label])) {
            $yearlyData[$label] = 0; // Default to 0 if no data for this month
        }
    }

    $dataPoints = array();
    foreach ($months as $monthNumber => $monthName) {
        $label = $monthName . " " . $currentYear;
        $dataPoints[] = array("label" => $label, "y" => isset($yearlyData[$label]) ? $yearlyData[$label] : 0);
    }

    mysqli_free_result($result);



    // daily
    // Query to fetch total_amount for completed orders, grouped by day
    $queryDaily = "SELECT DATE_FORMAT(order_date, '%Y-%m-%d') as day, SUM(total_amount) as total_amount 
    FROM orders
    WHERE order_status = 'completed'
GROUP BY DATE_FORMAT(order_date, '%Y-%m-%d')
ORDER BY day";
    $resultDaily = mysqli_query($conn, $queryDaily);

    $dataPointsDaily = array();

    while ($row = mysqli_fetch_assoc($resultDaily)) {
        $day = $row['day'];
        $dataPointsDaily[] = array("label" => $day, "y" => $row['total_amount']);
    }

    mysqli_free_result($resultDaily);


    // weekly
    // Query to fetch total_amount for completed orders, grouped by week
    $queryWeekly = "SELECT DATE_FORMAT(order_date, '%Y-%u') as week, 
    CONCAT('Week ', DATE_FORMAT(order_date, '%u'), ' of ', YEAR(order_date)) as weekLabel,
    SUM(total_amount) as total_amount 
    FROM orders
    WHERE order_status = 'completed'
    GROUP BY DATE_FORMAT(order_date, '%Y-%u')
    ORDER BY week";

    // Execute the query
    $resultWeekly = mysqli_query($conn, $queryWeekly);
    $dataPointsWeekly = array();

    while ($row = mysqli_fetch_assoc($resultWeekly)) {
        $week = $row['week'];
        $weekLabel = $row['weekLabel'];
        $dataPointsWeekly[] = array("label" => $weekLabel, "y" => $row['total_amount']);
    }

    // Free result set and close connection
    mysqli_free_result($resultWeekly);
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LaCocina | Dashboard</title>
        <link rel="stylesheet" href="../css/dashboard.css">
        <script src="../js/chart.js"></script>
    </head>

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
                            <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i><span
                                    class="Menutext">Dashboard</span></a>
                        </li>
                        <li>
                            <a href="order-create.php"><i class="fa-solid fa-list"></i><span
                                    class="Menutext">Order</span></a>
                        </li>
                        <li>
                            <a href="history.php"><i class="fa-solid fa-clock-rotate-left"></i><span
                                    class="Menutext">History</span></a>
                        </li>
                        <li>
                            <a href="inventory.php"><i class="fa-solid fa-clipboard-list"></i><span
                                    class="Menutext">Inventory</span></a>
                        </li>
                        <li>
                            <a href="account.php"><i class="fa-solid fa-user MenuIcons"></i><span
                                    class="Menutext">Accounts</span></a>
                        </li>
                    </ul>
                </div>
                <div class="profile-txt">
                    <p>Profile</p>
                </div>
                <div class="logout">
                    <a href="../logout.php" id="logout_button"><i class="fa-solid fa-right-from-bracket"></i><span
                            class="Menutext">Logout</span></a>
                </div>
                <div class="dashboard_sidebar_profile">
                    <img src='../<?= $_SESSION['loggedInUser']['image'] ?>' id="logo_dash" alt="User Profile">
                    <div class="title_dash">
                        <p>
                            <?php if (isset($_SESSION['loggedInUser']['name'])) {
                                echo '<p>' . $_SESSION['loggedInUser']['name'] . '</p>';
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
        </div>



        <div class="dashboard_mainbar" id="dashboard_mainbar">
            <div class="taas">
                <div class="taas-1">
                    <h4>Total Admins</h4>
                    <h2><i class="fa-solid fa-user-tie"></i><?= getCount('accounts') ?></h2>
                </div>
                <div class="taas-2">
                    <h4>Total Customers</h4>
                    <h2><i class="fa-solid fa-users"></i><?= getCount('customers') ?></h2>
                </div>
                <div class="taas-3">
                    <h4>Today's Orders</h4>
                    <h2><i class="fa-solid fa-cart-shopping"></i>
                        <?php
                        $todayDate = date('Y-m-d');
                        $todayOrders = mysqli_query($conn, "SELECT * FROM orders WHERE order_date = '$todayDate'");
                        if ($todayOrders) {
                            if (mysqli_num_rows($todayOrders) > 0) {
                                $totalCountOrders = mysqli_num_rows($todayOrders);
                                echo $totalCountOrders;
                            } else {
                                echo 'no records';
                            }
                        } else {
                            echo 'Something Went Wrong';
                        }
                        ?></h2>
                </div>
                <div class="taas-4">
                    <h4>Total Orders</h4>
                    <h2><i class="fa-solid fa-cart-shopping"></i><?= getCount('orders') ?></h2>
                </div>
            </div>


            <div class="baba">
                <div class="main_container-1">
                    <div id="chartContainerMonthly" style="height: 600px; "></div>
                </div>
            </div>
            <div class="baba1">
                <div class="main-container-2">
                    <div id="chartContainerDaily"></div>

                </div>
                <div class="main-container-3">
                    <div id="chartContainerWeekly"></div>

                </div>
            </div>

        </div>


        <!-- SIDE BAR -->
        <script src="../js/sidebar.js"></script>

        <?php include 'partials/footer.php'; ?>

        <script>
            window.onload = function() {
                var chartMonthly = new CanvasJS.Chart("chartContainerMonthly", {
                    animationEnabled: true,
                    backgroundColor: "#ffffff",
                    title: {
                        text: "Monthly Sales Report",
                        fontSize: 35,
                        fontColor: "#000000"

                    },
                    axisX: {
                        crosshair: {
                            enabled: true,
                            snapToDataPoint: true
                        },
                        labelAngle: -45,
                        labelMaxWidth: 100,
                        labelFontSize: 16,
                        labelFontColor: "#000000"
                    },
                    axisY: {
                        title: "Total Sales",
                        includeZero: true,
                        crosshair: {
                            enabled: true,
                            snapToDataPoint: true
                        },
                        titleFontSize: 25,
                        labelFontSize: 16,
                        labelFontColor: "#000000"

                    },
                    toolTip: {
                        enabled: true,
                        contentFormatter: function(e) {
                            var dataPoint = e.entries[0].dataPoint;
                            return "<div style='padding: 10px; color: #FFFFFF; background-color: #2C3E50; border: 2px solid #FFD700;'>Sales: ₱" +
                                dataPoint.y + "</div>";
                        },
                        fontColor: "#FFFFFF",
                        borderColor: "#af3828",
                        backgroundColor: "#000000",
                        borderThickness: 2,

                    },
                    data: [{
                        type: "area",
                        color: "#cb5242",
                        lineColor: "#000000",
                        markerColor: "#000000",
                        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartMonthly.render();



                // daily

                var chartDaily = new CanvasJS.Chart("chartContainerDaily", {
                    animationEnabled: true,
                    backgroundColor: "#ffffff",
                    title: {
                        text: "Daily Sales Report",
                        fontColor: "#000000"
                    },
                    axisX: {
                        crosshair: {
                            enabled: true,
                            snapToDataPoint: true
                        },
                        labelAngle: -45,
                        labelMaxWidth: 100,
                        labelFontColor: "#000000"
                    },
                    axisY: {
                        title: "Total Sales",
                        includeZero: true,
                        crosshair: {
                            enabled: true,
                            snapToDataPoint: true
                        },
                        titleFontColor: "#000000",
                        labelFontColor: "#000000"
                    },
                    toolTip: {
                        enabled: true,
                        contentFormatter: function(e) {
                            var dataPoint = e.entries[0].dataPoint;
                            return "<div style='padding: 10px; color: #FFFFFF; background-color: #2C3E50; border: 2px solid #FFD700;'>Sales: ₱" +
                                dataPoint.y + "</div>";
                        },
                        fontColor: "#FFFFFF",
                        borderColor: "#af3828",
                        backgroundColor: "#000000",
                        borderThickness: 2,
                    },
                    data: [{
                        type: "area",
                        color: "#cb5242",
                        lineColor: "#000000",
                        markerColor: "#000000",
                        dataPoints: <?php echo json_encode($dataPointsDaily, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartDaily.render();


                // weekly
                var chartWeekly = new CanvasJS.Chart("chartContainerWeekly", {
                    animationEnabled: true,
                    backgroundColor: "#ffffff",
                    title: {
                        text: "Weekly Sales Report",
                        fontColor: "#000000"
                    },
                    axisX: {
                        crosshair: {
                            enabled: true,
                            snapToDataPoint: true
                        },
                        labelAngle: -45,
                        labelMaxWidth: 100,
                        labelFontColor: "#000000"
                    },
                    axisY: {
                        title: "Total Sales",
                        includeZero: true,
                        crosshair: {
                            enabled: true,
                            snapToDataPoint: true
                        },
                        titleFontColor: "#000000",
                        labelFontColor: "#000000"
                    },
                    toolTip: {
                        enabled: true,
                        contentFormatter: function(e) {
                            var dataPoint = e.entries[0].dataPoint;
                            return "<div style='padding: 10px; color: #FFFFFF; background-color: #2C3E50; border: 2px solid #FFD700;'>Sales: ₱" +
                                dataPoint.y + "</div>";
                        },
                        fontColor: "#FFFFFF",
                        borderColor: "#FFD700",
                        backgroundColor: "#2C3E50",
                        borderThickness: 2
                    },
                    data: [{
                        type: "area",
                        color: "#cb5242",
                        lineColor: "#000000",
                        markerColor: "#000000",
                        dataPoints: <?php echo json_encode($dataPointsWeekly, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartWeekly.render();
            }
        </script>


    </body>

    </html>

<?php
}
?>