<?php

require 'config/function.php';



function logoutSession()
{

    unset($_SESSION['loggedIn']);
    unset($_SESSION['loggedInUser']);
}

 if(isset($_SESSION['loggedIn'])){

    logoutSession();
    redirect('index.php', 'Logout Succesfully');
}

