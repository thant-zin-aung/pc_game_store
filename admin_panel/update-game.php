<?php
    include('db-connect.php');
    include('db-executions.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $flag = upload_new_game($data);
        $response = [
            'status' => 'success',
            'message' => 'Data received successfully',
            'data' => $flag
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Handle JSON decoding error
        $response = [
            'status' => 'error',
            'message' => 'Invalid JSON data'
        ];
    }
} else {
    // Handle non-POST requests
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Only POST requests are allowed']);
}


function upload_new_game($data) {
    global $connect;
    $gameTitle = $data['gameTitle'];
    $developer = $data['developer'];
    $publisher = $data['publisher'];
    $specificationList = $data['specificationList'];
    $youtubeTrailerLink = $data['youtubeTrailerLink'];
    $downloadLinkList = $data['downloadLinkList'];
    $genreList = $data['genreList'];
    $gamePlayImagesList = $data['gamePlayImagesList'];
    $downloadSize = $data['downloadSize'];

    $addGameCondition = true;
    $gameTitle = $gameTitle;
    $gameDeveloper = $developer;
    $gamePublisher = $publisher;
    $isOnlineGame = "no";
    $imageLinks = explode("\n",$gamePlayImagesList);
    $posterImage = $imageLinks[0];
    $additionalImage1 = $imageLinks[1];
    $additionalImage2 = $imageLinks[2];
    $additionalImage3 = $imageLinks[3];
    $additionalImage4 = $imageLinks[4];
    $trailerLink = $youtubeTrailerLink;
    $specMinOs = $specificationList[0]['os'];
    $specMinProcessor = $specificationList[0]['processor'];
    $specMinMemory = $specificationList[0]['memory'];
    $specMinGraphics = $specificationList[0]['graphics'];
    $specMinStorage = $downloadSize.' GB';
    $specMinDirectX = "11";
    $specMaxOs = $specificationList[1]['os'];
    $specMaxProcessor = $specificationList[1]['processor'];
    $specMaxMemory = $specificationList[1]['memory'];
    $specMaxGraphics = $specificationList[1]['graphics'];
    $specMaxStorage = $downloadSize.' GB';
    $specMaxDirectX = "12"; 
    $downloadLink1 = "";
    $downloadLink2 = $downloadLinkList;

    // Order is important...
    $addGameQuery = "INSERT INTO game_list(
        title,developer,publisher,download_size,is_online_game,profile_image,additional_image_1,additional_image_2,additional_image_3,additional_image_4,trailer_link,download_link_1,download_link_2,
        spec_min_os,spec_min_processor,spec_min_memory,spec_min_graphics,spec_min_storage,spec_min_directx,spec_max_os,spec_max_processor,spec_max_memory,spec_max_graphics,spec_max_storage,spec_max_directx
    ) VALUES(
        '$gameTitle','$gameDeveloper','$gamePublisher',$downloadSize,'$isOnlineGame','$posterImage','$additionalImage1','$additionalImage2','$additionalImage3','$additionalImage4',
        '$trailerLink','$downloadLink1','$downloadLink2','$specMinOs','$specMinProcessor','$specMinMemory','$specMinGraphics','$specMinStorage','$specMinDirectX','$specMaxOs','$specMaxProcessor',
        '$specMaxMemory','$specMaxGraphics','$specMaxStorage','$specMaxDirectX'
    )";

    $addGameQueryResult = mysqli_query($connect,$addGameQuery);
    $lastGameId = get_last_game_id($connect);
    // $addGameGenreQuery = "INSERT INTO game_genre_list(game_id,genre_id) VALUES($lastGameId, 1)";
    //     $addGameGenreQueryResult = mysqli_query($connect,$addGameGenreQuery);
    //     if ( !$addGameGenreQuery ) {
    //         $addGameCondition = false;
    //     }
    $genres = get_genre_ids($genreList);
    for ( $count = 0 ; $count < count($genres) ; $count++ ) {
        $addGameGenreQuery = "INSERT INTO game_genre_list(game_id,genre_id) VALUES($lastGameId,$genres[$count])";
        $addGameGenreQueryResult = mysqli_query($connect,$addGameGenreQuery);
        if ( !$addGameGenreQuery ) {
            $addGameCondition = false;
            break;
        }
    }
    if ( $addGameQueryResult && $addGameCondition ) {
        return true;
    } else {
        return false;
    }
}

function get_genre_ids($genres) {
    global $connect;
    $genres = array_map('strtolower', $genres);
    $escapedGenres = array_map(function($genre) use ($connect) {
        return "'" . mysqli_real_escape_string($connect, $genre) . "'";
    }, $genres);
    $placeholders = implode(",", $escapedGenres);
    $sql = "SELECT id FROM genre_list WHERE LOWER(genre_type) IN ($placeholders)";
    $result = mysqli_query($connect, $sql);
    $genre_ids = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $genre_ids[] = $row['id'];
        }
    }
    if (empty($genre_ids)) {
        $genre_ids = array_rand(array_flip(range(1, 25)), 3);
    }
    return $genre_ids;
}
?>