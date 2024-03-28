<?php
    include('admin_panel/db-connect.php');
    include('admin_panel/db-executions.php');
    if ( isset($_POST['place_order']) ) {
        $loginUsername = trim($_POST['loginUsername']);
        $paymentType = $_POST['paymentType'];
        $transactionId = $_POST['transactionId'];
        if ( isset($_POST['noScreenshot']) ) {
            $uploadedScreenshot = NULL;
        } else {
            $uploadedScreenshot = $_FILES['uploadedScreenshot'];
        }
        // place_order($connect,$loginUsername,$paymentType,$transactionId,$uploadedScreenshot);
        $status = place_order($connect,$loginUsername,$paymentType,$transactionId,$uploadedScreenshot);
        echo json_encode(["status"=>$status]);
    }
?>