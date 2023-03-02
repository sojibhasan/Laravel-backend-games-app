<?php
    require('../model/Category.php');
    
    $category = new Category();
    $category_records = $category->mightyGetRecord();
    $master_array = $category_records;

    $newJsonString = json_encode($master_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    http_response_code(200);
    echo $newJsonString;
    die;
    header("Location: ../index.php");