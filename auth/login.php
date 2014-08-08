<?php
session_start();
require "../config.php"; //ПОДКЛЮЧЕНИЕ НАСТРОЕК
@ $db = new mysqli($db_server, $db_user, $db_pass, $db_name); //соединение с базой данных

$user = $_POST['user'];
$pass = $_POST['password'];
//
// НАПИСАТЬ ЛОГ АВТОРИЗАЦИЙ
// ТАБЛИЦА  siteauthlog
// ПОЛЯ id, login, password, time, result
//
$user = htmlspecialchars(trim($user));
$pass = htmlspecialchars(trim($pass));
$user = strtolower($user);
$user = preg_match('/^[a-z]{3,20}$/', $user) ? $user : '';
$pass = preg_match('/^[a-zA-Z0-9]{3,20}$/', $pass) ? $pass : '';

if (!empty($user) && !empty($pass))
{

    $query = "select * from `users` where userlogin = '".$user."' and userpassword = '".md5($pass)."'";
    $result = $db->query($query);
    $num_result = $result->num_rows;
    if ($num_result == "1")
    {
        $rows = $result->fetch_assoc();

        $sid = md5(time().$rows['username']);
        $sidlife = time()+1296000;
        $query = "insert into `session` values (NULL, '".$sid."','".$rows['userid']."')";
        $db->query($query);
        setcookie('sid', $sid, $sidlife, '/');//, $site['domen']);
        //$query = "insert into `siteauthlog` values (NULL, '".$user."','***','".time()."','1')";
        //$db->query($query);
    }
    else
    {
        $query = "insert into `siteauthlog` values (NULL, '".$user."','".$pass."','".time()."','0')";
        $db->query($query);
    }
}
$db->close();
Header("Location: ../index.php");