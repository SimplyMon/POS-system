<?php
session_start();


if (!isset($_SESSION['loggedInUser']['name'])) {
    header("Location: login.php");
    exit();
}
