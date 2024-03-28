<?php
    include('admin_panel/db-connect.php');
    include('admin_panel/db-executions.php');

    if(isset($_POST['addItem']) ) {
        $loginUsername = $_POST['loginUsername'];
        $gameId = (int)$_POST['gameId'];
        $addItemStatus = add_item_to_cart($connect,$loginUsername,$gameId);
        $cartItemList = get_cart_item_list($connect,$loginUsername);
        echo json_encode(["status"=>$addItemStatus,"cart_item_list"=>$cartItemList]);
    }
    if (isset($_POST['deleteItem']) ) {
        $loginUsername = $_POST['loginUsername'];
        $gameId = (int)$_POST['gameId'];
        $deleteItemStatus = delete_item_from_cart($connect,$loginUsername,$gameId);
        $cartItemList = get_cart_item_list($connect,$loginUsername);
        echo json_encode(["status"=>$deleteItemStatus,"cart_item_list"=>$cartItemList]);
    }
    if (isset($_POST['getItem']) ) {
        $loginUsername = $_POST['loginUsername'];
        $cartItemList = get_cart_item_list($connect,$loginUsername);
        echo json_encode(["cart_item_list"=>$cartItemList]);
    }
?>