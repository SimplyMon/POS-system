<?php
require '../config/function.php';


$paramResultId = checkParamId('id'); // Retrieve and store the 'id' parameter from the URL

if (is_numeric($paramResultId)) { // Check if the retrieved ID is numeric

    $categoryId = validate($paramResultId); // Validate and sanitize the ID

    $category = getById('categories', $categoryId);

    if ($category['status'] == 200) {
        $response = delete('categories', $categoryId);
        if ($response) {
            redirect('inventory.php', 'Category Deleted success');
        } else {
            redirect('inventory.php', 'Invalid something');
        }
    } else {
        redirect('inventory.php', $category['message']);
    }
} else {
    redirect('inventory.php', 'Invalid ID'); // Redirect with an error message if the ID is not numeric or other issues occur
}
