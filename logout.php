<?php
    session_start();
    if ( isset($_SESSION['is-user-logged-in']) ) {
        unset($_SESSION['is-user-logged-in']);
        unset($_SESSION['login-username']);
        header("location: index.php");
    }
?>