<?php 

function conectarDB() : mysqli {
    $db = mysqli_connect('127.0.0.1:3306', 'u317872803_ComCode', 'CocoVerde2025', 'u317872803_coco_verde');

    if(!$db) {
        echo 'Error de conexión';
        exit;
    }

    return $db;
}