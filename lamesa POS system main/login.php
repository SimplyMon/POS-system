<?php
require 'config/function.php';

if (isset($_POST['submit'])) {
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    if ($email != '' && $password != '') {
        $query = "SELECT * FROM accounts WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $storedPassword = $row['password'];

                // Use password_verify to check the hashed password
                if (!password_verify($password, $storedPassword)) {
                    redirect('login.php', 'Invalid Password, try again');
                }

                $_SESSION['loggedIn'] = true;
                $_SESSION['loggedInUser'] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'image' => $row['image'],
                    'position' => $row['position']
                ];

                // Redirect based on user position
                if ($row['position'] === 'counter') {
                    redirect('./content/counter.php', 'Redirected to counter page');
                } else {
                    redirect('./content/dashboard.php', 'Logged in successfully');
                }
            } else {
                redirect('login.php', 'Invalid Email, try again');
            }
        } else {
            redirect('login.php', 'Something went wrong');
        }
    } else {
        redirect('login.php', 'Please fill in all fields');
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/login-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Martian+Mono:wght@200&family=Oswald&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Lexend:wght@100..900&family=Russo+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="js/validation.js"></script>
    <link rel="icon" type="image" href="./pictures/lamesalogo.png">
</head>

<body>
    <div class="container-main">
        <!-- LOGO TITLE -->
        <div class="title">
            <a href="../lamesa website main/index.php" target="_blank"><img src="./pictures/logo.png" alt=""></a>
            <h1>La Cucina Inasal</h1>
        </div>
        <!-- FORM LOGIN -->
        <div class="login_container">
            <div class="login_title">
                <p>POS System Login</p>
            </div>
            <form name="f1" action="login.php" method="post">
                <div class="login_input_container">
                    <label>Username: </label>
                    <input type="text" id="email" name="email" placeholder="Email" required />
                </div>
                <div class="login_input_container">
                    <label>Password: </label>
                    <input type="password" placeholder="Password" id="password" name="password" required />
                </div>
                <div class="remember">
                    <input type="checkbox" onclick="togglePwd()">Show Password</label>
                </div>
                <div class="button_container">
                    <input type="submit" id="btn" value="Login" name="submit" />
                </div>
                <p class="err-msg" style="color: red;"><?php alertMessage(); ?></p>
            </form>
        </div>
    </div>



    <script>
        function validation() {
            var id = document.f1.user.value;
            var ps = document.f1.pass.value;
            if (id.length == "" && ps.length == "") {
                alert("User Name and Password fields are empty");
                return false;
            } else {
                if (id.length == "") {
                    alert("User Name is empty");
                    return false;
                }
                if (ps.length == "") {
                    alert("Password field is empty");
                    return false;
                }
            }
        }
    </script>
</body>




</script>
</body>

</html>