<?php
require '../lamesa POS system main/config/function.php';

$paramResult = checkParamId('index');

if (is_numeric($paramResult)) {

    $indexValue = validate($paramResult);

    if (isset($_SESSION['productItems']) && isset($_SESSION['productItemIds'])) {

        unset($_SESSION['productItems'][$indexValue]);
        unset($_SESSION['productItemIds'][$indexValue]);
        redirect('order.php', 'item removed');
    } else {
        redirect('order.php', 'there is now item');
    }
} else {
    redirect('order.php', 'param is not numeric');
}
