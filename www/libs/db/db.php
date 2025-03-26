<?php

function getPDO():PDO
{
//    $dsn = 'mysql:host=localhost;dbname=rugbygestion;charset=utf8';
    $dsn = 'mysql:host=db;dbname=rugbygestion;charset=utf8';
    $username = 'root';
    $password = 'pq04WX11me2005!';
    return new PDO($dsn, $username, $password);
}