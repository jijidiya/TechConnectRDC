<?php

function sanitize($str){
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

function redirect($url){
    header("location : $url");
    exit;
}

function dd($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit;
}
?>