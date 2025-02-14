<?php
require '../config/function.php';

// Retrieve and store the 'product_id' parameter from the URL
$paramResultId = checkParamId('id');

if (is_numeric($paramResultId)) { // Check if the retrieved ID is numeric
    $productId = validate($paramResultId); // Validate and sanitize the ID

    // Check if the raw product exists
    $rawProduct = getById('raw_product', $productId);

    if ($rawProduct['status'] == 200) {
        // Proceed to delete the raw product
        $response = delete('raw_product', $productId);
        if ($response) {
            redirect('inventory.php', 'Raw Product Deleted successfully');
        } else {
            redirect('inventory.php', 'Failed to delete raw product');
        }
    } else {
        redirect('inventory.php', $rawProduct['message']);
    }
} else {
    redirect('inventory.php', 'Invalid Product ID'); // Redirect with an error message if the ID is not numeric or other issues occur
}
