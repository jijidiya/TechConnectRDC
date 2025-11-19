<?php
session_start();

function isLogged(){
    return isset($_SESSION['user']);
}

function isFournisseur(){
    return isLogged() && $_SESSION['user']['role'] === 'fournisseur';
}

function isClient(){
    return isLogged() && $_SESSION['user']['role'] === 'client';
}

function isAdmin(){
    return isLogged() && $_SESSION['user']['role'] === 'admin';
}
?>