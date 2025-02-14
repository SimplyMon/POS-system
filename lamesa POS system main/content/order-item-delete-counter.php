<?php
require '../config/function.php';

$paramResult = checkParamId('index');

if (is_numeric($paramResult)) {

    $indexValue = validate($paramResult);

    if (isset($_SESSION['productItems']) && isset($_SESSION['productItemIds'])) {

        unset($_SESSION['productItems'][$indexValue]);
        unset($_SESSION['productItemIds'][$indexValue]);
        redirect('counter.php', 'item removed');
    } else {
        redirect('counter.php', 'there is now item');
    }
} else {
    redirect('counter.php', 'param is not numeric');
}
