<?php
include 'partials/header.php';


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
                <div class="search-container">
                    <a href="account.php" class="back_btn"><i class="fa-solid fa-arrow-left"></i>BACK</a>
                </div>
                <div>
                    <p>Find Accounts</p>
                </div>
                <div class="logouts">
                </div>
            </div>
            <div class="main_container">
                <div class="search_container">
                    <form method="GET" action="">
                        <input type="text" name="search" placeholder="Search...">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <?php
                if (isset($_GET['search'])) {
                    $search = $_GET['search'];
                    $sql = "SELECT * FROM `accounts` WHERE `name` LIKE '%$search%' OR `email` LIKE '%$search%' OR `id` LIKE '%$search%'";
                } else {
                    $sql = "SELECT * FROM `accounts`";
                }

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) == 0) {
                    echo '<p style="color: #00000094; font-size: 20px; padding-left: 20px; margin-bottom:600px; margin-top: 200px; text-align: center;">No records found.</p>';
                } else {
                ?>
                    <table class="table-user">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Staff_id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Password</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Position</th>
                                <th scope="col">Created</th>
                                <th scope="col">Updated</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['password']; ?></td>
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
                <?php
                }
                ?>
            </div>
        </div>





    </div>
    </div>


    <!-- SIDE BAR -->
    <script src="../js/sidebar.js"></script>