<?php
include 'partials/header.php';
include '../config/dbconnect.php';

if (!isset($_SESSION['loggedInUser']['name'])) {
    header('Location: login.php');
    exit();
}


$sql_total_users = 'SELECT COUNT(*) AS total_users FROM `accounts`';
$result_total_users = mysqli_query($conn, $sql_total_users);
$row_total_users = mysqli_fetch_assoc($result_total_users);
$total_users = $row_total_users['total_users'];



?>
<title>LaCocina | Accounts</title>

<link rel="stylesheet" href="../css/dashboard.css">

<link rel="stylesheet" href="../css/account.css">

</head>

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
                        <a href="order-create.php"><i class="fa-solid fa-list"></i><span class="Menutext">Order</span></a>
                    </li>
                    <li>
                        <a href="history.php"><i class="fa-solid fa-clock-rotate-left"></i><span class="Menutext">History</span></a>
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
                <div class="logouts"></div>
                <div>
                    <p>ACCOUNTS</p>
                </div>
                <div class="search-container">
                    <a href="search.php" class="search_btn"><i class="fa-solid fa-magnifying-glass"></i>SEARCH</a>
                </div>
            </div>

            <div class="main_container">

                <div class="form-container">
                    <div class="alert-container">
                        <a href="add.php" class="button"><span>Add New</span></a>
                        <?php if (isset($_GET['msg'])) {
                            $msg = $_GET['msg'];
                            echo '<div class="alert alert-warning alert fade show" role="alert">
                                ' . $msg . '
                                </div>';
                        } ?>
                    </div>


                    <table class="table-user">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Staff_id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Position</th>
                                <th scope="col">Created</th>
                                <th scope="col">Updated</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM `accounts`";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['gender']; ?></td>
                                    <td><?php echo $row['position']; ?></td>
                                    <td><?php echo $row['created']; ?></td>
                                    <td><?php echo $row['updated']; ?></td>

                                    <td class="centered-cell">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="link-dark icon-link"><i class="fa-solid fa-pen-to-square fs-3 me-3" style="color: green;"></i></a>
                                        <a href="#" class="link-dark icon-link delete-link" data-id="<?php echo $row['id']; ?>"><i class="fa-solid fa-trash fs-3" style="color: red;"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="total-user">Total Users: <?php echo $total_users; ?></div>
                </div>

            </div>




        </div>
    </div>
    </div>




    <!-- SIDE BAR -->
    <script src="../js/sidebar.js"></script>



    <?php include 'partials/footer.php'; ?>