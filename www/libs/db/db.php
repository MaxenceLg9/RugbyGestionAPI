<?php

function getPDO():PDO
{
    $dsn = 'mysql:host=mysql-gestionequiperugby.alwaysdata.net;dbname=gestionequiperugby_bd;charset=utf8';
//    $dsn = 'mysql:host=db;dbname=rugbygestion';
//    $options = [
//        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
//    ];

//    return new PDO($dsn, 'root', 'pq04WX11me2005!');
    return new PDO($dsn,"387507",'$iutinfo');
}
