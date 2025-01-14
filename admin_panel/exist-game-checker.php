<?php
    header( "Content-type: application/json");
    include('db-connect.php');
    include('db-executions.php');
    if(isset($_GET['game_title'])){
        $game_title = $_GET['game_title'];
        $checkResult = check_if_game_already_exist($connect, $game_title);
        echo json_encode([
            'status' => 'success',
            'already_exist'=> $checkResult,
            'message'=> $checkResult ? 'Game already exist' : 'Game does not exist'
        ]);
    } else {
        echo json_encode([
            'status' => 'failed',
            'already_exist'=> 404,
            'message'=> 'Please provide game title'
        ]);
    } 
?>