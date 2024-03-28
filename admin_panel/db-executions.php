<?php
    // include('db-connect.php');
    date_default_timezone_set("Asia/Yangon");
    include('app-config.php');
    function get_last_game_id($connect) {
        $lastGameId;
        $lastGameIdQuery = "SELECT id FROM game_list ORDER BY id DESC LIMIT 1";
        $lastGameIdQueryResult = mysqli_query($connect,$lastGameIdQuery);
        for ( $count = 0 ; $count < 1 ; $count++ ) {
            $lastGameIdQueryData = mysqli_fetch_array($lastGameIdQueryResult);
            $lastGameId = $lastGameIdQueryData['id'];
            break;
        }
        return $lastGameId;
    }

    function check_if_admin_already_exists($connect,$adminUsername) {
        $checkAdminQuery = "SELECT admin_username FROM admin_list WHERE admin_username='$adminUsername'";
        $checkAdminQueryResult = mysqli_query($connect,$checkAdminQuery);
        $totalAdmin = mysqli_num_rows($checkAdminQueryResult);
        return $totalAdmin>0 ? true : false;
    }

    function check_if_customer_already_exists($connect,$customerUsername) {
        $checkCustomerQuery = "SELECT customer_username FROM customer_list WHERE customer_username='$customerUsername'";
        $checkCustomerQueryResult = mysqli_query($connect,$checkCustomerQuery);
        $totalCustomer = mysqli_num_rows($checkCustomerQueryResult);
        return $totalCustomer>0 ? true : false;
    }

    function set_and_get_customer_data_to_array($customerData) {
        $customerDataArray = array();
        $customerDataArray['id'] = $customerData['id'];
        $customerDataArray['customer_username'] = $customerData['customer_username'];
        $customerDataArray['customer_password'] = $customerData['customer_password'];
        $customerDataArray['recovery_email'] = $customerData['recovery_email'];
        $customerDataArray['creation_date'] = $customerData['creation_date'];
        $customerDataArray['creation_time'] = $customerData['creation_time'];
        return $customerDataArray;
    }

    function get_total_customer_count($connect) {
        $totalCustomerQuery = "SELECT count(id) as total_customer FROM customer_list";
        $totalCustomerQueryResult = mysqli_query($connect,$totalCustomerQuery);
        return (int)mysqli_fetch_array($totalCustomerQueryResult)['total_customer'];
    }

    function get_customer_list($connect,$limit) {
        $customerList = array();
        $customerListQuery = "SELECT * FROM customer_list ORDER BY id DESC LIMIT $limit";
        $customerListQueryResult = mysqli_query($connect,$customerListQuery);
        $totalCustomer = mysqli_num_rows($customerListQueryResult);
        for ( $count=0 ; $count<$totalCustomer ; $count++ ) {
            $customerData = mysqli_fetch_array($customerListQueryResult);
            array_push($customerList,set_and_get_customer_data_to_array($customerData));
        }
        return $customerList;
    }

    function get_customer_info_by_username($connect,$loginUsername) {
        $customerDataArray = array();
        $customerQuery = "SELECT * FROM customer_list WHERE customer_username='$loginUsername'";
        $customerQueryResult = mysqli_query($connect,$customerQuery);
        $customerData = mysqli_fetch_array($customerQueryResult);
        $customerDataArray = set_and_get_customer_data_to_array($customerData);
        return $customerDataArray;
    }
    
    function get_customer_info_by_id($connect,$customerId) {
        $customerDataArray = array();
        $customerQuery = "SELECT * FROM customer_list WHERE id=$customerId";
        $customerQueryResult = mysqli_query($connect,$customerQuery);
        $customerData = mysqli_fetch_array($customerQueryResult);
        $customerDataArray = set_and_get_customer_data_to_array($customerData);
        return $customerDataArray;
    }

    function get_available_genre_list($connect) {
        $genreListArray = array();
        $genreQuery = "SELECT * FROM genre_list";
        $genreQueryResult = mysqli_query($connect,$genreQuery);
        $totalGenre = mysqli_num_rows($genreQueryResult);
        for ( $count = 0 ; $count < $totalGenre ; $count++ ) {
            $genreDataArray = array();
            $genreData = mysqli_fetch_array($genreQueryResult);
            $genreDataArray['id'] = $genreData['id'];
            $genreDataArray['genre_type'] = $genreData['genre_type'];
            array_push($genreListArray,$genreDataArray);
        }
        return $genreListArray;
    }

    function get_total_game($connect) {
        $totalGameQuery = "SELECT count(id) as total_game FROM game_list";
        $totalGameQueryResult = mysqli_query($connect,$totalGameQuery);
        return mysqli_fetch_array($totalGameQueryResult)['total_game'];
    }
    function get_total_online_or_offline_game($connect,$isOnline) {
        $isOnline = $isOnline?"yes":"no";
        $totalGameQuery = "SELECT count(id) as total_game FROM game_list WHERE is_online_game='$isOnline'";
        $totalGameQueryResult = mysqli_query($connect,$totalGameQuery);
        return mysqli_fetch_array($totalGameQueryResult)['total_game'];
    }

    function set_and_get_game_data_to_array($gameData) {
        $gameDataArray = array();
        $gameDataArray['id'] = $gameData['id'];
        $gameDataArray['title'] = $gameData['title'];
        $gameDataArray['developer'] = $gameData['developer'];
        $gameDataArray['publisher'] = $gameData['publisher'];
        $gameDataArray['download_size'] = $gameData['download_size'];
        $gameDataArray['is_online_game'] = $gameData['is_online_game'];
        $gameDataArray['profile_image'] = $gameData['profile_image'];
        $gameDataArray['additional_image_1'] = $gameData['additional_image_1'];
        $gameDataArray['additional_image_2'] = $gameData['additional_image_2'];
        $gameDataArray['additional_image_3'] = $gameData['additional_image_3'];
        $gameDataArray['additional_image_4'] = $gameData['additional_image_4'];
        $gameDataArray['trailer_link'] = $gameData['trailer_link'];
        $gameDataArray['download_link_1'] = $gameData['download_link_1'];
        $gameDataArray['download_link_2'] = $gameData['download_link_2'];
        $gameDataArray['spec_min_os'] = $gameData['spec_min_os'];
        $gameDataArray['spec_min_processor'] = $gameData['spec_min_processor'];
        $gameDataArray['spec_min_memory'] = $gameData['spec_min_memory'];
        $gameDataArray['spec_min_graphics'] = $gameData['spec_min_graphics'];
        $gameDataArray['spec_min_storage'] = $gameData['spec_min_storage'];
        $gameDataArray['spec_min_directx'] = $gameData['spec_min_directx'];
        $gameDataArray['spec_max_os'] = $gameData['spec_max_os'];
        $gameDataArray['spec_max_processor'] = $gameData['spec_max_processor'];
        $gameDataArray['spec_max_memory'] = $gameData['spec_max_memory'];
        $gameDataArray['spec_max_graphics'] = $gameData['spec_max_graphics'];
        $gameDataArray['spec_max_storage'] = $gameData['spec_max_storage'];
        $gameDataArray['spec_max_directx'] = $gameData['spec_max_directx'];
        return $gameDataArray;
    }

    function get_game_info($connect,$gameId) {
        $gameId = (int)$gameId;
        $gameDataArray = array();
        $gameInfoQuery = "SELECT * FROM game_list WHERE id=$gameId";
        $gameInfoQueryResult = mysqli_query($connect,$gameInfoQuery);
        $gameData = mysqli_fetch_array($gameInfoQueryResult);
        $gameDataArray = set_and_get_game_data_to_array($gameData);
        return $gameDataArray;
    }

    function get_genre_of_game($connect,$gameId) {
        $genreListArray = array();
        $genreQuery = "SELECT * FROM genre_list as gl INNER JOIN game_genre_list as ggl ON gl.id=ggl.genre_id WHERE ggl.game_id=$gameId";
        $genreQueryResult = mysqli_query($connect,$genreQuery);
        $totalGenre = mysqli_num_rows($genreQueryResult);
        for ( $count = 0; $count < $totalGenre ; $count++ ) {
            $genreDataArray = array();
            $genreData = mysqli_fetch_array($genreQueryResult);
            $genreDataArray['id']=$genreData['genre_id'];
            $genreDataArray['genre_type']=$genreData['genre_type'];
            array_push($genreListArray,$genreDataArray);
        }
        return $genreListArray;
    }

    function get_genre_type_by_genre_id($connect,$genreId) {
        $genreQuery = "SELECT * FROM genre_list WHERE id=$genreId";
        $genreQueryResult = mysqli_query($connect,$genreQuery);
        $genreData = mysqli_fetch_array($genreQueryResult);
        return $genreData['genre_type'];
    }

    function get_game_list($connect,$startIndex,$totalGame) {
        if ( $startIndex <= 0 ) $startIndex++;
        else $startIndex--;
        $gameListArray = array();
        $gameListQuery = "SELECT * FROM game_list ORDER BY id DESC LIMIT $startIndex,$totalGame";
        $gameListQueryResult = mysqli_query($connect,$gameListQuery);
        for ( $count = 0; $count < $totalGame ; $count++ ) {
            $gameDataArray = array();
            $gameData = mysqli_fetch_array($gameListQueryResult);
            $gameDataArray = set_and_get_game_data_to_array($gameData);
            array_push($gameListArray,$gameDataArray);
        }
        return $gameListArray;
    }

    function get_game_list_by_genre_list($connect,...$genreList) {
        $gameListArray = array();
        $gameListQuery = "SELECT * FROM game_list as gl INNER JOIN game_genre_list as ggl ON gl.id=ggl.game_id";
        for ( $count = 0 ; $count < count($genreList) ; $count++ ) {
            if ( $count == 0 ) $gameListQuery .= " WHERE ggl.genre_id=".$genreList[$count]['id'];
            else $gameListQuery .= " OR ggl.genre_id=".$genreList[$count]['id'];
        }
        $gameListQuery .= " GROUP BY ggl.game_id";
        $gameListQueryResult = mysqli_query($connect,$gameListQuery);
        $totalGame = mysqli_num_rows($gameListQueryResult);
        for ( $count = 0 ; $count < $totalGame; $count++ ) {
            $gameData = mysqli_fetch_array($gameListQueryResult);
            $gameDataArray = set_and_get_game_data_to_array($gameData);
            array_push($gameListArray,$gameDataArray);
        }
        return $gameListArray;
    }

    function get_game_list_by_genre_id_using_range($connect,$genreId,$startIndex,$totalGame) {
        $gameListArray = array();
        $gameListQuery = "SELECT * FROM game_list as gl INNER JOIN game_genre_list as ggl ON gl.id=ggl.game_id WHERE ggl.genre_id=$genreId ORDER BY id DESC LIMIT $startIndex,$totalGame";
        $gameListQueryResult = mysqli_query($connect,$gameListQuery);
        $totalGame = mysqli_num_rows($gameListQueryResult);
        for ( $count = 0 ; $count < $totalGame; $count++ ) {
            $gameData = mysqli_fetch_array($gameListQueryResult);
            $gameDataArray = set_and_get_game_data_to_array($gameData);
            array_push($gameListArray,$gameDataArray);
        }
        return $gameListArray;
    }

    function get_game_list_by_game_title_keyword_using_range($connect,$keyword,$startIndex,$totalGame) {
        $keywordGameList = array();
        $keywordGameListQuery = "SELECT * FROM game_list WHERE title LIKE '%%$keyword%%' ORDER BY id DESC LIMIT $startIndex,$totalGame";
        $keywordGameListQueryResult = mysqli_query($connect,$keywordGameListQuery);
        $totalKeywordGames = mysqli_num_rows($keywordGameListQueryResult);
        for ( $count = 0 ; $count < $totalKeywordGames ; $count++ ) {
            $keywordGameData = mysqli_fetch_array($keywordGameListQueryResult);
            array_push($keywordGameList,set_and_get_game_data_to_array($keywordGameData));
        }
        return $keywordGameList;
    }

    function get_game_list_by_genre_id($connect,$genreId) {
        $genre = [
            "id" => $genreId,
            "genre_type" => "none"
        ];
        $genreList = [$genre];
        return get_game_list_by_genre_list($connect,...$genreList);
    }
// 
    function get_online_game_list($connect) {
        $gameListArray = array();
        $gameListQuery = "SELECT * FROM game_list WHERE is_online_game='yes' ORDER BY id DESC";
        $gameListQueryResult = mysqli_query($connect,$gameListQuery);
        $totalGame = mysqli_num_rows($gameListQueryResult);
        for ( $count = 0 ; $count < $totalGame; $count++ ) {
            $gameData = mysqli_fetch_array($gameListQueryResult);
            $gameDataArray = set_and_get_game_data_to_array($gameData);
            array_push($gameListArray,$gameDataArray);
        }
        return $gameListArray;
    }

    function get_online_game_list_using_range($connect,$startIndex,$totalGame) {
        $gameListArray = array();
        $gameListQuery = "SELECT * FROM game_list WHERE is_online_game='yes' ORDER BY id DESC LIMIT $startIndex,$totalGame";
        $gameListQueryResult = mysqli_query($connect,$gameListQuery);
        $totalGame = mysqli_num_rows($gameListQueryResult);
        for ( $count = 0 ; $count < $totalGame; $count++ ) {
            $gameData = mysqli_fetch_array($gameListQueryResult);
            $gameDataArray = set_and_get_game_data_to_array($gameData);
            array_push($gameListArray,$gameDataArray);
        }
        return $gameListArray;
    }


    function check_item_exist_in_cart($connect,$loginUsername,$gameId) {
        $itemExistQuery = "SELECT * FROM cart WHERE customer_username='$loginUsername' AND game_id=$gameId";
        $totalResult = mysqli_num_rows(mysqli_query($connect,$itemExistQuery));
        return $totalResult>0 ? true : false;
    }

    function add_item_to_cart($connect,$loginUsername,$gameId) {
        if ( check_item_exist_in_cart($connect,$loginUsername,$gameId) ) return "exist";
        $addItemCartQuery = "INSERT INTO cart(customer_username,game_id) VALUES('$loginUsername',$gameId)";
        $addItemCartQueryResult = mysqli_query($connect,$addItemCartQuery);
        return $addItemCartQueryResult?"success":"fail";
    }

    function delete_item_from_cart($connect,$loginUsername,$gameId) {
        if ( !check_item_exist_in_cart($connect,$loginUsername,$gameId) ) return "not_exist";
        $deleteItemQuery = "DELETE FROM cart WHERE game_id=$gameId";
        $deleteItemQueryResult = mysqli_query($connect,$deleteItemQuery);
        return $deleteItemQueryResult?"success":"fail";
    }

    function get_cart_item_list($connect,$loginUsername) {
        $cartItemListArray = array();
        $cartItemListQuery = "SELECT * FROM cart WHERE customer_username='$loginUsername'";
        $cartItemListQueryResult = mysqli_query($connect,$cartItemListQuery);
        $totalCartItem = mysqli_num_rows($cartItemListQueryResult);
        for ( $count = 0 ; $count < $totalCartItem ; $count++ ) {
            $cartItemData = mysqli_fetch_array($cartItemListQueryResult);
            $gameId = (int)$cartItemData['game_id'];
            array_push($cartItemListArray,$gameId);
        }
        return $cartItemListArray;
    }

    function remove_cart_items($connect,$loginUsername) {
        $removeCartItemQuery = "DELETE FROM cart WHERE customer_username='$loginUsername'";
        return mysqli_query($connect,$removeCartItemQuery);
    }

    function get_last_order_id($connect,$customerId) {
        $lastOrderQuery = "SELECT id FROM order_list WHERE customer_id=$customerId ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($connect,$lastOrderQuery);
        return (int)mysqli_fetch_array($result)['id'];
    }

    function upload_screenshot($uploadedScreenshot,$destFileName) {
        $imageFileExt = strtolower(pathinfo($uploadedScreenshot["name"],PATHINFO_EXTENSION));
        $destFile = "admin_panel/screenshot_images/".$destFileName.".".$imageFileExt;
        return move_uploaded_file($uploadedScreenshot["tmp_name"], $destFile);
    }

    function place_order($connect,$loginUsername,$paymentType,$transactionId,$uploadedScreenshot) {
        global $unitPrice;
        $cartItemList = get_cart_item_list($connect,$loginUsername);
        $orderDate = date("Y-m-d");
        $orderTime = date("H:i:s");
        $totalGame = count($cartItemList);
        $totalPrice = $totalGame * $unitPrice;
        $orderStatus = "pending";
        $customerId = (int)get_customer_info_by_username($connect,$loginUsername)['id'];
        // paymentProcessMethod
        $isTransactionIdEmpty = empty($transactionId);
        $isUploadedScreenshotNull = $uploadedScreenshot == NULL;
        if ( !$isTransactionIdEmpty && !$isUploadedScreenshotNull ) $paymentProcessMethod="screenshot+transaction_id";
        else if ( !$isTransactionIdEmpty ) $paymentProcessMethod = "transaction_id";
        else if ( !$isUploadedScreenshotNull ) $paymentProcessMethod = "screenshot";
        // paymentProcessMethod
        $placeOrderQueryResult;
        if ( !$isUploadedScreenshotNull ) {
            $isScreenshotUploadOK = false;
            $destFileName = $loginUsername."_screenshot_".$orderDate."_".str_replace(":","-",$orderTime);
            $imageFileExt = strtolower(pathinfo($uploadedScreenshot["name"],PATHINFO_EXTENSION));
            $destFilePath = "admin_panel/screenshot_images/".$destFileName.".".$imageFileExt;
            $isScreenshotUploadOK = upload_screenshot($uploadedScreenshot,$destFileName);
            $placeOrderQuery = "INSERT INTO order_list(order_date,order_time,total_game,total_amount,payment_type,payment_process_method,transaction_id,uploaded_image,order_status,customer_id) VALUES(
                '$orderDate','$orderTime',$totalGame,$totalPrice,'$paymentType','$paymentProcessMethod','$transactionId','$destFilePath','$orderStatus',$customerId
            )";
            if ( $isScreenshotUploadOK ) {
                $placeOrderQueryResult = mysqli_query($connect,$placeOrderQuery);
            }
        } else {
            $placeOrderQuery = "INSERT INTO order_list(order_date,order_time,total_game,total_amount,payment_type,payment_process_method,transaction_id,order_status,customer_id) VALUES(
                '$orderDate','$orderTime',$totalGame,$totalPrice,'$paymentType','$paymentProcessMethod','$transactionId','$orderStatus',$customerId
            )";
            $placeOrderQueryResult = mysqli_query($connect,$placeOrderQuery);
        }
        if ( $placeOrderQueryResult ) {
            $orderedGameInsertStatus = true;
            $lastOrderId = get_last_order_id($connect,$customerId);
            foreach($cartItemList as $gameId) {
                $orderGameListQuery = "INSERT INTO ordered_games_list(order_id,game_id,customer_id) VALUES ($lastOrderId,$gameId,$customerId)";
                $orderGameListQueryResult = mysqli_query($connect,$orderGameListQuery);
                if ( !$orderedGameInsertStatus || !$orderGameListQueryResult ) $orderedGameInsertStatus = false;
            }
            
            if ( $orderedGameInsertStatus ) $removeCartItemStatus = remove_cart_items($connect,$loginUsername);
        }
        return $removeCartItemStatus?"success":"fail";
        // return $placeOrderQuery;
    }



    // backend admin dashboard function scripts.....
    function get_ordered_game_list_by_order_id($connect,$orderId) {
        $orderId = (int)$orderId;
        $orderedGameList = array();
        $orderedGameListQuery = "SELECT game_id FROM ordered_games_list WHERE order_id=$orderId";
        $orderedGameListQueryResult = mysqli_query($connect,$orderedGameListQuery);
        $totalGame = mysqli_num_rows($orderedGameListQueryResult);
        for ( $count = 0 ; $count < $totalGame ; $count++ ) {
            array_push($orderedGameList,(int)mysqli_fetch_array($orderedGameListQueryResult)['game_id']);
        }
        return $orderedGameList;
    }

    function get_ordered_game_list_by_customer_id($connect,$customerId) {
        $orderedGameList = array();
        $orderedGameListQuery = "SELECT game_id FROM ordered_games_list WHERE customer_id=$customerId";
        $orderedGameListQueryResult = mysqli_query($connect,$orderedGameListQuery);
        $totalGame = mysqli_num_rows($orderedGameListQueryResult);
        for ( $count = 0 ; $count < $totalGame ; $count++ ) {
            array_push($orderedGameList,(int)mysqli_fetch_array($orderedGameListQueryResult)['game_id']);
        }
        return $orderedGameList;
    }

    function set_and_get_order_data_to_array($orderData) {
        $orderDataArray = array();
        $orderDataArray['id'] = $orderData['id'];
        $orderDataArray['order_date'] = $orderData['order_date'];
        $orderDataArray['order_time'] = $orderData['order_time'];
        $orderDataArray['total_game'] = $orderData['total_game'];
        $orderDataArray['total_amount'] = $orderData['total_amount'];
        $orderDataArray['payment_type'] = $orderData['payment_type'];
        $orderDataArray['payment_type_long_form'] = $orderData['payment_type']=="kbzpay"?"KBZ Pay":"Wave Pay";
        $orderDataArray['payment_process_method'] = $orderData['payment_process_method'];
        $orderDataArray['transaction_id'] = $orderData['transaction_id'];
        $orderDataArray['uploaded_image'] = $orderData['uploaded_image'];
        $orderDataArray['order_status'] = $orderData['order_status'];
        $orderDataArray['customer_id'] = $orderData['customer_id'];
        return $orderDataArray;
    }

    function get_order_info_list($connect,$limit) {
        $orderList = array();
        $orderListQuery = "SELECT * FROM order_list ORDER BY id DESC LIMIT $limit";
        $orderListQueryResult = mysqli_query($connect,$orderListQuery);
        $totalOrder = mysqli_num_rows($orderListQueryResult);
        for ( $count = 0 ; $count < $totalOrder ; $count++ ) {
            $orderData = mysqli_fetch_array($orderListQueryResult);
            array_push($orderList,set_and_get_order_data_to_array($orderData));
        }
        return $orderList;
    }

    function get_order_info_by_order_id($connect,$orderId) {
        $orderInfoQuery = "SELECT * FROM order_list WHERE id=$orderId";
        $orderInfoQueryResult = mysqli_query($connect,$orderInfoQuery);
        $totalOrder = mysqli_num_rows($orderInfoQueryResult);
        $orderData = mysqli_fetch_array($orderInfoQueryResult);
        return set_and_get_order_data_to_array($orderData);
    }

    function get_order_list_by_customer_id($connect,$customerId) {
        $orderList = array();
        $orderInfoQuery = "SELECT * FROM order_list WHERE customer_id=$customerId ORDER BY id DESC";
        $orderInfoQueryResult = mysqli_query($connect,$orderInfoQuery);
        $totalOrder = mysqli_num_rows($orderInfoQueryResult);
        for ( $count = 0 ; $count < $totalOrder ; $count++ ) {
            $orderData = mysqli_fetch_array($orderInfoQueryResult);
            array_push($orderList,set_and_get_order_data_to_array($orderData));
        }
        return $orderList;
    }

    function get_total_order_by_customer_id($connect,$customerId) {
        $totalOrderQuery = "SELECT count(id) as total_order FROM order_list WHERE customer_id=$customerId";
        $totalOrderQueryResult = mysqli_query($connect,$totalOrderQuery);
        return mysqli_fetch_array($totalOrderQueryResult)['total_order'];
    }

    // function get_total_ordered_games_by_customer_id($connect,$customerId) {
    //     $totalOrderedGamesQuery = "SELECT count(game_id) as total_ordered_games FROM ordered_games_list WHERE customer_id=$customerId";
    //     $totalOrderedGamesQueryResult = mysqli_query($connect,$totalOrderedGamesQuery);
    //     return mysqli_fetch_array($totalOrderedGamesQueryResult)['total_ordered_games'];
    // }

    function get_total_order_count($connect) {
        $totalOrderQuery = "SELECT count(id) as total_order FROM order_list WHERE order_status='approved'";
        $totalOrderQueryResult = mysqli_query($connect,$totalOrderQuery);
        return (int)mysqli_fetch_array($totalOrderQueryResult)['total_order'];
    }

    function get_total_ordered_games_count($connect) {
        $totalOrderedGamesQuery = "SELECT SUM(total_game) as total_game FROM order_list WHERE order_status='approved'";
        $totalOrderedGamesQueryResult = mysqli_query($connect,$totalOrderedGamesQuery);
        return (int)mysqli_fetch_array($totalOrderedGamesQueryResult)['total_game'];
    }

    function get_total_new_orders_count($connect) {
        $newOrderQuery = "SELECT count(id) as total_new_order FROM order_list WHERE order_status='pending'";
        $newOrderQueryResult = mysqli_query($connect,$newOrderQuery);
        return (int)mysqli_fetch_array($newOrderQueryResult)['total_new_order'];
    }

    function get_order_status_list($connect) {
        $approvedStatus = 0;
        $declinedStatus = 0;
        $pendingStatus = 0;
        $orderStatusQuery = "SELECT order_status FROM order_list";
        $orderStatusQueryResult = mysqli_query($connect,$orderStatusQuery);
        $totalOrderStatus = mysqli_num_rows($orderStatusQueryResult);
        for ( $count=0 ; $count < $totalOrderStatus; $count++ ) {
            $orderStatus = mysqli_fetch_array($orderStatusQueryResult)['order_status'];
            switch($orderStatus){
                case 'approved':
                    $approvedStatus++; break;
                case 'declined':
                    $declinedStatus++; break;
                case 'pending':
                    $pendingStatus++; break;
            }
        }
        return [
            'approved' => $approvedStatus,
            'declined' => $declinedStatus,
            'pending' => $pendingStatus
        ];
    }


    function get_purchased_game_list_by_customer_id($connect,$customerId) {
        $purchasedGameList = array();
        $purchasedGameListQuery = "SELECT gl.* FROM game_list as gl INNER JOIN ordered_games_list as ogl ON ogl.game_id=gl.id INNER JOIN order_list as ol ON ol.id=ogl.order_id WHERE ol.order_status='approved' AND ol.customer_id=$customerId ORDER BY ogl.order_id DESC";
        $purchasedGameListQueryResult = mysqli_query($connect,$purchasedGameListQuery);
        $totalPurchasedGame = mysqli_num_rows($purchasedGameListQueryResult);
        for ( $count = 0 ; $count < $totalPurchasedGame ; $count++ ) {
            $gameData = mysqli_fetch_array($purchasedGameListQueryResult);
            array_push($purchasedGameList,set_and_get_game_data_to_array($gameData));
        }
        return $purchasedGameList;
    }

    function check_if_game_purchased($connect,$gameId,$customerId) {
        $gameId = (int)$gameId;
        $customerId = (int)$customerId;
        // $checkPurchasedQuery = "SELECT ol.order_status FROM order_list as ol INNER JOIN ordered_games_list as ogl ON ol.id=ogl.order_id INNER JOIN game_list as gl ON gl.id=ogl.game_id WHERE gl.id=$gameId AND ol.customer_id=$customerId";
        $checkPurchasedQuery = "SELECT ol.order_status FROM order_list as ol INNER JOIN ordered_games_list as ogl ON ol.id=ogl.order_id INNER JOIN game_list as gl ON gl.id=ogl.game_id WHERE gl.id=$gameId AND ol.customer_id=$customerId ORDER BY ol.id DESC LIMIT 1";
        $checkPurchasedQueryResult = mysqli_query($connect,$checkPurchasedQuery);
        $isGamePurchased = mysqli_num_rows($checkPurchasedQueryResult)==0 ? false : true;
        $orderStatus = mysqli_fetch_array($checkPurchasedQueryResult);
        return $isGamePurchased && trim($orderStatus['order_status']) == "approved";
    }

    function is_admin($connect,$username) {
        $adminQuery = "SELECT admin_username FROM admin_list WHERE admin_username='$username'";
        $adminQueryResult = mysqli_query($connect,$adminQuery);
        $totalAdmin = mysqli_num_rows($adminQueryResult);
        return $totalAdmin>0 ? true : false;
    }
