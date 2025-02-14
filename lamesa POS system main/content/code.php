<?php
require '../config/function.php';

// INSERT CATEGORY
if (isset($_POST['saveCategory'])) {
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1 : 0;

    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status,
    ];

    // Insert data into the database
    $result = insert('categories', $data);
    if ($result) {
        // Successful insertion
        redirect('inventory.php', 'Category Created successfully.');
    } else {
        // Insertion failed
        redirect('categories-create.php', 'Something went wrong.');
    }
}

// UPDATE CATEGORY
if (isset($_POST['updateCategory'])) {
    $categoryId = validate($_POST['categoryId']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1 : 0;

    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status,
    ];

    // Insert data into the database
    $result = update('categories', $categoryId, $data);
    if ($result) {
        // Successful insertion
        redirect('inventory.php?id=' . $categoryId, 'Category Updated successfully.');
    } else {
        // Insertion failed
        redirect('inventory.php?id=' . $categoryId, 'Something went wrong.');
    }
}

// INSERT PRODUCT
if (isset($_POST['saveProduct'])) {
    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) ? 1 : 0;

    // Check if the category is active
    $categoryQuery = "SELECT status FROM categories WHERE id = '$category_id'";
    $categoryResult = mysqli_query($conn, $categoryQuery);

    if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
        $category = mysqli_fetch_assoc($categoryResult);

        if ($category['status'] == 1) {
            // Category is active, proceed with product insertion

            if ($_FILES['image']['size'] > 0) {
                $path = '../assets/uploads/products';
                $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                $filename = time() . '.' . $image_ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename);

                $finalImage = 'assets/uploads/products/' . $filename;
            } else {
                $finalImage = '';
            }

            $data = [
                'category_id' => $category_id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $finalImage,
                'status' => $status,
            ];

            // Insert data into the products table
            $result = insert('products', $data);
            if ($result) {
                $product_id = mysqli_insert_id($conn);

                // Update raw_product quantities based on product quantity
                $rawMaterialQuery = "UPDATE raw_product SET raw_material_quantity = raw_material_quantity - $quantity WHERE product_id = $product_id";
                mysqli_query($conn, $rawMaterialQuery);

                // Successful insertion
                redirect('inventory.php', 'Product Created successfully.');
            } else {
                // Insertion failed
                redirect('Products-create.php', 'Something went wrong.');
            }
        } else {
            // Category is not active
            redirect('Products-create.php', 'Your selected category is not available.');
        }
    } else {
        // Category does not exist
        redirect('Products-create.php', 'The selected category does not exist.');
    }
}

// UPDATE PRODUCT
if (isset($_POST['updateProduct'])) {
    $product_id = validate($_POST['product_id']);
    $new_quantity = validate($_POST['quantity']);

    // Fetch existing product data
    $productData = getById('products', $product_id);
    if (!$productData) {
        redirect('inventory.php', 'No such Product Found');
    }

    $current_quantity = $productData['data']['quantity'];
    $quantity_difference = $new_quantity - $current_quantity;

    // If the quantity is increased, decrement raw products accordingly
    if ($quantity_difference > 0) {
        $required_quantity = $quantity_difference;

        // Fetch raw products in FIFO order (oldest first)
        $rawProducts = mysqli_query($conn, "SELECT * FROM raw_product WHERE product_id = '$product_id' AND raw_material_quantity > 0 ORDER BY date_delivered ASC");

        while ($required_quantity > 0 && ($rawProduct = mysqli_fetch_assoc($rawProducts))) {
            $raw_product_id = $rawProduct['id'];
            $raw_quantity = $rawProduct['raw_material_quantity'];

            if ($raw_quantity >= $required_quantity) {
                // If the current raw product can fully cover the required quantity
                $new_raw_quantity = $raw_quantity - $required_quantity;
                mysqli_query($conn, "UPDATE raw_product SET raw_material_quantity = $new_raw_quantity WHERE id = '$raw_product_id'");
                $required_quantity = 0;
            } else {
                // Use up the entire current raw product quantity
                mysqli_query($conn, "UPDATE raw_product SET raw_material_quantity = 0 WHERE id = '$raw_product_id'");
                $required_quantity -= $raw_quantity;
            }
        }

        // Check if there's still a requirement left
        if ($required_quantity > 0) {
            // Handle the case where there are not enough raw materials to fulfill the order
            redirect('inventory.php?id=' . $product_id, 'Not enough raw Stocks to update the sales product quantity.');
        }
    }
    // Handle file upload
    if ($_FILES['image']['size'] > 0) {
        $path = '../assets/uploads/products';
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;
        $finalImage = 'assets/uploads/products/' . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename)) {
            // Delete old image if update successful
            $deleteImage = '../' . $productData['data']['image'];
            if (file_exists($deleteImage)) {
                unlink($deleteImage);
            }
        } else {
            // Handle upload failure
            $finalImage = $productData['data']['image']; // Revert to old image if upload fails
        }
    } else {
        // No new image uploaded, keep existing image
        $finalImage = $productData['data']['image'];
    }
    // Prepare data array for database update
    $data = [
        'category_id' => validate($_POST['category_id']),
        'name' => validate($_POST['name']),
        'description' => validate($_POST['description']),
        'price' => validate($_POST['price']),
        'quantity' => $new_quantity,
        'image' => $finalImage,
        'status' => isset($_POST['status']) ? 1 : 0,
    ];

    // Update data in the database
    $result = update('products', $product_id, $data);

    if ($result) {
        redirect('inventory.php?id=' . $product_id, 'Product updated successfully.');
    } else {
        redirect('inventory.php?id=' . $product_id, 'Failed to update product.');
    }
}

// INSERT RAW PRODUCT
if (isset($_POST['saveRawProduct'])) {
    $product_id = validate($_POST['product_id']);
    $raw_material_name = validate($_POST['raw_material_name']);
    $raw_material_quantity = validate($_POST['raw_material_quantity']);
    $date_delivered = validate($_POST['date_delivered']);

    $data = [
        'product_id' => $product_id,
        'raw_material_name' => $raw_material_name,
        'raw_material_quantity' => $raw_material_quantity,
        'date_delivered' => $date_delivered,
    ];

    // Insert into raw_product table
    $result = insert('raw_product', $data);

    if ($result) {
        // Successful insertion
        redirect('inventory.php', 'Raw Product added successfully.');
    } else {
        // Insertion failed
        redirect('raw_product_create.php', 'Failed to add Raw Product.');
    }
}

// UPDATE RAW PRODUCTS

if (isset($_POST['updateRawProduct'])) {
    $id = validate($_POST['id']);
    $raw_material_name = validate($_POST['raw_material_name']);
    $raw_material_quantity = validate($_POST['raw_material_quantity']);
    $date_delivered = validate($_POST['date_delivered']);

    // Prepare data array for database update
    $data = [
        'raw_material_name' => $raw_material_name,
        'raw_material_quantity' => $raw_material_quantity,
        'date_delivered' => $date_delivered,
    ];

    // Update data in the database
    $result = update('raw_product', $id, $data);

    if ($result) {
        redirect('inventory.php', 'Raw product updated successfully.');
    } else {
        redirect('inventory.php', 'Failed to update raw product.');
    }
}
