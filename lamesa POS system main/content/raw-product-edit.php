<?php include 'partials/header.php';


?>

<head>

    <title>LaCocina | Inventory</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/inventory.css">
    <link rel="stylesheet" href="../css/inventorycrud.css">

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
                        <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i><span class="Menutext">Dashboard</span></a>
                    </li>
                    <li>
                        <a href="content/order.php"><i class="fa-solid fa-list"></i><span class="Menutext">Order</span></a>
                    </li>
                    <li>
                        <a href="content/history.php"><i class="fa-solid fa-clock-rotate-left"></i><span class="Menutext">History</span></a>
                    </li>
                    <li style="background-color: #ffffff3f;">
                        <a href="inventory.php"><i class="fa-solid fa-clipboard-list"></i><span class="Menutext">Inventory</span></a>
                    </li>
                    <li>
                        <a href="account.php"><i class="fa-solid fa-user MenuIcons"></i><span class="Menutext">Accounts</span></a>
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
                    <p>Edit Raw Product</p>
                </div>
                <div class="logouts">
                </div>
            </div>

            <div class="main_container">
                <?php
                // Retrieve the raw product ID from the query parameter
                $paramValue = checkParamId('id');
                $product_id = checkParamId('product_id');


                if (!is_numeric($paramValue)) {
                    echo "<h5>Id is not an integer</h5>";
                    exit;
                }

                // Fetch raw product data
                $rawProduct = getById('raw_product', $paramValue);

                if ($rawProduct && isset($rawProduct['status']) && $rawProduct['status'] == 200) {
                    // Extract data
                    $data = $rawProduct['data'] ?? [];
                    $name = $data['raw_material_name'] ?? '';
                    $quantity = $data['raw_material_quantity'] ?? '';
                    $dateDelivered = $data['date_delivered'] ?? '';
                } else {
                    echo "<h5>Something went wrong or raw product not found</h5>";
                    exit;
                } ?>
                <div class="main_add_container">
                    <div class="container">
                        <div class="text-center">
                            <h3>Edit Raw Product</h3>
                        </div>
                        <form action="code.php" method="POST">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($paramValue) ?>">
                            <div class="row">
                                <div class="col-6">
                                    <label>Raw Material Name *</label>
                                    <input type="text" name="raw_material_name" required value="<?= htmlspecialchars($name) ?>" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label>Quantity *</label>
                                    <input type="text" name="raw_material_quantity" required value="<?= htmlspecialchars($quantity) ?>" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label>Date Delivered *</label>
                                    <input type="date" name="date_delivered" required value="<?= htmlspecialchars($dateDelivered) ?>" class="form-control">
                                </div>
                                <div class="col-8">
                                    <a href="raw-product-view.php?product_id=<?= htmlspecialchars($product_id) ?>" class="btn1">Cancel</a>
                                    <button type="submit" name="updateRawProduct" class="btn2">Update<i class="fa-solid fa-user-check"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>





        </div>
    </div>





    <!-- SIDE BAR -->
    <script src="../js/sidebar.js"></script>

    <?php include 'partials/footer.php'; ?>