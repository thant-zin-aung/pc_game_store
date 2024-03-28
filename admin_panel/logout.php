<?php
    session_start();
    if ( isset($_SESSION['is_admin_logged_in']) ) {
        unset($_SESSION['is_admin_logged_in']);
        unset($_SESSION['admin_username']);
        header("location: login.php");
    }
?>