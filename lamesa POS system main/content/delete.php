<?php

include "../connect/dbconnect.php";

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id > 0) {
  $sql = "SELECT `image` FROM `accounts` WHERE `id` = $id";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $deleteSql = "DELETE FROM `accounts` WHERE `id` = $id";
    $deleteResult = mysqli_query($conn, $deleteSql);

    if ($deleteResult) {
      $deleteImage = "../" . $row['image'];
      if (file_exists($deleteImage) && !is_dir($deleteImage)) {
        unlink($deleteImage);
      }
      header("Location: account.php?msg=Data deleted successfully");
    } else {
      header("Location: account.php?msg=Failed to delete data");
    }
  } else {
    header("Location: account.php?msg=Record not found");
  }
} else {
  header("Location: account.php?msg=Invalid ID");
}

exit;
