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
                    <a class="btnview" href="inventory.php"> <i class="fa-solid fa-arrow-left"></i> Back to Inventory</a>
                </div>
                <div>
                    <p>Edit Raw Product</p>
                </div>
                <div class="logouts">
                </div>
            </div>

            <div class="main_container">
                <?php

                // Get the product ID from URL parameter
                $product_id = checkParamId('product_id');
                if (!is_numeric($product_id)) {
                    echo "<h5>Product ID is not valid</h5>";
                    exit;
                }

                // Fetch the raw products related to this product ID, sorted by date_delivered
                $rawProductsQuery = "
        SELECT * FROM raw_product
        WHERE product_id = '$product_id'
        ORDER BY date_delivered ASC
";
                $rawProductsResult = mysqli_query($conn, $rawProductsQuery);

                // Fetch product details for display or use
                $productDetailsQuery = "
        SELECT name FROM products
        WHERE id = '$product_id'
";
                $productDetailsResult = mysqli_query($conn, $productDetailsQuery);
                $product = mysqli_fetch_assoc($productDetailsResult); ?>
                <div class="taas-header">
                    <h2>Raw Products for <?= htmlspecialchars($product['name']); ?></h2>
                    <?php alertMessage(); ?>
                    <a href="raw-product-create.php?product_id=<?= htmlspecialchars($product_id); ?>" class="btn-add">Add Raw Ingredients</a>
                </div>

                <!-- Display Raw Products -->
                <div class="table-container mt-5">
                    <table class="table-user">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Raw Product ID</th>
                                <th scope="col">Raw Ingredients Name</th>
                                <th scope="col">Raw Ingredients Quantity</th>
                                <th scope="col">Date Delivered</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($rawProductsResult) > 0) : ?>
                                <?php while ($item = mysqli_fetch_assoc($rawProductsResult)) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['id']); ?></td>
                                        <td><?= htmlspecialchars($item['raw_material_name']); ?></td>
                                        <td><?= htmlspecialchars($item['raw_material_quantity']); ?></td>
                                        <td><?= date('Y-m-d', strtotime($item['date_delivered'])); ?></td>
                                        <td class="centered-cell">
                                            <a href="raw-product-edit.php?id=<?= htmlspecialchars($item['id']); ?>&product_id=<?= htmlspecialchars($product_id); ?>"><i class="fa-solid fa-pen-to-square fs-3"></i></a>
                                            <a href="raw-products-delete.php?id=<?= htmlspecialchars($item['id']); ?>&product_id=<?= htmlspecialchars($product_id); ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="fa-solid fa-trash fs-3"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5">No Raw Products Found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>





    <!-- SIDE BAR -->
    <script src="../js/sidebar.js"></script>

    <?php include 'partials/footer.php'; ?>