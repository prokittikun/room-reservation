<?php
function connect_db()
{
    $host = '100.102.216.7';
    $db = 'room_reservation_db';
    $username = 'root';
    $password = '123456';
    return  new PDO("mysql:host=$host;dbname=$db", $username, $password);
}
