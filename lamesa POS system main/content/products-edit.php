<?php include 'partials/header.php'; ?>

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
                    <p>Edit Products</p>
                </div>
                <div class="logouts">
                </div>
            </div>

            <div class="main_container">
                <div class="main_add_container">
                    <div class="container">
                        <div class="text-center">
                            <h3>Edit Product</h3>
                        </div>
                        <form action="code.php" method="POST" enctype="multipart/form-data">
                            <?php
                            $paramValue = checkParamId('id');
                            if (!is_numeric($paramValue)) {
                                echo "<h5>Id is not an integer</h5>";
                                return false;
                            }

                            $product = getbyid('products', $paramValue);

                            if ($product) {

                                if ($product['status'] == 200) {
                            ?>

                                    <input type="hidden" name="product_id" value=<?= $product['data']['id'] ?>>
                                    <div class="row">
                                        <div class="col-1">
                                            <label>Select Category</label>
                                            <select name="category_id" class="form-select" id="">
                                                <option value="">Select category</option>
                                                <?php
                                                $categories = getAll('categories');
                                                if ($categories) {
                                                    if (mysqli_num_rows($categories) > 0) {
                                                        foreach ($categories as $cateItem) {
                                                ?>
                                                            <option value="<?= $cateItem['id']; ?>" <?= $product['data']['category_id'] == $cateItem['id'] ? 'selected' : ''; ?>>
                                                                <?= $cateItem['name']; ?>
                                                            </option>
                                                <?php
                                                        }
                                                    } else {
                                                        echo '<option value="">No Categories Found</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">Something Went Wrong</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <label>Product Name *</label>
                                            <input type="text" name="name" required value="<?= $product['data']['name'] ?>" class="form-control">
                                        </div>
                                        <div class="col-5">
                                            <label>Product Price *</label>
                                            <input type="text" name="price" required value="<?= $product['data']['price'] ?>" class="form-control">
                                        </div>
                                        <div class="col-6">
                                            <label>Quantity *</label>
                                            <input type="text" name="quantity" required value="<?= $product['data']['quantity'] ?>" class="form-control">
                                        </div>
                                        <div class="col-4">
                                            <label>Product Description *</label>
                                            <textarea name="description" class="form-control" rows="3"><?= $product['data']['description'] ?></textarea>
                                        </div>
                                        <div class="col-7">
                                            <label>Image *</label>
                                            <input type="file" name="image" class="form-control">
                                            <img src="../<?= $product['data']['image'] ?>" style="width: 50px; height: 50px" alt="img" />
                                        </div>
                                        <div class="col-8">
                                            <a href="inventory.php" class="btn1">Cancel</a>
                                            <button type="submit" name="updateProduct" class="btn2">Update<i class="fa-solid fa-user-check"></i></button>
                                        </div>
                                    </div>
                            <?php

                                } else {
                                    echo '<h5>' . $product['message'] . '</h5>';
                                    return false;
                                }
                            } else {
                                echo "<h5>Something went worn</h5>";
                                return false;
                            } ?>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>





    <!-- SIDE BAR -->
    <script src="../js/sidebar.js"></script>

    <?php include 'partials/footer.php'; ?>