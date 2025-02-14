<?php
include 'partials/header.php';
?>

<title>LaCocina | Inventory</title>

<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="../css/inventory.css">




</head>


<body>
    <div class="dashboard_container">
        <!-- SIDEBAR -->
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
                    <li class="list active">
                        <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i><span class="Menutext">Dashboard</span></a>
                    </li>
                    <li>
                        <a href="order-create.php"><i class="fa-solid fa-list"></i><span class="Menutext">Order</span></a>
                    </li>
                    <li>
                        <a href="history.php"><i class="fa-solid fa-clock-rotate-left"></i><span class="Menutext">History</span></a>
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

        <?php

        // fetch my product table
        $products = getAll('products');

        if (!$products) {
            echo '<h4>SOMETHING WENT WRONG</h4>';
            return false;
        }

        // fetch my category table
        $categories = getAll('categories');

        if (!$categories) {
            echo '<h4>SOMETHING WENT WRONG</h4>';
            return false;
        }

        // REPORT QUERY FOR TOTAL CATEGORY
        $countCategoryQuery = "SELECT COUNT(*) AS totalCategory FROM categories";
        $categoryCountRes = mysqli_query($conn, $countCategoryQuery);

        if ($categoryCountRes) {
            $totalCategoriesRow = mysqli_fetch_assoc($categoryCountRes);
            $totalCategories = $totalCategoriesRow['totalCategory'];
        } else {
            $totalCategories = 0;
        }


        // REPORT QUERY FOR TOTAL PRODUCT
        $countProductQuery = "SELECT COUNT(*) AS totalProduct FROM products";
        $productCountRes = mysqli_query($conn, $countProductQuery);

        if ($productCountRes) {
            $totalProductsRow = mysqli_fetch_assoc($productCountRes);
            $totalProducts = $totalProductsRow['totalProduct'];
        } else {
            $totalProducts = 0;
        }

        // REPORT QUERY FOR PRODUCT LOW STOCKS
        $countProductLow = "SELECT COUNT(*) AS countLow FROM products WHERE quantity >= 1 AND quantity <= 10";
        $productLow = mysqli_query($conn, $countProductLow);

        if ($productLow) {
            $productLowRow = mysqli_fetch_assoc($productLow);
            $lowStockCount = $productLowRow['countLow'];
        } else {
            $lowStockCount = 0;
        }

        // REPORT QUERY FOR PRODUCT NO STOCKS
        $countProductNo = "SELECT COUNT(*) AS countNo FROM products WHERE quantity < 1";
        $productNo = mysqli_query($conn, $countProductNo);

        if ($productNo) {
            $productNoRow = mysqli_fetch_assoc($productNo);
            $NoStockCount = $productNoRow['countNo'];
        } else {
            $NoStockCount = 0;
        }


        // Handle product category and stock filtering
        $selectedCategory = isset($_GET['category']) ? validate($_GET['category']) : '';
        $selectedAvailability = isset($_GET['availability']) ? validate($_GET['availability']) : '';


        $query = "SELECT * FROM products WHERE 1=1";

        if ($selectedCategory != '') {
            $query .= " AND category_id = '$selectedCategory'";
        }

        if ($selectedAvailability != '') {
            if ($selectedAvailability == 'Out of stock') {
                $query .= " AND quantity < 1";
            } elseif ($selectedAvailability == 'Low stock') {
                $query .= " AND quantity >= 1 AND quantity <= 10";
            } elseif ($selectedAvailability == 'In-stock') {
                $query .= " AND quantity > 10";
            }
        }

        $query .= " ORDER BY id ASC";

        $products = mysqli_query($conn, $query);

        if (!$products) {
            echo '<h4>SOMETHING WENT WRONG</h4>';
            return false;
        }

        // handle category filtering




        ?>


        <!-- MAINBAR -->
        <div class="dashboard_mainbar" id="dashboard_mainbar">
            <div class="dashboard_topnav1">
                <div class="inventory-item categories">
                    <h4><a href="#">Categories</a></h4>
                    <div class="count"><?php echo ($totalCategories); ?></div>
                    <div class="description">Last 7 days</div>
                </div>
                <div class="inventory-item total-products">
                    <h4><a href="#">Total Products</a></h4>
                    <div class="count"><?php echo ($totalProducts); ?></div>
                    <div class="description">Last 7 days</div>
                    <div class="description">₱25000 Revenue</div>
                </div>
                <div class="inventory-item low-stocks">
                    <h4><a href="#">Low Stocks</a></h4>
                    <div class="count"><?php echo ($lowStockCount); ?></div>
                    <div class="description">Ordered</div>
                    <div class="description"><?php echo ($lowStockCount); ?> Not in stock</div>
                </div>
                <div class="inventory-item top-selling">
                    <h4><a href="#">No Stocks</a></h4>
                    <div class="count"><?php echo ($NoStockCount); ?></div>
                    <div class="description">Last 7 days</div>
                    <div class="description">₱2500 Cost</div>
                </div>
            </div>

            <!-- Display Product my List -->
            <div class="main_container">
                <div class="taas-header">
                    <h2>Product List</h2>
                    <?php alertMessage(); ?>
                    <a href="products-create.php">Add Product</a>
                </div>
                <!-- Filter -->
                <div class="filter-container">
                    <form action="" method="GET">
                        <div class="filter-row">
                            <div class="filter-item">
                                <select name="category" class="filter-select">
                                    <option value="">--Select Category--</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?= $category['id']; ?>" <?= $selectedCategory == $category['id'] ? 'selected' : ''; ?>>
                                            <?= $category['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="filter-item">
                                <select name="availability" class="filter-select">
                                    <option value="">--Select Availability--</option>
                                    <option value="Out of stock" <?= $selectedAvailability == 'Out of stock' ? 'selected' : ''; ?>>Out of stock</option>
                                    <option value="Low stock" <?= $selectedAvailability == 'Low stock' ? 'selected' : ''; ?>>Low stock</option>
                                    <option value="In-stock" <?= $selectedAvailability == 'In-stock' ? 'selected' : ''; ?>>In-stock</option>
                                </select>
                            </div>
                            <div class="filter-item">
                                <button type="submit" class="btn-submit">Filter</button>
                                <a href="inventory.php" class="btn-reset">RESET</a>
                            </div>
                        </div>
                    </form>
                </div>



                <div class="table-container">
                    <table class="table-user">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Product ID</th>
                                <th scope="col">Image</th>
                                <th scope="col">Products</th>
                                <th scope="col">Buying Price</th>
                                <th scope="col">Available Quantity</th>
                                <th scope="col">Availability</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($products) > 0) : ?>
                                <?php while ($item = mysqli_fetch_assoc($products)) : ?>
                                    <tr>
                                        <td><?= ($item['id']) ?></td>
                                        <td>
                                            <img src="../<?= ($item['image']) ?>" style="width: 100px; height: 100%;" alt="Product Image" />
                                        </td>
                                        <td><?= ($item['name']) ?></td>
                                        <td><?= '₱' . ($item['price']) ?></td>
                                        <td><?= ($item['quantity']) ?></td>
                                        <td>
                                            <?php
                                            if ($item['quantity'] < 1) {
                                                echo '<span style="background-color: rgb(254, 84, 84);border-radius: 5px;padding:5px;color:white">Out of stock</span>';
                                            } elseif ($item['quantity'] >= 1 && $item['quantity'] <= 10) {
                                                echo '<span style="background-color: orange;border-radius: 5px;padding:5px;color:white">Low stock</span>';
                                            } else {
                                                echo '<span style="background-color: #70b57f;border-radius: 5px;padding:5px;color:white">In-stock</span>';
                                            }
                                            ?>
                                        </td>
                                        <td class="centered-cell">
                                            <a href="products-edit.php?id=<?= ($item['id']) ?>"><i class="fa-solid fa-pen-to-square fs-3"></i></a>
                                            <a href="products-delete.php?id=<?= ($item['id']) ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="fa-solid fa-trash fs-3"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7">No Records Found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Display Raw Product List -->
            <div class="main_container">
                <div class="taas-header">
                    <h2>Raw Stocks List</h2>
                    <?php alertMessage(); ?>
                    <a href="raw-product-create.php">Add Raw Product</a>
                </div>

                <div class="table-container">
                    <table class="table-user">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Product ID</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Stocks</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch unique product_id entries from raw_product table
                            $rawProductsQuery = "
                    SELECT rp.id, rp.product_id, p.name, SUM(rp.raw_material_quantity) AS total_quantity, MIN(rp.date_delivered) AS first_delivered_date
                    FROM raw_product rp
                    JOIN products p ON rp.product_id = p.id
                    GROUP BY rp.product_id
                ";
                            $rawProducts = mysqli_query($conn, $rawProductsQuery);

                            if ($rawProducts && mysqli_num_rows($rawProducts) > 0) :
                                while ($rawProduct = mysqli_fetch_assoc($rawProducts)) :
                            ?>
                                    <tr>
                                        <td><?= $rawProduct['product_id']; ?></td>
                                        <td><?= $rawProduct['name']; ?></td>
                                        <td><?= $rawProduct['total_quantity']; ?></td>
                                        <td class="centered-cell">
                                            <a href="raw-products-delete.php?product_id=<?= $rawProduct['id']; ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="fa-solid fa-trash fs-3"></i></a>
                                            <a class="btnview" href="raw-product-view.php?product_id=<?= $rawProduct['product_id']; ?>">View</a>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else :
                                ?>
                                <tr>
                                    <td colspan="5">No Records Found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>




            <?php
            $categories = getAll('categories');
            if (!$categories) {
                echo '<h4>SOMETHING WENT WRONG</h4>';
                return false;
            }
            if (mysqli_num_rows($categories) > 0) {
            ?>

                <!-- display category my list -->
                <div class="main_container">
                    <div class="taas-header">
                        <h2>Category List</h2>
                        <?php alertMessage(); ?>
                        <a href="categories-create.php">Add Category</a>
                    </div>
                    <!-- Filter -->

                    <div class="table-container">
                        <table class="table-user">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Availability</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($item = mysqli_fetch_assoc($categories)) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['id']) ?></td>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td>
                                            <?php
                                            if ($item['status'] == 1) {
                                                echo '<span style="background-color: #70b57f;border-radius: 5px;padding:5px;color:white">Available</span>';
                                            } else {
                                                echo '<span style="background-color: rgb(254, 84, 84);border-radius: 5px;padding:5px;color:white">Not Available</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="categories-edit.php?id=<?= htmlspecialchars($item['id']) ?>"><i class="fa-solid fa-pen-to-square fs-3"></i></a>
                                            <a href="categories-delete.php?id=<?= htmlspecialchars($item['id']) ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="fa-solid fa-trash fs-3"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            <?php
            } else {
            ?>
                <h5 class="mb-0">No Records Found</h5>
            <?php
            }
            ?>

        </div>





    </div>




    <!-- SIDE BAR -->
    <script src="../js/sidebar.js"></script>

    <?php include 'partials/footer.php'; ?>