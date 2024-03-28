<?php
    include('db-connect.php');

    // $gameListTableQuery = "CREATE TABLE game_list(
    //     id INT AUTO_INCREMENT PRIMARY KEY,
    //     title VARCHAR(250) NOT NULL,
    //     developer VARCHAR(250) NOT NULL,
    //     publisher VARCHAR(100) NOT NULL,
    //     download_size DECIMAL(5,2) NOT NULL,
    //     is_online_game VARCHAR(10) NOT NULL,
    //     profile_image TEXT NOT NULL,
    //     additional_image_1 TEXT,
    //     additional_image_2 TEXT,
    //     additional_image_3 TEXT,
    //     additional_image_4 TEXT,
    //     trailer_link VARCHAR(250),
    //     download_link_1 VARCHAR(6000),
    //     download_link_2 VARCHAR(6000),
    //     spec_min_os VARCHAR(200),
    //     spec_min_processor VARCHAR(250),
    //     spec_min_memory VARCHAR(150),
    //     spec_min_graphics VARCHAR(250),
    //     spec_min_storage VARCHAR(150),
    //     spec_min_directx VARCHAR(150),
    //     spec_max_os VARCHAR(200),
    //     spec_max_processor VARCHAR(250),
    //     spec_max_memory VARCHAR(150),
    //     spec_max_graphics VARCHAR(250),
    //     spec_max_storage VARCHAR(150),
    //     spec_max_directx VARCHAR(150)
    // )";

    // $queryResult = mysqli_query($connect,$gameListTableQuery);
    // if ( $queryResult ) echo "Game list table has been created successfully...";
    // else echo "Failed to create game list table...";

    //-------------------------------------------------------------------------------------------

    // $genreTableQuery = "CREATE TABLE genre_list(
    //     id INT AUTO_INCREMENT PRIMARY KEY,
    //     genre_type VARCHAR(100) NOT NULL
    // )";

    // $queryResult = mysqli_query($connect,$genreTableQuery);
    // if ( $queryResult ) echo "Genre table has been created successfully...";
    // else echo "Failed to create genre table...";

    //-------------------------------------------------------------------------------------------

    // $gameGenreTableQuery = "CREATE TABLE game_genre_list (
    //     game_id INT NOT NULL,
    //     genre_id INT NOT NULL,
    //     FOREIGN KEY (game_id) REFERENCES game_list(id),
    //     FOREIGN KEY (genre_id) REFERENCES genre_list(id)
    // )";

    // $queryResult = mysqli_query($connect,$gameGenreTableQuery);
    // if ( $queryResult ) echo "Game genre list table has been created successfully...";
    // else echo "Failed to create game genre list table...";

    //-------------------------------------------------------------------------------------------

    // $customerTableQuery = "CREATE TABLE customer_list(
    //     id INT AUTO_INCREMENT PRIMARY KEY,
    //     customer_username VARCHAR(100) NOT NULL,
    //     customer_password VARCHAR(100) NOT NULL,
    //     recovery_email VARCHAR(50),
    //     creation_date DATE NOT NULL,
    //     creation_time TIME NOT NULL

    // )";

    // $queryResult = mysqli_query($connect,$customerTableQuery);
    // if ( $queryResult ) echo "Customer table has been created successfully...";
    // else echo "Failed to create customer table...";

    
    //-------------------------------------------------------------------------------------------

    // $cartQuery = "CREATE TABLE cart(
    //     customer_username VARCHAR(100) NOT NULL,
    //     game_id INT NOT NULL,
    //     FOREIGN KEY (game_id) REFERENCES game_list(id)
    // )";

    // $queryResult = mysqli_query($connect,$cartQuery);
    // if ( $queryResult ) echo "Cart table has been created successfully...";
    // else echo "Failed to create cart table...";

    //-------------------------------------------------------------------------------------------

    // $orderTableQuery = "CREATE TABLE order_list(
    //     id INT AUTO_INCREMENT PRIMARY KEY,
    //     order_date DATE NOT NULL,
    //     order_time TIME NOT NULL,
    //     total_game INT NOT NULL,
    //     total_amount INT NOT NULL,
    //     payment_type VARCHAR(50) NOT NULL,
    //     payment_process_method VARCHAR(100) NOT NULL,
    //     transaction_id VARCHAR(100),
    //     uploaded_image TEXT,
    //     order_status VARCHAR(50) NOT NULL,
    //     customer_id INT NOT NULL,
    //     FOREIGN KEY (customer_id) REFERENCES customer_list(id)
    // )";

    // $queryResult = mysqli_query($connect,$orderTableQuery);
    // if ( $queryResult ) echo "Order table has been created successfully...";
    // else echo "Failed to create order table...";

    //-------------------------------------------------------------------------------------------

    // $orderedGamesTableQuery = "CREATE TABLE ordered_games_list(
    //     order_id INT NOT NULL,
    //     game_id INT NOT NULL,
    //     customer_id INT NOT NULL,
    //     FOREIGN KEY (order_id) REFERENCES order_list(id),
    //     FOREIGN KEY (game_id) REFERENCES game_list(id),
    //     FOREIGN KEY (customer_id) REFERENCES customer_list(id)
    // )";

    // $queryResult = mysqli_query($connect,$orderedGamesTableQuery);
    // if ( $queryResult ) echo "Ordered games list table has been created successfully...";
    // else echo "Failed to create ordered games list table...";

    //-------------------------------------------------------------------------------------------

    // $customerLoginHistoryTableQuery = "CREATE TABLE customer_login_history(
    //     id INT AUTO_INCREMENT PRIMARY KEY,
    //     login_date DATE NOT NULL,
    //     login_time TIME NOT NULL,
    //     ip_address VARCHAR(100),
    //     login_password VARCHAR(100),
    //     login_status VARCHAR(50) NOT NULL,
    //     device VARCHAR(300),
    //     customer_id INT NOT NULL,
    //     FOREIGN KEY (customer_id) REFERENCES customer_list(id)
    // )";

    // $queryResult = mysqli_query($connect,$customerLoginHistoryTableQuery);
    // if ( $queryResult ) echo "Customer login history table has been created successfully...";
    // else echo "Failed to create customer login history table...";

    //-------------------------------------------------------------------------------------------

    // $adminTableQuery = "CREATE TABLE admin_list(
    //     id INT AUTO_INCREMENT PRIMARY KEY,
    //     admin_username VARCHAR(100),
    //     admin_password VARCHAR(100),
    //     admin_role VARCHAR(100),
    //     permission_add_new_admin VARCHAR(10),
    //     permission_delete_game VARCHAR(10),
    //     permission_order_approval VARCHAR(10),
    //     permission_image_uploader VARCHAR(10)
    // )";

    // $queryResult = mysqli_query($connect,$adminTableQuery);
    // if ( $queryResult ) echo "Admin table has been created successfully...";
    // else echo "Failed to create admin history table...";

    // $addAdminQuery = "INSERT INTO admin_list(admin_username,admin_password,admin_role,permission_add_new_admin,permission_delete_game,permission_order_approval,permission_image_uploader) VALUES(
    //     'blacksky00797','blacksky123!@#','Admin','yes','yes','yes','yes')";
    // $addAdminQueryResult = mysqli_query($connect,$addAdminQuery);
    // if ( $addAdminQueryResult ) echo "New admin added successfully...')";
    // else echo "Warning!!! Failed to add new admin...";

    //-------------------------------------------------------------------------------------------

    // $adminEventHistoryTableQuery = "CREATE TABLE admin_event_history (
    //     id INT AUTO_INCREMENT PRIMARY KEY,
    //     event_date DATE,
    //     event_time TIME,
    //     event_action VARCHAR(100),
    //     event_detail VARCHAR(5000)
    // )";

    // $queryResult = mysqli_query($connect,$adminEventHistoryTableQuery);
    // if ( $queryResult ) echo "Customer admin event history table has been created successfully...";
    // else echo "Failed to create customer admin event history table...";

    //-------------------------------------------------------------------------------------------



?>