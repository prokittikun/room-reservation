<?php
function connect_db()
{
    $host = 'localhost';
    $db = 'room_reservation_db';
    $username = 'root';
    $password = '';
    return  new PDO("mysql:host=$host;dbname=$db", $username, $password);
}
