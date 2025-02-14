<?php
include 'partials/header.php';

if (isset($_POST['saveAccount'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $position = $_POST['position'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/profile";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);
        $finalImage = "assets/uploads/profile/" . $filename;
    } else {
        $finalImage = '';
    }

    $sql = 'INSERT INTO `accounts`(`name`, `gender`, `email`, `password`, `position`, `created`, `updated`, `image`) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?)';

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssss', $name, $gender, $email, $hashedPassword, $position, $finalImage);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        header('Location: account.php?msg=New record created successfully');
    } else {
        echo 'Failed: ' . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
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
                    <li>
                        <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i><span class="Menutext">Dashboard</span></a>
                    </li>
                    <li>
                        <a href="content/order.php"><i class="fa-solid fa-list"></i><span class="Menutext">Order</span></a>
                    </li>
                    <li>
                        <a href="content/history.php"><i class="fa-solid fa-clock-rotate-left"></i><span class="Menutext">History</span></a>
                    </li>
                    <li>
                        <a href="inventory.php"><i class="fa-solid fa-clipboard-list"></i><span class="Menutext">Inventory</span></a>
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
                <div>
                </div>
                <div>
                    <p>Add Accounts</p>
                </div>
                <div class="logouts">
                </div>
            </div>



            <div class="main_container">
                <div class="main_add_container">
                    <div class="container">
                        <div class="text-center">
                            <h3>Add New Account</h3>
                        </div>

                        <div class="add-container">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col1">
                                        <label class="form-label">Name:</label>
                                        <input type="text" class="form-control" name="name" placeholder="name" required>
                                    </div>

                                    <div class="col3">
                                        <label class="form-label">Username:</label>
                                        <input class="form-control" name="email" placeholder="username" required>
                                    </div>

                                    <div class="col2">
                                        <label class="form-label">Password:</label>
                                        <input type="text" class="form-control" name="password" placeholder="password" required>
                                    </div>

                                    <div class="col4">
                                        <label class="form-label">Position:</label>
                                        <select class="form-control" name="position" required id="position-select">
                                            <option value="">-- Select Position --</option>
                                            <option value="owner">Owner</option>
                                            <option value="manager">Manager</option>
                                            <option value="counter">Counter</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Gender:</label>
                                        <div class="gender-options">
                                            <input type="radio" class="form-check-input" name="gender" id="male" value="male" required>
                                            <label for="male" class="form-input-label">Male</label>
                                            <input type="radio" class="form-check-input" name="gender" id="female" value="female" required>
                                            <label for="female" class="form-input-label">Female</label>
                                        </div>
                                    </div>

                                    <div class="col5">
                                        <label class="form-label">Created:</label>
                                        <input type="text" class="form-control" name="created" placeholder="MM/DD/YY" id="created_date" required>
                                    </div>

                                    <div class="col6">
                                        <label class="form-label">Updated:</label>
                                        <input type="text" class="form-control" name="updated" placeholder="MM/DD/YY" id="updated_date" required>
                                    </div>

                                    <div class="">
                                        <label for="">Image *</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>


                                </div>
                                <div class="buttons-add">

                                    <a href="account.php" class="btn1">Cancel</a>
                                    <button type="submit" class="btn2" name="saveAccount">Add<i class="fa-solid fa-plus"></i></button>
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