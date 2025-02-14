<?php include 'partials/header.php';

$categories = getAll('categories');

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

?>



<title>LaCocina | Order</title>

<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="../css/order-create.css">
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
                    <li>
                        <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i><span class="Menutext">Dashboard</span></a>
                    </li>
                    <li style="background-color: #ffffff3f;">
                        <a href="order-create.php"><i class="fa-solid fa-list"></i><span class="Menutext">Order</span></a>
                    </li>
                    <li>
                        <a href="history.php"><i class="fa-solid fa-clock-rotate-left"></i><span class="Menutext">History</span></a>
                    </li>
                    <li>
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
                    <p>Create orders</p>
                </div>
                <div class="logouts">
                </div>
            </div>

            <div class="main_container">
                <div class="orders-tab">
                    <div class="product-section">
                        <div class="top-bar">
                            <h2>Select Product</h2>
                            <form action="" method="GET">
                                <div class="filter-item">
                                    <select name="category" class="filter-select">
                                        <option value="">--Select Category--</option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category['id']; ?>" <?= $selectedCategory == $category['id'] ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn-submit">Filter</button>
                                <a href="order-create.php" class="btn-reset">RESET</a>
                            </form>
                        </div>

                        <div class="product-list">
                            <?php
                            if ($products) {
                                if (mysqli_num_rows($products) > 0) {
                                    foreach ($products as $prodItem) {
                                        $imageUrl = $prodItem['image'];
                                        $productId = $prodItem['id'];
                                        $productName = $prodItem['name'];
                                        $productPrice = $prodItem['price'];
                                        $quantity = $prodItem['quantity']; // Get the product quantity

                                        // Determine the card color and message
                                        $cardClass = $quantity > 0 ? '' : 'out-of-stock'; // Add a class if the product is out of stock
                                        $availabilityMessage = $quantity > 0 ? '' : 'Product Not Available'; // Message if out of stock
                            ?>
                                        <button type="button" class="product-button <?= $cardClass; ?>" data-product-id="<?= $productId; ?>">
                                            <img src="../<?= $imageUrl; ?>" alt="<?= htmlspecialchars($productName); ?>" class="product-image">
                                            <span class="product-name"><?= htmlspecialchars($productName); ?></span>
                                            <span style="font-weight: bold;" class="product-price"><?= '₱ ' . number_format($productPrice, 2); ?></span>
                                            <?php if ($availabilityMessage) : ?>
                                                <span class="availability-message"><?= htmlspecialchars($availabilityMessage); ?></span>
                                            <?php else : ?>
                                                <input type="number" name="quantity" value="1" class="quantity-input">
                                            <?php endif; ?>
                                        </button>

                            <?php
                                    }
                                } else {
                                    echo '<p>No Product Found</p>';
                                }
                            } else {
                                echo '<p>Something Went Wrong</p>';
                            }
                            ?>
                        </div>
                        <input type="hidden" name="product_id" id="product_id">
                    </div>

                    <div class="cart-section">
                        <h2>Order Summary</h2>
                        <div id="productArea">
                            <?php
                            if (isset($_SESSION['productItems'])) {
                                $sessionProducts = $_SESSION['productItems'];
                                if (empty($sessionProducts)) {
                                    unset($_SESSION['productItems']);
                                    unset($_SESSION['productItemIds']);
                                }
                            ?>
                                <div class="cart-table">
                                    <?php
                                    $grandTotal = 0;
                                    foreach ($sessionProducts as $key => $item) :
                                        $itemTotal = $item['price'] * $item['quantity'];
                                        $grandTotal += $itemTotal;
                                    ?>
                                        <div class="cart-item">
                                            <img src="../<?= $item['image']; ?>" alt="<?= $item['name']; ?>" class="cart-item-image">
                                            <div class="pangalawa">
                                                <span class="cart-item-name"><?= $item['name']; ?></span>
                                                <span class="cart-item-price"><?= '₱' . $item['price']; ?></span>
                                            </div>
                                            <div class="qtyBox">
                                                <input type="hidden" value="<?= $item['product_id'] ?>" class="prodId">
                                                <button class="decrement">-</button>
                                                <input type="text" value="<?= $item['quantity']; ?>" class="qty">
                                                <button class="increment">+</button>
                                            </div>
                                            <a href="order-item-delete.php?index=<?= $key; ?>" class="remove-btn">Remove</a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="grand-total">
                                    <h3>Grand Total: <?= '₱' . number_format($grandTotal, 0); ?></h3>
                                </div>
                                <div class="checkout-section">
                                    <label>Select Payment</label>
                                    <select required id="payment_mode">
                                        <option value="">--SELECT PAYMENT--</option>
                                        <option value="Cash Payment">CASH PAYMENT</option>
                                        <option value="Online Payment">ONLINE PAYMENT(Gcash, Paymaya)</option>
                                    </select>
                                    <label>Enter Customer Name</label>
                                    <button type="button" class="proceedToPlace">Proceed to Place Order</button>
                                </div>
                            <?php
                            } else {
                                echo '<h5>No Items Added</h5>';
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

    <script>
        function quantityIncDec(prodId, qty) {
            $.ajax({
                type: "POST",
                url: "orders-code.php",
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

    <?php include 'partials/footer.php'; ?>