<?php

include 'partials/header.php';



$id = $_GET['id'];
if (isset($_POST['updateAccount'])) {
    $account_id = $_POST['id'];

    // check kung may id acc sa database
    $accountData = getById('accounts', $account_id);
    if (!$accountData) {
        redirect('account.php', 'No such Account Found');
        exit;
    }

    $name = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $position = $_POST['position'];
    $created = $_POST['created'];
    $updated = $_POST['updated'];

    // Hash the password if it is provided
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // Keep the existing hashed password if no new password is provided
        $hashedPassword = $accountData['password'];
    }

    // Handle file upload
    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/profile";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;
        $finalImage = "assets/uploads/profile/" . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename)) {
            // Delete old image if update successful
            $deleteImage = "../" . $accountData['image']; // Access image directly from accountData
            if (file_exists($deleteImage)) {
                unlink($deleteImage);
            }
        } else {
            // Handle upload failure
            $finalImage = $accountData['image']; // Revert to old image if upload fails
        }
    } else {
        // No new image uploaded, keep existing image
        $finalImage = $accountData['image'];
    }

    // Update the database with the hashed password
    $sql = "UPDATE `accounts` SET 
                `name`='$name', 
                `password`='$hashedPassword', 
                `email`='$email', 
                `gender`='$gender', 
                `position`='$position', 
                `created`='$created', 
                `updated`='$updated', 
                `image`='$finalImage' 
            WHERE `id` = $account_id";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        header('Location: account.php?msg=Data updated successfully');
    } else {
        echo 'Failed: ' . mysqli_error($conn);
    }
}
?>


<link rel="stylesheet" href="../css/dashboard.css">

<link rel="stylesheet" href="../css/account.css">

<body>


    <div class="dashboard_container">
        <div class="dashboard_sidebar" id="dashboard_sidebar">
            <div class="dashboard_sidebar_logo" id="logo_dashs">
                <img src="../pictures/logo.png" alt="">
                <p class="title_dash">La Cocina Inasal</p>
            </div>
            <div class="dashboard_sidebar_content">
                <ul class="dashboard_sidebar_list" id="dashboard_sidebar_list">
                    <div id="hamburger">
                        <a href="" id="hamburger_button"><i class="fa-solid fa-bars"></i></a>
                    </div>
                    <div class="features">
                        <p>Features</p>
                    </div>
                    <li class="list active">
                        <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i><span class="Menutext">Dashboard</span></a>
                    </li>
                    <li>
                        <a href="content/order.php"><i class="fa-solid fa-list"></i><span class="Menutext">Order</span></a>
                    </li>
                    <li>
                        <a href="content/history.php"><i class="fa-solid fa-clock-rotate-left"></i><span class="Menutext">History</span></a>
                    </li>
                    <li>
                        <a href="content/inventory.php"><i class="fa-solid fa-clipboard-list"></i><span class="Menutext">Inventory</span></a>
                    </li>
                    <li style="background-color: #ffffff3f;">
                        <a href="account.php"><i class="fa-solid fa-user MenuIcons"></i><span class="Menutext">Accounts</span></a>
                    </li>
                </ul>
            </div>
            <div class="profile-txt">
                <p>Profile</p>
            </div>
            <div class="logout">
                <a href="../logout.php" id="logout_button"><i class="fa-solid fa-right-from-bracket"></i><span class="Menutext">Logout</span></a>
            </div>

            <div class="dashboard_sidebar_profile">
                <img src='../<?= ($_SESSION['loggedInUser']['image']) ?>' id="logo_dash" alt="User Profile">
                <div class="title_dash">
                    <p>
                        <?php if (isset($_SESSION['loggedInUser']['name'])) {
                            echo '<p>' . ($_SESSION['loggedInUser']['name']) . '</p>';
                        } ?>
                    </p>
                    <p style="font-size: 14px; color: rgba(255, 255, 255, 0.562);">
                        <?php if (isset($_SESSION['loggedInUser']['position'])) {
                            echo htmlspecialchars($_SESSION['loggedInUser']['position']);
                        } ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="dashboard_mainbar" id="dashboard_mainbar">
            <div class="dashboard_topnav">
                <div></div>
                <div>
                    <p>Edit Accounts</p>
                </div>
                <div class="logouts">
                    <a href="logout.php" id="logout_button"></a>
                </div>
            </div>

            <div class="main_container">
                <div class="main_edit_container">
                    <div class="edit-container">
                        <div class="user-info">
                            <h3>Edit User Information</h3>
                            <p class="text-def">Click update after changing any information</p>
                        </div>

                        <div class="form-edit-container">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <?php
                                // QUERY
                                $sql = "SELECT * FROM `accounts` WHERE id = $id LIMIT 1";
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);
                                ?>

                                <div class="row">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>"> <!-- Hidden input for id -->
                                    <div class="col">
                                        <label class="form-label">Name:</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>">
                                    </div>

                                    <div class="col">
                                        <label class="form-label">Username:</label>
                                        <input class="form-control" name="email" value="<?php echo $row['email']; ?>">
                                    </div>

                                    <div class="col">
                                        <label class="form-label">Password:</label>
                                        <input type="text" class="form-control" name="password" value="<?php echo $row['password']; ?>">
                                    </div>

                                    <div class="col-edit">
                                        <label class="form-label">Position:</label>
                                        <?php $position = $row['position']; ?>
                                        <select class="form-control" name="position" id="position-select">
                                            <option value="">-- Select Position --</option>
                                            <option value="owner" <?php if ($position == 'owner') echo 'selected'; ?>>Owner</option>
                                            <option value="manager" <?php if ($position == 'manager') echo 'selected'; ?>>Manager</option>
                                            <option value="counter" <?php if ($position == 'counter') echo 'selected'; ?>>Counter</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Gender:</label>
                                        <div class="gender-options">
                                            <?php $gender = $row['gender']; ?>
                                            <input type="radio" class="form-check-input" name="gender" id="male" value="male" <?php if ($gender == 'male') echo 'checked'; ?>>
                                            <label for="male" class="form-input-label">Male</label>
                                            <input type="radio" class="form-check-input" name="gender" id="female" value="female" <?php if ($gender == 'female') echo 'checked'; ?>>
                                            <label for="female" class="form-input-label">Female</label>
                                        </div>
                                    </div>

                                    <div class="cole1">
                                        <label class="form-label">Created:</label>
                                        <input type="text" class="form-control datepicker" name="created" id="created_date" value="<?php echo $row['created']; ?>">
                                    </div>
                                    <div class="cole2">
                                        <label class="form-label">Updated:</label>
                                        <input type="text" class="form-control datepicker" name="updated" id="updated_date" value="<?php echo $row['updated']; ?>">
                                    </div>
                                    <div class="">
                                        <label for="">Image *</label>
                                        <input type="file" name="image" class="form-control">
                                        <img src="../<?= $row['image'] ?>" style="width: 50px; height: 50px" alt="img" />
                                    </div>
                                </div>
                                <div class="buttons-add">
                                    <a href="account.php" class="btn1 btn-danger">Cancel</a>
                                    <button type="submit" class="btn2 btn-success" name="updateAccount">Update<i class="fa-solid fa-user-check"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SIDE BAR -->
    <script src="../js/sidebar.js"></script>

    <!-- DATE PICKER -->
    <script src="../js/date-picker.js"></script>


    <?php include 'partials/footer.php'; ?>