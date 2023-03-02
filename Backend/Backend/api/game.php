<?php
    require('../model/Game.php');
    
    $game = new Game();
    $game_records = $game->mightyGetRecord();

    $is_featured = isset($_GET) && isset($_GET['is_featured']) ? $_GET['is_featured'] : null;

    if( $is_featured != null && $is_featured == 1 ) {
        $game_records = $game->mightyGetFeaturedRecord();
    }

    $category_id = isset($_GET) && isset($_GET['category_id']) ? $_GET['category_id'] : null;
    if( $category_id != null ) {
        $game_records = $game->mightyGetModuleCategoryRecord($category_id);
    }
    
    $master_array = $game_records;

    $newJsonString = json_encode($master_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    http_response_code(200);
    echo $newJsonString;
    die;
    header("Location: ../index.php");