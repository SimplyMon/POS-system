<?php include 'header.php';
include 'navbar.php';
require './config/function.php';

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
    <link rel="stylesheet" href="css/menu.css">
    <title>LaMesa Inasal | MENU</title>
</head>

<body>
    <!-- NAVBAR -->
    <div class="menu-container">
        <header class="title-container">
            <h1 style="color: white; font-size: 60px; text-align: center;">OUR AMAZING MENU</h1>
        </header>


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


        <div class="product-section" style="margin-top: 50px; margin: bottom 100px;">
            <form id="productForm">
                <div class="product-list">
                    <?php
                    if ($products) {
                        if (mysqli_num_rows($products) > 0) {
                            foreach ($products as $prodItem) {
                                $imageUrl = $prodItem['image'];
                                $productId = $prodItem['id'];
                                $productName = $prodItem['name'];
                                $productDescription = $prodItem['description'];
                                $productPrice = $prodItem['price'];
                    ?>
                                <div class="product-card <?= $cardClass; ?>" data-product-id="<?= $productId; ?>">
                                    <img src="../lamesa POS system main../<?= $imageUrl; ?>" alt="<?= $productName; ?>" class="product-image">
                                    <div class="product-info">
                                        <div class="product-info1">
                                            <span class="product-name"><?= htmlspecialchars($productName); ?></span>
                                            <span class="product-price"><?= '₱' . number_format($productPrice, 2); ?></span>
                                        </div>
                                        <span class="product-description"><?= htmlspecialchars($productDescription); ?></span>
                                    </div>
                                </div>
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
            </form>
        </div>


        <?php
        include 'footer.php';
        ?>

</body>

</html>