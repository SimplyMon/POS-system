<?php
require './config/function.php';

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
                $path = "../assets/uploads/products";
                $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                $filename = time() . '.' . $image_ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);

                $finalImage = "assets/uploads/products/" . $filename;
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

            // Insert data into the database
            $result = insert('products', $data);
            if ($result) {
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
    $product_id = $_POST['product_id']; // Corrected initialization of $product_id

    // Check if product exists
    $productData = getById('products', $product_id);
    if (!$productData) {
        redirect('inventory.php', 'No such Product Found');
    }

    // Validate and sanitize input data
    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) ==  true ? 1 : 0;

    // Handle file upload
    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;
        $finalImage = "assets/uploads/products/" . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename)) {
            // Delete old image if update successful
            $deleteImage = "../" . $productData['data']['image'];
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
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $finalImage,
        'status' => $status,
    ];

    // Update data in the database
    $result = update('products', $product_id, $data);

    if ($result) {
        // Successful update
        redirect('inventory.php?id=' . $product_id, 'Product updated successfully.');
    } else {
        // Update failed
        redirect('inventory.php?id=' . $product_id, 'Failed to update product.');
    }
}
