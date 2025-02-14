<?php
$server = "localhost";
$userid = "root";
$pwd = "";
$dbname = "lamesa";
$conn = mysqli_connect($server, $userid, $pwd, $dbname);



if (!$conn)
  die("Connection Error: " . mysqli_connect_error());
