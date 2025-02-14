<?php
require '../config/function.php';
if (!isset($_SESSION['loggedInUser']['name'])) {
    header('Location: ../index.php');
    exit();
}


?>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Set character encoding to UTF-8 -->
    <meta charset="UTF-8">

    <!-- Set the viewport to ensure the page is responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link to Font Awesome for icon fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Preconnect to Google Fonts for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <!-- Preconnect to Google Fonts' static content delivery -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Link to Google Font 'Material Symbols Outlined' -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Link to Google Fonts: 'Kanit' and 'Poppins' -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Link to AlertifyJS CSS for alert and notification styles -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/default.min.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->