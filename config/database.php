<?php 

function conectarDB() : mysqli {
    $db = mysqli_connect('localhost', 'root', 'Enzo45458753', 'coco_verde');

    if(!$db) {
        echo 'Error de conexión';
        exit;
    }

    return $db;
}