<?php
    require('../model/AppSetting.php');
    require('../model/Category.php');
    require('../model/Game.php');
    ini_set('display_errors', 1);
    $master_array = [];

    $appsetting = new AppSetting();
    $appsetting_records = $appsetting->mightyQuery("SELECT * FROM `app_settings`");
    
    if($appsetting_records->num_rows > 0){
        foreach( $appsetting_records as $k => $val ){
            $value = json_decode($val['value']);
            $master_array[$val['key']] = $value;
        }
    }
    
    $game = new Game();
    $game_records = $game->mightyGetFeaturedRecord();

    $master_array['featured_game'] = $game_records;

    $category = new Category();
    $category_record = $category->mightyGetRecord();
    $category_list = [];
    foreach ($category_record as $key => $value) {
        $value['game'] = [];
        $value['game'] = $game->mightyGetModuleCategoryRecord($value['id']);
        $category_list[] = $value;
    }

    $master_array['category'] = $category_list;

    $newJsonString = json_encode($master_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    http_response_code(200);
    echo $newJsonString;
    
    die;