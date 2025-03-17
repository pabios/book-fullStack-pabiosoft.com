<?php
function dbConnect()
{
    // $db = "";
    try{
        $db = new \PDO('mysql:host=localhost;dbname=follow;charset=utf8', 'root', 'pass');
        echo 'succes';
        return $db;
    }catch(Exception $e){
        echo ' impossible de se connecter';
        die();
    }
}

dbConnect();
