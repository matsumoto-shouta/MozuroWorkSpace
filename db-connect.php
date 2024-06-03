<?php
    const SERVER = 'mysql305.phy.lolipop.lan';
    const DBNAME = 'LAA1517807-insta';
    const USER = 'LAA1517807';
    const PASS = 'Pass0514';

    $connect = 'mysql:host='. SERVER . ';dbname='. DBNAME . ';charset=utf8';
    $pdo = new PDO($connect,USER,PASS);
?>


