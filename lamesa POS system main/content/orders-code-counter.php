<?php
include '../config/function.php';


if (!isset($_SESSION['productItems'])) {
    $_SESSION['productItems'] = [];
}

if (!isset($_SESSION['productItemIds'])) {
    $_SESSION['productItemIds'] = [];
}

// add item and update item in cart
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $checkProduct = mysqli_query($conn, "SELECT * FROM products WHERE id = '$productId' LIMIT 1");
    if ($checkProduct && mysqli_num_rows($checkProduct) > 0) {
        $row = mysqli_fetch_assoc($checkProduct);
        if ($row['quantity'] < $quantity) {
            jsonResponse(400, 'error', 'Sorry, this item is currently out of stock. We only have ' . $row['quantity'] . ' available.');
        }

        $itemFound = false;
        foreach ($_SESSION['productItems'] as $key => $prodSessionItem) {
            if ($prodSessionItem['product_id'] == $row['id']) {
                $_SESSION['productItems'][$key]['quantity'] = $quantity;
                $itemFound = true;
                break;
            }
        }

        if (!$itemFound) {
            $_SESSION['productItems'][] = [
                'product_id' => $row['id'],
                'name' => $row['name'],
                'image' => $row['image'],
                'price' => $row['price'],
                'quantity' => $quantity
            ];
        }
        jsonResponse(200, 'success', 'Item Added in Cart');
    } else {
        jsonResponse(404, 'error', 'No product found');
    }
}




// Increment and decrement product quantity in DAtabase
if (isset($_POST['productIncDec']) && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $checkProduct = mysqli_query($conn, "SELECT * FROM products WHERE id = '$productId' LIMIT 1");
    if ($checkProduct && mysqli_num_rows($checkProduct) > 0) {
        $row = mysqli_fetch_assoc($checkProduct);
        if ($row['quantity'] < $quantity) {
            jsonResponse(400, 'error', 'Only ' . $row['quantity'] . ' quantity available');
        }

        $itemFound = false;
        foreach ($_SESSION['productItems'] as $key => $prodSessionItem) {
            if ($prodSessionItem['product_id'] == $row['id']) {
                $_SESSION['productItems'][$key]['quantity'] = $quantity;
                $itemFound = true;
                break;
            }
        }

        if (!$itemFound) {
            $_SESSION['productItems'][] = [
                'product_id' => $row['id'],
                'name' => $row['name'],
                'image' => $row['image'],
                'price' => $row['price'],
                'quantity' => $quantity
            ];
        }
        jsonResponse(200, 'success', 'Item updated');
    } else {
        jsonResponse(404, 'error', 'No product found');
    }
}



// proceed to place
if (isset($_POST['proceedToPlaceBtn'])) {

    $name = rand(100000, 999999); // Generate a 6-digit random number
    $payment_mode = validate($_POST['payment_mode']);

    $_SESSION['invoice_no'] = "INV-" . rand(111111, 999999);
    $_SESSION['name'] = $name; // Store the random number in session
    $_SESSION['payment_mode'] = $payment_mode;

    if ($name != '') {
        $data = [
            'name' => $name,
        ];

        $result = insert('customers', $data);

        if ($result) {
            jsonResponse(200, 'success', 'Customer Created Successfully');
        } else {
            jsonResponse(500, 'error', 'Something went wrong');
        }
    } else {
        jsonResponse(422, 'warning', 'Please fill required fields');
    }

    jsonResponse(200, 'success', 'Proceed to Place Order');
}



// Handle saving order
if (isset($_POST['saveOrder'])) {
    // Validate and retrieve session data
    $name = validate($_SESSION['name']); // Use the customer_no session variable
    $invoice_no = validate($_SESSION['invoice_no']);
    $payment_mode = validate($_SESSION['payment_mode']);
    $order_placed_by = $_SESSION['loggedInUser']['id'];

    // Check if customer exists
    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE name = '$name' LIMIT 1");
    if (!$checkCustomer) {
        jsonResponse(500, 'error', 'Database error: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($checkCustomer) > 0) {
        $customerData = mysqli_fetch_assoc($checkCustomer);

        // Check if there are products to order
        if (!isset($_SESSION['productItems'])) {
            jsonResponse(404, 'warning', 'No Items to place order!');
        }

        $sessionProducts = $_SESSION['productItems'];

        // Calculate total amount
        $totalAmount = 0;
        foreach ($sessionProducts as $amtItem) {
            $totalAmount += $amtItem['price'] * $amtItem['quantity'];
        }

        // Prepare order data
        $data = [
            'customer_id' => $customerData['id'],
            'customer_name' => $customerData['name'],
            'tracking_no' => rand(111111, 999999),
            'invoice_no' => $invoice_no,
            'total_amount' => $totalAmount,
            'order_date' => date('Y-m-d'),
            'order_status' => 'pending',
            'payment_mode' => $payment_mode,
            'order_placed_by_id' => $order_placed_by,
        ];

        // Start transaction
        mysqli_autocommit($conn, false);

        // Insert order
        $result = insert('orders', $data);
        if (!$result) {
            mysqli_rollback($conn);
            jsonResponse(500, 'error', 'Database error: ' . mysqli_error($conn));
        }

        $lastOrderId = mysqli_insert_id($conn);

        // Insert order items
        foreach ($sessionProducts as $prodItem) {
            $productId = $prodItem['product_id'];
            $price = $prodItem['price'];
            $quantity = $prodItem['quantity'];

            $dataOrderItem = [
                'order_id' => $lastOrderId,
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity
            ];
            $orderItemQuery = insert('order_items', $dataOrderItem);
            if (!$orderItemQuery) {
                mysqli_rollback($conn);
                jsonResponse(500, 'error', 'Database error: ' . mysqli_error($conn));
            }

            // Update product quantity
            $checkProductQuantityQuery = mysqli_query($conn, "SELECT * FROM products WHERE id = $productId");
            if (!$checkProductQuantityQuery) {
                mysqli_rollback($conn);
                jsonResponse(500, 'error', 'Database error: ' . mysqli_error($conn));
            }

            $productQtyData = mysqli_fetch_assoc($checkProductQuantityQuery);
            $totalProductQuantity = $productQtyData['quantity'] - $quantity;

            $dataUpdate = [
                'quantity' => $totalProductQuantity
            ];

            $updateProductQty = update('products', $productId, $dataUpdate);
            if (!$updateProductQty) {
                mysqli_rollback($conn);
                jsonResponse(500, 'error', 'Database error: ' . mysqli_error($conn));
            }
        }

        // Commit transaction
        mysqli_commit($conn);

        // Clear session variables
        unset($_SESSION['productItemsIds']);
        unset($_SESSION['productItems']);
        unset($_SESSION['cphone']);
        unset($_SESSION['payment_mode']);
        unset($_SESSION['invoice_no']);

        jsonResponse(200, 'success', 'Order placed successfully');
    } else {
        jsonResponse(404, 'warning', 'No customer found');
    }
}
