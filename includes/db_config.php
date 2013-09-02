<?php

    $connection_const = array (
        "DB_HOST" => "localhost",
        "DB_USERNAME" => "gallery",
        "DB_PASSWORD" => "123",
        "DB_NAME" => "photo_gallery"
    );
    
    foreach($connection_const as $const => $value){
        defined("{$const}") ? null : define("{$const}", "{$value}");
    }

?>
