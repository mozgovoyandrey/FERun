<?php
session_start();
require "../config.php"; //ПОДКЛЮЧЕНИЕ НАСТРОЕК
$sid = $_COOKIE['sid'];
@ $db = new mysqli($db_server, $db_user, $db_pass, $db_name); //соединение с базой данных

if (!empty($sid))
{
    //$sidlife = time()-100;
    setcookie ('sid', '', -100);

    $query = "select * from `session` where sid = '".$sid."'";
    $result = $db->query($query);
    $num_result = $result->num_rows;
    if ($num_result == 1)
    {
        $rows = $result->fetch_assoc();
        $query_del = "delete from `session` where id = '".$rows['id']."'";
        $result_del = $db->query($query_del);
    }
}

$db->close();
Header("Location: ../index.php");