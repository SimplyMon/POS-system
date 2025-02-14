<?php require './config/function.php';

// fetch my category table
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
    <link rel="stylesheet" href="css/order-create.css">

    <title>LaMesa Inasal | MENU</title>
</head>


<body>

    <!-- NAVBAR -->
    <div class="menu-container">
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

        <div class="filter-container">
            <div class="filter-row">
                <div class="filter-item">
                    <ul class="category">
                        <li><a href="?category=">ALL</a></li>
                        <?php foreach ($categories as $category) : ?>
                            <li>
                                <a href="?category=<?= $category['id']; ?>&availability=<?= urlencode($selectedAvailability); ?>" style="<?= $selectedCategory == $category['id'] ? 'color: #AF3728; font-weight: bold; text-decoration: underline black 3px;' : ''; ?>">
                                    <?= htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
        </div>


        <div class="main_container" style="margin-top: 50px;">
            <div class="orders-tab">
                <div class="product-section">
                    <form id="productForm">
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
                                            <img src="../lamesa POS system main../<?= $imageUrl; ?>" alt="<?= $productName; ?>" class="product-image">
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
                    </form>
                </div>

                <div class="cart-section">
                    <h2>Your Cart</h2>
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
                                        <img src="../lamesa POS system main../<?= $item['image']; ?>" alt="<?= $item['name']; ?>" class="cart-item-image">
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




        <?php
        include 'footer.php'
        ?>

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

        <script>
            function updateAvailabilityFilter(value) {
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('availability', value);
                window.location.search = urlParams.toString();
            }
        </script>

</body>

</html>