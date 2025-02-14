<?php
require '../config/function.php';


$paramResultId = checkParamId('id'); // Retrieve and store the 'id' parameter from the URL

if (is_numeric($paramResultId)) { // Check if the retrieved ID is numeric

    $productId = validate($paramResultId); // Validate and sanitize the ID

    $product = getById('products', $productId);

    if ($product['status'] == 200) {
        $response = delete('products', $productId);
        if ($response) {
            $deleteImage = "../" . $product['data']['image'];
            if (file_exists($deleteImage)) {
                unlink($deleteImage);
            }
            redirect('inventory.php', 'Product Deleted success');
        } else {
            redirect('inventory.php', 'Invalid something');
        }
    } else {
        redirect('inventory.php', $product['message']);
    }
} else {
    redirect('inventory.php', 'Invalid ID'); // Redirect with an error message if the ID is not numeric or other issues occur
}
